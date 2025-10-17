<?php

namespace App\Livewire\Profesor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tarea;
use App\Models\Tutoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Tareas extends Component
{
    use WithFileUploads;

    public $titulo;
    public $descripcion;
    public $fecha_limite;
    public $archivo;
    public $tareas;
    public $editTareaId = null;

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

        $profesor = Auth::user();

        $tutoria = Tutoria::where('profesor_id', $profesor->id)->first();
        if (!$tutoria) {
            session()->flash('error', 'No se encontró tutoría asignada al profesor.');
            return;
        }

        $rutaArchivo = null;
        if ($this->archivo) {
            $archivoOriginal = $this->archivo->getClientOriginalName();
            $rutaArchivo = $this->archivo->storeAs('tareas', $archivoOriginal, 'public');
        }

        Tarea::create([
            'titulo' => $this->titulo,
            'descripcion' => $this->descripcion,
            'fecha_limite' => $this->fecha_limite,
            'archivo' => $rutaArchivo,
            'profesor_id' => $profesor->id,
            'tutoria_id' => $tutoria->id,
        ]);

        $this->reset(['titulo', 'descripcion', 'fecha_limite', 'archivo']);
        $this->cargarTareas();

        session()->flash('success', 'Tarea creada correctamente.');
    }

    public function eliminarTarea($id)
    {
        $tarea = Tarea::findOrFail($id);

        if ($tarea->archivo && Storage::disk('public')->exists($tarea->archivo)) {
            Storage::disk('public')->delete($tarea->archivo);
        }

        $tarea->delete();
        $this->cargarTareas();

        session()->flash('success', 'Tarea eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.profesor.tareas')
               ->layout('components.layouts.profesor');
    }
}
