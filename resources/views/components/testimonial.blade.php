@props(['avatar','name','role'=>'Estudiante'])

<div class="card card-mentoria h-100 p-3">
  <div class="d-flex align-items-center gap-3 mb-2">
    <img src="{{ asset($avatar) }}" class="avatar" alt="{{ $name }}" loading="lazy">
    <div>
      <div class="fw-semibold">{{ $name }}</div>
      <div class="text-secondary small">{{ $role }}</div>
    </div>
  </div>
  <p class="mb-0">“Encontré cursos claros y mentores que realmente acompañan. Mejoré mis oportunidades laborales en semanas.”</p>
</div>
