<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-700 text-white px-4 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center shadow">
        <span class="font-bold text-xl mb-2 sm:mb-0">Admin Panel</span>
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 w-full sm:w-auto">
            <a href="{{ route('admin.profesores.create') }}" class="px-4 py-2 rounded-lg font-semibold shadow bg-blue-800 text-white hover:bg-blue-900 transition">Agregar Profesor</a>
            <a href="{{ route('admin.cursos.create') }}" class="px-4 py-2 rounded-lg font-semibold shadow bg-green-200 text-green-900 hover:bg-green-400 transition">Agregar Curso</a>
            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto" style="display:inline">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-lg font-semibold shadow bg-red-600 text-white hover:bg-red-800 transition w-full sm:w-auto">Cerrar sesión</button>
            </form>
        </div>
    </nav>
    <main class="container mx-auto py-8">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    @livewireScripts
</body>
</html>
