<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoTutoria extends Model
{
    protected $table = 'alumno_tutoria';
protected $fillable = ['alumno_id','tutoria_id','nivel_actual','puntaje_global'];

public function alumno(){ return $this->belongsTo(\App\Models\User::class,'alumno_id'); }
public function tutoria(){ return $this->belongsTo(\App\Models\Tutoria::class); }
public function progresos(){ return $this->hasMany(\App\Models\ProgresoRubro::class,'alumno_tutoria_id'); }
public function planVigente(){ return $this->hasOne(\App\Models\PlanAprendizaje::class,'alumno_tutoria_id')->latestOfMany(); }

}
