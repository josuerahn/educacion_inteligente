<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AlumnoTutoria;
use App\Servicios\IA\TutorIA;

class GenerarPlanIaCommand extends Command
{
    protected $signature = 'ia:plan {alumno_tutoria_id}';
    protected $description = 'Genera diagnóstico y plan de aprendizaje IA para un alumno en una tutoría.';

    public function handle(TutorIA $tutor)
    {
        $id = (int) $this->argument('alumno_tutoria_id');
        $at = AlumnoTutoria::with(['alumno', 'tutoria'])->find($id);

        if (!$at) {
            $this->error("No existe un registro alumno_tutoria con ID $id");
            return Command::FAILURE;
        }

        $this->info("Generando plan IA para: {$at->alumno->name} ({$at->tutoria->name}) ...");

        try {
            $plan = $tutor->generarDiagnosticoYPlan($at);
            $this->info("✅ Plan generado correctamente (ID: {$plan->id})");
        } catch (\Throwable $e) {
            $this->error("❌ Error: ".$e->getMessage());
        }

        return Command::SUCCESS;
    }
}
