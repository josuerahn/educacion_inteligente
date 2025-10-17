<?php
namespace App\Http\Controllers;

use App\Models\AlumnoTutoria;

class IAController extends Controller {
  public function panelAlumno(AlumnoTutoria $alumnoTutoria){
    return view('alumno.tutor-ia', compact('alumnoTutoria'));
  }
  public function generarPlan(AlumnoTutoria $alumnoTutoria){
    \App\Jobs\GenerarPlanIA::dispatch($alumnoTutoria->id);
    return back()->with('ok','Plan solicitado a la IA');
  }
}
