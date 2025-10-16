@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-6">
    <div class="w-full max-w-md mx-auto px-6">
        <div class="bg-white shadow-xl rounded-2xl px-6 py-8">
            <h2 class="text-3xl font-bold mb-6 text-center text-blue-700">Iniciar Sesión</h2>
            
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                @if($errors->any())
                    <div class="mb-2 p-2 bg-red-100 text-red-700 rounded text-sm">
                        @if($errors->has('email'))
                            {{ $errors->first('email') }}
                        @elseif($errors->has('password'))
                            {{ $errors->first('password') }}
                        @else
                            Credenciales incorrectas.
                        @endif
                    </div>
                @endif

                <input type="email" name="email" value="{{ old('email') }}" 
                    placeholder="Correo electrónico" 
                    autocomplete="email"
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>

                <div class="relative">
                    <input type="password" name="password" id="login_password" 
                        placeholder="Contraseña" 
                        autocomplete="current-password"
                        class="w-full pr-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    
                    <button type="button" 
                        class="absolute right-3 top-2.5 text-gray-400 hover:text-blue-600 focus:outline-none" 
                        id="eye-login_password" 
                        onclick="togglePassword('login_password')">
                        <!-- ícono de ojo -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 3.944-5.065 7-9.542 7-4.477 0-8.268-3.056-9.542-7z"/>
                        </svg>
                    </button>
                </div>

                <button type="submit" 
                    class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                    Ingresar
                </button>
            </form>

            <script>
            function togglePassword(id) {
                const input = document.getElementById(id);
                const eye = document.getElementById('eye-' + id).querySelector('svg');
                if (input.type === 'password') {
                    input.type = 'text';
                    eye.classList.add('text-blue-600');
                } else {
                    input.type = 'password';
                    eye.classList.remove('text-blue-600');
                }
            }
            </script>

            <div class="mt-6 text-center">
                <span class="text-gray-600">¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Regístrate</a>
            </div>
        </div>
    </div>
</div>
@endsection
