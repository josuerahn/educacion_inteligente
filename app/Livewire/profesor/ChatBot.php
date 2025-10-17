<?php

namespace App\Http\Livewire\Profesor;

use Livewire\Component;
use App\Models\User;
use App\Models\Tarea;
use App\Models\Entrega;
use OpenAI\Laravel\Facades\OpenAI; // paquete OpenAI Laravel

class ChatBot extends Component
{
    public $message = '';
    public $chat = [];

    public function sendMessage()
    {
        if (empty(trim($this->message))) return;

        $this->chat[] = ['user' => 'Tú', 'message' => $this->message];

        $profesor = auth()->user();
        $msgLower = strtolower($this->message);
        $respuesta = '';

        // Respuestas rápidas desde la DB
        if (str_contains($msgLower, 'lista de alumnos')) {
            $alumnos = User::where('role_id', 3)
                ->where('tutoria_id', $profesor->tutoria_id)
                ->pluck('name')
                ->toArray();
            $respuesta = "Tus alumnos son: " . implode(', ', $alumnos);
        } elseif (str_contains($msgLower, 'tareas completas')) {
            $total = Entrega::whereHas('tarea', function($q) use ($profesor) {
                $q->where('profesor_id', $profesor->id);
            })->whereNotNull('calificacion')->count();
            $respuesta = "Tus alumnos han completado $total tareas.";
        } else {
            // Preguntas abiertas -> IA
            $openAIresponse = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente de profesor. Responde con información sobre los alumnos según la base de datos si es posible.'],
                    ['role' => 'user', 'content' => $this->message],
                ],
            ]);
            $respuesta = $openAIresponse['choices'][0]['message']['content'] ?? 'No pude responder eso.';
        }

        $this->chat[] = ['user' => 'IA', 'message' => $respuesta];
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.profesor.chat-bot');
    }
}
