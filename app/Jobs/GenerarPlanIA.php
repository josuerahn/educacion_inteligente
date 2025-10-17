<?php
namespace App\Jobs;

use App\Models\AlumnoTutoria;
use App\Servicios\IA\TutorIA;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerarPlanIA implements ShouldQueue {
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  public function __construct(public int $alumnoTutoriaId) {}
  public function handle(TutorIA $tutor) {
    $at = AlumnoTutoria::with(['alumno','tutoria'])->findOrFail($this->alumnoTutoriaId);
    $tutor->generarDiagnosticoYPlan($at);
  }
}
