<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Página Pokémon')</title>
    <!-- Enlazando el CSS -->
    <link rel="stylesheet" href="{{ asset('View/css/welcome.css') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('View/assets/img/logo.png') }}">

    <!-- Agrega otros archivos CSS si es necesario -->
    @yield('additional-css')
</head>
<body>
<header>
    <!-- Aquí puedes incluir la Navbar común -->
</header>

<main>
    @yield('content') <!-- Aquí se cargará el contenido específico de cada página -->
</main>

<footer>
    <!-- Footer común -->
</footer>

<!-- Enlazando el JS -->
<script src="{{ asset('View/js/scripts.js') }}"></script>
@yield('additional-js')
</body>
</html>
