@extends('layouts.app')

@section('title', 'Home')

@section('content')
<style>
    body, html {
        height: 100%;
        overflow-y: auto; /* Enable vertical scrolling */
    }

    .container-fluid {
        min-height: 100vh; /* Ensure the container takes at least the full viewport height */
    }

    .community-image-container {
        width: 140px; /* Adjust this value as needed */
        height: 132px;
    }

    .community-card {
        border-top: 0.75px solid #CCCCCC; /* Top border with the specified color and thickness */
        border-bottom: 0.75px solid #CCCCCC; /* Bottom border with the specified color and thickness */
        border-left: none; /* Remove left border */
        border-right: none; /* Remove right border */
        border-radius: 0;
    }

    .community-card + .community-card {
        margin-top: -2px; /* Adjust the gap between adjacent cards to avoid overlap of borders */
    }

    .community-card .card-body {
        min-height: 120px; /* Ensure content doesn't overlap */
    }

    .community-card .members-text {
        font-size: 16px;
        font-weight: 600;
    }

    /* Adjust the search bar styles */
    .form-control {
        border-right: 0; /* Remove the right border to connect with button */
        border-radius: 30px 0 0 30px; /* Round the left side */
        border: 1px solid #CCCCCC;
        background-color: #EEEEEE; /* Light border color to match button */
    }

    .btn {
        border-radius: 0 30px 30px 0; /* Round the right side */
        border: 1px solid #CCCCCC; /* Same border color as search input */
        border-left: 0; /* Remove the left border to connect with input */
        background-color: #EEEEEE; /* Ensure button background matches search input */
        color: black; /* Make button text color consistent */
    }
    .btn:hover, .btn:focus {
        border-color: #CCCCCC; /* Ensure no change in border color on hover/focus */
        background-color: #EEEEEE; /* Ensure no change in background on hover/focus */
        color: black; /* Ensure no change in text color on hover/focus */
    }
    .btn i {
        padding-right: 10px; /* Adjust icon padding if necessary */
    }
    .btn-create-community {
        background-color: white;
        border: 1px solid black;
        color: black;
        font-weight: bold;
        border-radius: 30px; /* Make the button round */
    }

    .btn-create-community:hover {
        background-color: #E6E3FF; /* Hover background color */
        color: black; /* Keep the text color same on hover */
    }
</style>

<div class="container-fluid mt-5">
    <div class="row justify-content-start align-items-center">
        <div class="col-12 d-flex">
            <!-- Button to create a new community -->
            <a href="{{ route('community.create') }}" class="btn btn-create-community mb-3">
                + Create Community
            </a>

            <!-- Search bar -->
            <div class="ms-3 flex-grow-1">
                <form method="GET" action="{{ route('home') }}" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search communities..." aria-label="Search communities">
                        <button class="btn" type="submit">
                            <i class="bi bi-search"></i> <!-- Bootstrap icon for search -->
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Message when no communities are found -->
    @if($communities->isEmpty())
        <div class="alert alert-warning mt-3">
            No communities found for your search.
        </div>
    @endif

    <!-- List all communities -->
    <div id="communityList">
        @foreach ($communities as $community)
            <div class="card mb-0 community-card">
                <div class="row g-0">
                    <!-- Community Header Image -->
                    <div class="col-md-1 d-flex align-items-center justify-content-center p-3 community-image-container">
                        <img 
                            src="{{ $community->header_image ?? 'https://via.placeholder.com/150' }}" 
                            alt="{{ $community->name }}" 
                            class="img-fluid rounded-circle" 
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <!-- Community Info -->
                    <div class="col-md-10 d-flex align-items-center justify-content-center">
                        <div class="card-body">
                            <h5 class="card-title" style="font-size: 25px; font-weight: bold;">{{ $community->name }}</h5>
                            <p class="card-text" style="font-size: 16px;">{{ Illuminate\Support\Str::limit($community->description, 150) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Members count positioned at the top-right corner -->
                <p class="card-text position-absolute top-0 end-0 p-3 members-text text-muted">
                    Members: {{ $community->members->count() }}
                </p>
            </div>
        @endforeach
    </div>
</div>
@endsection
