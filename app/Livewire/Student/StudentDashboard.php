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
        // Preparar datos que la vista espera (mismo comportamiento que la ruta /alumno/dashboard)
        $user = $this->alumno;
        $tutoriasCollection = collect();
        $perTutoria = [];
        $studentStats = [
            'total_tutorias' => 0,
            'total_tareas' => 0,
            'submitted' => 0,
            'approved' => 0,
            'avg_grade' => null,
            'xp' => 0,
        ];

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('tutorias')) {
                $with = [];
                if (\Illuminate\Support\Facades\Schema::hasTable('users')) $with[] = 'profesor';
                if (\Illuminate\Support\Facades\Schema::hasTable('tareas')) $with[] = 'tareas';
                $tutoriasCollection = \App\Models\Tutoria::with($with)->get();
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('tareas')) {
                $studentStats['total_tareas'] = \App\Models\Tarea::count();
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('entregas')) {
                $entregasAlumno = \App\Models\Entrega::where('alumno_id', $user->id)->get();
                $studentStats['submitted'] = $entregasAlumno->count();
                $grades = $entregasAlumno->pluck('calificacion')->filter(fn($v) => $v !== null);
                $studentStats['avg_grade'] = $grades->count() ? round($grades->avg(), 2) : null;
                $studentStats['approved'] = $grades->filter(fn($v) => $v >= 6)->count();
                $studentStats['xp'] = (int)$entregasAlumno->sum(fn($e) => 50 + (floatval($e->calificacion ?? 0) * 10));
            }

            if (\Illuminate\Support\Facades\Schema::hasTable((new \App\Models\TutoriaSolicitud)->getTable())) {
                $studentStats['total_tutorias'] = \App\Models\TutoriaSolicitud::where('alumno_id', $user->id)->count();
            } else {
                $studentStats['total_tutorias'] = $tutoriasCollection->count();
            }

            foreach ($tutoriasCollection as $t) {
                $tareas = collect($t->tareas ?? []);
                $total = $tareas->count();
                $entregadas = 0;
                if ($total && \Illuminate\Support\Facades\Schema::hasTable('entregas')) {
                    $tareaIds = $tareas->pluck('id')->filter()->values()->all();
                    if (!empty($tareaIds)) {
                        $entregadas = \App\Models\Entrega::whereIn('tarea_id', $tareaIds)
                                        ->where('alumno_id', $user->id)
                                        ->count();
                    }
                }
                $percent = $total ? round(($entregadas / $total) * 100) : 0;

                $prof = $t->profesor ?? null;
                $profData = null;
                if ($prof) {
                    $profData = [
                        'name' => $prof->name ?? null,
                        'photo' => $prof->profile_photo_url ?? null,
                    ];
                }

                $perTutoria[] = [
                    'id' => $t->id ?? 't_'.$t->nombre ?? uniqid(),
                    'nombre' => $t->nombre ?? $t->name ?? 'Tutoría',
                    'total_tareas' => $total,
                    'entregadas' => $entregadas,
                    'percent' => $percent,
                    'profesor' => $profData,
                ];
            }

            if ($tutoriasCollection->isEmpty()) {
                $demo = ['Programación','Metodología','Matemáticas','Comunicación','Desarrollo web'];
                foreach ($demo as $i => $n) {
                    $perTutoria[] = ['id'=>'t_'.$i,'nombre'=>$n,'total_tareas'=>3,'entregadas'=>0,'percent'=>0,'profesor'=>null];
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Alumno dashboard (Livewire) error: '.$e->getMessage());
        }

        $profesor = User::where('role_id', 2)
            ->where('tutoria_id', $this->alumno->tutoria_id)
            ->first();

        return view('livewire.student.student-dashboard', [
            'alumno' => $this->alumno,
            'profesor' => $profesor,
            'tutoriasCollection' => $tutoriasCollection,
            'perTutoria' => $perTutoria,
            'studentStats' => $studentStats,
        ]);
    }
}
