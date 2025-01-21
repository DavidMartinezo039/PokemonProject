@extends('layouts.navbar-layout')

@section('title', 'Login')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/login.css') }}">
@endsection

@section('content')
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <!-- Sign Up Form -->
        <div class="signup">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <label for="chk" aria-hidden="true">Sign up</label>

                <!-- User Name -->
                <input type="text" name="name" placeholder="User name" value="{{ old('name') }}" required>
                @error('name')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Email -->
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Password -->
                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Confirm Password -->
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit">Sign up</button>
            </form>
        </div>

        <!-- Login Form -->
        <div class="login">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <label for="chk" aria-hidden="true">Login</label>

                <!-- Email -->
                <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                @error('email')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <!-- Password -->
                <input type="password" name="password" placeholder="Password" required>
                @error('password')
                <p class="error-message">{{ $message }}</p>
                @enderror

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
@endsection
