<div class="p-6 min-h-screen bg-gray-100 scroll-smooth">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between mb-8 gap-4">
        <div class="flex items-center gap-4 w-full sm:w-auto justify-center sm:justify-start">
            <!-- Aquí puedes agregar info o iconos si lo deseas -->
        </div>
    </div>

    {{-- Perfil del Profesor --}}
    @php
        $profUser = auth()->user();
        $profName = $profUser->name ?? 'Profesor';
        $profEmail = $profUser->email ?? null;
        $profPhotoUrl = $profUser && !empty($profUser->profile_photo)
            ? asset('storage/'.$profUser->profile_photo)
            : 'https://ui-avatars.com/api/?name='.urlencode($profName);
    @endphp

    <div class="bg-white rounded-xl shadow p-6 flex flex-col sm:flex-row items-center gap-6 mb-8 w-full">
        <img
            src="{{ $profPhotoUrl }}"
            class="w-20 h-20 rounded-full object-cover border-2 border-blue-500 cursor-pointer"
            wire:click="verFotoPerfil('{{ $profPhotoUrl }}')"
            alt="Foto de perfil de {{ $profName }}">

        <div class="text-center sm:text-left w-full">
            <p class="font-bold text-xl text-blue-700">{{ $profName }}</p>
            <p class="text-gray-600">{{ $profEmail ?? '' }}</p>

            <div class="flex flex-col sm:flex-row gap-2 mt-2 justify-center sm:justify-start w-full">
                <button type="button"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors w-full sm:w-auto"
                        wire:click="editarPerfil">
                    Editar Perfil
                </button>
                <button type="button"
                        class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500 transition-colors w-full sm:w-auto"
                        wire:click="acercaDe">
                    Acerca de
                </button>
            </div>
        </div>
    </div>

    {{-- Chat con IA --}}
    <div id="chat-ia" class="mb-8">
        @livewire('profesor.chat-bot')
    </div>

   
</div>
