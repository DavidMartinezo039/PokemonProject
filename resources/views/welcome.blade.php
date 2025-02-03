@extends('layouts.app') <!-- Extiende la plantilla principal -->

@section('title', 'Inicio') <!-- Título de la página -->

@section('additional-js')

    <script src="{{ asset('View/js/navbar.js') }}"></script>

@endsection

@section('content')
    <x-navbar />


    <!-- Masthead -->
    <header class="masthead">
        <div class="container">
            <!-- <div class="masthead-subheading">Welcome To Poke Project!</div> -->
            <div class="masthead-heading text-uppercase">Welcome To Poket Project</div>
            <a class="btn btn-primary btn-xl text-uppercase" href="#services">Tell Me More</a>
        </div>
    </header>

    <!-- Servicios -->
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Services</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
            <div class="row text-center">
                <!-- Tu contenido de servicio aquí -->
            </div>
        </div>
    </section>
@endsection
