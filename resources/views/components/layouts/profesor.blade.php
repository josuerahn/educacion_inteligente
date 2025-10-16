<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Profesor - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white px-4 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center shadow">
        <span class="font-bold text-xl mb-2 sm:mb-0">Bienvenido Profesor</span>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto justify-center sm:justify-end">

            <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold shadow hover:bg-red-800 transition-colors w-full sm:w-auto">Cerrar sesión</button>
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
