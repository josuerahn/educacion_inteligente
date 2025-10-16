@extends('layouts.app')

            @section('content')
            <div class="min-h-screen flex items-center justify-center bg-gray-100 py-4">
                    <div class="w-full max-w-full sm:max-w-md md:max-w-lg mx-auto px-2 sm:px-6 md:px-0">
                        <div class="bg-white shadow-xl rounded-2xl px-2 py-6 sm:px-8 sm:py-10">
                            <h2 class="text-3xl font-bold mb-6 text-center text-blue-700">Crear cuenta</h2>
                                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    <div class="flex flex-col items-center mb-6">
                                        <label for="profile_photo" class="mb-2 text-sm font-medium text-gray-700">Foto de perfil</label>
                                        <input type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" id="profile_photo" name="profile_photo" accept="image/*">
                                    </div>
                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 w-full">
                                        <div class="relative">
                                            <input type="text" name="name" placeholder=" Nombre completo" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required value="{{ old('name') }}">
                                            
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.657 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="email" name="email" placeholder=" Correo electrónico" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required value="{{ old('email') }}">
                                            @error('email')
                                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                            @enderror
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12v1m0 4v1m-8-5v1m0 4v1m-2-7a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2v-8z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" placeholder=" Contraseña" class="w-full pl-10 pr-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                            @error('password')
                                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                            @enderror
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4v2m-4-6a4 4 0 018 0v2a4 4 0 01-8 0v-2z" /></svg>
                                            </span>
                                            <span class="absolute right-3 top-2.5 text-gray-400 cursor-pointer" onclick="togglePassword('password')">
                                                <svg id="eye-password" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" Confirmar contraseña" class="w-full pl-10 pr-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4v2m-4-6a4 4 0 018 0v2a4 4 0 01-8 0v-2z" /></svg>
                                            </span>
                                            <span class="absolute right-3 top-2.5 text-gray-400 cursor-pointer" onclick="togglePassword('password_confirmation')">
                                                <svg id="eye-password_confirmation" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="text" name="whatsapp" placeholder=" WhatsApp" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required value="{{ old('whatsapp') }}">
                                            @error('whatsapp')
                                                <span class="text-red-600 text-xs">{{ $message }}</span>
                                            @enderror
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10a9 9 0 0118 0c0 4.418-3.582 8-8 8s-8-3.582-8-8z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="text" name="dni" placeholder=" DNI" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required value="{{ old('dni') }}">
                                            @error('dni')
                                                <span class="text-red-600 text-xs">{{ $message }}</span> 
                                            @enderror
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4v2m-4-6a4 4 0 018 0v2a4 4 0 01-8 0v-2z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="text" name="carrera" placeholder=" Carrera" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required value="{{ old('carrera') }}">
                                         
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2-.896 2-2s-.896-2-2-2-2 .896-2 2 .896 2 2 2zm0 0v2m0 4v2m-4-6a4 4 0 018 0v2a4 4 0 01-8 0v-2z" /></svg>
                                            </span>
                                        </div>
                                        <div class="relative">
                                            <input type="date" name="fecha_nacimiento" placeholder=" Fecha de nacimiento" class="w-full pl-10 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required value="{{ old('fecha_nacimiento') }}">
                                        
                                            <span class="absolute left-3 top-2.5 text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 4h10m-10 4h10" /></svg>
                                            </span>
                                        </div>
                                        <div>
                                            <select name="course_id" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                                                <option value="">Selecciona tu Curso</option>
                                                @foreach($cursos as $curso)
                                                    <option value="{{ $curso->id }}" {{ old('role_id') == $curso->id ? 'selected' : '' }}>{{ $curso->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Redes sociales</label>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <button type="button" class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200" onclick="showSocialInput('linkedin')">LinkedIn</button>
                                            <button type="button" class="px-3 py-1 rounded-lg bg-gray-800 text-white hover:bg-gray-700" onclick="showSocialInput('github')">GitHub</button>
                                            <button type="button" class="px-3 py-1 rounded-lg bg-gray-300 text-gray-800 hover:bg-gray-400" onclick="showSocialInput('gitlab')">GitLab</button>
                                            <button type="button" class="px-3 py-1 rounded-lg bg-cyan-100 text-cyan-700 hover:bg-cyan-200" onclick="showSocialInput('wordpress')">WordPress</button>
                                            <button type="button" class="px-3 py-1 rounded-lg bg-green-100 text-green-700 hover:bg-green-200" onclick="showSocialInput('notion')">Notion</button>
                                        </div>
                                        <div id="social-inputs"></div>
                                    </div>
                                    <script>
                                
                                    
                                    function previewProfilePhoto(event) {
                                        const input = event.target;
                                        const previewDiv = document.getElementById('profile_photo_preview');
                                        const previewImg = document.getElementById('profile_photo_img');
                                        if (input.files && input.files[0]) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                previewImg.src = e.target.result;
                                                previewDiv.classList.remove('hidden');
                                            }
                                            reader.readAsDataURL(input.files[0]);
                                        } else {
                                            previewDiv.classList.add('hidden');
                                            previewImg.src = '#';
                                        }
                                    }
                                    function togglePassword(id) {
                                        const input = document.getElementById(id);
                                        const eye = document.getElementById('eye-' + id);
                                        if (input.type === 'password') {
                                            input.type = 'text';
                                            eye.classList.add('text-blue-600');
                                        } else {
                                            input.type = 'password';
                                            eye.classList.remove('text-blue-600');
                                        }
                                    }
                                    const socialInputs = {
                                        linkedin: {label: 'LinkedIn', color: 'blue-100', text: 'blue-700'},
                                        github: {label: 'GitHub', color: 'gray-800', text: 'white'},
                                        gitlab: {label: 'GitLab', color: 'gray-300', text: 'gray-800'},
                                        wordpress: {label: 'WordPress', color: 'cyan-100', text: 'cyan-700'},
                                        notion: {label: 'Notion', color: 'green-100', text: 'green-700'}
                                    };
                                    function showSocialInput(network) {
                                        const container = document.getElementById('social-inputs');
                                        const inputDiv = document.getElementById('input-' + network);
                                        if (!inputDiv) {
                                            const div = document.createElement('div');
                                            div.className = 'flex items-center gap-2 mb-2';
                                            div.id = 'input-' + network;
                                            div.innerHTML = `<span class='px-3 py-1 rounded-lg bg-${socialInputs[network].color} 
                                            text-${socialInputs[network].text}'>${socialInputs[network].label}</span><input type='text' 
                                            name='${network}' class='flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400' 
                                            placeholder='Enlace de ${socialInputs[network].label}'>`;
                                            container.appendChild(div);
                                        } else {
                                            inputDiv.remove();
                                        }
                                    }
                                    </script>
                                    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                        Crear cuenta</button>
                                </form>
                                <div class="mt-4 text-center">
                                    <span class="text-gray-600">¿Ya tienes una cuenta?</span>
                                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Inicia sesión</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endsection
