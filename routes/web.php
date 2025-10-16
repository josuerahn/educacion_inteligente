
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Profesor\Dashboard;

// --------------------
// Página principal redirige al login
// --------------------
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/health', fn() => 'ok');

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
// Dashboard del Profesor con Livewire
// --------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/profesor/dashboard', \App\Livewire\profesor\Dashboard::class)
        ->name('profesor.dashboard');
});

// --------------------
// Dashboard del Alumno con Livewire
// --------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/alumno/dashboard', \App\Livewire\Student\StudentDashboard::class)
        ->name('student.student-dashboard');
});

// --------------------
// Dashboard del Admin con Livewire
// --------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function() {
        $profesores = \App\Models\User::where('role_id', 2)->get();
        $cursos = \App\Models\Course::all();
        return view('livewire.admin.dashboard', compact('profesores', 'cursos'));
    })->name('admin.dashboard');
});
// Rutas para agregar profesor y curso desde el panel admin
Route::middleware(['auth'])->group(function () {

    Route::get('/admin/profesores/agregar', function() {
        $cursos = \App\Models\Course::all();
        return view('livewire.admin.agregar-profesor', compact('cursos'));
    })->name('admin.profesores.create');

    Route::post('/admin/profesores/agregar', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'course_id' => 'required|exists:courses,id',
        ]);
        // Validar que el curso no tenga ya un profesor asignado
        $yaAsignado = \App\Models\User::where('role_id', 2)
            ->where('course_id', $request->course_id)
            ->exists();
        if ($yaAsignado) {
            return redirect()->route('admin.dashboard')->with('error', 'Ese curso ya tiene un profesor asignado.');
        }
        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => 2,
            'course_id' => $request->course_id,
        ]);
        // Redirigir al dashboard y mostrar mensaje
        return redirect()->route('admin.dashboard')->with('success', 'Profesor agregado correctamente.');
    })->name('admin.profesores.store');

    // Ruta para eliminar profesor
    Route::delete('/admin/profesores/{id}/eliminar', function($id) {
        $profesor = \App\Models\User::where('id', $id)->where('role_id', 2)->first();
        if ($profesor) {
            $profesor->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Profesor eliminado correctamente.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'No se pudo eliminar el profesor.');
    })->name('admin.delete.profesor');


    Route::get('/admin/cursos/agregar', function() {
        return view('livewire.admin.agregar-curso');
    })->name('admin.cursos.create');

    Route::post('/admin/cursos/agregar', function(\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:courses,name',
            'description' => 'required|string',
        ], [
            'name.unique' => 'Este nombre ya existe',
        ]);
        \App\Models\Course::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);
        // Redirigir al dashboard y mostrar mensaje
        return redirect()->route('admin.dashboard')->with('success', 'Curso agregado correctamente.');
    })->name('admin.cursos.store');

    // Ruta para eliminar curso
    Route::delete('/admin/cursos/{id}/eliminar', function($id) {
        $curso = \App\Models\Course::find($id);
        if ($curso) {
            $curso->delete();
            return redirect()->route('admin.dashboard')->with('success', 'Curso eliminado correctamente.');
        }
        return redirect()->route('admin.dashboard')->with('error', 'No se pudo eliminar el curso.');
    })->name('admin.delete.curso');
});
