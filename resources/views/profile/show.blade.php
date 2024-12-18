@extends('layouts.nav')

@section('title', 'Profile')

@section('page-title', 'Profile')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('page-title', 'Profile')

@section('main-content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card mb-5 mt-4">
                <div class="card-header text-center">
                    <h3>Profile</h3>
                </div>
                <div class="card-body text-center">
                    <!-- Display Current Profile Picture -->
                    <div class="position-relative p-4">
                        <img 
                            src="{{ $user->profile_picture ?? 'https://via.placeholder.com/150' }}" 
                            alt="Profile Picture" 
                            class="img-thumbnail rounded-circle"    
                            style="width: 150px; height: 150px;"
                        >
                        <button type="button" id="change-picture-button" 
                            class="icon-c rounded-circle justify-content-center mt-3" 
                            style="width: 40px; height: 40px; display:block; padding:0; margin:auto;"> 
                            <i class="bi bi-camera"></i>
                        </button>
                        <form id="upload-picture-form" action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data" style="display:none">
                            @csrf
                            <div class="mt-4">
                                <label for="profile_picture" class="form-label">Upload New Profile Picture</label>
                                <input type="file" name="profile_picture" id="profile_picture" class="form-control w-75 mx-auto" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Upload</button>
                            <button type="button" id="cancel-upload-button" class="btn btn-secondary mt-2">Cancel</button>
                        </form>
                    </div>
                    
                </class=>
                <div class="card-body">
                    <div class="mt-1 mb-4 text-start">
                        <h5 class="fw-bold mb-2">{{ $user->username }}</h5>
                    </div>
                    <form id="edit-profile-form" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-4 form-group row text-start">
                            <label for="username" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-8">
                                <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" readonly>
                                <div class="text-end mt-2">
                                    <button type="button" id="edit-profile-btn" class="btn btn-primary">Edit Profile</button>
                                    <button type="submit" id="save-profile-btn" class="btn btn-secondary" style="display: none;">Save Changes</button>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4 form-group row text-start">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <input type="email" name="email" id="email" class="form-control-plaintext" value="{{ $user->email }}" readonly>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <button type="button" id="change-password-btn" class="btn btn-secondary">Change Password</button>
                    </div>

                    <form id="change-password-form" action="{{ route('profile.change_password') }}" method="POST" style="display: none;" class="mt-4">
                        @csrf
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </form>
                <div>
            <div> 

        </div>
    </div>
</div>
<script>
    document.getElementById('edit-profile-btn').addEventListener('click', function() {
        // Enable input fields
        document.getElementById('username').removeAttribute('readonly');
        
        // Show save button and hide edit button
        document.getElementById('edit-profile-btn').style.display = 'none';
        document.getElementById('save-profile-btn').style.display = 'inline-block';
    });

    document.getElementById('change-picture-button').addEventListener('click', function() {
        document.getElementById('upload-picture-form').style.display = 'block';
        document.getElementById('change-picture-button').style.display = 'none';
    });

    document.getElementById('cancel-upload-button').addEventListener('click', function() {
        document.getElementById('upload-picture-form').style.display = 'none';
        document.getElementById('change-picture-button').style.display = 'block';
    });

    document.getElementById('change-password-btn').addEventListener('click', function() {
        const form = document.getElementById('change-password-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    });
</script>
@endsection
