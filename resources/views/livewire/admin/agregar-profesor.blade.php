@extends('components.layouts.admin-layout')
@section('title', 'Agregar Profesor')
@section('content')
<div class="w-full min-h-screen flex items-center justify-center bg-gray-50 px-2 sm:px-0">
    <div class="w-full max-w-md bg-white p-6 sm:p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Agregar Profesor</h2>
        <form method="POST" action="{{ route('admin.profesores.store') }}" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="name" placeholder="Nombre" class="w-full mb-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required value="{{ old('name') }}">
            </div>
            <div>
                <input type="email" name="email" placeholder="Correo" class="w-full mb-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required value="{{ old('email') }}">
            </div>
            <div>
                <input type="password" name="password" placeholder="Contraseña" class="w-full mb-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required>
            </div>
            <div>
                <select name="tutoria_id" class="w-full mb-2 p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-200 focus:outline-none transition" required>
                    <option value="">Selecciona una Tutoria</option>
                    @foreach($tutorias as $tutoria)
                        @php
                            $ocupado = \App\Models\User::where('role_id', 2)->where('tutoria_id', $tutoria->id)->exists();
                        @endphp
                        <option value="{{ $tutoria->id }}" @if($ocupado) disabled style="background:#f8d7da;color:#721c24;" @endif>
                            {{ $tutoria->name }} @if($ocupado) (Ocupado) @endif
                        </option>
                    @endforeach
                </select>
                @error('profesor.tutoria_id')
                    <div class="mb-2 text-red-600 text-sm">{{ $message }}</div>
                @enderror
            </div>
            <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-300 rounded-xl text-center">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl w-full sm:w-auto">Agregar</button>
            </div>
        </form>
    </div>
</div>
@endsection
