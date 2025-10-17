<div class="p-4">
    <!-- Mensajes de éxito -->
    @if (session()->has('success'))
        <div class="bg-green-200 text-green-800 p-3 rounded mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-200 text-red-800 p-3 rounded mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Formulario Crear / Editar -->
    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <h2 class="text-xl font-bold mb-4 text-gray-800">
            @if($editTareaId) 
                <span class="text-blue-600">✏️ Editar Tarea</span>
            @else 
                <span class="text-green-600">➕ Crear Nueva Tarea</span>
            @endif
        </h2>
        
        <div class="grid gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" wire:model="titulo" placeholder="Ej: Ejercicios de matemáticas" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('titulo') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea wire:model="descripcion" placeholder="Detalles de la tarea..." rows="3" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                @error('descripcion') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha límite *</label>
                <input type="date" wire:model="fecha_limite" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('fecha_limite') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Archivo adjunto
                    @if($editTareaId)
                        <span class="text-gray-500 text-xs">(Opcional: deja vacío si no quieres cambiar el archivo)</span>
                    @endif
                </label>
                <input type="file" wire:model="archivo" class="w-full border border-gray-300 rounded-lg p-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('archivo') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                
                @if($archivo)
                    <div class="mt-2 text-sm text-green-600 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Archivo seleccionado: {{ $archivo->getClientOriginalName() }}
                    </div>
                @endif
            </div>

            <div class="flex gap-3 pt-2">
                @if($editTareaId)
                    <button wire:click="actualizarTarea" class="flex-1 bg-blue-600 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        💾 Actualizar Tarea
                    </button>
                    <button wire:click="cancelarEdicion" class="flex-1 bg-gray-400 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-500 transition-colors">
                        ❌ Cancelar
                    </button>
                @else
                    <button wire:click="crearTarea" class="w-full bg-green-600 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                        ➕ Crear Tarea
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Listado de Tareas -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">📚 Mis Tareas Creadas</h2>
        </div>

        @if($tareas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha Límite</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Archivo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($tareas as $tarea)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $tarea->titulo }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $tarea->descripcion ?? 'Sin descripción' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($tarea->fecha_limite)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tarea->archivo)
                                    @php
                                        $nombreCompleto = basename($tarea->archivo);
                                        $nombreMostrar = preg_replace('/^\d+_/', '', $nombreCompleto);
                                        $extension = strtolower(pathinfo($nombreCompleto, PATHINFO_EXTENSION));
                                        
                                        $iconColor = match($extension) {
                                            'pdf' => 'text-red-600',
                                            'doc', 'docx' => 'text-blue-600',
                                            'xls', 'xlsx' => 'text-green-600',
                                            'jpg', 'jpeg', 'png' => 'text-purple-600',
                                            default => 'text-gray-600'
                                        };
                                    @endphp
                                    <a href="{{ asset($tarea->archivo) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline">
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-sm">{{ $nombreMostrar }}</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm italic">Sin archivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex gap-2">
                                    <button wire:click="editarTarea({{ $tarea->id }})" 
                                            class="bg-yellow-500 text-white px-3 py-1.5 rounded-lg hover:bg-yellow-600 transition-colors text-sm font-medium">
                                        ✏️ Editar
                                    </button>
                                    <button wire:click="eliminarTarea({{ $tarea->id }})" 
                                            onclick="return confirm('¿Estás seguro de eliminar esta tarea?\n\nEsta acción no se puede deshacer.')" 
                                            class="bg-red-600 text-white px-3 py-1.5 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                        🗑️ Eliminar
                                    </button>
                                    <a href="{{ route('profesor.entregas', $tarea->id) }}" 
                                       class="bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium inline-flex items-center gap-1">
                                        📄 Ver Entregas
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tareas creadas</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza creando tu primera tarea usando el formulario de arriba.</p>
            </div>
        @endif
    </div>
</div>