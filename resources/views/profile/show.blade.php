@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="card-header text-center">
                    <h3>Profile</h3>
                </div>
                <div class="card-body text-center">
                    <!-- Display Current Profile Picture -->
                    <div class="mb-4">
                        <img 
                            src="{{ $user->profile_picture ?? 'https://via.placeholder.com/150' }}" 
                            alt="Profile Picture" 
                            class="img-thumbnail rounded-circle" 
                            style="width: 150px; height: 150px;"
                        >
                    </div>

                    <!-- Upload New Profile Picture Form -->
                    <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label">Upload New Profile Picture</label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
