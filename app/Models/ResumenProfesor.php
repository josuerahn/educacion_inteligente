<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumenProfesor extends Model
{
    protected $table = 'resumenes_profesor';
    protected $fillable = ['profesor_id', 'tutoria_id', 'fecha', 'contenido_json'];
}
