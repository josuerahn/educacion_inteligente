@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-[#223040] text-white flex flex-col items-center py-12 px-4">
  <div class="max-w-2xl w-full bg-white/5 rounded-lg shadow p-8 flex flex-col items-center">
    <h1 class="text-3xl font-bold mb-6 text-center">Acerca de la UTN Facultad Regional Resistencia</h1>
    <p class="mb-4 text-white/80 text-center">
      La UTN Facultad Regional Resistencia es una institución líder en formación tecnológica, comprometida con el desarrollo académico y personal de sus estudiantes. Nuestro objetivo es crear un ambiente donde cada estudiante se sienta cómodo, acompañado y motivado a superarse día a día.
    </p>
    <p class="mb-6 text-white/80 text-center">
      A través de tutorías inteligentes, brindamos apoyo personalizado, promoviendo el bienestar, la inclusión y el crecimiento profesional en el ámbito de la tecnología y la ingeniería.
    </p>
    <h2 class="text-2xl font-bold mb-4 text-center">Objetivos de las Tutorías Inteligentes</h2>
    <ul class="list-disc pl-6 space-y-2 text-white/80 text-left w-full max-w-xl mx-auto mb-6">
      <li>Fomentar el crecimiento académico y personal de los estudiantes en carreras tecnológicas.</li>
      <li>Ofrecer acompañamiento personalizado mediante inteligencia artificial y tutores humanos.</li>
      <li>Promover la inclusión, el bienestar y la motivación en el proceso de aprendizaje.</li>
      <li>Impulsar la formación en tecnología y la innovación educativa en la región.</li>
    </ul>
    <div class="mt-6 text-white/80 text-center">
      <h2 class="text-xl font-bold mb-2">Más información sobre la UTN</h2>
      <p>La UTN FRRe cuenta con carreras de grado, posgrado, investigación, extensión universitaria y una amplia comunidad de estudiantes y docentes comprometidos con la excelencia académica y el desarrollo tecnológico de la región.</p>
      <p class="mt-2">Para conocer más, visita el <a href="https://www.frre.utn.edu.ar/" target="_blank" class="underline text-[#FF2D20]">sitio oficial de la UTN FRRe</a>.</p>
    </div>
  </div>
</div>
@endsection
