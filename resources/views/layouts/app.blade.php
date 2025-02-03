<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Página Pokémon')</title>
    <!-- Enlazando el CSS -->
    <link rel="stylesheet" href="{{ asset('View/css/welcome.css') }}">
    <!-- Agrega otros archivos CSS si es necesario -->
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
