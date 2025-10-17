<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoriaSolicitud extends Model
{
    // La migración crea la tabla `tutoria_solicitudes` (con 'e').
    // Eloquent por convención esperaba `tutoria_solicituds` (sin 'e').
    // Forzamos el nombre correcto para evitar errores SQL por tabla no encontrada.
    protected $table = 'tutoria_solicitudes';

    protected $fillable = ['tutoria_id', 'alumno_id', 'profesor_id', 'estado'];

    public function tutoria()
    {
        return $this->belongsTo(Tutoria::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }
}
