<?php
namespace App\Servicios\IA;

use App\Models\{AlumnoTutoria, InteraccionesIa, PlanAprendizaje, ProgresoRubro};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TutorIA {
  public function __construct(private ClienteOpenAI $openai) {}

  public function generarDiagnosticoYPlan(AlumnoTutoria $at): PlanAprendizaje {
    $rubros = ProgresoRubro::where('alumno_tutoria_id',$at->id)->with('rubro')->get()
      ->map(fn($p)=>['rubro'=>$p->rubro->nombre,'puntaje'=>$p->puntaje]);

    $system = "Eres un tutor pedagógico experto en {$at->tutoria->name}.
Devuelve JSON: nivel_actual, diagnostico[rubro,fortalezas,debilidades], objetivos[], plan_sugerido[{titulo,rubros,pasos,criterios}], tips[]. Niveles: inicial,básico,intermedio,avanzado.";

    $user = "Contexto:
Alumno: {$at->alumno->name}
Tutoría: {$at->tutoria->name}
Rubros: ".json_encode($rubros)."

Pide:
1) nivel_actual
2) diagnóstico por rubro
3) 3-5 objetivos
4) plan_sugerido [{titulo,rubros,pasos,criterios}]
5) tips";

    $res = $this->openai->chat([
      ['role'=>'system','content'=>$system],
      ['role'=>'user','content'=>$user],
    ]);
    $json = json_decode($res['choices'][0]['message']['content'] ?? '{}', true);

    return DB::transaction(function() use ($at,$json,$system,$user,$res){
      $plan = PlanAprendizaje::create([
        'alumno_tutoria_id'=>$at->id,
        'objetivos'=>implode("\n",$json['objetivos'] ?? []),
        'tareas_json'=>json_encode($json['plan_sugerido'] ?? []),
        'tips_json'=>json_encode($json['tips'] ?? []),
        'generado_por'=>'ia',
        'vigente_hasta'=>Carbon::now()->addDays(14),
      ]);

      InteraccionesIa::create([
        'alumno_tutoria_id'=>$at->id,
        'rol'=>'profesor','tipo'=>'diagnostico',
        'entrada'=>json_encode(['system'=>$system,'user'=>$user]),
        'salida'=>json_encode($json),
        'tokens'=>$res['usage']['total_tokens'] ?? 0,
        'costo_usd'=>0,
      ]);

      if (!empty($json['nivel_actual'])) {
        $at->update(['nivel_actual'=>$json['nivel_actual']]);
      }
      return $plan;
    });
  }
}
