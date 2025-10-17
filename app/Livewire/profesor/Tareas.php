<?php

namespace App\Livewire\Profesor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tarea;
use App\Models\Tutoria;
use App\Models\Entrega;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Tareas extends Component
{
    use WithFileUploads;

    // Campos del formulario
    public $titulo, $descripcion, $fecha_limite, $archivo;

    // Estados del CRUD
    public $tareas;
    public $editTareaId = null;
    public $verEntregasTareaId = null;
    public $entregas = [];

    public function mount()
    {
        $this->cargarTareas();
    }

    public function cargarTareas()
    {
        $this->tareas = Tarea::where('profesor_id', Auth::id())->get();
    }

    public function crearTarea()
    {
        $this->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'required|date',
            'archivo' => 'nullable|file|max:10240',
        ]);

        $tutoriaId = Auth::user()->tutoria_id; // se asigna automáticamente

        $archivoPath = $this->archivo ? $this->archivo->store('tareas', 'public') : null;

        Tarea::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha_limite' => $this->fecha_limite,
            'archivo' => $archivoPath,
            'profesor_id' => Auth::id(),
            'tutoria_id' => $tutoriaId,
        ]);

        session()->flash('success', 'Tarea creada correctamente.');

        $this->reset(['titulo', 'descripcion', 'fecha_limite', 'archivo']);
        $this->cargarTareas();
    }

    public function editarTarea($id)
    {
        $tarea = Tarea::findOrFail($id);
        $this->editTareaId = $id;
        $this->titulo = $tarea->titulo;
        $this->descripcion = $tarea->descripcion;
        $this->fecha_limite = $tarea->fecha_limite;
    }

    public function actualizarTarea()
    {
        $this->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_limite' => 'required|date',
            'archivo' => 'nullable|file|max:10240',
        ]);

        $tarea = Tarea::findOrFail($this->editTareaId);

        if ($this->archivo) {
            if ($tarea->archivo) Storage::disk('public')->delete($tarea->archivo);
            $tarea->archivo = $this->archivo->store('tareas', 'public');
        }

        $tarea->titulo = $this->titulo;
        $tarea->descripcion = $this->descripcion;
        $tarea->fecha_limite = $this->fecha_limite;
        $tarea->save();

        session()->flash('success', 'Tarea actualizada correctamente.');

        $this->reset(['titulo', 'descripcion', 'fecha_limite', 'archivo', 'editTareaId']);
        $this->cargarTareas();
    }

    public function eliminarTarea($id)
    {
        $tarea = Tarea::find($id);
        if ($tarea && $tarea->profesor_id === Auth::id()) {
            if ($tarea->archivo) Storage::disk('public')->delete($tarea->archivo);
            $tarea->delete();
            session()->flash('success', 'Tarea eliminada correctamente.');
            $this->cargarTareas();
        }
    }

    public function verEntregas($tareaId)
    {
        $this->verEntregasTareaId = $tareaId;
        $this->entregas = Entrega::where('tarea_id', $tareaId)->with('alumno')->get();
    }

   public function render()
{
    return view('livewire.profesor.tareas')
           ->layout('components.layouts.profesor');
}

}
