@extends('layouts.navbar-layout')

@section('title', 'Login')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/login.css') }}">
@endsection

@section('content')
    <div class="login-wrapper">
        <div class="login-container">
            <div class="form-box">
                <h2>Login</h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                        @error('password')
                        <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me">
                            <input type="checkbox" id="remember_me" name="remember">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit">Login</button>

                    <p>Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection
