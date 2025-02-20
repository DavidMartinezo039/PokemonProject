@extends('layouts.app') <!-- Extiende la plantilla principal -->

@section('title', 'Inicio') <!-- Título de la página -->

@section('additional-css')

    <link rel="stylesheet" href="{{ asset('View/css/navbar.css') }}">

@endsection

@section('additional-js')

    <script src="{{ asset('View/js/navbar.js') }}"></script>

@endsection

@section('content')
    <x-navbar />


    <!-- Masthead -->
    <header class="masthead">
        <div class="container">
            <!-- <div class="masthead-subheading">Welcome To Poke Project!</div> -->
            <div class="masthead-heading text-uppercase">{{__('Welcome  To Poket Project')}}</div>
            <a class="btn btn-primary btn-xl text-uppercase" href="{{ route('user-sets.index') }}">{{__('My Sets')}}</a>
        </div>
    </header>
@endsection
