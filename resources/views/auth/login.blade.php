@extends('layouts.app')

@section('title', 'Login')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('content')
<div class="card">
    <div>
        <div class="auth-title">Sign In</div>
        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <!-- Assign an ID to the form so the button outside can target it -->
        <form id="loginForm" action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    class="form-control" 
                    id="email" 
                    placeholder="Enter email" 
                    value="{{ old('email') }}" 
                    required
                >
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    name="password" 
                    class="form-control" 
                    id="password" 
                    placeholder="Enter password" 
                    required
                >
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('register') }}" class="hyperlink">Don't have an account? Sign Up  </a>
                <a href="{{ route('password.request') }}" class= "hyperlink">Forgot password?</a>
            </div>
        </form>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        <!-- The button references the form via the form attribute -->
        <button type="submit" form="loginForm" class="btn btn-primary">Sign In</button>
    </div>
</div>
@endsection