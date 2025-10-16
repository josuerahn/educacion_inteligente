<!doctype html>
<html lang="es" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Mentoria – Educación & Cursos')</title>
  <meta name="description" content="@yield('meta_description', 'Potenciá tu aprendizaje con cursos y mentores verificados.')">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  {{-- Google Fonts (opcional) y estilos --}}
    {{-- Google Fonts opcional (si las necesitás) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    {{-- Carga rápida de Bootstrap CSS CDN para estilos base y responsive --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUa6Yk0h2e6KcQ2h5Yh4Y8Y6qY7Q6K8l6Z6hcY1bZ6v6Q6Y6Z6Q6Y" crossorigin="anonymous">
    {{-- CSS fallback compilado mínimo (public/css) --}}
    <link rel="stylesheet" href="{{ asset('css/stilelanding.css') }}">
    {{-- Vite para JS (mantener) --}}
    @vite(['resources/js/app.js'])
</head>
<body>
  @include('partials._navbar')

  <main class="flex-grow-1">
    @yield('content')
  </main>

  @include('partials._footer')
</body>
</html>
