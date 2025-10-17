<?php

namespace App\Http\Livewire\profesor;

use Livewire\Component;
use App\Models\Entrega;
use App\Models\Tarea;
use Livewire\WithPagination;

class Entregas extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $tareaId; // Recibirá el parámetro de la URL
    public $tareaSeleccionada = null;
    public $calificacion = null;
    public $entregaSeleccionada = null;

    // Recibe el parámetro al inicializar el componente
    public function mount($tareaId)
    {
        $this->tareaId = $tareaId;
    }

    public function render()
    {
        $profesor = auth()->user();

        // Traer todas las entregas de la tarea específica del profesor
        $entregas = Entrega::with(['alumno', 'tarea'])
            ->whereHas('tarea', function ($q) use ($profesor) {
                $q->where('profesor_id', $profesor->id);
            })
            ->where('tarea_id', $this->tareaId) // filtrado por tareaId
            ->latest()
            ->paginate(10);

        $tareas = Tarea::where('profesor_id', $profesor->id)->get();

        return view('livewire.profesor.entregas', compact('entregas', 'tareas'));
    }

    public function seleccionarEntrega($id)
    {
        $this->entregaSeleccionada = Entrega::find($id);
        $this->calificacion = $this->entregaSeleccionada->calificacion;
    }

    public function calificarEntrega()
    {
        if (!$this->entregaSeleccionada) return;

        $this->validate([
            'calificacion' => 'required|numeric|min:0|max:10'
        ]);

        $this->entregaSeleccionada->update([
            'calificacion' => $this->calificacion,
        ]);

        session()->flash('mensaje', '✅ Calificación guardada correctamente.');
        $this->reset(['entregaSeleccionada', 'calificacion']);
    }

    public function clearFlash()
    {
        session()->forget('mensaje');
    }
}
