@extends('layouts.navbar-layout')

@section('title', 'Login')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/login.css') }}">
@endsection

@section('content')
    <div class="main">

        <div class="form">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label>Login</label>

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <button type="submit">Login</button>
            </form>
        </div>

        <div class="link">
            <a class="register-link" href="{{ route('register') }}">Sing up</a>
        </div>
    </div>
@endsection
