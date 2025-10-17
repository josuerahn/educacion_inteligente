<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>SICEP — Dashboard del Alumno</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <style>
    body {
      background: #f6f8fb;
      color: #0f1724;
    }

    .card-rounded {
      border-radius: 14px;
    }

    .avatar-circle {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      background: #e7f0ff;
      color: #0b63d8;
      font-size: 1.25rem;
    }

    .tutoria-card {
      transition: transform .12s ease, box-shadow .12s ease;
    }

    .tutoria-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
    }

    .stat-number {
      font-size: 1.35rem;
      font-weight: 700;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-light bg-white border-bottom mb-4">
    <div class="container-fluid">
      <div class="d-flex align-items-center gap-3">
        <svg width="36" height="36" fill="#0b63d8" viewBox="0 0 24 24">
          <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z" />
        </svg>
        <div>
          <div class="h6 mb-0">SICEP — Dashboard del Alumno</div>
          <small class="text-muted">Progreso, promedio y experiencia</small>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-danger">Cerrar sesión</button>
        </form>
      </div>

    </div>
  </nav>

  <main class="container">
    <div class="row g-4">

      <!-- SIDEBAR -->
      <aside class="col-12 col-lg-4">
        <div class="card card-rounded shadow-sm mb-3 p-3">
          @php
          $nombreAl = $alumno->name ?? ($alumno['name'] ?? 'Alumno Demo');
          $emailAl = $alumno->email ?? ($alumno['email'] ?? 'alumno@demo.local');
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

          <h6 class="text-uppercase small text-muted mb-1">Resumen</h6>
          <div class="d-flex gap-3 align-items-center">
            <div>
              <div class="text-muted small">Tutorías</div>
              <div class="stat-number">{{ $studentStats['total_tutorias'] ?? 0 }}</div>
            </div>
            <div>
              <div class="text-muted small">Tareas</div>
              <div class="stat-number">{{ $studentStats['total_tareas'] ?? 0 }}</div>
            </div>
            <div>
              <div class="text-muted small">Entregadas</div>
              <div class="stat-number">{{ $studentStats['submitted'] ?? 0 }}</div>
            </div>
          </div>

          <hr class="my-3">

          <div>
            <div class="small text-muted">Promedio de notas</div>
            <div class="h4">{{ $studentStats['avg_grade'] !== null ? $studentStats['avg_grade'] : '—' }}</div>
            <div class="small text-muted mt-2">Experiencia (XP)</div>
            <div class="fw-semibold">{{ $studentStats['xp'] ?? 0 }} xp</div>
          </div>
        </div>
<div class="card card-rounded shadow-sm p-3 mb-4">
  <div class="d-flex align-items-center justify-content-between">
    <div>
      <h5 class="mb-0">Inscribirme a Tutorías</h5>
      <small class="text-muted">Ver profesores, horarios y cupos</small>
    </div>
    <a href="{{ route('tutorias.index') }}" class="btn btn-primary">
      Ver tutorías disponibles
    </a>
  </div>
</div>
       
        <div class="card card-rounded shadow-sm p-3">
          <h6 class="mb-2">Detalles rápidos</h6>
          <ul class="list-unstyled small text-muted mb-0">
            <li>Tareas aprobadas: {{ $studentStats['approved'] ?? 0 }}</li>
            <li>Entregas realizadas: {{ $studentStats['submitted'] ?? 0 }}</li>
            <li>Porcentaje general:
              @php
              $total = ($studentStats['total_tareas'] ?? 0);
              $pct = $total ? round((($studentStats['submitted'] ?? 0) / $total) * 100) : 0;
              @endphp
              {{ $pct }}%
            </li>
          </ul>
        </div>
      </aside>

      
      <!-- ACCESO A TUTORÍAS -->



      <!-- MAIN -->
      <section class="col-12 col-lg-8">
        <div class="card card-rounded shadow-sm p-3 mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h5 class="mb-0">Progreso y desempeño</h5>
              <small class="text-muted">Gráficos por tutoría y estado general</small>
            </div>
          </div>

          <div class="row g-3">
            <div class="col-12 col-md-5">
              <div class="card p-3 chart-card">
                <canvas id="donutEstado"></canvas>
              </div>
            </div>

            <div class="col-12 col-md-7">
              <div class="card p-3">
                <canvas id="barTutoria" style="height:220px"></canvas>
              </div>
            </div>

            <div class="col-12">
              {{-- Incluir componente de tareas para que el alumno vea y suba entregas --}}
              @livewire('student.tareas')
            </div>

          </div>
        </div>
      </section>

    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  // datos del servidor (Blade -> JS)
  window.SICEP_USER_STATS = <?php echo json_encode($studentStats ?? []); ?>;
  window.SICEP_PER_TUTORIA = <?php echo json_encode($perTutoria ?? []); ?>;

    (function() {
      const stats = window.SICEP_USER_STATS || {};
      const perTut = window.SICEP_PER_TUTORIA || [];

      // Donut: entregadas / aprobadas / pendientes
      const submitted = stats.submitted || 0;
      const approved = stats.approved || 0;
      const total = stats.total_tareas || (Math.max(submitted, approved, 1));
      const pendientes = Math.max(total - submitted, 0);

      const donutCtx = document.getElementById('donutEstado').getContext('2d');
      new Chart(donutCtx, {
        type: 'doughnut',
        data: {
          labels: ['Entregadas', 'Aprobadas', 'Pendientes'],
          datasets: [{
            data: [submitted, approved, pendientes],
            backgroundColor: ['#0ea5a4', '#10b981', '#94a3b8']
          }]
        },
        options: {
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          cutout: '60%'
        }
      });

      // Bar: progreso por tutoría (percent)
      const barCtx = document.getElementById('barTutoria').getContext('2d');
      const labels = perTut.map(p => p.nombre);
      const dataPercent = perTut.map(p => p.percent);
      new Chart(barCtx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'Progreso %',
            data: dataPercent,
            backgroundColor: '#3b82f6'
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              max: 100
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    })();
  </script>
</body>

</html>