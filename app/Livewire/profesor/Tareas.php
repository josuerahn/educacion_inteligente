<?php

namespace App\Livewire\Profesor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Tareas extends Component
{
    use WithFileUploads;

    // Variables públicas que se exponen a la vista
    public $tareas;
    public $titulo;
    public $descripcion;
    public $fecha_limite;
    public $archivo;
    public $editTareaId = null;
    public $verEntregasTareaId = null;
    public $entregas;

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
            'archivo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $archivoPath = null;
        if ($this->archivo) {
            $archivoPath = $this->archivo->store('tareas', 'public');
        }

        Tarea::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha_limite' => $this->fecha_limite,
            'archivo' => $archivoPath,
            'profesor_id' => Auth::id(),
        ]);

        session()->flash('success', 'Tarea creada correctamente.');
        $this->reset(['titulo', 'descripcion', 'fecha_limite', 'archivo']);
        $this->cargarTareas();
    }

    public function editarTarea($id)
    {
        $tarea = Tarea::findOrFail($id);
        $this->editTareaId = $tarea->id;
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
            'archivo' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
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
        $tarea = Tarea::findOrFail($id);
        if ($tarea->archivo) Storage::disk('public')->delete($tarea->archivo);
        $tarea->delete();

        session()->flash('success', 'Tarea eliminada correctamente.');
        $this->cargarTareas();
    }

    public function verEntregas($id)
    {
        $this->verEntregasTareaId = $id;
        $tarea = Tarea::find($id);
        $this->entregas = $tarea->entregas ?? collect();
    }

    public function render()
    {
        return view('livewire.profesor.tareas');
    }
}
