<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteraccionesIa extends Model
{
    protected $table = 'interacciones_ia';
    protected $fillable = ['alumno_tutoria_id', 'rol', 'tipo', 'entrada', 'salida', 'tokens', 'costo_usd'];

    public function alumnoTutoria()
    {
        return $this->belongsTo(\App\Models\AlumnoTutoria::class);
    }
}
