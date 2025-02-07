<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('View/assets/img/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('View/css/Cards/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/navigation.css') }}">
    <link rel="stylesheet" href="{{ asset('View/css/error-message.css') }}">
    @yield('additional-css')
</head>
<body>
@include('layouts.navbar-layout')

<main>

    <x-card-list :cards="$cards"/>

</main>

<script src="{{ asset('/View/js/cards.js') }}"></script>
@yield('additional-js')
</body>
</html>
