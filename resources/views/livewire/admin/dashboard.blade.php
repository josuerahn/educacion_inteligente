@extends('components.layouts.admin-layout')
@section('title', 'Dashboard')

@section('content')
<div class="w-full min-h-screen flex flex-col items-center justify-start bg-gray-50 px-2 sm:px-0 py-4">
    <div class="w-full max-w-6xl bg-white p-4 sm:p-10 rounded-2xl shadow-2xl">
        @if (session()->has('mensaje'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-center text-sm">{{ session('mensaje') }}</div>
        @endif
        @if (session('success'))
            <div class="mb-4 p-3 bg-emerald-100 text-emerald-800 rounded text-center text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded text-center text-sm">{{ session('error') }}</div>
        @endif

        <h2 class="text-2xl font-bold mb-6 text-center">Profesores</h2>
        <div class="overflow-x-auto mb-8">
            <table class="min-w-full border rounded-xl text-base">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Nombre</th>
                        <th class="px-6 py-3 text-left">Correo</th>
                        <th class="px-6 py-3 text-left">Curso Asignado</th>
                        <th class="px-6 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($profesores as $profesor)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3 align-top">{{ $profesor->name }}</td>
                            <td class="px-6 py-3 align-top">{{ $profesor->email }}</td>
                            <td class="px-6 py-3 align-top">{{ $profesor->course?->name ?? '-' }}</td>
                            <td class="px-6 py-3 align-top">
                                <form method="POST" action="{{ route('admin.delete.profesor', $profesor->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm bg-red-600 text-white px-4 py-2 rounded-xl w-full sm:w-auto" onclick="return confirm('¿Seguro que quieres eliminar al profesor {{ $profesor->name }}?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <h2 class="text-2xl font-bold mb-6 text-center">Cursos</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border rounded-xl text-base">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Nombre</th>
                        <th class="px-6 py-3 text-left">Descripción</th>
                        <th class="px-6 py-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cursos as $curso)
                        @php
                            $ocupado = \App\Models\User::where('role_id', 2)->where('course_id', $curso->id)->exists();
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-3 align-top">{{ $curso->name }}</td>
                            <td class="px-6 py-3 align-top">{{ $curso->description }}</td>
                            <td class="px-6 py-3 align-top">
                                @if($ocupado)
                                    <button type="button" class="btn btn-danger btn-sm bg-gray-400 text-white px-4 py-2 rounded-xl w-full sm:w-auto cursor-not-allowed" disabled title="No se puede eliminar el curso porque tiene un profesor asignado">Eliminar</button>
                                @else
                                    <form method="POST" action="{{ route('admin.delete.curso', $curso->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm bg-red-600 text-white px-4 py-2 rounded-xl w-full sm:w-auto" onclick="return confirm('¿Seguro que quieres eliminar el curso {{ $curso->name }}?')">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
