@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'Forgot Password')

@section('content')
<div class="container">
    <div class="card">
        <div>
            <div class="auth-title">Forgot Password</div>
            @if (session('status'))
                <div class="alert alert-success mb-3">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
            @endif

            <form id="forgotPasswordForm" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input 
                        type="email"
                        name="email" 
                        class="form-control" 
                        id="email" 
                        placeholder="Enter your email address" 
                        value="{{ old('email') }}" 
                        required
                    >
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('login') }}" class="hyperlink">Back to Login</a>
                </div>
            </form>
        </div>
        doesn't work yet
        <div class="d-flex justify-content-center align-items-center">
            <button type="submit" form="forgotPasswordForm" class="btn btn-primary">
                Send Password Reset Link
            </button>
        </div>
    </div>
</div>
@endsection