@extends('layouts.navbar-layout')

@section('title', 'Sign Up')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/login.css') }}">
@endsection

@section('additional-js')
    <script src="{{ asset('View/js/login.js') }}"></script>
@endsection


@section('content')
    <div class="main">

        <div class="form">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <label>Sign Up</label>

                <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                @error('password_confirmation')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <button type="submit">Register</button>
            </form>
        </div>

        <div class="link">
            <a class="register-link" href="{{ route('login') }}">Login</a>
        </div>
@endsection
