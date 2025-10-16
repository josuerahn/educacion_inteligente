<div class="p-4">

    <!-- Mensajes de éxito -->
    @if (session()->has('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulario Crear / Editar -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="font-bold mb-2">
            @if($editTareaId) Editar Tarea @else Crear Tarea @endif
        </h2>
        <div class="flex flex-col gap-2">
            <input type="text" wire:model="titulo" placeholder="Título" class="border p-2 rounded">
            <textarea wire:model="descripcion" placeholder="Descripción" class="border p-2 rounded"></textarea>
            <input type="date" wire:model="fecha_limite" class="border p-2 rounded">
            <input type="file" wire:model="archivo" class="border p-2 rounded">

            @if($editTareaId)
                <button wire:click="actualizarTarea" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Actualizar</button>
                <button wire:click="$set('editTareaId', null)" class="bg-gray-400 text-white px-4 py-2 rounded mt-2">Cancelar</button>
            @else
                <button wire:click="crearTarea" class="bg-green-600 text-white px-4 py-2 rounded mt-2">Crear</button>
            @endif
        </div>
    </div>

    <!-- Listado de Tareas -->
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="font-bold mb-2">Mis Tareas</h2>
        @if($tareas->count() > 0)
            <table class="w-full table-auto border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Título</th>
                        <th class="border p-2">Descripción</th>
                        <th class="border p-2">Fecha Límite</th>
                        <th class="border p-2">Archivo</th>
                        <th class="border p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tareas as $tarea)
                        <tr>
                            <td class="border p-2">{{ $tarea->titulo }}</td>
                            <td class="border p-2">{{ $tarea->descripcion }}</td>
                            <td class="border p-2">{{ $tarea->fecha_limite }}</td>
                            <td class="border p-2">
                                @if($tarea->archivo)
                                    <a href="{{ asset('storage/' . $tarea->archivo) }}" target="_blank" class="text-blue-600 underline">Ver</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="border p-2 flex gap-2">
                                <button wire:click="editarTarea({{ $tarea->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Editar</button>
                                <button wire:click="eliminarTarea({{ $tarea->id }})" class="bg-red-600 text-white px-2 py-1 rounded">Eliminar</button>
                                <button wire:click="verEntregas({{ $tarea->id }})" class="bg-blue-600 text-white px-2 py-1 rounded">Ver Entregas</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No tienes tareas registradas.</p>
        @endif
    </div>

    <!-- Sección de Entregas -->
    @if($verEntregasTareaId)
        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-bold mb-2">Entregas de la Tarea: {{ $tareas->find($verEntregasTareaId)->titulo }}</h2>
            @if($entregas && $entregas->count() > 0)
                <table class="w-full table-auto border-collapse border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border p-2">Alumno</th>
                            <th class="border p-2">Archivo</th>
                            <th class="border p-2">Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entregas as $entrega)
                            <tr>
                                <td class="border p-2">{{ $entrega->alumno->name }}</td>
                                <td class="border p-2">
                                    @if($entrega->archivo)
                                        <a href="{{ asset('storage/' . $entrega->archivo) }}" target="_blank" class="text-blue-600 underline">Ver</a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="border p-2">{{ $entrega->comentario }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No hay entregas para esta tarea.</p>
            @endif
        </div>
    @endif

</div>
