@extends('layouts.navbar-layout')

@section('title', 'Sign Up')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/login.css') }}">
@endsection

@section('content')
    <div class="login-wrapper">
        <div class="form-box">
            <h2>Sign Up</h2>
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
@endsection
