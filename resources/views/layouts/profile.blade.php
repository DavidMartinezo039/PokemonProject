<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perfil de Usuario')</title>
    <!-- CSS principal -->
    <link rel="stylesheet" href="{{ asset('View/css/navbar.css') }}">
    @yield('additional-css')
</head>
<body>
<x-navbar /> <!-- Incluye la barra de navegación -->

<header class="bg-gray-800 text-white py-4">
    <div class="container mx-auto">
        @yield('header') <!-- Contenido específico del encabezado -->
    </div>
</header>

<main class="py-8">
    <div class="container mx-auto">
        @yield('content') <!-- Contenido dinámico -->
    </div>
</main>

<footer class="bg-gray-900 text-white text-center py-4">
    <p>&copy; {{ date('Y') }} Tu Proyecto. Todos los derechos reservados.</p>
</footer>

<script src="{{ asset('/View/js/navbar.js') }}"></script>
    @yield('additional-js')
</body>
</html>
