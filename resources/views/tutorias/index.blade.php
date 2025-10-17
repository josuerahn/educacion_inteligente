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

  <div class="row g-4">
    {{-- SIDEBAR (resumen simple) --}}
    <aside class="col-12 col-lg-4">
      @php
        $total = $tutorias->total();
        $pend = collect($estadosAlumno)->filter(fn($e)=>$e==='pendiente')->count();
        $acep = collect($estadosAlumno)->filter(fn($e)=>$e==='aceptada')->count();
      @endphp

      <div class="card card-rounded shadow-sm mb-3">
        <div class="card-body">
          <h6 class="fw-semibold">Resumen</h6>
          <div class="row text-center mt-3">
            <div class="col-4 border-end">
              <div class="text-muted small">Total</div>
              <div class="stat-number">{{ $total }}</div>
            </div>
            <div class="col-4 border-end">
              <div class="text-muted small">Solicitadas</div>
              <div class="stat-number text-warning">{{ $pend }}</div>
            </div>
            <div class="col-4">
              <div class="text-muted small">Inscripto</div>
              <div class="stat-number text-success">{{ $acep }}</div>
            </div>
          </div>
        </div>
      </div>
    </aside>

    {{-- LISTA DE TUTORÍAS --}}
    <section class="col-12 col-lg-8">
      <div class="card card-rounded shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="mb-0">Listado</h5>
            <small class="text-muted">Profesor, horario y cupos</small>
          </div>
        </div>

        <div class="row g-3">
          @forelse($tutorias as $t)
            @php
              $estadoAlumno = $estadosAlumno[$t->id] ?? null; // pendiente | aceptada | rechazada | null
              $aceptadas    = $aceptadasPorTutoria[$t->id] ?? 0;
              $capacidad    = $capacidad ?? 10;
              $cuposRest    = max($capacidad - $aceptadas, 0);
              $disabled     = $cuposRest === 0 || in_array($estadoAlumno, ['pendiente','aceptada']);
              $badge        = $estadoAlumno === 'aceptada' ? 'success' :
                              ($estadoAlumno === 'pendiente' ? 'warning' :
                              ($cuposRest===0 ? 'secondary' : 'light'));
              $badgeText    = $estadoAlumno === 'aceptada' ? 'Aceptada' :
                              ($estadoAlumno === 'pendiente' ? 'Pendiente' :
                              ($cuposRest===0 ? 'Sin cupos' : 'Disponible'));
            @endphp

            <div class="col-12">
              <div class="card tutoria-card card-rounded p-4 h-100">
                <div class="d-flex align-items-start gap-3">
                  <div class="flex-shrink-0">
                    @if($t->profesor && $t->profesor->profile_photo)
                      <img src="{{ asset('storage/'.$t->profesor->profile_photo) }}" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;" alt="">
                    @else
                      <div class="avatar-circle" style="width:56px;height:56px;font-size:1rem;">
                        {{ strtoupper(substr($t->name,0,1)) }}
                      </div>
                    @endif
                  </div>

                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                      <div class="me-3">
                        <h4 class="tutoria-title mb-1">{{ $t->name }}</h4>
                        <div class="text-muted small">
                          <div><strong>Profesor:</strong> {{ $t->profesor->name ?? '—' }}</div>
                          <div><strong>Horario/Agenda:</strong> {{ $t->description ?? 'A coordinar' }}</div>
                        </div>
                      </div>
                      <span class="badge text-bg-{{ $badge }}">{{ $badgeText }}</span>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                      <form action="{{ route('tutorias.solicitar',$t) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg-sm" @disabled($disabled)>
                          @if($estadoAlumno === 'aceptada') Ya inscripto
                          @elseif($estadoAlumno === 'pendiente') En revisión…
                          @elseif($cuposRest === 0) Sin cupos
                          @else Anotarme
                          @endif
                        </button>
                      </form>

                      <div class="ms-auto text-muted small align-self-center">
                        Cupos: {{ $cuposRest }} / {{ $capacidad }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <div class="col-12">
              <div class="alert alert-light border">No hay tutorías disponibles por ahora.</div>
            </div>
          @endforelse
        </div>

        <d
