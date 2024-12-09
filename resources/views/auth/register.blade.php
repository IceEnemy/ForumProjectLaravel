@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="card">
        <div>
            <div class="auth-title">Sign Up</div>
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="registerForm" action="{{ route('register.process') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        class="form-control" 
                        id="username" 
                        placeholder="Enter username" 
                        value="{{ old('username') }}" 
                        required
                    >
                </div>
                
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
                
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="form-control" 
                        id="password_confirmation" 
                        placeholder="Confirm password" 
                        required
                    >
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('login') }}" class="hyperlink">Already have an account? Sign In</a>
                </div>
            </form>
        </div>
        <div class="d-flex justify-content-center align-items-center">
            <button type="submit" form="registerForm" class="btn btn-primary">Sign Up</button>
        </div>
    </div>
</div>
@endsection