<div class="bg-white p-4 rounded shadow w-full max-w-xl mx-auto">
    <div class="mb-4 max-h-96 overflow-y-auto">
        @foreach($chat as $msg)
            <div class="mb-2">
                <strong>{{ $msg['user'] }}:</strong> {{ $msg['message'] }}
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="sendMessage" class="flex gap-2">
        <input type="text" wire:model.defer="message" placeholder="Escribe tu mensaje..." class="flex-1 border rounded px-2 py-1">
        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 transition">Enviar</button>
    </form>
</div>
