@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-8">
    <div class="w-full max-w-2xl px-8 py-10 glass rounded-3xl shadow-2xl glow hover:shadow-blue-500/50 transition">
        <h2 class="text-4xl font-bold mb-6 text-center text-blue-400 tracking-wide">Crear cuenta</h2>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="flex flex-col items-center">
                <label for="profile_photo" class="mb-2 text-sm text-blue-300 font-medium">Foto de perfil</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                    class="block w-full text-sm text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-600/20 file:text-blue-200 hover:file:bg-blue-700/30 cursor-pointer">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <input type="text" name="name" placeholder="Nombre completo" class="input-futurista" required>
    <input type="email" name="email" placeholder="Correo electrónico" class="input-futurista" required>
    <input type="password" name="password" id="password" placeholder="Contraseña" class="input-futurista" required>
    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" class="input-futurista" required>
    
    <input type="date" name="fecha_nacimiento" placeholder="Fecha de nacimiento" class="input-futurista" required>
</div>


            <button type="submit"
                class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-500 hover:scale-[1.02] transition duration-300 shadow-lg shadow-blue-500/30">
                Crear cuenta
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-300">¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-blue-400 hover:underline font-semibold">Inicia sesión</a>
            </p>
        </div>
    </div>

    <style>
    .input-futurista {
        @apply w-full px-4 py-2.5 rounded-lg border transition duration-200;
        background: rgba(255, 255, 255, 0.08); /* fondo translúcido oscuro */
        border-color: rgba(59, 130, 246, 0.4); /* azul tenue */
        color: #f8fafc; /* texto muy claro */
    }
    .input-futurista::placeholder {
        color: rgba(203, 213, 225, 0.85); /* gris suave para mejor lectura */
    }
    .input-futurista:focus {
        background: rgba(30, 64, 175, 0.3); /* azul más intenso al foco */
        border-color: #3b82f6;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.4);
        outline: none;
    }
    .social-btn {
        @apply px-3 py-1 rounded-full font-semibold hover:scale-105 transition;
    }
</style>


    <script>
        function showSocialInput(network) {
            const container = document.getElementById('social-inputs');
            const existing = document.getElementById('input-' + network);
            if (existing) return existing.remove();

            const div = document.createElement('div');
            div.id = 'input-' + network;
            div.innerHTML = `<input type="text" name="${network}" placeholder="Enlace de ${network}"
                              class="input-futurista">`;
            container.appendChild(div);
        }
    </script>
</div>
@endsection
