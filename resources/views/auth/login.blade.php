@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-10">
    <div class="w-full max-w-md px-8 py-10 glass rounded-3xl shadow-2xl glow hover:shadow-blue-500/50 transition">
        <h2 class="text-4xl font-bold mb-6 text-center text-blue-400 tracking-wide">Iniciar Sesión</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            @if($errors->any())
                <div class="p-3 bg-red-500/20 text-red-300 rounded text-sm text-center animate-pulse">
                    {{ $errors->first() ?? 'Credenciales incorrectas' }}
                </div>
            @endif

            <div class="space-y-2">
                <label class="text-sm font-semibold text-blue-300">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="usuario@correo.com"
                    class="w-full px-4 py-2.5 bg-transparent border border-blue-500/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-blue-900/10 placeholder-gray-400 text-gray-100" required>
            </div>

            <div class="space-y-2 relative">
                <label class="text-sm font-semibold text-blue-300">Contraseña</label>
                <input type="password" name="password" id="login_password"
                    placeholder="********"
                    class="w-full px-4 py-2.5 bg-transparent border border-blue-500/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-blue-900/10 placeholder-gray-400 text-gray-100" required>
                <button type="button" class="absolute right-3 top-9 text-gray-400 hover:text-blue-400" onclick="togglePassword('login_password')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 3.944-5.065 7-9.542 7-4.477 0-8.268-3.056-9.542-7z"/>
                    </svg>
                </button>
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-500 hover:scale-[1.02] transition duration-300 shadow-lg shadow-blue-500/30">
                Ingresar
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-gray-300">¿No tienes cuenta?
                <a href="{{ route('register') }}" class="text-blue-400 hover:underline font-semibold">Regístrate</a>
            </p>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</div>
@endsection
