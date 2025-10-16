<div class="min-h-screen bg-slate-50 text-slate-800">
  <!-- HEADER -->
  <header class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <div class="flex items-center gap-3">
          <svg class="w-10 h-10 text-blue-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <div>
            <h1 class="text-lg sm:text-xl font-semibold">SICEP — Dashboard del Alumno (demo)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Seleccioná una tutoría para seguimiento — demo sin autenticación</p>
          </div>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <a href="{{ route('student.demo') }}" class="text-sm px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200">Vista demo</a>
        <a href="#" class="text-sm px-3 py-1.5 rounded-lg bg-white border hover:bg-slate-50">Ayuda</a>
      </div>
    </div>
  </header>

  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- SIDEBAR: Alumno + estado -->
    <aside class="lg:col-span-1 space-y-6">
      <section class="bg-white rounded-2xl border p-4 shadow-sm">
        @php
          $al = $alumno ?? null;
          $nombreAl = $al?->name ?? 'Alumno Demo';
          $emailAl  = $al?->email ?? 'alumno@demo.local';
          $initial = strtoupper(substr($nombreAl,0,1));
        @endphp

        <div class="flex items-center gap-4">
          <div class="h-16 w-16 rounded-full bg-slate-200 flex items-center justify-center text-xl font-semibold text-slate-700">
            {{ $initial }}
          </div>
          <div>
            <div class="text-sm font-medium">{{ $nombreAl }}</div>
            <div class="text-xs text-slate-500">{{ $emailAl }}</div>
          </div>
        </div>

        <hr class="my-4 border-slate-100">

        <div>
          <h3 class="text-xs font-medium text-slate-600 uppercase tracking-wide">Estado de inscripción</h3>
          <p id="estado-global" class="mt-2 text-sm text-slate-600">No estás anotado en ninguna tutoría.</p>
        </div>

        <div class="mt-4">
          <button id="ver-mi-seleccion" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700">
            Ver mi selección
          </button>
        </div>
      </section>

      <section class="bg-white rounded-2xl border p-4 shadow-sm">
        <h4 class="text-sm font-semibold mb-2">Información</h4>
        <p class="text-sm text-slate-600">Esta versión es una demo estática. Las acciones se guardan en tu navegador (localStorage). Para persistir en servidor se requiere autenticación y una migration.</p>
      </section>
    </aside>

    <!-- MAIN: Lista de tutorías -->
    <section class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-2xl border p-4 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h2 class="text-lg font-semibold">Tutorías disponibles</h2>
            <p class="text-sm text-slate-500">Elegí una para anotarte — demo local</p>
          </div>

          <div class="w-full sm:w-72">
            <input id="search" type="search" placeholder="Buscar por nombre, materia, horario…" aria-label="Buscar tutorías"
              class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-100">
          </div>
        </div>

        <div class="mt-4">
          <div id="tutorias-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @php
              $lista = $tutorias ?? ['Programación','Metodología','Matemáticas','Comunicación','Desarrollo web'];
            @endphp

            @foreach($lista as $i => $t)
              @php
                $key = is_object($t) ? ($t->id ?? 't_'.$i) : 't_'.$i;
                $nombre = is_object($t) ? ($t->nombre ?? ($t->name ?? 'Tutoría')) : (string) $t;
                $materia = is_object($t) ? ($t->materia ?? null) : null;
                $prof = is_object($t) ? ($t->profesor ?? null) : null;
                $profName = is_array($prof) ? ($prof['name'] ?? null) : (is_object($prof) ? ($prof->name ?? null) : null);
                $horario = is_object($t) ? ($t->horario ?? null) : null;
                $cupos = is_object($t) ? ($t->cupos ?? null) : null;
              @endphp

              <article class="tutoria-card bg-white border rounded-2xl p-4 shadow-sm hover:shadow-md transition" data-key="{{ $key }}" aria-labelledby="tut-{{ $key }}">
                <div class="flex items-start justify-between gap-3">
                  <div class="flex-1 min-w-0">
                    <h3 id="tut-{{ $key }}" class="font-semibold text-slate-800 truncate">{{ $nombre }}</h3>
                    <div class="mt-2 text-sm text-slate-600 space-y-1">
                      <div><span class="font-medium text-slate-700">Materia:</span> {{ $materia ?? '—' }}</div>
                      <div><span class="font-medium text-slate-700">Profesor:</span> {{ $profName ?? '—' }}</div>
                      <div><span class="font-medium text-slate-700">Horario:</span> {{ $horario ?? 'A coordinar' }}</div>
                      <div><span class="font-medium text-slate-700">Cupos:</span> {{ $cupos ?? '—' }}</div>
                    </div>
                  </div>

                  <div class="flex flex-col items-end gap-2">
                    <span class="estado-badge text-[11px] px-2 py-1 rounded-lg border bg-slate-50 text-slate-600 border-slate-200">Disponible</span>

                    <div class="flex items-center gap-2">
                      <button class="btn-solicitar inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white hover:bg-blue-700"
                        data-key="{{ $key }}" aria-label="Solicitar inscripción en {{ $nombre }}">
                        Solicitar
                      </button>

                      <button class="btn-cancelar hidden inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50"
                        data-key="{{ $key }}" aria-label="Cancelar solicitud de {{ $nombre }}">
                        Cancelar
                      </button>
                    </div>
                  </div>
                </div>
              </article>
            @endforeach
          </div>
        </div>

        <p class="mt-4 text-sm text-slate-500">Los estados se almacenan en tu navegador (localStorage). Recarga la página para comprobar persistencia local.</p>
      </div>

      <!-- Profesor asignado -->
      <div class="bg-white rounded-2xl border p-4 shadow-sm">
        <h3 class="text-sm font-semibold mb-3">Profesor asignado</h3>
        @if(isset($profesor) && $profesor)
          <div class="flex items-center gap-4">
            @if(!empty($profesor->profile_photo))
              <img src="{{ asset('storage/'.$profesor->profile_photo) }}" alt="Foto profesor" class="h-14 w-14 rounded-full object-cover">
            @else
              <div class="h-14 w-14 rounded-full bg-slate-200 flex items-center justify-center font-semibold text-slate-600">
                {{ strtoupper(substr($profesor->name ?? 'P',0,1)) }}
              </div>
            @endif

            <div>
              <div class="font-medium text-slate-800">Prof. {{ $profesor->name ?? 'Profesor Demo' }}</div>
              <div class="text-sm text-slate-500">{{ $profesor->email ?? 'profesor@demo.local' }}</div>
            </div>
          </div>
        @else
          <div class="text-sm text-slate-600">No hay profesor asignado (modo demo).</div>
        @endif
      </div>
    </section>
  </main>

  <script>
    (function () {
      const STORAGE_KEY = 'sicep_tutorias_estados_demo'; // { key: 'pendiente'|'aceptada' }
      const grid = document.getElementById('tutorias-grid');
      const searchInput = document.getElementById('search');
      const estadoGlobal = document.getElementById('estado-global');
      const verMiSeleccion = document.getElementById('ver-mi-seleccion');

      function loadEstados() {
        try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; }
        catch { return {}; }
      }
      function saveEstados(obj) { localStorage.setItem(STORAGE_KEY, JSON.stringify(obj)); }

      function updateCard(card, estado) {
        const badge = card.querySelector('.estado-badge');
        const btnSolicitar = card.querySelector('.btn-solicitar');
        const btnCancelar = card.querySelector('.btn-cancelar');

        if (!badge || !btnSolicitar || !btnCancelar) return;

        if (estado === 'pendiente') {
          badge.textContent = 'Pendiente';
          badge.className = 'estado-badge text-[11px] px-2 py-1 rounded-lg border bg-amber-50 text-amber-700 border-amber-200';
          btnSolicitar.textContent = 'En revisión…';
          btnSolicitar.disabled = true;
          btnSolicitar.classList.add('opacity-50', 'cursor-not-allowed');
          btnCancelar.classList.remove('hidden');
        } else if (estado === 'aceptada') {
          badge.textContent = 'Inscripto';
          badge.className = 'estado-badge text-[11px] px-2 py-1 rounded-lg border bg-emerald-50 text-emerald-700 border-emerald-200';
          btnSolicitar.textContent = 'Inscripto';
          btnSolicitar.disabled = true;
          btnSolicitar.classList.add('opacity-50', 'cursor-not-allowed');
          btnCancelar.classList.add('hidden');
        } else {
          badge.textContent = 'Disponible';
          badge.className = 'estado-badge text-[11px] px-2 py-1 rounded-lg border bg-slate-50 text-slate-600 border-slate-200';
          btnSolicitar.textContent = 'Solicitar';
          btnSolicitar.disabled = false;
          btnSolicitar.classList.remove('opacity-50', 'cursor-not-allowed');
          btnCancelar.classList.add('hidden');
        }
      }

      function renderEstados() {
        const estados = loadEstados();
        document.querySelectorAll('.tutoria-card').forEach(card => {
          const key = card.getAttribute('data-key');
          updateCard(card, estados[key] ?? null);
        });
        // actualizar estado global (muestra la primera pendiente/aceptada si existe)
        const keys = Object.keys(loadEstados());
        if (keys.length === 0) {
          estadoGlobal.textContent = 'No estás anotado en ninguna tutoría.';
        } else {
          // mostrar primer estado significativo
          for (const k of keys) {
            const e = statesValue(loadEstados()[k]);
            if (e) { estadoGlobal.textContent = `Seleccion: ${k} — ${loadEstados()[k]}`; break; }
          }
        }
      }

      function statesValue(v) {
        return v === 'pendiente' || v === 'aceptada' ? v : null;
      }

      if (grid) {
        grid.addEventListener('click', (e) => {
          const solicitar = e.target.closest('.btn-solicitar');
          const cancelar = e.target.closest('.btn-cancelar');

          if (solicitar) {
            const key = solicitar.getAttribute('data-key');
            const estados = loadEstados();
            estados[key] = 'pendiente';
            saveEstados(estados);
            renderEstados();
            solicitar.blur();
            return;
          }

          if (cancelar) {
            const key = cancelar.getAttribute('data-key');
            const estados = loadEstados();
            if (estados[key]) delete estados[key];
            saveEstados(estados);
            renderEstados();
            cancelar.blur();
            return;
          }
        });
      }

      if (searchInput) {
        searchInput.addEventListener('input', (e) => {
          const q = e.target.value.toLowerCase().trim();
          document.querySelectorAll('.tutoria-card').forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(q) ? '' : 'none';
          });
        });
      }

      if (verMiSeleccion) {
        verMiSeleccion.addEventListener('click', () => {
          const estados = loadEstados();
          const keys = Object.keys(estados);
          if (keys.length === 0) {
            alert('No tenés ninguna selección guardada.');
            return;
          }
          const lista = keys.map(k => `${k} — ${estados[k]}`).join('\n');
          alert('Tus selecciones:\n' + lista);
        });
      }

      // render inicial
      renderEstados();
    })();
  </script>
</div>

