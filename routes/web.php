<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IAController;

// Livewire
use App\Http\Livewire\profesor\Dashboard as ProfesorDashboard;
use App\Http\Livewire\profesor\Tareas as ProfesorTareas;
use App\Http\Livewire\profesor\ChatBot;
use App\Http\Livewire\Student\StudentDashboard;

// Modelos
use App\Models\Tutoria;
use App\Models\TutoriaSolicitud;
use App\Models\User;
use App\Models\Tarea;
use App\Models\Entrega;

/*
|--------------------------------------------------------------------------
| Página principal redirige al login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/
// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Profesor y CRUD de Tareas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('profesor')->name('profesor.')->group(function () {

    // Dashboard principal
    Route::get('/dashboard', ProfesorDashboard::class)->name('dashboard');

    // Gestión de tareas
    Route::get('/tareas', ProfesorTareas::class)->name('tareas');

    // Ver entregas de una tarea
    Route::get('/tareas/{tareaId}/entregas', function ($tareaId) {
        return view('profesor.entregas', ['tareaId' => $tareaId]);
    })->name('entregas');

    // Chat Bot
    Route::get('/chat-bot', function () {
        return view('profesor.chat');
    })->name('chat-bot');

    // IA
    Route::post('/ia/plan/{alumnoTutoria}', [IAController::class,'generarPlan'])->name('ia.plan');
});

/*
|--------------------------------------------------------------------------
| Dashboard Alumno
|--------------------------------------------------------------------------
*/
Route::prefix('alumno')->middleware('auth')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/tutor-ia/{alumnoTutoria}', [IAController::class,'panelAlumno'])->name('tutor-ia');
});

/*
|--------------------------------------------------------------------------
| Tutorías públicas y solicitudes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/tutorias', function () {
        $alumnoId = auth()->id();
        $capacidad = 10;
        $tutorias = Tutoria::with(['profesor','tareas'])->paginate(9);

        $estadosAlumno = Schema::hasTable((new TutoriaSolicitud)->getTable())
            ? TutoriaSolicitud::where('alumno_id', $alumnoId)->pluck('estado', 'tutoria_id')
            : [];

        $aceptadasPorTutoria = Schema::hasTable((new TutoriaSolicitud)->getTable())
            ? TutoriaSolicitud::selectRaw('tutoria_id, COUNT(*) as c')
                ->where('estado','aceptada')
                ->groupBy('tutoria_id')
                ->pluck('c','tutoria_id')
            : [];

        return view('tutorias.index', compact('tutorias','capacidad','estadosAlumno','aceptadasPorTutoria'));
    })->name('tutorias.index');

    Route::post('/tutorias/{tutoria}/solicitar', function (Tutoria $tutoria) {
        $user = auth()->user();
        $user->tutoria_id = $tutoria->id;
        $user->save();
        return back()->with('ok','Inscripción realizada.');
    })->name('tutorias.solicitar');

    Route::post('/tutorias/{tutoria}/profesor/{profesor}/solicitar', [\App\Http\Controllers\TutoriaProfesorController::class, 'solicitar'])
        ->name('tutorias.profesor.solicitar');

    // Descarga de archivos de tareas y entregas
    Route::get('/tareas/{tarea}/descargar', function (Tarea $tarea) {
        $user = auth()->user();
        if (!$user) abort(403);

        $allowed = ($user->role_id == 2 && $tarea->profesor_id == $user->id) 
                || ($user->role_id != 2 && $user->tutoria_id == $tarea->tutoria_id);

        if (!$allowed) abort(403);

        $path = \Storage::disk('public')->path($tarea->archivo);
        return response()->download($path);
    })->name('student.tareas.download');

    Route::get('/entregas/{entrega}/descargar', function ($entregaId) {
        $user = auth()->user();
        $ent = \App\Models\Entrega::find($entregaId);
        if (!$ent) abort(404);

        $allowed = ($user->id == $ent->alumno_id) 
                || ($user->role_id == 2 && $user->id == optional($ent->tarea)->profesor_id);

        if (!$allowed) abort(403);

        $path = \Storage::disk('public')->path($ent->archivo);
        return response()->download($path);
    })->name('student.entregas.download');
});

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {

    Route::get('/dashboard', function () {
        $profesores = User::where('role_id', 2)->get();
        $tutorias = Tutoria::all();
        return view('livewire.admin.dashboard', compact('profesores', 'tutorias'));
    })->name('dashboard');

    // CRUD profesores y tutorias aquí (igual que tu código actual)
});
