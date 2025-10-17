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
        return $this->belongsTo(User::class);
    }

    public function tutoria()
    {
        return $this->belongsTo(Tutoria::class);
    }
}
