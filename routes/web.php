<?php

use App\Livewire\Profesor\Tareas;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Profesor\TareaController;
use App\Livewire\Profesor\Dashboard as ProfesorDashboard;
use App\Livewire\Profesor\Tareas as ProfesorTareas;
use App\Livewire\Student\StudentDashboard;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Tutoria;
use App\Models\Tarea;
use App\Models\Entrega;
use App\Models\TutoriaSolicitud;
use App\Models\User;
use App\Http\Controllers\IAController;
use Illuminate\Support\Facades\Auth;

// --------------------
// Página principal redirige al login
// --------------------
Route::get('/', function () {
    return redirect()->route('login');
});

// --------------------
// Autenticación
// --------------------

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --------------------
// Dashboard del Profesor y CRUD de tareas
// --------------------
Route::middleware(['auth'])->group(function () {
    Route::view('/profesor/tareas', 'livewire.profesor.tareas')
        ->name('profesor.tareas');
});


// --------------------
// Dashboard del Alumno con Livewire
// --------------------
Route::prefix('alumno')->middleware(['auth'])->name('student.')->group(function () {

    // Dashboard alumno (autenticado)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (! $user) {
            abort(403);
        }

        // valores por defecto
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
            // Cargar tutorías (con profesor y tareas) si existe la tabla
            if (Schema::hasTable('tutorias')) {
                $with = [];
                if (Schema::hasTable('users')) $with[] = 'profesor';
                if (Schema::hasTable('tareas')) $with[] = 'tareas';
                $tutoriasCollection = Tutoria::with($with)->get();
            }

            // total tareas (global)
            if (Schema::hasTable('tareas')) {
                $studentStats['total_tareas'] = Tarea::count();
            }

            // entregas del alumno
            if (Schema::hasTable('entregas')) {
                $entregasAlumno = Entrega::where('alumno_id', $user->id)->get();
                $studentStats['submitted'] = $entregasAlumno->count();
                $grades = $entregasAlumno->pluck('calificacion')->filter(fn($v) => $v !== null);
                $studentStats['avg_grade'] = $grades->count() ? round($grades->avg(), 2) : null;
                $studentStats['approved'] = $grades->filter(fn($v) => $v >= 6)->count();
                // XP simple: 50 xp por entrega + calificación * 10
                $studentStats['xp'] = (int)$entregasAlumno->sum(fn($e) => 50 + (floatval($e->calificacion ?? 0) * 10));
            }

            // total tutorías inscritas por el alumno (TutoriaSolicitud)
            if (Schema::hasTable('tutoria_solicituds') || Schema::hasTable('tutoria_solicitud')) {
                // intenta con el nombre correcto; usa modelo
                $studentStats['total_tutorias'] = TutoriaSolicitud::where('alumno_id', $user->id)->count();
            } else {
                // fallback: contar tutorías disponibles
                $studentStats['total_tutorias'] = $tutoriasCollection->count();
            }

            // progreso por cada tutoria
            foreach ($tutoriasCollection as $t) {
                $tareas = collect($t->tareas ?? []);
                $total = $tareas->count();
                $entregadas = 0;
                if ($total && Schema::hasTable('entregas')) {
                    $tareaIds = $tareas->pluck('id')->filter()->values()->all();
                    if (!empty($tareaIds)) {
                        $entregadas = Entrega::whereIn('tarea_id', $tareaIds)
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
                        'photo' => $prof->profile_photo_url ?? ($prof->profile_photo ? asset('storage/'.$prof->profile_photo) : null),
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

            // Si no hay tutorias en BD, crear listas demo vacías (no demo funcional, solo para UI)
            if ($tutoriasCollection->isEmpty()) {
                $demo = ['Programación','Metodología','Matemáticas','Comunicación','Desarrollo web'];
                foreach ($demo as $i => $n) {
                    $perTutoria[] = ['id'=>'t_'.$i,'nombre'=>$n,'total_tareas'=>3,'entregadas'=>0,'percent'=>0,'profesor'=>null];
                }
            }

        } catch (\Throwable $e) {
            Log::error('Alumno dashboard error: '.$e->getMessage());
            // mantener valores por defecto en caso de fallo
        }

        return view('livewire.student.student-dashboard', [
            'tutoriasCollection' => $tutoriasCollection,
            'perTutoria' => $perTutoria,
            'alumno' => $user,
            'studentStats' => $studentStats,
        ]);
    })->name('dashboard');

    // Si quieres mantener ruta para ver una tutoria concreta
    Route::get('/tutoria/{tutoria}', function (Tutoria $tutoria) {
        $user = auth()->user();
        abort_unless($user, 403);

        return view('tutorias.show', compact('tutoria'));
    })->name('tutoria.show');

});


//IA
Route::middleware(['auth'])->group(function () {
  Route::post('/profesor/ia/plan/{alumnoTutoria}', [IAController::class,'generarPlan'])->name('profesor.ia.plan');
  Route::get('/alumno/tutor-ia/{alumnoTutoria}', [IAController::class,'panelAlumno'])->name('alumno.tutor-ia');
});
// --------------------

// Tutorias
Route::middleware(['auth'])->group(function () {
    // LISTADO
    Route::get('/tutorias', function () {
        $alumnoId  = Auth::id();
        $capacidad = 10; // cupo fijo pedido

        // Cargamos profesor (según tu relación existente) y las solicitudes del alumno para marcar estado
        $tutorias = Tutoria::with(['profesor'])
            ->with(['tareas']) // opcional si querés mostrar algo de tareas
            ->paginate(9);

        // Mapeo rápido: tutoria_id => estado del alumno (pendiente/aceptada/rechazada)
        $estadosAlumno = TutoriaSolicitud::where('alumno_id', $alumnoId)
            ->pluck('estado', 'tutoria_id');

        // Conteo de aceptadas por tutoria_id
        $aceptadasPorTutoria = TutoriaSolicitud::selectRaw('tutoria_id, COUNT(*) as c')
            ->where('estado', 'aceptada')
            ->groupBy('tutoria_id')
            ->pluck('c', 'tutoria_id');

        return view('tutorias.index', compact('tutorias','capacidad','estadosAlumno','aceptadasPorTutoria'));
    })->name('tutorias.index');

    // ANOTARME (solicitud pendiente)
    Route::post('/tutorias/{tutoria}/solicitar', function (Tutoria $tutoria) {
        $alumnoId = Auth::id();

        // Evitar duplicados
        $solicitud = TutoriaSolicitud::firstOrCreate(
            ['tutoria_id' => $tutoria->id, 'alumno_id' => $alumnoId],
            ['estado' => 'pendiente']
        );

        return back()->with('ok', $solicitud->wasRecentlyCreated
            ? 'Solicitud enviada. Esperá la aprobación del profesor.'
            : 'Ya tenés una solicitud para esta tutoría.');
    })->name('tutorias.solicitar');

    // Solicitar a un profesor concreto (nuevo endpoint)
    Route::post('/tutorias/{tutoria}/profesor/{profesor}/solicitar', [\App\Http\Controllers\TutoriaProfesorController::class, 'solicitar'])
        ->name('tutorias.profesor.solicitar');

    // Profesor responde a una solicitud
    Route::post('/tutorias/solicitud/{solicitud}/responder', [\App\Http\Controllers\TutoriaProfesorController::class, 'responder'])
        ->name('tutorias.solicitud.responder');
    
    // Profesor dashboard (Livewire)
    Route::get('/profesor/dashboard', \App\Livewire\Profesor\Dashboard::class)->name('profesor.dashboard');
});
//----------------


// --------------------
// Dashboard del Admin con Livewire y gestión de profesores y tutorias
// --------------------
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        $profesores = \App\Models\User::where('role_id', 2)->get();
        $tutorias = \App\Models\Tutoria::all();
        return view('livewire.admin.dashboard', compact('profesores', 'tutorias'));
    })->name('dashboard');

    // Profesor: agregar
    Route::get('/profesores/agregar', function () {
        $tutorias = \App\Models\Tutoria::all();
        return view('livewire.admin.agregar-profesor', compact('tutorias'));
    })->name('profesores.create');

    Route::post('/profesores/agregar', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'tutoria_id' => 'required|exists:tutorias,id',
        ]);

        $yaAsignado = \App\Models\User::where('role_id', 2)
            ->where('tutoria_id', $request->tutoria_id)
            ->exists();
        if ($yaAsignado) {
            return redirect()->route('admin.dashboard')->with('error', 'Ese curso ya tiene un profesor asignado.');
        }

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => 2,
            'tutoria_id' => $request->tutoria_id,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Profesor agregado correctamente.');
    })->name('profesores.store');

    // Profesor: eliminar
    Route::delete('/profesores/{id}/eliminar', function ($id) {
        $profesor = \App\Models\User::where('id', $id)->where('role_id', 2)->first();
        if ($profesor) {
            $profesor->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Profesor eliminado correctamente.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'No se pudo eliminar el profesor.');
    })->name('delete.profesor');

    // Tutorias: agregar
    Route::get('/tutoria/agregar', function () {
        return view('livewire.admin.agregar-tutoria');
    })->name('tutorias.create');

    Route::post('/tutoria/agregar', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tutorias,name',
            'description' => 'required|string',
        ], [
            'name.unique' => 'Este nombre ya existe',
        ]);

        \App\Models\Tutoria::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Tutoria agregado correctamente.');
    })->name('tutorias.store');

    // Tutorias: eliminar
    Route::delete('/tutorias/{id}/eliminar', function ($id) {
        $curso = \App\Models\Tutoria::find($id);
        if ($curso) {
            $curso->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Tutoria eliminado correctamente.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'No se pudo eliminar la Tutoria.');
    })->name('delete.tutoria');
});
