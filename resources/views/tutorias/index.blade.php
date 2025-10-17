@extends('layouts.app') {{-- o tu layout --}}
@section('title','Tutorías disponibles')

@section('content')
<div class="container my-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h4 class="mb-0">Tutorías disponibles</h4>
      <small class="text-muted">Elegí una tutoría y enviá tu solicitud</small>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">Volver</a>
  </div>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="row g-3">
    @forelse($tutorias as $t)
      @php
        $estadoAlumno = $estadosAlumno[$t->id] ?? null;             // pendiente | aceptada | rechazada | null
        $aceptadas    = $aceptadasPorTutoria[$t->id] ?? 0;
        $cuposRest    = max(($capacidad ?? 10) - $aceptadas, 0);
        $disabled     = $cuposRest === 0 || in_array($estadoAlumno, ['pendiente','aceptada']);
        $badge        = $estadoAlumno === 'aceptada' ? 'success' :
                        ($estadoAlumno === 'pendiente' ? 'warning' :
                        ($cuposRest===0 ? 'secondary' : 'light'));
        $badgeText    = $estadoAlumno === 'aceptada' ? 'Aceptada' :
                        ($estadoAlumno === 'pendiente' ? 'Pendiente' :
                        ($cuposRest===0 ? 'Sin cupos' : 'Disponible'));
      @endphp

      <div class="col-12 col-md-6 col-lg-4">
        <div class="card card-rounded tutoria-card">
          <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-2">
              @if($t->profesor && $t->profesor->profile_photo)
                <img src="{{ asset('storage/'.$t->profesor->profile_photo) }}" class="rounded-circle" width="36" height="36" alt="">
              @else
                <div class="avatar-circle" style="width:36px;height:36px;font-size:.9rem;">
                  {{ strtoupper(substr($t->profesor->name ?? 'P',0,1)) }}
                </div>
              @endif
              <div class="small">
                <div class="fw-semibold">{{ $t->name }}</div>
                <div class="text-muted">{{ Str::limit($t->description ?? '—', 60) }}</div>
              </div>
              <span class="ms-auto badge text-bg-{{ $badge }}">{{ $badgeText }}</span>
            </div>

            <ul class="list-unstyled small text-muted mb-3">
              <li><span class="text-dark">Profesor:</span> {{ $t->profesor->name ?? '—' }}</li>
              <li><span class="text-dark">Horario/Agenda:</span> {{ $t->description ?? 'A coordinar' }}</li>
              <li><span class="text-dark">Cupos:</span> {{ $cuposRest }} / {{ $capacidad ?? 10 }}</li>
            </ul>

            <form action="{{ route('tutorias.solicitar',$t) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-primary w-100" @disabled($disabled)>
                @if($estadoAlumno === 'aceptada') Ya inscripto
                @elseif($estadoAlumno === 'pendiente') En revisión…
                @elseif($cuposRest === 0) Sin cupos
                @else Anotarme
                @endif
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-light border">No hay tutorías disponibles por ahora.</div>
      </div>
    @endforelse
  </div>

  <div class="mt-3">
    {{ $tutorias->links() }}
  </div>
</div>
@endsection
