<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    use HasFactory;

    protected $fillable = ['tutoria_id', 'nombre', 'peso'];
    public function tutoria()
    {
        return $this->belongsTo(\App\Models\Tutoria::class);
    }
}
