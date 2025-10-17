<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAprendizaje extends Model
{
    protected $table = 'planes_aprendizaje';
protected $fillable = ['alumno_tutoria_id','version','objetivos','tareas_json','tips_json','generado_por','vigente_hasta'];

public function alumnoTutoria(){ return $this->belongsTo(\App\Models\AlumnoTutoria::class); }

}
