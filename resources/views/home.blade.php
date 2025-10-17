<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tutorías Inteligentes</title>
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <!-- Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans">
  <div class="min-h-screen text-white relative" style="background: #223040;">
    <div class="dots-bg"></div>
    <header class="max-w-6xl mx-auto px-6 pt-4 pb-8 flex flex-row items-center justify-between">
      <div class="flex items-center gap-4">
        <img src="https://scontent-eze1-1.xx.fbcdn.net/v/t39.30808-6/326793251_741690590462725_5456463394634643659_n.jpg?_nc_cat=106&ccb=1-7&_nc_sid=6ee11a&_nc_ohc=FStwGBZ7E2UQ7kNvwEQ8jYg&_nc_oc=Adl5lbvc-cxqDV1WF2TRH95nD2VYfqQbytQqa7S1gcFa0hiwis9pET4dCQNyEzWknME&_nc_zt=23&_nc_ht=scontent-eze1-1.xx&_nc_gid=kTVGGMEUdbQ9XkqdtoZfwg&oh=00_AfdvopcwGy23COfnFlZPUCXHzEfedLi06c7QItel_MUEtA&oe=68F7C130" alt="Logo UTN FRRe" class="w-32 h-32 rounded shadow animate-shine" loading="lazy">
        <span class="font-semibold text-2xl tracking-wide">Tutorías Inteligentes UTN FRRe</span>
      </div>
      <div class="flex-1"></div>
      <nav class="flex flex-wrap items-center gap-2 md:gap-6 justify-end">
        <a href="#how" class="hover:underline">Cómo funciona</a>
        <a href="{{ route('register') }}" class="rounded-md bg-[#FF2D20] px-4 py-2 text-white">Registrarse</a>
        @guest
          <a href="{{ route('login') }}" class="rounded-md bg-[#2c3e56] px-4 py-2 text-white ml-2 border border-white/20 hover:bg-[#223040] transition">Iniciar sesión</a>
        @else
          <a href="{{ route('dashboard') }}" class="ml-2 text-sm text-black/70 dark:text-white/70 hover:underline">Mi dashboard</a>
        @endguest
      </nav>
    </header>

  <main class="max-w-6xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8 lg:gap-24">
      <section class="space-y-6 flex flex-col items-start text-left w-full lg:max-w-xl lg:pr-0">
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">
          Sistema de Tutorías Inteligentes con IA
        </h1>
        <p class="text-lg text-black/70 dark:text-white/70 max-w-xl">
          Tutorías personalizadas que se adaptan al ritmo del estudiante usando modelos de IA y seguimiento pedagógico.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 items-start">
          <a href="#features" class="inline-block text-sm text-black/70 dark:text-white/70">Ver características</a>
        </div>

        <div id="contact" class="mt-6 w-full max-w-xl">
          <!-- Si usas Livewire, cambia form por: <form wire:submit.prevent="submit"> y añade wire:model en inputs -->
          <form id="landing-form" action="#" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#2c3e56] p-6 rounded-lg shadow">
            @csrf
            <input name="name" placeholder="Nombre" class="rounded border p-3 bg-[#223040]/20 text-white placeholder-white/70" />
            <input name="email" type="email" placeholder="Email" class="rounded border p-3 bg-[#223040]/20 text-white placeholder-white/70" />
            <textarea name="message" placeholder="Mensaje (opcional)" class="col-span-1 sm:col-span-2 rounded border p-3 h-28 bg-[#223040]/20 text-white placeholder-white/70"></textarea>
            <div class="col-span-1 sm:col-span-2 flex justify-end">
              <button type="submit" class="rounded bg-[#223040] px-5 py-2 text-white font-medium hover:bg-[#1a2533]">Enviar</button>
            </div>
          </form>
        </div>
      </section>

      <aside class="space-y-6 flex flex-col items-start w-full lg:max-w-md lg:pl-0">
        <ul id="features" class="space-y-4 w-full">
          <li class="flex gap-4 items-start p-4 bg-[#2c3e56] rounded-lg shadow">
            <div class="p-3 bg-[#223040]/20 rounded-full">🔍</div>
            <div>
              <h3 class="font-semibold text-white">Tutoría personalizada</h3>
              <p class="text-sm text-white/80">Planes adaptados al nivel del estudiante.</p>
            </div>
          </li>
          <li class="flex gap-4 items-start p-4 bg-[#2c3e56] rounded-lg shadow">
            <div class="p-3 bg-[#223040]/20 rounded-full">⚙️</div>
            <div>
              <h3 class="font-semibold text-white">Integración IA</h3>
              <p class="text-sm text-white/80">Recomendaciones y ejercicios automáticos.</p>
            </div>
          </li>
          <li class="flex gap-4 items-start p-4 bg-[#2c3e56] rounded-lg shadow">
            <div class="p-3 bg-[#223040]/20 rounded-full">📊</div>
            <div>
              <h3 class="font-semibold text-white">Seguimiento</h3>
              <p class="text-sm text-white/80">Dashboards para docentes y tutores.</p>
            </div>
          </li>
        </ul>
      </aside>


      <!-- Sección Institucional y Objetivos, ordenada y simétrica -->
      <section class="w-full mt-16 flex flex-col items-center gap-8">
  <div class="max-w-3xl w-full bg-gradient-to-br from-[#3a4a63] via-[#5a6b85] to-[#223040] rounded-lg shadow p-8 flex flex-col items-center">
          <h2 class="text-2xl font-bold mb-4 text-center">Acerca de la UTN Facultad Regional Resistencia</h2>
          <p class="mb-4 text-white/80 text-center">
            La UTN Facultad Regional Resistencia es una institución líder en formación tecnológica, comprometida con el desarrollo académico y personal de sus estudiantes. Nuestro objetivo es crear un ambiente donde cada estudiante se sienta cómodo, acompañado y motivado a superarse día a día.
          </p>
          <p class="mb-6 text-white/80 text-center">
            A través de tutorías inteligentes, brindamos apoyo personalizado, promoviendo el bienestar, la inclusión y el crecimiento profesional en el ámbito de la tecnología y la ingeniería.
          </p>
          <h2 class="text-2xl font-bold mb-4 text-center">Objetivos de las Tutorías Inteligentes</h2>
          <ul class="list-disc pl-6 space-y-2 text-white/80 text-left w-full max-w-xl mx-auto">
            <li>Fomentar el crecimiento académico y personal de los estudiantes en carreras tecnológicas.</li>
            <li>Ofrecer acompañamiento personalizado mediante inteligencia artificial y tutores humanos.</li>
            <li>Promover la inclusión, el bienestar y la motivación en el proceso de aprendizaje.</li>
            <li>Impulsar la formación en tecnología y la innovación educativa en la región.</li>
          </ul>
        </div>
      </section>

    </main>

    <footer class="w-full px-6 py-8 text-sm text-black/60 dark:text-white/60 flex justify-center items-center fixed bottom-0 left-0 bg-transparent">
      © {{ date('Y') }} Tutorías Inteligentes — Todos los derechos reservados
    </footer>
  </div>
</html>