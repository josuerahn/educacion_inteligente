<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SICEP — Dashboard del Alumno (demo)</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <style>
    body { background: #f6f8fb; color: #0f1724; }
    .card-rounded { border-radius: 14px; }
    .tutoria-card { transition: transform .16s ease, box-shadow .16s ease; }
    .tutoria-card:hover { transform: translateY(-6px); box-shadow: 0 10px 30px rgba(15,23,42,.07); }
    .avatar-circle { width:72px; height:72px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:700; background:#e7f0ff; color:#0b63d8; font-size:1.25rem; }
    .small-muted { color:#6b7280; }
    .stat-number { font-size:1.45rem; font-weight:700; }
    .tutoria-title { font-size:1.05rem; font-weight:700; }
    .btn-lg-sm { padding:.6rem .9rem; font-size:.85rem; border-radius:.75rem; }
    .chart-card { min-height:220px; display:flex; align-items:center; justify-content:center; }
  </style>
</head>
<body>
  <nav class="navbar navbar-light bg-white border-bottom mb-4">
    <div class="container-fluid">
      <div class="d-flex align-items-center gap-3">
        <svg width="40" height="40" fill="#0b63d8" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z"/>
        </svg>
        <div>
          <div class="h5 mb-0">SICEP — Dashboard del Alumno (demo)</div>
          <small class="small-muted">Seleccioná una tutoría para seguimiento — demo sin autenticación</small>
        </div>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('student.demo') }}" class="btn btn-sm btn-outline-secondary">Vista demo</a>
        <a href="#" class="btn btn-sm btn-light border">Ayuda</a>
      </div>
    </div>
  </nav>

  <main class="container">
    <div class="row g-4">

      <!-- SIDEBAR -->
      <aside class="col-12 col-lg-4">
        <div class="card card-rounded shadow-sm mb-3">
          <div class="card-body">
            @php
              $al = $alumno ?? null;
              $nombreAl = $al->name ?? ($alumno['name'] ?? 'Alumno Demo');
              $emailAl  = $al->email ?? ($alumno['email'] ?? 'alumno@demo.local');
              $initial = strtoupper(substr($nombreAl,0,1));
            @endphp

            <div class="d-flex align-items-center gap-3">
              <div class="avatar-circle">{{ $initial }}</div>
              <div>
                <div class="fw-semibold">{{ $nombreAl }}</div>
                <div class="small text-muted">{{ $emailAl }}</div>
              </div>
            </div>

            <hr class="my-3">

            <h6 class="text-uppercase small-muted mb-1">Estado de inscripción</h6>
            <p id="estado-global" class="mb-3">No estás anotado en ninguna tutoría.</p>

            <div class="d-grid">
              <button id="ver-mi-seleccion" class="btn btn-primary btn-sm btn-lg-sm">Ver mi selección</button>
            </div>
          </div>
        </div>

        <div class="card card-rounded shadow-sm mb-3">
          <div class="card-body">
            <h6 class="fw-semibold">Resumen</h6>
            <div class="row text-center mt-3">
              <div class="col-4 border-end">
                <div class="text-muted small">Total</div>
                <div id="stat-total" class="stat-number">0</div>
              </div>
              <div class="col-4 border-end">
                <div class="text-muted small">Solicitadas</div>
                <div id="stat-pendiente" class="stat-number text-warning">0</div>
              </div>
              <div class="col-4">
                <div class="text-muted small">Inscripto</div>
                <div id="stat-aceptada" class="stat-number text-success">0</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card card-rounded shadow-sm">
          <div class="card-body">
            <h6 class="fw-semibold">Información</h6>
            <p class="small text-muted mb-0">Versión demo. Acciones guardadas en tu navegador (localStorage). Para persistir en servidor se requiere autenticación y migration.</p>
          </div>
        </div>
      </aside>

      <!-- MAIN -->
      <section class="col-12 col-lg-8">
        <div class="card card-rounded shadow-sm mb-4 p-3">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="mb-0">Tutorías disponibles</h5>
              <small class="text-muted">Elegí una para anotarte — demo local</small>
            </div>

            <div class="d-flex gap-2 align-items-center">
              <div class="me-2" style="width:260px;">
                <input id="search" type="search" class="form-control form-control-sm" placeholder="Buscar por nombre, materia, horario…">
              </div>
              <button id="limpiar-estados" class="btn btn-outline-secondary btn-sm btn-lg-sm">Limpiar todo</button>
            </div>
          </div>

          <!-- gráfico y lista -->
          <div class="row g-3">
            <div class="col-12">
              <div class="card card-rounded border-0 p-3 chart-card shadow-sm">
                <canvas id="estadoChart" style="max-height:220px; width:100%;"></canvas>
              </div>
            </div>

            <div id="tutorias-grid" class="col-12">
              <div class="row g-4">
                @php
                  $lista = $tutorias ?? ['Programación','Metodología','Matemáticas','Comunicación','Desarrollo web'];
                @endphp

                @foreach($lista as $i => $t)
                  @php
                    $key = is_object($t) ? ($t->id ?? 't_'.$i) : 't_'.$i;
                    $nombre = is_object($t) ? ($t->nombre ?? ($t->name ?? 'Tutoría')) : (string)$t;
                    $materia = is_object($t) ? ($t->materia ?? null) : null;
                    $prof = is_object($t) ? ($t->profesor ?? null) : null;
                    $profName = is_array($prof) ? ($prof['name'] ?? null) : (is_object($prof) ? ($prof->name ?? null) : null);
                    $horario = is_object($t) ? ($t->horario ?? null) : null;
                    $cupos = is_object($t) ? ($t->cupos ?? null) : null;
                  @endphp

                  <div class="col-12 col-md-6">
                    <div class="card tutoria-card card-rounded p-4 h-100" data-key="{{ $key }}">
                      <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                          <div class="avatar-circle" style="width:56px;height:56px;font-size:1rem;">{{ strtoupper(substr($nombre,0,1)) }}</div>
                        </div>

                        <div class="flex-grow-1">
                          <div class="d-flex justify-content-between align-items-start">
                            <div class="me-3">
                              <h4 class="tutoria-title mb-1" title="{{ $nombre }}">{{ $nombre }}</h4>
                              <div class="text-muted small">
                                <div><strong>Materia:</strong> {{ $materia ?? '—' }}</div>
                                <div><strong>Profesor:</strong> {{ $profName ?? '—' }}</div>
                              </div>
                            </div>

                            <div class="text-end">
                              <span class="badge bg-secondary estado-badge">Disponible</span>
                            </div>
                          </div>

                          <div class="mt-3 d-flex gap-2">
                            <button class="btn btn-primary btn-solicitar btn-lg-sm" data-key="{{ $key }}">Solicitar</button>
                            <button class="btn btn-outline-secondary btn-cancelar btn-lg-sm d-none" data-key="{{ $key }}">Cancelar</button>
                            <div class="ms-auto text-muted small align-self-center">Horario: {{ $horario ?? 'A coordinar' }} · Cupos: {{ $cupos ?? '—' }}</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach

              </div>
            </div>

            <div class="col-12">
              <p class="mt-3 small text-muted mb-0">Los estados se almacenan en tu navegador (localStorage). Recarga la página para comprobar persistencia local.</p>
            </div>
          </div>
        </div>

        <!-- Profesor asignado -->
        <div class="card card-rounded shadow-sm p-3">
          <h6 class="mb-3">Profesor asignado</h6>

          @php
            $profName = $profesor->name ?? ($profesor['name'] ?? null);
            $profEmail = $profesor->email ?? ($profesor['email'] ?? null);
          @endphp

          @if($profName || $profEmail)
            <div class="d-flex align-items-center gap-3">
              @if(!empty($profesor->profile_photo ?? null))
                <img src="{{ $profesor->profile_photo_url }}" alt="Foto profesor" class="rounded-circle" style="width:64px;height:64px;object-fit:cover;">
              @else
                <div class="avatar-circle" style="width:64px;height:64px;">{{ strtoupper(substr($profName ?? 'P',0,1)) }}</div>
              @endif

              <div>
                <div class="fw-semibold">Prof. {{ $profName ?? 'Profesor Demo' }}</div>
                <div class="small text-muted">{{ $profEmail ?? 'profesor@demo.local' }}</div>
              </div>
            </div>
          @else
            <div class="small text-muted">No hay profesor asignado (modo demo).</div>
          @endif
        </div>
      </section>
    </div>
  </main>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function () {
      const STORAGE_KEY = 'sicep_tutorias_estados_demo'; // objeto { key: 'pendiente'|'aceptada' }
      const grid = document.getElementById('tutorias-grid');
      const searchInput = document.getElementById('search');
      const estadoGlobal = document.getElementById('estado-global');
      const verMiSeleccion = document.getElementById('ver-mi-seleccion');
      const limpiarBtn = document.getElementById('limpiar-estados');

      function loadEstados() { try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || {}; } catch { return {}; } }
      function saveEstados(obj) { localStorage.setItem(STORAGE_KEY, JSON.stringify(obj)); }

      function setBadge(badge, estado) {
        if (!badge) return;
        badge.className = 'badge estado-badge';
        if (estado === 'pendiente') { badge.classList.add('bg-warning','text-dark'); badge.innerText = 'Pendiente'; }
        else if (estado === 'aceptada') { badge.classList.add('bg-success'); badge.innerText = 'Inscripto'; }
        else { badge.classList.add('bg-secondary'); badge.innerText = 'Disponible'; }
      }

      function updateCard(card, estado) {
        const badge = card.querySelector('.estado-badge');
        const btnSolicitar = card.querySelector('.btn-solicitar');
        const btnCancelar = card.querySelector('.btn-cancelar');
        if (!badge || !btnSolicitar || !btnCancelar) return;

        setBadge(badge, estado);

        if (estado === 'pendiente') {
          btnSolicitar.innerText = 'En revisión…'; btnSolicitar.disabled = true; btnSolicitar.classList.add('opacity-75');
          btnCancelar.classList.remove('d-none');
        } else if (estado === 'aceptada') {
          btnSolicitar.innerText = 'Inscripto'; btnSolicitar.disabled = true; btnSolicitar.classList.add('opacity-75');
          btnCancelar.classList.add('d-none');
        } else {
          btnSolicitar.innerText = 'Solicitar'; btnSolicitar.disabled = false; btnSolicitar.classList.remove('opacity-75');
          btnCancelar.classList.add('d-none');
        }
      }

      function renderEstados() {
        const estados = loadEstados();
        const cards = document.querySelectorAll('[data-key]');
        cards.forEach(cardCol => {
          const card = cardCol.querySelector ? (cardCol.querySelector('.tutoria-card') ?? cardCol) : cardCol;
          const key = cardCol.getAttribute('data-key') ?? (card && card.getAttribute('data-key'));
          if (!key) return;
          updateCard(card, estados[key] ?? null);
        });

        // stats
        const allKeys = Array.from(document.querySelectorAll('[data-key]')).map(n => n.getAttribute('data-key'));
        const total = allKeys.length;
        const estadosObj = estados;
        const pendientes = Object.values(estadosObj).filter(v => v === 'pendiente').length;
        const aceptadas = Object.values(estadosObj).filter(v => v === 'aceptada').length;

        document.getElementById('stat-total').innerText = total;
        document.getElementById('stat-pendiente').innerText = pendientes;
        document.getElementById('stat-aceptada').innerText = aceptadas;

        // estado global
        if (pendientes === 0 && aceptadas === 0) {
          estadoGlobal.innerText = 'No estás anotado en ninguna tutoría.';
        } else {
          const firstKey = Object.keys(estadosObj).find(k => estadosObj[k] === 'pendiente' || estadosObj[k] === 'aceptada');
          estadoGlobal.innerText = firstKey ? `Selección: ${firstKey} — ${estadosObj[firstKey]}` : 'Tienes selecciones guardadas';
        }

        // actualizar gráfico
        updateChart(pendientes, aceptadas, total - pendientes - aceptadas);
      }

      // Chart.js inicial
      let estadoChart = null;
      function createChart() {
        const ctx = document.getElementById('estadoChart').getContext('2d');
        estadoChart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: ['Pendiente','Inscripto','Disponible'],
            datasets: [{
              data: [0,0,0],
              backgroundColor: ['#f59e0b','#10b981','#94a3b8'],
              borderWidth: 0
            }]
          },
          options: {
            plugins: {
              legend: { position: 'bottom', labels: { boxWidth:12, padding:12 } },
              tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw}` } }
            },
            maintainAspectRatio: false,
            cutout: '60%'
          }
        });
      }

      function updateChart(pendiente, aceptada, disponible) {
        if (!estadoChart) return;
        estadoChart.data.datasets[0].data = [pendiente, aceptada, disponible];
        estadoChart.update();
      }

      // eventos sobre grid (delegación)
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

      // búsqueda local
      if (searchInput) {
        searchInput.addEventListener('input', (e) => {
          const q = e.target.value.toLowerCase().trim();
          document.querySelectorAll('[data-key]').forEach(col => {
            const text = col.textContent.toLowerCase();
            col.style.display = text.includes(q) ? '' : 'none';
          });
        });
      }

      // ver selección
      if (verMiSeleccion) {
        verMiSeleccion.addEventListener('click', () => {
          const estados = loadEstados();
          const keys = Object.keys(estados);
          if (keys.length === 0) { return alert('No tenés ninguna selección guardada.'); }
          const lista = keys.map(k => `${k} — ${estados[k]}`).join('\n');
          alert('Tus selecciones:\n' + lista);
        });
      }

      // limpiar todo
      if (limpiarBtn) {
        limpiarBtn.addEventListener('click', () => {
          if (!confirm('¿Eliminar todas las selecciones guardadas en este navegador?')) return;
          localStorage.removeItem(STORAGE_KEY);
          renderEstados();
        });
      }

      // init
      createChart();
      renderEstados();
    })();
  </script>
</body>
</html>

