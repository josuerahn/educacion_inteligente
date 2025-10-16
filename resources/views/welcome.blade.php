@extends('layouts.app')

@section('title','Mentoria — Education & Course')
@section('meta_description','Empowering minds to unlock potential. Cursos, mentores, reseñas y mapa de sedes.')

@section('content')
  {{-- HERO --}}
  <section class="py-5 section-light">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-12 col-lg-6">
          <span class="badge-mentoria mb-3 d-inline-flex align-items-center gap-2">
            <svg class="hero-bulb" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
              <path d="M9 18h6M10 22h4M6 9a6 6 0 1 1 12 0c0 2.5-1.2 3.9-2.5 5.2-.6.6-1 1.4-1 2.3v.5h-5v-.5c0-.9-.4-1.7-1-2.3C7.2 12.9 6 11.5 6 9Z"/>
            </svg>
            Empowering minds to unlock potential
          </span>

          <h1 class="display-5 fw-bold mt-2">Aprendé con cursos y mentores de primer nivel</h1>
          <p class="lead text-secondary mt-3">
            Clases prácticas, seguimiento personalizado y comunidad. Empezá hoy mismo con planes gratuitos.
          </p>

          <div class="d-flex flex-wrap gap-3 mt-4">
            <a href="#cursos" class="btn-cta">Explorar cursos</a>
            <a href="#mentores" class="btn btn-outline-primary btn-lg">Conocer mentores</a>
          </div>

          <ul class="list-check mt-4 text-secondary">
            <li>Certificados verificables</li>
            <li>Clases en vivo y grabadas</li>
            <li>Garantía de satisfacción</li>
          </ul>
        </div>

        <div class="col-12 col-lg-6 text-center">
          <img src="{{ asset('images/imagenprincipal.jpg') }}"
               class="img-fluid rounded-2xl"
               alt="Estudiante disfrutando del aprendizaje en línea">
        </div>
      </div>

      {{-- Badges/estadísticas --}}
      <div class="row row-cols-2 row-cols-md-4 g-3 mt-4 text-center">
        <div class="col"><div class="card card-mentoria py-3">+1200 Cursos</div></div>
        <div class="col"><div class="card card-mentoria py-3">+350 Mentores</div></div>
        <div class="col"><div class="card card-mentoria py-3">4.8/5 Rating</div></div>
        <div class="col"><div class="card card-mentoria py-3">Marketing Genius 2024</div></div>
      </div>
    </div>
  </section>

  {{-- CATEGORÍAS --}}
  <section class="py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 class="h3 fw-bold mb-0">Elegí tu categoría</h2>
        <a href="#" class="link-primary">Ver todas</a>
      </div>

      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
        <div class="col">
          <div class="card card-mentoria">
            <img src="{{ asset('images/imagensecundaria.svg') }}" class="card-img-top" alt="Programación">
            <div class="card-body">
              <h6 class="mb-1">Programación</h6>
              <p class="text-secondary small mb-0">Laravel, JS, Python</p>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card card-mentoria">
            <img src="{{ asset('images/imagen3.jpg') }}" class="card-img-top" alt="Diseño">
            <div class="card-body">
              <h6 class="mb-1">Diseño</h6>
              <p class="text-secondary small mb-0">UI/UX, Figma, Branding</p>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card card-mentoria">
            <img src="{{ asset('images/imagen4.jpg') }}" class="card-img-top" alt="Marketing">
            <div class="card-body">
              <h6 class="mb-1">Marketing</h6>
              <p class="text-secondary small mb-0">SEO, Ads, Contenido</p>
            </div>
          </div>
        </div>

        <div class="col">
          <div class="card card-mentoria">
            <div class="card-body d-flex align-items-center justify-content-center" style="height:194px">
              <span class="text-secondary">Y mucho más…</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CURSOS DESTACADOS --}}
  <section id="cursos" class="py-5 section-light">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 class="h3 fw-bold mb-0">Cursos destacados</h2>
        <a href="#" class="link-primary">Ver todos</a>
      </div>

      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <div class="col">
          <x-course-card image="images/imagen5.jpg" title="Laravel 12 desde cero" teacher="Ana Pérez" rating="4.9" price="$19.99"/>
        </div>
        <div class="col">
          <x-course-card image="images/imagen6.jpg" title="Diseño de interfaces con Figma" teacher="Luis Gómez" rating="4.7" price="$14.99"/>
        </div>
        <div class="col">
          <x-course-card image="images/imagen7.jpg" title="SEO & Marketing de Contenidos" teacher="Camila Ruiz" rating="4.8" price="$9.99"/>
        </div>
      </div>
    </div>
  </section>

  {{-- MENTORES --}}
  <section id="mentores" class="py-5">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 class="h3 fw-bold mb-0">Conocé a nuestros mentores</h2>
        <a href="#" class="link-primary">Ver todos</a>
      </div>

      <div class="row row-cols-2 row-cols-md-4 g-4">
        <div class="col"><x-instructor-card image="images/imagen1.jpg"       name="Sofía Díaz"   role="Full-stack Dev"/></div>
        <div class="col"><x-instructor-card image="images/instructor-2.svg"  name="Marcos Vera"  role="UI/UX Designer"/></div>
        <div class="col"><x-instructor-card image="images/instructor-3.svg"  name="Julia Benítez" role="SEO Strategist"/></div>
        <div class="col"><x-instructor-card image="images/instructor-4.svg"  name="Pedro López"  role="Data Analyst"/></div>
      </div>
    </div>
  </section>

  {{-- TESTIMONIOS --}}
  <section id="opiniones" class="py-5 section-light">
    <div class="container">
      <div class="d-flex justify-content-between align-items-end mb-3">
        <h2 class="h3 fw-bold mb-0">Lo que dicen los estudiantes</h2>
        <div class="text-secondary small">4.8/5 en 2.500+ reseñas</div>
      </div>

      <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col"><x-testimonial avatar="images/imagen1.jpg"      name="Estefanía M."/></div>
        <div class="col"><x-testimonial avatar="images/instructor-2.svg" name="Rodrigo P."/></div>
        <div class="col"><x-testimonial avatar="images/instructor-3.svg" name="María S."/></div>
      </div>
    </div>
  </section>

  {{-- MAPA / SEDE + CONTACTO --}}
  <section id="contacto" class="py-5">
    <div class="container">
      <div class="row g-4 align-items-stretch">
        <div class="col-12 col-lg-6">
          <div class="card card-mentoria h-100">
            <div class="card-body">
              <h3 class="h5 fw-bold mb-3">Encontranos</h3>
              <p class="text-secondary">Sede central, Formosa — Atención de lunes a viernes 9 a 18 h.</p>
              <div class="ratio ratio-4x3 rounded-2xl overflow-hidden">
                <iframe title="Mapa Mentoria" src="https://maps.google.com/maps?q=Formosa%20Argentina&t=&z=12&ie=UTF8&iwloc=&output=embed" loading="lazy"></iframe>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-6">
          <div class="card card-mentoria h-100">
            <div class="card-body">
              <h3 class="h5 fw-bold mb-3">Escribinos</h3>
              <form class="row g-3">
                <div class="col-md-6">
                  <label class="form-label" for="name">Nombre</label>
                  <input id="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label" for="email">Email</label>
                  <input id="email" type="email" class="form-control" required>
                </div>
                <div class="col-12">
                  <label class="form-label" for="msg">Mensaje</label>
                  <textarea id="msg" rows="4" class="form-control" required></textarea>
                </div>
                <div class="col-12">
                  <button class="btn-cta" type="submit">Enviar consulta</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection
