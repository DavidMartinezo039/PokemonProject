@extends('layouts.navbar-layout')

@section('title', 'Email Verification')

@section('additional-css')
    <link rel="stylesheet" href="{{ asset('View/css/login.css') }}">
@endsection

@section('additional-js')
    <script src="{{ asset('View/js/login.js') }}"></script>
@endsection

@section('content')
    <div class="main">

        <div class="form">
            <div class="text-message">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="text-message">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div class="text-center">
                    <button type="submit" class="btn">
                        {{ __('Resend Verification Email') }}
                    </button>
                </div>
            </form>

            <div class="mt-4 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>

        <div class="link text-center">
            <a class="register-link" href="{{ route('login') }}">Login</a>
        </div>
    </div>
@endsection
