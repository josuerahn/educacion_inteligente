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
