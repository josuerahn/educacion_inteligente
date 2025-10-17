<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\TutoriaSolicitud;

class SolicitudTutoriaNotification extends Notification
{
    use Queueable;

    protected $solicitud;

    public function __construct(TutoriaSolicitud $solicitud)
    {
        $this->solicitud = $solicitud;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => sprintf('%s quiere ingresar a tu tutoria %s', $this->solicitud->alumno->name ?? 'Un alumno', $this->solicitud->tutoria->name ?? ''),
            'solicitud_id' => $this->solicitud->id,
            'tutoria_id' => $this->solicitud->tutoria_id,
            'alumno_id' => $this->solicitud->alumno_id,
        ];
    }
}
