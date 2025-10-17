
<div class="container py-3">
  <div class="card card-rounded shadow-sm p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h5 class="mb-0">Mis tareas</h5>
        <small class="text-muted">Descargá la tarea del profesor y subí tu entrega</small>
      </div>
    </div>
  </div>

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row g-4">
    @forelse($tareas as $tarea)
      @php
        $deadline = $tarea->fecha_entrega ?? null;
        $fileUrl = !empty($tarea->archivo) ? route('student.tareas.download', $tarea->id) : null;
        $prof = $tarea->tutoria->profesor ?? null;
        $profPhoto = $prof->profile_photo_url ?? ($prof->profile_photo ? asset('storage/'.$prof->profile_photo) : null);
        $entregas = \App\Models\Entrega::where('tarea_id',$tarea->id)->where('alumno_id', auth()->id())->orderBy('created_at','desc')->get();
      @endphp

      <div class="col-12">
        <div class="card p-3 card-rounded tutoria-card">
          <div class="row align-items-center">
            <div class="col-md-8">
              <div class="d-flex align-items-start gap-3">
                <div>
                  @if($profPhoto)
                    <img src="{{ $profPhoto }}" alt="Profesor" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                  @else
                    <div class="avatar-circle" style="width:48px;height:48px;font-size:.9rem;">{{ strtoupper(substr($prof->name ?? ($tarea->titulo ?? 'T'),0,1)) }}</div>
                  @endif
                </div>
                <div>
                  <div class="fw-semibold">{{ $tarea->titulo ?? 'Tarea #' . $tarea->id }}</div>
                  <div class="small text-muted">{{ Str::limit($tarea->descripcion ?? '', 160) }}</div>
                  <div class="small text-muted mt-1">Tutoría: {{ $tarea->tutoria->nombre ?? $tarea->tutoria->name ?? '—' }}</div>
                </div>
              </div>
            </div>

            <div class="col-md-4 text-end">
              <div class="small text-muted">Entrega: {{ $deadline ? \Carbon\Carbon::parse($deadline)->format('d M Y H:i') : 'A coordinar' }}</div>
              <div class="mt-2 d-flex justify-content-end gap-2">
                @if($fileUrl)
                  <a href="{{ $fileUrl }}" class="btn btn-outline-secondary btn-sm" target="_blank">Descargar tarea</a>
                @endif
                <button wire:click="startUpload({{ $tarea->id }})" class="btn btn-primary btn-sm">Subir entrega</button>
              </div>
            </div>
          </div>

          @if($uploadingFor == $tarea->id)
            <hr>
            <form wire:submit.prevent="uploadEntrega({{ $tarea->id }})">
              <div class="row g-2 align-items-center">
                <div class="col-md-6">
                  <input type="file" wire:model="archivo" class="form-control form-control-sm" />
                  @error('archivo') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                  <input type="text" wire:model="comentario" class="form-control form-control-sm" placeholder="Comentario (opcional)" />
                </div>
                <div class="col-md-2 text-end">
                  <button type="button" wire:click="cancelUpload()" class="btn btn-outline-secondary btn-sm">Cancelar</button>
                  <button type="submit" class="btn btn-success btn-sm">Enviar</button>
                </div>
              </div>
            </form>
          @endif

          @if($entregas->isNotEmpty())
            <hr>
            <div class="small text-muted">Tus entregas:</div>
            <ul class="list-unstyled mb-0">
              @foreach($entregas as $ent)
                <li class="d-flex align-items-center justify-content-between py-1">
                  <div>
                    <strong>{{ $ent->created_at->format('d/m H:i') }}</strong>
                    <span class="text-muted small ms-2">{{ $ent->comentario }}</span>
                    @if($ent->calificacion !== null)
                      <span class="badge bg-success ms-2">Nota: {{ $ent->calificacion }}</span>
                    @endif
                  </div>
                  <div>
                    <a href="{{ route('student.entregas.download', $ent->id) }}" class="btn btn-outline-secondary btn-sm">Descargar</a>
                  </div>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>

    @empty
      <div class="col-12">
        <div class="card p-3 text-center">
          No hay tareas disponibles por ahora.
        </div>
      </div>
    @endforelse
  </div>
</div>
