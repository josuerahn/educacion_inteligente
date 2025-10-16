<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SICEP - Plataforma</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: #f1f5f9;
        }
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .glow {
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.4);
        }
    </style>
    @livewireStyles
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-blue-900 to-gray-800">
    @yield('content')
    @livewireScripts
</body>
</html>
