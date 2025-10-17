<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_limite',
        'archivo',
        'profesor_id',
        'tutoria_id',
    ];

    public function profesor()
    {
        // El campo en la tabla es 'profesor_id' según la migración
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function tutoria()
    {
        return $this->belongsTo(\App\Models\Tutoria::class, 'tutoria_id');
    }

    public function entregas()
    {
        return $this->hasMany(\App\Models\Entrega::class, 'tarea_id');
    }

    // Alias: creador (sugerido) apunta al mismo profesor
    public function creador()
    {
        return $this->belongsTo(\App\Models\User::class, 'profesor_id');
    }
}
