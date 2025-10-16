<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
      {{-- Bombilla simple inline para el logo --}}
      <svg class="hero-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
        <path d="M9 18h6M10 22h4M6 9a6 6 0 1 1 12 0c0 2.5-1.2 3.9-2.5 5.2-.6.6-1 1.4-1 2.3v.5h-5v-.5c0-.9-.4-1.7-1-2.3C7.2 12.9 6 11.5 6 9Z"/>
      </svg>
      <strong>Mentoria</strong><span>Edu</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        <li class="nav-item"><a class="nav-link" href="#cursos">Cursos</a></li>
        <li class="nav-item"><a class="nav-link" href="#mentores">Mentores</a></li>
        <li class="nav-item"><a class="nav-link" href="#opiniones">Opiniones</a></li>
        <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
        <li class="nav-item ms-lg-3"><a class="btn btn-outline-primary" href="#">Ingresar</a></li>
        <li class="nav-item ms-2"><a class="btn btn-cta" href="#">Comenzá gratis</a></li>
      </ul>
    </div>
  </div>
</nav>
