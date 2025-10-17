<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresoRubro extends Model
{
    protected $table = 'progreso_rubros';
    protected $fillable = ['alumno_tutoria_id', 'rubro_id', 'puntaje', 'evidencia_json', 'ultima_actualizacion'];

    public function rubro()
    {
        return $this->belongsTo(\App\Models\Rubro::class);
    }
    public function alumnoTutoria()
    {
        return $this->belongsTo(\App\Models\AlumnoTutoria::class);
    }
}
