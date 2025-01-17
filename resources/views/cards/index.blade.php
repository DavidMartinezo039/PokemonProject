@extends('layouts.navbar-layout') <!-- Extiende la plantilla principal -->

@section('title', 'Cards') <!-- Título de la página -->

@section('content')
    <x-navbar/>

    <h1>Lista de Cartas</h1>

    <x-card-list :cards="$cards"/>
@endsection
