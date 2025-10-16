@props(['image','name','role'=>'Mentor'])

<div class="card card-mentoria h-100 text-center p-3">
  <img src="{{ asset($image) }}" class="mx-auto d-block rounded-circle mb-3" alt="{{ $name }}" width="96" height="96" loading="lazy">
  <h6 class="mb-1">{{ $name }}</h6>
  <p class="text-secondary small mb-3">{{ $role }}</p>
  <div class="d-flex justify-content-center gap-2">
    <a href="#" class="btn btn-sm btn-outline-primary">Perfil</a>
    <a href="#" class="btn btn-sm btn-cta">Contacto</a>
  </div>
</div>
