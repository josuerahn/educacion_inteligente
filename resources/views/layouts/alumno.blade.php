@extends('layouts.app')

@section('head')
  {{-- Usar Bootstrap y Chart.js en el layout del alumno (anulamos el tailwind oscuro) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <style>
    /* Forzar colores claros para el dashboard del alumno */
    body { background: #f6f8fb; color: #0f1724; }
    .card-rounded { border-radius: 14px; }
  </style>
@endsection

@section('body-class','')

@section('content')
  <div class="container py-4">
    @livewire('student.student-dashboard')
  </div>
@endsection

@section('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
