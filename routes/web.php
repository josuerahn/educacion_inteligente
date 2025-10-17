    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Auth\RegisterController;
    use App\Http\Controllers\Auth\LoginController;
    use App\Livewire\Profesor\Dashboard as ProfesorDashboard;
    use App\Livewire\Profesor\Tareas as ProfesorTareas;
    use App\Livewire\Student\StudentDashboard;

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
    // --------------------
    // Dashboard del Profesor y CRUD de tareas
    // --------------------
    // Dashboard del Profesor y CRUD de tareas
    // --------------------
    Route::middleware(['auth'])->prefix('profesor')->name('profesor.')->group(function () {
        // Dashboard del profesor
        Route::get('/dashboard', ProfesorDashboard::class)->name('dashboard');
        
        // Gestión de tareas
        Route::get('/tareas', ProfesorTareas::class)->name('tareas');
        
    });

    // --------------------
    // Dashboard del Alumno con Livewire
    // --------------------
    Route::prefix('alumno')->middleware(['auth'])->name('student.')->group(function () {
        Route::get('/dashboard', StudentDashboard::class)->name('student-dashboard');
    });

    // Rutas para tutorías públicas/soporte
    Route::middleware(['auth'])->group(function () {
        Route::get('/tutorias', function () {
            $alumnoId  = \Illuminate\Support\Facades\Auth::id();
            $capacidad = 10;
            $tutorias = \App\Models\Tutoria::with(['profesor','tareas'])->paginate(9);
            $estadosAlumno = [];
            if (\Illuminate\Support\Facades\Schema::hasTable((new \App\Models\TutoriaSolicitud)->getTable())) {
                $estadosAlumno = \App\Models\TutoriaSolicitud::where('alumno_id', $alumnoId)->pluck('estado', 'tutoria_id');
            }
            $aceptadasPorTutoria = [];
            if (\Illuminate\Support\Facades\Schema::hasTable((new \App\Models\TutoriaSolicitud)->getTable())) {
                $aceptadasPorTutoria = \App\Models\TutoriaSolicitud::selectRaw('tutoria_id, COUNT(*) as c')
                    ->where('estado','aceptada')
                    ->groupBy('tutoria_id')
                    ->pluck('c','tutoria_id');
            }
            return view('tutorias.index', compact('tutorias','capacidad','estadosAlumno','aceptadasPorTutoria'));
        })->name('tutorias.index');

        Route::post('/tutorias/{tutoria}/solicitar', function (\App\Models\Tutoria $tutoria) {
            $alumnoId = \Illuminate\Support\Facades\Auth::id();
            // inscripción directa: asignar la tutoria al alumno sin elegir profesor (si tu diseño lo permite)
            $user = \App\Models\User::find($alumnoId);
            if ($user) {
                $user->tutoria_id = $tutoria->id;
                $user->save();
                return back()->with('ok','Inscripción realizada.');
            }
            return back()->with('error','No se pudo inscribir.');
        })->name('tutorias.solicitar');

        // Solicitar a un profesor concreto para una tutoria (formulario en tutorias.index)
        Route::post('/tutorias/{tutoria}/profesor/{profesor}/solicitar', [\App\Http\Controllers\TutoriaProfesorController::class, 'solicitar'])
            ->name('tutorias.profesor.solicitar');

        // Descarga de archivos de una tarea (archivo subido por profesor)
        Route::get('/tareas/{tarea}/descargar', function (\App\Models\Tarea $tarea) {
            // Solo usuarios autenticados pueden descargar; además permitir si el alumno está inscrito o es profesor dueño
            $user = \Illuminate\Support\Facades\Auth::user();
            if (!$user) {
                abort(403);
            }

            // Si la tarea no tiene archivo
            if (empty($tarea->archivo)) {
                abort(404);
            }

            // Permitir descarga si el usuario es profesor y es el autor (profesor_id) o si es alumno inscrito en la tutoria
            $allowed = false;
            if ($user->role_id == 2 && $tarea->profesor_id == $user->id) {
                $allowed = true;
            }
            // comprobar inscripción del alumno (campo tutoria_id en users)
            if ($user->role_id != 2 && $user->tutoria_id == $tarea->tutoria_id) {
                $allowed = true;
            }

            if (! $allowed) {
                abort(403);
            }

            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($tarea->archivo);
            return response()->download($path);
        })->name('student.tareas.download');

        // Descarga de archivos de una entrega (archivo subido por alumno)
        Route::get('/entregas/{entrega}/descargar', function ($entregaId) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (!$user) abort(403);

            $ent = \App\Models\Entrega::find($entregaId);
            if (! $ent) abort(404);

            // Permitir si es el alumno dueño o el profesor de la tarea
            $allowed = false;
            if ($user->id == $ent->alumno_id) $allowed = true;
            if ($user->role_id == 2 && $user->id == optional($ent->tarea)->profesor_id) $allowed = true;

            if (! $allowed) abort(403);

            $path = \Illuminate\Support\Facades\Storage::disk('public')->path($ent->archivo);
            return response()->download($path);
        })->name('student.entregas.download');
    });

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

    // Rutas para que el profesor vea las entregas de una tarea y las califique
    Route::middleware(['auth'])->prefix('profesor')->name('profesor.')->group(function () {
        Route::get('/entregas/{tarea}', function ($tareaId) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (! $user || $user->role_id != 2) abort(403);

            $tarea = \App\Models\Tarea::with('tutoria')->find($tareaId);
            if (! $tarea) abort(404);

            // Opcional: verificar que el profesor es el profesor de la tutoria
            if ($tarea->profesor_id && $tarea->profesor_id != $user->id) {
                // Si no es el profesor autor, permitir si es administrador (role_id 1)
                if ($user->role_id != 1) abort(403);
            }

            $entregas = \App\Models\Entrega::where('tarea_id', $tarea->id)->with('alumno')->orderBy('created_at','desc')->get();

            return view('livewire.profesor.entregas', compact('tarea','entregas'));
        })->name('entregas');

        // Calificar una entrega (simple)
        Route::post('/entregas/{entrega}/calificar', function (\Illuminate\Http\Request $request, $entregaId) {
            $user = \Illuminate\Support\Facades\Auth::user();
            if (! $user || $user->role_id != 2) abort(403);

            $ent = \App\Models\Entrega::find($entregaId);
            if (! $ent) abort(404);

            $cal = intval($request->input('calificacion'));
            $ent->calificacion = $cal;
            $ent->save();

            return back()->with('success', 'Entrega calificada.');
        })->name('entregas.calificar');
    });
