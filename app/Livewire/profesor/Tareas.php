<?php

namespace App\Livewire\Profesor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tarea;
use App\Models\Tutoria;
use Illuminate\Support\Facades\Auth;

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

        if (!$profesor->tutoria_id) {
            session()->flash('error', 'No tienes una tutoría asignada.');
            return;
        }

        $tutoria = Tutoria::find($profesor->tutoria_id);
        if (!$tutoria) {
            session()->flash('error', 'No se encontró la tutoría asignada.');
            return;
        }

        $rutaArchivo = null;
        if ($this->archivo) {
            try {
                // Guardar archivo en storage/app/public/tareas con nombre original
                $rutaArchivo = $this->archivo->storeAs('tareas', $this->archivo->getClientOriginalName(), 'public');
            } catch (\Exception $e) {
                session()->flash('error', 'Error al guardar el archivo: ' . $e->getMessage());
                return;
            }
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

    public function editarTarea($id)
    {
        $tarea = Tarea::where('id', $id)
            ->where('profesor_id', Auth::id())
            ->firstOrFail();

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
            'archivo' => 'nullable|file|max:10240',
        ]);

        $tarea = Tarea::where('id', $this->editTareaId)
            ->where('profesor_id', Auth::id())
            ->firstOrFail();

        $tarea->titulo = $this->titulo;
        $tarea->descripcion = $this->descripcion;
        $tarea->fecha_limite = $this->fecha_limite;

        if ($this->archivo) {
            try {
                // Eliminar archivo anterior si existe
                if ($tarea->archivo && \Storage::disk('public')->exists($tarea->archivo)) {
                    \Storage::disk('public')->delete($tarea->archivo);
                }

                // Guardar nuevo archivo con nombre original
                $rutaArchivo = $this->archivo->storeAs('tareas', $this->archivo->getClientOriginalName(), 'public');
                $tarea->archivo = $rutaArchivo;

            } catch (\Exception $e) {
                session()->flash('error', 'Error al actualizar el archivo: ' . $e->getMessage());
                return;
            }
        }

        $tarea->save();

        $this->reset(['titulo', 'descripcion', 'fecha_limite', 'archivo', 'editTareaId']);
        $this->cargarTareas();

        session()->flash('success', 'Tarea actualizada correctamente.');
    }

    public function cancelarEdicion()
    {
        $this->reset(['titulo', 'descripcion', 'fecha_limite', 'archivo', 'editTareaId']);
    }

    public function eliminarTarea($id)
    {
        $tarea = Tarea::where('id', $id)
            ->where('profesor_id', Auth::id())
            ->firstOrFail();

        // Eliminar archivo físico si existe
        if ($tarea->archivo && \Storage::disk('public')->exists($tarea->archivo)) {
            \Storage::disk('public')->delete($tarea->archivo);
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
