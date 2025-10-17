<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutoria;
use App\Models\TutoriaSolicitud;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SolicitudTutoriaNotification;

class TutoriaProfesorController extends Controller
{
    // alumno solicita a un profesor concreto para una tutoria
    public function solicitar(Request $request, Tutoria $tutoria, User $profesor)
    {
        $alumnoId = Auth::id();
        if (!$alumnoId) return redirect()->route('login');

        // evitar duplicados
        $sol = TutoriaSolicitud::firstOrCreate([
            'tutoria_id' => $tutoria->id,
            'alumno_id' => $alumnoId,
        ], [
            'profesor_id' => $profesor->id,
            'estado' => 'pendiente'
        ]);

        // notificar al profesor
        $profesor->notify(new SolicitudTutoriaNotification($sol));

        return back()->with('ok','Solicitud enviada al profesor.');
    }

    // profesor responde (aceptar/rechazar)
    public function responder(Request $request, TutoriaSolicitud $solicitud)
    {
        $user = Auth::user();
        if (!$user || $user->id !== $solicitud->profesor_id) abort(403);

        $action = $request->input('action'); // 'aceptar'|'rechazar'
        if ($action === 'aceptar') {
            $solicitud->estado = 'aceptada';
            $solicitud->save();

            // asignar al alumno (puede variarse según tu diseño)
            $alumno = $solicitud->alumno;
            $alumno->tutoria_id = $solicitud->tutoria_id;
            $alumno->save();

            return back()->with('ok','Solicitud aceptada.');
        }

        if ($action === 'rechazar') {
            $solicitud->estado = 'rechazada';
            $solicitud->save();
            return back()->with('ok','Solicitud rechazada.');
        }

        return back()->with('error','Acción no válida.');
    }
}
