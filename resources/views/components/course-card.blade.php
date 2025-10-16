@props(['image','title','teacher','rating'=>4.8,'price'=>'Gratis'])

<div class="card card-mentoria h-100">
  <img src="{{ asset($image) }}" class="card-img-top" alt="{{ $title }}" loading="lazy">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <span class="badge text-bg-light border">Curso</span>
      <span class="text-warning fw-bold">★ {{ $rating }}</span>
    </div>
    <h5 class="card-title">{{ $title }}</h5>
    <p class="text-secondary mb-3">Por {{ $teacher }}</p>
    <div class="d-flex justify-content-between align-items-center">
      <span class="fw-bold">{{ $price }}</span>
      <a href="#" class="btn btn-sm btn-outline-primary">Ver más</a>
    </div>
  </div>
</div>
