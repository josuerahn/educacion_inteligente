<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    use HasFactory;

    protected $table = 'entregas';

    protected $fillable = [
        'tarea_id',
        'alumno_id',
        'archivo',
        'comentario',
        'fecha_entrega',
        'calificacion',
    ];

    /**
     * Relación: una entrega pertenece a una tarea
     */
    public function tarea()
    {
        return $this->belongsTo(\App\Models\Tarea::class, 'tarea_id');
    }

    /**
     * Relación: una entrega pertenece a un alumno (usuario)
     */
    public function alumno()
    {
        // usar alumno_id (coincide con $fillable)
        return $this->belongsTo(\App\Models\User::class, 'alumno_id');
    }
}
