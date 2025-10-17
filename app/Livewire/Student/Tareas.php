<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\TutoriaSolicitud;
use App\Models\Tarea;
use App\Models\Entrega;

class Tareas extends Component
{
    use WithFileUploads;

    public $tareas = [];
    public $archivo; // file upload
    public $comentario = '';
    public $uploadingFor = null; // tarea id actualmente a subir

    protected $rules = [
        'archivo' => 'required|file|max:10240|mimes:pdf,doc,docx,zip,txt,rar',
        'comentario' => 'nullable|string|max:1000',
    ];

    public function mount()
    {
        $this->loadTareas();
    }

    public function loadTareas()
    {
    $user = Auth::user();
        if (! $user) {
            $this->tareas = [];
            return;
        }

        // tutoria ids where alumno está inscrito (estado no rechazado)
        $tutIds = [];
        if (Schema::hasTable((new TutoriaSolicitud)->getTable())) {
            $tutIds = TutoriaSolicitud::where('alumno_id', $user->id)
                ->whereNotIn('estado', ['rechazada','cancelada'])
                ->pluck('tutoria_id')
                ->toArray();
        }

        if (empty($tutIds)) {
            // si no está inscripto, intentar mostrar todas disponibles (acceso de solo lectura)
            $this->tareas = Schema::hasTable((new Tarea)->getTable())
                ? Tarea::with(['tutoria','tutoria.profesor'])->orderBy('created_at','desc')->get()
                : collect([]);
        } else {
            // Nota: la columna en la tabla de tareas es 'fecha_limite'
            $this->tareas = Tarea::whereIn('tutoria_id', $tutIds)
                ->with(['tutoria','tutoria.profesor'])
                ->orderBy('fecha_limite','asc')
                ->get();
        }
    }

    public function startUpload($tareaId)
    {
        $this->resetValidation();
        $this->archivo = null;
        $this->comentario = '';
        $this->uploadingFor = $tareaId;
    }

    public function cancelUpload()
    {
        $this->uploadingFor = null;
        $this->archivo = null;
        $this->comentario = '';
    }

   public function uploadEntrega($tareaId)
{
    $this->validate();

    $user = Auth::user();
    if (! $user) {
        $this->addError('auth', 'Debe iniciar sesión.');
        return;
    }

    $tarea = Tarea::find($tareaId);
    if (! $tarea) {
        $this->addError('tarea', 'Tarea no encontrada.');
        return;
    }

    // Verificar que el alumno esté inscripto en la tutoría
    if (Schema::hasTable((new TutoriaSolicitud)->getTable())) {
        $isInscripto = TutoriaSolicitud::where('alumno_id', $user->id)
            ->where('tutoria_id', $tarea->tutoria_id)
            ->exists();
        if (! $isInscripto) {
            $this->addError('perm', 'No estás inscripto en la tutoría de esta tarea.');
            return;
        }
    }

    // 🚫 Restricción: verificar si ya tiene una entrega previa para esta tarea
    $yaEntregada = Entrega::where('tarea_id', $tarea->id)
        ->where('alumno_id', $user->id)
        ->exists();

    if ($yaEntregada) {
        $this->addError('entrega', 'Ya realizaste una entrega para esta tarea. No se puede volver a entregar.');
        return;
    }

    // 📂 Guardar archivo
    $path = $this->archivo->store('entregas', 'public');

    // 🧾 Registrar la entrega
    $entrega = Entrega::create([
        'tarea_id'      => $tarea->id,
        'alumno_id'     => $user->id,
        'archivo'       => $path,
        'comentario'    => $this->comentario,
        'fecha_entrega' => now(),
    ]);

    // 🔄 Refrescar estado
    $this->uploadingFor = null;
    $this->archivo = null;
    $this->comentario = '';
    $this->loadTareas();

    session()->flash('success', 'Entrega subida correctamente.');
    $this->dispatch('entrega-uploaded', id: $entrega->id);
}


    // obtener entregas del usuario para una tarea (usado en la vista)
    public function getEntregasForTarea($tareaId)
    {
    $user = Auth::user();
        if (! $user) return collect([]);
        return Entrega::where('tarea_id', $tareaId)->where('alumno_id', $user->id)->orderBy('created_at','desc')->get();
    }

    public function render()
    {
        return view('livewire.student.tareas', [
            'tareas' => $this->tareas
        ]);
    }
}
