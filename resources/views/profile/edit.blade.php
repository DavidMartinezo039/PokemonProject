@extends('layouts.profile')

@section('title', 'Perfil de Usuario')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/profile.css') }}">
@endsection

@section('additional-js')

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

@endsection

@section('header')
    <h1 class="text-2xl font-bold">Bienvenido a tu perfil, {{ Auth::user()->name }}</h1>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="card">
            <h2 class="section-title">Información de tu perfil</h2>
            <!-- Aquí puedes incluir contenido dinámico -->
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card">
            <h2 class="section-title">Cambiar contraseña</h2>
            @include('profile.partials.update-password-form')
        </div>

        <div class="card">
            <h2 class="section-title">Eliminar cuenta</h2>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
