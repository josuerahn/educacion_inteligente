<div class="p-6 bg-gray-100 min-h-screen">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">📘 Entregas de Alumnos</h2>

        @if(session()->has('mensaje'))
            <div wire:poll.3s="clearFlash" class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('mensaje') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead class="bg-blue-100 text-blue-700">
                    <tr>
                        <th class="p-3 text-left">Alumno</th>
                        <th class="p-3 text-left">Archivo</th>
                        <th class="p-3 text-left">Fecha Entrega</th>
                        <th class="p-3 text-left">Calificación</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($entregas as $entrega)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $entrega->alumno->name }}</td>
                            <td class="p-3">
                                <a href="{{ Storage::url($entrega->archivo) }}" target="_blank" class="text-blue-500 hover:underline">
                                    Ver archivo
                                </a>
                            </td>
                            <td class="p-3">{{ $entrega->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-3">{{ $entrega->calificacion ?? 'Sin calificar' }}</td>
                            <td class="p-3">
                                <button wire:click="seleccionarEntrega({{ $entrega->id }})"
                                    class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                    Calificar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-4">
                                No hay entregas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $entregas->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Calificación --}}
    @if($entregaSeleccionada)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">
                <h2 class="text-xl font-bold mb-4 text-blue-700">Calificar Entrega</h2>
                <p class="mb-3">
                    <strong>Alumno:</strong> {{ $entregaSeleccionada->alumno->name }}
                </p>
                <form wire:submit.prevent="calificarEntrega">
                    <div class="mb-4">
                        <label class="block font-bold mb-1">Calificación (0 a 10)</label>
                        <input type="number" wire:model="calificacion" min="0" max="10" step="0.1"
                               class="w-full border rounded p-2 focus:ring focus:ring-blue-300">
                        @error('calificacion') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button type="button" wire:click="$set('entregaSeleccionada', null)"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
