<div class="min-h-[80vh] bg-slate-50">
  <!-- HEADER -->
  <header class="bg-white border-b">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8v-10h-8v10zm0-18v6h8V3h-8z" />
        </svg>
        <h1 class="text-2xl font-bold text-blue-700">SICEP - Dashboard del Alumno</h1>
      </div>
      <div class="flex gap-2 flex-wrap">

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Cerrar sesión</button>
        </form>
      </div>
    </div>
  </header>

  <main class="mx-auto max-w-6xl px-4 sm:px-6 py-4 space-y-4 sm:space-y-6">

    <!-- MIS DATOS -->
    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div class="flex items-center justify-between px-4 sm:px-5 py-3 border-b border-slate-100">
        <h2 class="text-slate-800 font-semibold">Mis Datos</h2>

        @if(!$editando)
          <button wire:click="habilitarEdicion"
                  class="inline-flex items-center gap-1.5 text-slate-600 hover:text-slate-800 text-xs font-medium rounded-lg border border-slate-200 bg-white px-2.5 py-1.5">
            <svg class="h-4 w-4 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4h2m-1 0v4m0 0l7 7M4 13l7-7"/></svg>
            Editar
          </button>
        @endif
      </div>

      <div class="px-4 sm:px-6 py-5">
        <!-- AVATAR -->
        <div class="mb-4">
          @if($alumno->profile_photo)
            <img src="{{ asset('storage/'.$alumno->profile_photo) }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-blue-50" alt="Foto">
          @else
            <div class="h-16 w-16 rounded-full bg-slate-200 ring-2 ring-slate-100 flex items-center justify-center text-slate-600 font-semibold">
              {{ strtoupper(substr($alumno->name,0,1)) }}
            </div>
          @endif
        </div>

        @if (session()->has('mensaje'))
          <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-emerald-800 text-sm">
            {{ session('mensaje') }}
          </div>
        @endif

        @if(!$editando)
          <!-- LECTURA -->
          <div class="grid sm:grid-cols-2 gap-4">
            @php
              $dni = $alumno->dni ?? null;
              $fecha = $alumno->fecha_nacimiento ?? null; // usa el nombre real del campo
            @endphp

            <div class="space-y-1">
              <label class="text-[12px] text-slate-500">Nombre completo</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">{{ $nombre ?? $alumno->name }}</div>
            </div>

            <div class="space-y-1">
              <label class="text-[12px] text-slate-500">Correo electrónico</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800 truncate">{{ $email ?? $alumno->email }}</div>
            </div>

            <div class="space-y-1">
              <label class="text-[12px] text-slate-500">WhatsApp</label>
              <div class="relative">
                <div class="rounded-xl border border-slate-200 bg-slate-50 pl-3 pr-9 py-2.5 text-sm text-slate-800">{{ $whatsapp ?: ($alumno->whatsapp ?? '—') }}</div>
                <span class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500">
                  <!-- ícono globo -->
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15a4 4 0 01-4 4H7l-4 4V7a4 4 0 014-4h10a4 4 0 014 4v8z" stroke-width="2"/></svg>
                </span>
              </div>
            </div>

          <div class="space-y-1">
              <label class="text-[12px] text-slate-500">Curso</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                {{ $alumno->course ? $alumno->course->name : '—' }}
              </div>
            </div>

            <div class="space-y-1">
              <label class="text-[12px] text-slate-500">DNI</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">{{ $dni ?: '—' }}</div>
            </div>

            <div class="space-y-1">
              <label class="text-[12px] text-slate-500">Carrera</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">{{ $carrera ?: ($alumno->carrera ?? '—') }}</div>
            </div>

        

            <div class="space-y-1 sm:col-span-2">
              <label class="text-[12px] text-slate-500">Fecha de nacimiento</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                {{ $fecha ? \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') : '—' }}
              </div>
            </div>

            <div class="space-y-1 sm:col-span-2">
              <label class="text-[12px] text-slate-500">Redes sociales</label>
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-800">
                @php
                  $sp = $alumno->socialProfiles->first();
                @endphp
                @if($sp && ($sp->linkedin || $sp->github || $sp->gitlab || $sp->wordpress || $sp->notion))
                  <div class="flex flex-wrap gap-2">
                    @if($sp->linkedin)
                      <a href="{{ $sp->linkedin }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-xs border-slate-200 text-blue-700 bg-white hover:bg-slate-50">
                        <span class="i">in</span> LinkedIn
                      </a>
                    @endif
                    @if($sp->github)
                      <a href="{{ $sp->github }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-xs border-slate-200 text-slate-700 bg-white hover:bg-slate-50">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .5C5.73.5.98 5.24.98 11.5c0 4.85 3.14 8.96 7.49 10.41.55.11.75-.24.75-.53 0-.26-.01-1.12-.02-2.03-3.05.66-3.7-1.3-3.7-1.3-.5-1.27-1.23-1.6-1.23-1.6-.99-.68.08-.67.08-.67 1.1.08 1.68 1.14 1.68 1.14.97 1.67 2.55 1.19 3.18.91.1-.7.38-1.19.68-1.46-2.44-.28-5-1.22-5-5.43 0-1.2.43-2.18 1.14-2.95-.11-.28-.5-1.41.11-2.94 0 0 .94-.3 3.08 1.13a10.6 10.6 0 0 1 2.8-.38c.95 0 1.91.13 2.8.38 2.14-1.43 3.08-1.13 3.08-1.13.61 1.53.22 2.66.11 2.94.71.77 1.14 1.75 1.14 2.95 0 4.22-2.57 5.15-5.01 5.42.39.34.73 1.01.73 2.03 0 1.46-.01 2.63-.01 2.99 0 .29.2.64.76.53 4.34-1.45 7.48-5.56 7.48-10.41C23.02 5.24 18.27.5 12 .5z"/></svg>
                        GitHub
                      </a>
                    @endif
                    @if($sp->gitlab)
                      <a href="{{ $sp->gitlab }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-xs border-slate-200 text-orange-700 bg-white hover:bg-slate-50">
                        GitLab
                      </a>
                    @endif
                    @if($sp->wordpress)
                      <a href="{{ $sp->wordpress }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-xs border-slate-200 text-indigo-700 bg-white hover:bg-slate-50">
                        WordPress
                      </a>
                    @endif
                    @if($sp->notion)
                      <a href="{{ $sp->notion }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl border px-3 py-1.5 text-xs border-slate-200 text-black bg-white hover:bg-slate-50">
                        Notion
                      </a>
                    @endif
                  </div>
                @else
                  <span class="text-slate-400">No hay redes sociales configuradas</span>
                @endif
              </div>
            </div>
          </div>
        @else
          <!-- EDICIÓN (respetando tus bindings) -->
          <div class="grid lg:grid-cols-2 gap-6">
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-sm">
              <p class="text-slate-500 mb-2">Tus datos actuales</p>
              <div class="space-y-1">
                <p><span class="text-slate-500">Nombre:</span> <span class="font-medium text-slate-800">{{ $alumno->name }}</span></p>
                <p><span class="text-slate-500">Email:</span> <span class="font-medium text-slate-800">{{ $alumno->email }}</span></p>
                @if($alumno->whatsapp)<p><span class="text-slate-500">WhatsApp:</span> <span class="font-medium text-slate-800">{{ $alumno->whatsapp }}</span></p>@endif
                @if($alumno->comision)<p><span class="text-slate-500">Comisión:</span> <span class="font-medium text-slate-800">{{ $alumno->comision }}</span></p>@endif
                @if($alumno->carrera)<p><span class="text-slate-500">Carrera:</span> <span class="font-medium text-slate-800">{{ $alumno->carrera }}</span></p>@endif
              </div>
            </div>

            <form wire:submit.prevent="actualizarDatos" class="space-y-4" enctype="multipart/form-data">
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">Nombre completo</label>
                <input type="text" wire:model.defer="nombre" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100">
                @error('nombre') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
              </div>
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">Correo electrónico</label>
                <input type="email" wire:model.defer="email" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100">
                @error('email') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
              </div>
              <div class="grid sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-[12px] text-slate-600 mb-1">WhatsApp</label>
                  <input type="text" wire:model.defer="whatsapp" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100">
                </div>
               
              </div>
              <div>
   
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">Foto de perfil</label>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                  <input type="file" wire:model="nuevaFoto" accept="image/*"
                         class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-slate-800 file:px-3 file:py-2 file:text-white hover:file:bg-slate-700">
                  <button type="button" wire:click="actualizarFoto"
                          class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                    Actualizar foto
                  </button>
                </div>
                @error('nuevaFoto') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                @if($nuevaFoto)
                  <img src="{{ $nuevaFoto->temporaryUrl() }}" class="h-14 w-14 rounded-full object-cover mt-3 ring-2 ring-blue-100" alt="Preview">
                @endif
              </div>

              <div>
                <label class="block text-[12px] text-slate-600 mb-1">LinkedIn</label>
                <input type="url" wire:model.defer="linkedin" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100" placeholder="https://linkedin.com/in/usuario">
              </div>
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">GitHub</label>
                <input type="url" wire:model.defer="github" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100" placeholder="https://github.com/usuario">
              </div>
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">GitLab</label>
                <input type="url" wire:model.defer="gitlab" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100" placeholder="https://gitlab.com/usuario">
              </div>
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">WordPress</label>
                <input type="url" wire:model.defer="wordpress" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100" placeholder="https://usuario.wordpress.com">
              </div>
              <div>
                <label class="block text-[12px] text-slate-600 mb-1">Notion</label>
                <input type="url" wire:model.defer="notion" class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-sm shadow-sm focus:ring-2 focus:ring-blue-100" placeholder="https://notion.so/usuario">
              </div>

              <div class="flex items-center gap-2 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-semibold text-white hover:bg-blue-700">
                  Guardar cambios
                </button>
                <button type="button" wire:click="cancelarEdicion" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        @endif
      </div>
    </section>

    <!-- PERFIL DEL PROFESOR -->

<section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
  <div class="px-4 sm:px-5 py-3 border-b border-slate-100">
    <h2 class="text-slate-800 font-semibold">Perfil del Profesor</h2>
  </div>

  @if($profesor)
    <div class="p-4 sm:p-6">
      <article class="p-4 rounded-2xl border border-slate-200 bg-white hover:shadow-sm transition">
        <div class="flex items-center gap-3">
          @if($profesor->profile_photo)
            <img src="{{ asset('storage/'.$profesor->profile_photo) }}"
                 class="h-12 w-12 rounded-full object-cover ring-2 ring-slate-100" alt="Foto">
          @else
            <div class="h-12 w-12 rounded-full bg-slate-200 ring-2 ring-slate-100 flex items-center justify-center text-slate-600 font-semibold">
              {{ strtoupper(substr($profesor->name,0,1)) }}
            </div>
          @endif
          <div class="leading-tight">
            <h3 class="text-slate-800 font-medium">Prof. {{ $profesor->name }}</h3>
            <p class="text-[11px] text-slate-500 -mt-0.5">Profesor</p>
            <a class="text-xs text-blue-600 underline" href="mailto:{{ $profesor->email }}">{{ $profesor->email }}</a>
            <div class="flex gap-2 mt-2 flex-wrap">
              @if($profesor->whatsapp)
                <a href="https://wa.me/{{ $profesor->whatsapp }}" target="_blank" rel="noopener" title="WhatsApp" class="text-green-500 hover:text-green-700">
                  <svg class="w-6 h-6 inline" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.52 3.48A12 12 0 003.48 20.52a12 12 0 0017.04-17.04zm-8.52 18.52a10.5 10.5 0 01-5.62-1.62l-.4-.24-4.13 1.08 1.1-4.03-.26-.42A10.5 10.5 0 1112 22.01zm5.2-7.2c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.13-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.27-.48.09-.19.05-.36-.02-.5-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.36-.01-.56-.01-.19 0-.5.07-.76.34-.26.27-1 1-1 2.43s1.03 2.82 1.18 3.02c.15.2 2.03 3.18 5.01 4.34.7.27 1.25.43 1.68.55.71.19 1.36.16 1.87.1.57-.07 1.65-.67 1.89-1.32.24-.65.24-1.21.17-1.32z" />
                  </svg>
                </a>
              @endif
              @php $sp = $profesor->socialProfile ?? null; @endphp
              @if($sp)
                @if($sp->github)
                  <a href="{{ $sp->github }}" target="_blank" rel="noopener" title="GitHub" class="text-gray-800 hover:text-black">
                    <svg class="w-6 h-6 inline" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 .5a12 12 0 00-3.79 23.4c.6.11.82-.26.82-.58v-2.02c-3.34.73-4.04-1.61-4.04-1.61-.55-1.4-1.35-1.77-1.35-1.77-1.1-.75.08-.74.08-.74 1.22.09 1.87 1.26 1.87 1.26 1.08 1.86 2.83 1.32 3.52 1.01.11-.8.42-1.32.76-1.62-2.66-.3-5.46-1.33-5.46-5.9 0-1.3.47-2.36 1.24-3.19-.13-.31-.54-1.55.12-3.23 0 0 1.01-.32 3.3 1.22a11.5 11.5 0 016 0c2.29-1.54 3.3-1.22 3.3-1.22.66 1.68.25 2.92.12 3.23.77.83 1.24 1.89 1.24 3.19 0 4.58-2.81 5.6-5.49 5.9.43.37.81 1.1.81 2.22v3.29c0 .32.22.69.83.58A12 12 0 0012 .5z" />
                    </svg>
                  </a>
                @endif
                @if($sp->gitlab)
                  <a href="{{ $sp->gitlab }}" target="_blank" rel="noopener" title="GitLab" class="text-orange-500 hover:text-orange-700">
                    <svg class="w-6 h-6 inline" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M22.65 13.02l-1.12-3.44-1.83-5.61a.62.62 0 00-1.18 0l-1.82 5.61H7.3L5.48 3.97a.62.62 0 00-1.18 0L2.47 9.58 1.35 13.02a1.23 1.23 0 00.46 1.36l10.2 7.4 10.2-7.4a1.23 1.23 0 00.44-1.36z" />
                    </svg>
                  </a>
                @endif
                @if($sp->notion)
                  <a href="{{ $sp->notion }}" target="_blank" rel="noopener" title="Notion" class="text-gray-800 hover:text-black">
                    <svg class="w-6 h-6 inline" viewBox="0 0 24 24" fill="currentColor">
                      <rect x="3" y="3" width="18" height="18" rx="3" />
                      <path d="M8 8h2l4 6V8h2v8h-2l-4-6v6H8V8z" fill="white" />
                    </svg>
                  </a>
                @endif
                @if($sp->wordpress)
                  <a href="{{ $sp->wordpress }}" target="_blank" rel="noopener" title="WordPress" class="text-blue-500 hover:text-blue-700">
                    <svg class="w-6 h-6 inline" viewBox="0 0 24 24" fill="currentColor">
                      <circle cx="12" cy="12" r="10" />
                      <path d="M6.5 8.5c.8 0 1.3.5 1.5 1.1l2.7 7.7L8.5 12c-.3-.7-.6-1.4-.6-2 0-.6.2-1 .6-1.5H6.5zM12 7c.8 0 1.5.2 2 .5-.5.5-.8 1.2-.8 2 0 .8.4 1.8.8 3l1.6 4.5c-1 .5-2 .8-3 .8-1.1 0-2.1-.3-3-.8L12 7zM15.6 8.5h1.9c.3.5.5 1 .5 1.6 0 .8-.4 1.8-.8 3l-1.2 3.3-2.1-6c-.2-.6-.4-1.1-.4-1.6 0-.6.2-1.1.6-1.6.5-.3 1-.5 1.6-.5z" fill="white" />
                    </svg>
                  </a>
                @endif
                @if($sp->linkedin)
                  <a href="{{ $sp->linkedin }}" target="_blank" rel="noopener" title="LinkedIn" class="text-blue-600 hover:text-blue-800">
                    <svg class="w-6 h-6 inline" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M4 3a2 2 0 100 4 2 2 0 000-4zM3 8h2.9v13H3zM9 8h2.8v1.9h.1A3 3 0 0115 8c3 0 3.6 2 3.6 4.6V21H16v-7c0-1.7-.1-3.8-2.3-3.8-2.3 0-2.7 1.8-2.7 3.7V21H9z" />
                    </svg>
                  </a>
                @endif
              @endif
            </div>
          </div>
        </div>
      </article>
    </div>
  @else
    <div class="p-6 text-slate-600">No tienes profesor asignado a tu curso.</div>
  @endif
</section>
