<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tutoria;
use App\Models\TutoriaSolicitud;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SolicitudTutoriaNotification;
use Illuminate\Support\Facades\DB;

class TutoriaProfesorController extends Controller
{
    // alumno solicita a un profesor concreto para una tutoria
    public function solicitar(Request $request, Tutoria $tutoria, User $profesor)
    {
        $alumnoId = Auth::id();
        if (!$alumnoId) return redirect()->route('login');

        // Inscribir directamente al alumno en la tutoria con el profesor seleccionado
        // Usamos transacción para evitar condiciones de carrera al comprobar cupos
        try {
            $result = DB::transaction(function () use ($tutoria, $profesor, $alumnoId) {
                // obtener capacidad del pivot (si existe)
                $prof = $tutoria->profesores()->where('users.id', $profesor->id)->first();
                $cap = 10; // default
                if ($prof && isset($prof->pivot) && isset($prof->pivot->capacity)) {
                    $cap = intval($prof->pivot->capacity ?: 10);
                }

                // contar aceptadas actuales para este profesor y tutoria
                $aceptadas = TutoriaSolicitud::where('tutoria_id', $tutoria->id)
                    ->where('profesor_id', $profesor->id)
                    ->where('estado', 'aceptada')
                    ->lockForUpdate()
                    ->count();

                if ($aceptadas >= $cap) {
                    return ['ok' => false, 'message' => 'No hay cupos disponibles para este profesor.'];
                }

                // crear o marcar como aceptada
                $sol = TutoriaSolicitud::firstOrCreate([
                    'tutoria_id' => $tutoria->id,
                    'alumno_id' => $alumnoId,
                ], [
                    'profesor_id' => $profesor->id,
                    'estado' => 'aceptada'
                ]);

                // si ya existía pero estaba pendiente/rechazada, actualizar a aceptada
                if ($sol->estado !== 'aceptada') {
                    $sol->profesor_id = $profesor->id;
                    $sol->estado = 'aceptada';
                    $sol->save();
                }

                // asignar la tutoria al alumno
                $alumno = $sol->alumno;
                if ($alumno) {
                    $alumno->tutoria_id = $tutoria->id;
                    $alumno->save();
                }

                return ['ok' => true, 'message' => 'Te has inscrito correctamente en la tutoría.'];
            });

            if (! $result['ok']) {
                return back()->with('error', $result['message']);
            }

            return back()->with('ok', $result['message']);

        } catch (\Throwable $e) {
            // Log error si deseas
            return back()->with('error', 'Error al inscribirte: '.$e->getMessage());
        }
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
