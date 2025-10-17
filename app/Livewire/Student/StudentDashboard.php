<?php
namespace App\Livewire\Student;
use Livewire\Component;
use Livewire\Attributes\Layout; 
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\SocialProfile;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class StudentDashboard extends Component
{
    use WithPagination, WithFileUploads;

    // props del perfil
    public ?User $alumno = null;
    public bool $editando = false;
    public string $nombre = '';
    public string $email = '';
    public ?string $whatsapp = null;
    public ?string $comision = null;
    public ?string $carrera  = null;
    public ?string $linkedin = null;
public ?string $github   = null;
public ?string $gitlab   = null;
public ?string $wordpress = null;
public ?string $notion    = null;

    // props de la grilla de profesores
    public string $q = '';
    public int $perPage = 8;

    public $nuevaFoto; // Para el archivo subido

    // nuevas props para tutorías
    public array $tutorias = [];
    public ?string $selectedTutoria = null;

    protected $queryString = ['q'];

   public function mount()
{
    $user = Auth::user();
    if ($user instanceof User) {
        $this->alumno = $user->load('tutoria'); // Cargar la relación 'tutoria'
        $this->fill([
            'nombre'   => $this->alumno->name ?? '',
            'email'    => $this->alumno->email ?? '',
            'whatsapp' => $this->alumno->whatsapp,
            'comision' => $this->alumno->comision,
            'carrera'  => $this->alumno->carrera,
        ]);

        // Cargar redes sociales si existen
        $sp = $this->alumno->socialProfiles()->first();
        if ($sp) {
            $this->linkedin  = $sp->linkedin;
            $this->github    = $sp->github;
            $this->gitlab    = $sp->gitlab;
            $this->wordpress = $sp->wordpress;
            $this->notion    = $sp->notion;
        }

        // cargar tutorías disponibles (puedes cambiar por consulta a modelo Tutoria si lo prefieres)
        $this->tutorias = [
            'Programación',
            'Metodología',
            'Matemáticas',
            'Comunicación',
            'Desarrollo web',
        ];

        // valor guardado en el usuario (campo selected_tutoria)
        $this->selectedTutoria = $this->alumno->selected_tutoria ?? null;
    }
}


    public function updatingQ() { $this->resetPage(); }

    public function habilitarEdicion() { $this->editando = true; }

    public function cancelarEdicion()
    {
        // volvemos a los datos del modelo
        $this->mount();
        $this->editando = false;
    }

public function actualizarDatos()
{
    $this->validate([
        'nombre'   => ['required','string','max:255'],
        'email'    => ['required','email','max:255', Rule::unique('users','email')->ignore($this->alumno->id)],
        'whatsapp' => ['nullable','string','max:50'],
        'comision' => ['nullable','string','max:50'],
        'carrera'  => ['nullable','string','max:100'],

        // redes
        'linkedin'  => ['nullable','url','max:255'],
        'github'    => ['nullable','url','max:255'],
        'gitlab'    => ['nullable','url','max:255'],
        'wordpress' => ['nullable','url','max:255'],
        'notion'    => ['nullable','url','max:255'],
    ]);

    // Usuario
    $this->alumno->update([
        'name'      => $this->nombre,
        'email'     => $this->email,
        'whatsapp'  => $this->whatsapp,
        'comision'  => $this->comision,
        'carrera'   => $this->carrera,
    ]);

    // Upsert redes (funciona si tu relación es hasOne o hasMany (tomando first))
    $sp = $this->alumno->socialProfiles()->first();
    if (!$sp) {
        // si no existe, crear
        $sp = $this->alumno->socialProfiles()->create([
            'linkedin'  => $this->linkedin,
            'github'    => $this->github,
            'gitlab'    => $this->gitlab,
            'wordpress' => $this->wordpress,
            'notion'    => $this->notion,
        ]);
    } else {
        // si existe, actualizar
        $sp->update([
            'linkedin'  => $this->linkedin,
            'github'    => $this->github,
            'gitlab'    => $this->gitlab,
            'wordpress' => $this->wordpress,
            'notion'    => $this->notion,
        ]);
    }

    // refrescar y re-llenar props (para que la UI vea los cambios)
    $this->alumno->refresh();
    $this->fill([
        'nombre'   => $this->alumno->name,
        'email'    => $this->alumno->email,
        'whatsapp' => $this->alumno->whatsapp,
        'comision' => $this->alumno->comision,
        'carrera'  => $this->alumno->carrera,
    ]);

    $this->editando = false;
    session()->flash('mensaje', 'Datos actualizados correctamente.');
}


    public function actualizarFoto()
    {
        $this->validate([
            'nuevaFoto' => 'nullable|image|max:4048', // 4MB máximo
        ]);

        if ($this->nuevaFoto) {
            $ruta = $this->nuevaFoto->store('profile-photos', 'public');
            $this->alumno->profile_photo = $ruta;
            $this->alumno->save();
            $this->alumno->refresh();
            session()->flash('mensaje', 'Foto de perfil actualizada.');
        }
    }

    public function elegirTutoria()
    {
        $this->validate([
            'selectedTutoria' => ['required','string','max:255'],
        ]);

        $this->alumno->update([
            'selected_tutoria' => $this->selectedTutoria,
        ]);

        $this->alumno->refresh();
        session()->flash('mensaje', 'Te has anotado en: '.$this->selectedTutoria);
    }

    #[Layout('layouts.alumno')]
    public function render()
    {
        $profesor = User::where('role_id', 2)
            ->where('tutoria_id', $this->alumno->tutoria_id)
            ->first();

        return view('livewire.student.student-dashboard', [
            'alumno' => $this->alumno,
            'profesor' => $profesor,
        ]);
        
    }
}
