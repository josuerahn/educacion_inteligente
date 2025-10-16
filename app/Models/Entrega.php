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
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación: una entrega pertenece a un alumno (usuario)
     */
    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}
