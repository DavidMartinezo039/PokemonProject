@extends('layouts.navbar-layout')

@section('title', 'Error 404 - No encontrado')

@section('content')
    <style>

        body {
            margin-top: 100px;
        }

    </style>
    <div class="container">
        <h1>Error 404 - No encontrado</h1>
        <p>{{ $exception->getMessage() }}</p>
        <p>Lo sentimos, la página o el recurso que estás buscando no existe o no tienes permisos para verlo.</p>
    </div>
@endsection
