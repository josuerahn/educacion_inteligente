@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto p-4 space-y-6">
  <h1 class="text-2xl font-bold">Mi Tutor IA ({{ $alumnoTutoria->tutoria->name }})</h1>
  <p>Nivel: <b>{{ ucfirst($alumnoTutoria->nivel_actual) }}</b></p>

  <h3 class="font-semibold">Progreso por rubro</h3>
  <div class="grid sm:grid-cols-2 gap-3">
    @foreach($alumnoTutoria->progresos as $p)
      <div class="p-3 rounded border bg-white">
        <div class="flex justify-between">
          <span>{{ $p->rubro->nombre }}</span>
          <span>{{ $p->puntaje }}%</span>
        </div>
        <div class="mt-2 h-2 bg-slate-200 rounded">
          <div class="h-2 rounded bg-blue-600" style="width: {{ $p->puntaje }}%"></div>
        </div>
      </div>
    @endforeach
  </div>

  @php $plan = $alumnoTutoria->planVigente; @endphp
  @if($plan)
    <h3 class="font-semibold mt-6">Tareas sugeridas</h3>
    @foreach(json_decode($plan->tareas_json, true) as $t)
      <div class="p-3 rounded border bg-white mb-2">
        <div class="font-medium">{{ $t['titulo'] ?? 'Tarea' }}</div>
        <div class="text-sm text-slate-600">Criterios: {{ implode('; ', $t['criterios'] ?? []) }}</div>
      </div>
    @endforeach
  @else
    <div class="p-3 rounded border bg-amber-50">Aún no hay plan. Pedile al profesor que lo genere.</div>
  @endif
</div>
@endsection
