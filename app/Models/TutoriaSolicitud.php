<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoriaSolicitud extends Model
{
    protected $fillable = ['tutoria_id', 'alumno_id', 'estado'];

    public function tutoria() {
        return $this->belongsTo(Tutoria::class);
    }

    public function alumno() {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}
