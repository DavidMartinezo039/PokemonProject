<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Página Pokémon')</title>
    <!-- Enlazando el CSS -->
    <link rel="stylesheet" href="{{ asset('View/css/navbar.css') }}">
    <!-- Agrega otros archivos CSS si es necesario -->
    @yield('additional-css')

</head>
<body>
<x-navbar/>
@yield('content')

<!-- Enlazando el JS -->
<script src="{{ asset('/View/js/navbar.js') }}"></script>
@yield('additional-js')
</body>
</html>
