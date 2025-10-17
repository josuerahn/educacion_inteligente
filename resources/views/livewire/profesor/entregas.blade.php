@extends('layouts.app')

@section('head')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('body-class','')

@section('content')
<div class="container py-4">
  <div class="card card-rounded shadow-sm p-4">
    <h4 class="mb-3">Entregas de la tarea: {{ $tarea->titulo ?? 'Tarea' }}</h4>

    @if($entregas->isEmpty())
      <div class="text-muted">No hay entregas aún.</div>
    @else
      <ul class="list-group">
        @foreach($entregas as $ent)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>{{ $ent->alumno->name ?? 'Alumno' }}</strong>
              <div class="small text-muted">{{ $ent->created_at->format('d/m H:i') }} · {{ $ent->comentario ?? '' }}</div>
            </div>
            <div>
              <a href="{{ route('student.entregas.download', $ent->id) }}" class="btn btn-sm btn-outline-secondary">Descargar</a>
              @if(is_null($ent->calificacion))
                <form method="POST" action="{{ route('profesor.entregas.calificar', $ent->id) }}" class="d-inline">
                  @csrf
                  <input type="number" name="calificacion" min="0" max="10" class="form-control d-inline-block" style="width:80px;display:inline" placeholder="Nota">
                  <button class="btn btn-sm btn-primary">Guardar</button>
                </form>
              @else
                <span class="badge bg-success">Nota: {{ $ent->calificacion }}</span>
              @endif
            </div>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
