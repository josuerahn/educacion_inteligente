<?php

namespace App\Livewire\Profesor;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    use WithPagination, WithFileUploads;

    public $q = '';
    public ?User $alumnoSeleccionado = null;

    public $mostrarEditarPerfil = false;
    public $mostrarAcercaDe = false;

    public $profesorEdit = [];
    public $fotoPerfilGrande = null;
    public $fotoPerfilProfesor;
    public ?int $confirmarEliminarId = null;

    /** Flag para ignorar ?ver= cuando el usuario limpia el buscador */
    public bool $forceIgnoreVer = false;

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        // Si querés permitir selección inicial por ?ver=, mantené esto;
        // si NO, comentá las 3 líneas siguientes.
        $verId = request()->integer('ver');
        if ($verId) $this->seleccionarAlumno($verId);
    }

    public function updatedQ(): void
    {
        $this->resetPage();

        if (strlen($this->q) === 0) {
            // Al limpiar el buscador: salir del detalle y volver a la lista completa
            $this->forceIgnoreVer = true;
            $this->alumnoSeleccionado = null;
        } else {
            $this->forceIgnoreVer = false;
        }
    }

    /** Enter en el buscador => mantener SOLO la lista filtrada (sin abrir detalle) */
    public function buscarAhora(): void
    {
        if (strlen($this->q) < 4) return;

        // Solo asegurar paginación desde el inicio y no tocar el detalle:
        $this->resetPage();
        $this->alumnoSeleccionado = null;
    }

    public function seleccionarAlumno($id): void
    {
        $this->alumnoSeleccionado = User::with('socialProfile')->find($id);
        $this->forceIgnoreVer = false;
        // SIN JS: el scroll se hace con ancla en la vista (#detalle-alumno)
    }

    public function ocultarDetalle(): void
    {
        $this->alumnoSeleccionado = null;
        $this->forceIgnoreVer = true;
        // SIN JS: el scroll se hace con ancla en la vista (#lista-alumnos)
    }

    public function confirmarEliminar(int $id): void
    {
        $this->confirmarEliminarId = $id;
    }

    public function cancelarEliminar(): void
    {
        $this->confirmarEliminarId = null;
    }

    public function eliminarAlumno($id): void
    {
        User::find($id)?->delete();

        $this->confirmarEliminarId = null;
        $this->alumnoSeleccionado  = null;
        session()->flash('mensaje', 'Alumno eliminado correctamente.');
        $this->resetPage();
    }

    public function editarPerfil(): void
    {
        $u = Auth::user();
        $this->profesorEdit = [
            'name'             => $u->name,
            'email'            => $u->email,
            'dni'              => $u->dni ?? '',
            'whatsapp'         => $u->whatsapp ?? '',
            'fecha_nacimiento' => $u->fecha_nacimiento ?? '',
        ];
        $this->mostrarEditarPerfil = true;
    }

    public function guardarPerfil(): void
    {
        $user = Auth::user();

    $user->name             = $this->profesorEdit['name'] ?? $user->name;
    $user->email            = $this->profesorEdit['email'] ?? $user->email;
    $user->dni              = $this->profesorEdit['dni'] ?? null;
    $user->whatsapp         = $this->profesorEdit['whatsapp'] ?? null;
    $user->fecha_nacimiento = $this->profesorEdit['fecha_nacimiento'] ?? null;
    if (!empty($this->profesorEdit['password'])) {
        $user->password = bcrypt($this->profesorEdit['password']);
    }

        if ($this->fotoPerfilProfesor) {
            $this->validate([
                'fotoPerfilProfesor' => 'image|max:2048',
            ]);
            $path = $this->fotoPerfilProfesor->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

   
    $user->refresh();

        $this->mostrarEditarPerfil = false;
        $this->fotoPerfilProfesor  = null;

        session()->flash('mensaje', 'Perfil actualizado correctamente.');
    }

    public function verFotoPerfil($url): void
    {
        $this->fotoPerfilGrande = $url;
    }

    public function cerrarFotoPerfil(): void
    {
        $this->fotoPerfilGrande = null;
    }

    public function acercaDe(): void
    {
        $this->mostrarAcercaDe = true;
    }

    public function cerrarModales(): void
    {
        $this->mostrarEditarPerfil = false;
        $this->mostrarAcercaDe     = false;
    }

    public function clearFlash(): void
    {
        if (session()->has('mensaje')) {
            session()->forget('mensaje');
        }
    }

    public function render()
    {
        if (!Auth::check() || Auth::user()->role_id != 2) {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }

        $profesor = Auth::user();
        $alumnos = User::with('socialProfile')
            ->where('role_id', 3)
            ->where('tutoria_id', $profesor->tutoria_id)
            ->where(function ($query) {
                $q = $this->q;
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->paginate(5);

        $sugerencias = collect();
        if (strlen($this->q) >= 4) {
            $sugerencias = User::with('socialProfile')
                ->where('role_id', 3)
                ->where('tutoria_id', $profesor->tutoria_id)
                ->where(function ($qq) {
                    $qq->where('name', 'like', "%{$this->q}%")
                       ->orWhere('email', 'like', "%{$this->q}%");
                })
                ->orderBy('name')
                ->limit(8)
                ->get();
        }

        return view('livewire.profesor.dashboard', [
            'alumnos'     => $alumnos,
            'sugerencias' => $sugerencias,
    ])->layout('components.layouts.profesor');
    }
}