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
        border-radius: 30px; /* Fully rounded corners */
    }

    .btn-create-community:hover {
        background-color: #E6E3FF; /* Hover background color */
        color: black; /* Keep text color the same on hover */
    }

    .btn-create-community:focus, .btn-create-community:active {
        background-color: white !important; /* Keep the original background color */
        border-color: black !important;     /* Keep the original border color */
        color: black !important;            /* Keep text color black */
    }

    /* Override the active/focus state to allow hover effect */
    .btn-create-community:focus:hover, .btn-create-community:active:hover {
        background-color: #E6E3FF !important; /* Hover effect when clicked or focused */
        color: black !important;
    }

    .btn-primary {
        border-radius: 30px; /* Make the button fully rounded */
        border: 1px solid #CCCCCC;
    }

</style>

<div class="container-fluid mt-5">
    <div class="row justify-content-start align-items-center">
        <div class="col-12 d-flex">
            <!-- Button to create a new community -->
            <button type="button" class="btn btn-create-community mb-3" data-bs-toggle="modal" data-bs-target="#createCommunityModal" onclick="checkLoginBeforeModal()">
                + Create Community
            </button>

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

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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
                            src="{{ $community->profile_image ?? 'https://via.placeholder.com/150' }}" 
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

<!-- Create Community Modal -->
<div class="modal fade" id="createCommunityModal" tabindex="-1" aria-labelledby="createCommunityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCommunityModalLabel">Create Community</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('community.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Community Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter the community name..." required>
                        <small class="form-text text-muted">Choose a name that represents the community's purpose or theme.</small>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Enter a brief description of the community..." required></textarea>
                        <small class="form-text text-muted">Describe the purpose or focus of the community.</small>
                    </div>
                    <div class="mb-3">
                        <label for="header_image" class="form-label">Header Image</label>
                        <input type="file" name="header_image" id="header_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Community Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="rules" class="form-label">Community Rules</label>
                        <textarea name="rules" id="rules" class="form-control" rows="3" placeholder="Enter the community rules here..."></textarea>
                        <small class="form-text text-muted">Enter the rules that members should follow in this community.</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Create Community</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection


@push('scripts')
<script>
function checkLoginBeforeModal() {
    if (!{{ Auth::check() ? 'true' : 'false' }}) {
        // Show an alert or an error message before redirecting
        alert('You need to log in first to create a community.');

        // Redirect to login page with an error flag in URL
        window.location.href = '{{ route('login') }}?error=true';
    } else {
        // Proceed with showing the modal if logged in
        $('#createCommunityModal').modal('show');
    }
}
</script>
@endpush