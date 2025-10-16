
<div class="p-6 min-h-screen bg-gray-100">
    <div class="p-6 min-h-screen bg-gray-100 scroll-smooth">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between mb-8 gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto justify-center sm:justify-start">
                <!-- Aquí puedes agregar info o iconos si lo deseas -->
            </div>
        </div>

        {{-- Perfil del Profesor --}}
    <div class="bg-white rounded-xl shadow p-6 flex flex-col sm:flex-row items-center gap-6 mb-8 w-full">
            <img
                src="{{ auth()->user()->profile_photo
                    ? asset('storage/'.auth()->user()->profile_photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                class="w-20 h-20 rounded-full object-cover border-2 border-blue-500 cursor-pointer"
                wire:click="verFotoPerfil('{{ auth()->user()->profile_photo
                    ? asset('storage/'.auth()->user()->profile_photo)
                    : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}')">

            <div class="text-center sm:text-left w-full">
                <p class="font-bold text-xl text-blue-700">{{ auth()->user()->name }}</p>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
                <div class="flex flex-col sm:flex-row gap-2 mt-2 justify-center sm:justify-start w-full">
                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors w-full sm:w-auto" wire:click="editarPerfil">
                        Editar Perfil
                    </button>
                    <button type="button" class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500 transition-colors w-full sm:w-auto" wire:click="acercaDe">
                        Acerca de
                    </button>
                </div>
            </div>
        </div>

        {{-- Lista de Alumnos --}}
        <div id="lista-alumnos" class="bg-white rounded-xl shadow p-6">
            <h2 class="text-2xl font-bold mb-4 text-blue-700">Lista de Alumnos</h2>

            @if(session()->has('mensaje'))
            <div wire:poll.3s="clearFlash" class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('mensaje') }}
            </div>
            @endif

            {{-- Buscador --}}
            <div class="mb-4 relative">
                <input
                    type="text"
                    placeholder="Buscar por nombre o email..."
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-300"
                    wire:model.debounce.150ms="q"
                    wire:keydown.enter.prevent="buscarAhora" />

                {{-- Sugerencias (4+ letras) --}}
                @if(strlen($q) >= 4)
                @if($sugerencias->isNotEmpty())
                <ul class="absolute left-0 right-0 mt-1 max-h-64 overflow-auto rounded border bg-white shadow-md z-50">
                    @foreach($sugerencias as $sug)
                    <li>
                        <a href="?ver={{ $sug->id }}#detalle-alumno"
                            class="flex items-center gap-2 p-2 hover:bg-gray-100 cursor-pointer">
                            <img src="{{ $sug->profile_photo ? asset('storage/'.$sug->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($sug->name) }}"
                                class="w-8 h-8 rounded-full object-cover">
                            <span>{{ $sug->name }} ({{ $sug->email }})</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="absolute left-0 right-0 mt-1 rounded border bg-white p-2 text-sm text-gray-500 shadow z-50">
                    Sin resultados
                </div>
                @endif
                @endif



            </div>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-blue-100 text-blue-700">
                        <tr>
                            <th class="p-3 text-left">Foto</th>
                            <th class="p-3 text-left">Alumno</th>
                            <th class="p-3 text-left">Email</th>
                            <th class="p-3 text-left">Redes</th>
                            <th class="p-3 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alumnos as $alumno)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <img
                                    src="{{ $alumno->profile_photo
                                            ? asset('storage/'.$alumno->profile_photo)
                                            : 'https://ui-avatars.com/api/?name='.urlencode($alumno->name) }}"
                                    class="w-12 h-12 rounded-full object-cover border-2 border-green-400 cursor-pointer"
                                    wire:click="verFotoPerfil('{{ $alumno->profile_photo
                                            ? asset('storage/'.$alumno->profile_photo)
                                            : 'https://ui-avatars.com/api/?name='.urlencode($alumno->name) }}')">
                            </td>

                            <td class="p-3 font-medium">{{ $alumno->name }}</td>
                            <td class="p-3">{{ $alumno->email }}</td>

                            {{-- Redes --}}
                            <td class="p-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($alumno->whatsapp)
                                    <a href="https://wa.me/{{ $alumno->whatsapp }}" target="_blank" rel="noopener" title="WhatsApp" class="text-green-500 hover:text-green-700">
                                        {{-- ícono --}}
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.52 3.48A12 12 0 003.48 20.52a12 12 0 0017.04-17.04zm-8.52 18.52a10.5 10.5 0 01-5.62-1.62l-.4-.24-4.13 1.08 1.1-4.03-.26-.42A10.5 10.5 0 1112 22.01zm5.2-7.2c-.28-.14-1.65-.81-1.9-.9-.25-.09-.43-.14-.61.14-.18.28-.7.9-.86 1.08-.16.18-.32.2-.6.07-.28-.13-1.18-.44-2.25-1.4-.83-.74-1.39-1.65-1.55-1.93-.16-.28-.02-.43.12-.57.13-.13.28-.34.42-.51.14-.17.18-.29.27-.48.09-.19.05-.36-.02-.5-.07-.14-.61-1.47-.84-2.01-.22-.53-.45-.46-.61-.47-.16-.01-.36-.01-.56-.01-.19 0-.5.07-.76.34-.26.27-1 1-1 2.43s1.03 2.82 1.18 3.02c.15.2 2.03 3.18 5.01 4.34.7.27 1.25.43 1.68.55.71.19 1.36.16 1.87.1.57-.07 1.65-.67 1.89-1.32.24-.65.24-1.21.17-1.32z" />
                                        </svg>
                                    </a>
                                    @endif

                                    @if($alumno->socialProfile)
                                    @if($alumno->socialProfile->github)
                                    <a href="{{ $alumno->socialProfile->github }}" target="_blank" rel="noopener" title="GitHub" class="text-gray-800 hover:text-black">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 .5a12 12 0 00-3.79 23.4c.6.11.82-.26.82-.58v-2.02c-3.34.73-4.04-1.61-4.04-1.61-.55-1.4-1.35-1.77-1.35-1.77-1.1-.75.08-.74.08-.74 1.22.09 1.87 1.26 1.87 1.26 1.08 1.86 2.83 1.32 3.52 1.01.11-.8.42-1.32.76-1.62-2.66-.3-5.46-1.33-5.46-5.9 0-1.3.47-2.36 1.24-3.19-.13-.31-.54-1.55.12-3.23 0 0 1.01-.32 3.3 1.22a11.5 11.5 0 016 0c2.29-1.54 3.3-1.22 3.3-1.22.66 1.68.25 2.92.12 3.23.77.83 1.24 1.89 1.24 3.19 0 4.58-2.81 5.6-5.49 5.9.43.37.81 1.1.81 2.22v3.29c0 .32.22.69.83.58A12 12 0 0012 .5z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if($alumno->socialProfile->gitlab)
                                    <a href="{{ $alumno->socialProfile->gitlab }}" target="_blank" rel="noopener" title="GitLab" class="text-orange-500 hover:text-orange-700">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M22.65 13.02l-1.12-3.44-1.83-5.61a.62.62 0 00-1.18 0l-1.82 5.61H7.3L5.48 3.97a.62.62 0 00-1.18 0L2.47 9.58 1.35 13.02a1.23 1.23 0 00.46 1.36l10.2 7.4 10.2-7.4a1.23 1.23 0 00.44-1.36z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if($alumno->socialProfile->notion)
                                    <a href="{{ $alumno->socialProfile->notion }}" target="_blank" rel="noopener" title="Notion" class="text-gray-800 hover:text-black">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <rect x="3" y="3" width="18" height="18" rx="3" />
                                            <path d="M8 8h2l4 6V8h2v8h-2l-4-6v6H8V8z" fill="white" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if($alumno->socialProfile->wordpress)
                                    <a href="{{ $alumno->socialProfile->wordpress }}" target="_blank" rel="noopener" title="WordPress" class="text-blue-500 hover:text-blue-700">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="M6.5 8.5c.8 0 1.3.5 1.5 1.1l2.7 7.7L8.5 12c-.3-.7-.6-1.4-.6-2 0-.6.2-1 .6-1.5H6.5zM12 7c.8 0 1.5.2 2 .5-.5.5-.8 1.2-.8 2 0 .8.4 1.8.8 3l1.6 4.5c-1 .5-2 .8-3 .8-1.1 0-2.1-.3-3-.8L12 7zM15.6 8.5h1.9c.3.5.5 1 .5 1.6 0 .8-.4 1.8-.8 3l-1.2 3.3-2.1-6c-.2-.6-.4-1.1-.4-1.6 0-.6.2-1.1.6-1.6.5-.3 1-.5 1.6-.5z" fill="white" />
                                        </svg>
                                    </a>
                                    @endif
                                    @if($alumno->socialProfile->linkedin)
                                    <a href="{{ $alumno->socialProfile->linkedin }}" target="_blank" rel="noopener" title="LinkedIn" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M4 3a2 2 0 100 4 2 2 0 000-4zM3 8h2.9v13H3zM9 8h2.8v1.9h.1A3 3 0 0115 8c3 0 3.6 2 3.6 4.6V21H16v-7c0-1.7-.1-3.8-2.3-3.8-2.3 0-2.7 1.8-2.7 3.7V21H9z" />
                                        </svg>
                                    </a>
                                    @endif
                                    @endif
                                </div>
                            </td>

                            {{-- Acciones --}}
                            <td class="p-3">
                                <div class="flex items-center gap-2">
                                    @if($alumnoSeleccionado && $alumnoSeleccionado->id === $alumno->id)
                                    {{-- Ocultar => SUBIR a la lista (sin ?ver=) --}}
                                    <a href="{{ request()->url() }}#lista-alumnos"
                                        class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors">
                                        Ocultar
                                    </a>
                                    @else
                                    {{-- Ver => BAJAR al detalle con selección vía ?ver= --}}
                                    <a href="{{ request()->url() }}?ver={{ $alumno->id }}#detalle-alumno"
                                        class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors">
                                        Ver
                                    </a>
                                    @endif

                                    {{-- Eliminar --}}
                                    <button type="button"
                                        wire:click="confirmarEliminar({{ $alumno->id }})"
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                                        Eliminar
                                    </button>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-3">
                    {{ $alumnos->links() }}
                </div>
            </div>
        </div>

        {{-- Ancla para el detalle --}}
        <div id="detalle-alumno"></div>

        {{-- Card de Detalle del Alumno --}}
        @if($alumnoSeleccionado)
        <div class="mt-8 p-6 bg-white rounded-xl shadow flex flex-col md:flex-row gap-6 items-center">
            <img
                src="{{ $alumnoSeleccionado->profile_photo
                        ? asset('storage/'.$alumnoSeleccionado->profile_photo)
                        : 'https://ui-avatars.com/api/?name='.urlencode($alumnoSeleccionado->name) }}"
                class="w-32 h-32 rounded-full object-cover border-2 border-green-400 cursor-pointer"
                wire:click="verFotoPerfil('{{ $alumnoSeleccionado->profile_photo
                        ? asset('storage/'.$alumnoSeleccionado->profile_photo)
                        : 'https://ui-avatars.com/api/?name='.urlencode($alumnoSeleccionado->name) }}')">
            <div>
                <h2 class="text-xl font-bold mb-3 text-blue-700">Detalles del Alumno</h2>
                <ul class="space-y-2">
                    <li><strong>Nombre:</strong> {{ $alumnoSeleccionado->name ?? '-' }}</li>
                    <li><strong>Email:</strong> {{ $alumnoSeleccionado->email ?? '-' }}</li>
                    </li>
                    <li><strong>DNI:</strong> {{ $alumnoSeleccionado->dni ?? '-' }}</li>
                    <li><strong>Fecha de Nacimiento:</strong> {{ $alumnoSeleccionado->fecha_nacimiento ?? '-' }}</li>
                </ul>
            </div>
        </div>
        @endif

        {{-- Modales --}}
        @if($mostrarEditarPerfil)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-700">Editar Perfil</h2>
                <form wire:submit.prevent="guardarPerfil">
                    <div class="mb-2"><label class="block font-bold">Nombre</label><input type="text" wire:model.defer="profesorEdit.name" class="w-full border rounded p-2"></div>
                    <div class="mb-2"><label class="block font-bold">Email</label><input type="email" wire:model.defer="profesorEdit.email" class="w-full border rounded p-2"></div>
                    <div class="mb-2"><label class="block font-bold">Nueva Contraseña</label><input type="password" wire:model.defer="profesorEdit.password" class="w-full border rounded p-2" autocomplete="new-password"></div>
                    <div class="mb-2"><label class="block font-bold">Foto de Perfil</label><input type="file" wire:model="fotoPerfilProfesor" class="w-full" accept="image/*"></div>
                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Guardar</button>
                        <button type="button" wire:click="cerrarModales" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition-colors">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if(!empty($confirmarEliminarId))
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-2 text-blue-700">Confirmar eliminación</h2>
                <p class="text-gray-700">¿Seguro que querés eliminar este alumno? Esta acción no se puede deshacer.</p>
                <div class="flex gap-2 mt-4 justify-end">
                    <button type="button" wire:click="cancelarEliminar" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition-colors">Cancelar</button>
                    <button type="button" wire:click="eliminarAlumno({{ $confirmarEliminarId }})" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition-colors">Sí, eliminar</button>
                </div>
            </div>
        </div>
        @endif

        @if($fotoPerfilGrande)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50" wire:click="cerrarFotoPerfil">
            <img src="{{ $fotoPerfilGrande }}" class="max-w-lg max-h-[80vh] rounded-xl shadow-2xl border-4 border-white">
        </div>
        @endif

        @if($mostrarAcercaDe)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-700">Acerca de</h2>
                <ul class="space-y-2">
                    <li><strong>Nombre:</strong> {{ auth()->user()?->name ?? '-' }}</li>
                    <li><strong>Email:</strong> {{ auth()->user()?->email ?? '-' }}</li>
                    <li><strong>Fecha de Nacimiento:</strong> {{ auth()->user()?->fecha_nacimiento ?? '-' }}</li>
                    <li><strong>Tutoria:</strong> {{ auth()->user()?->tutorias->name ?? '-' }}</li>
                    <li><strong>Descripción de la Tutoria:</strong> {{ auth()->user()?->tutoria->description ?? '-' }}</li>
                </ul>
                <div class="flex justify-end mt-4">
                    <button type="button" wire:click="cerrarModales" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">Cerrar</button>
                </div>
            </div>
        </div>
        @endif
    </div>