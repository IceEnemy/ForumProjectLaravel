@extends('layouts.nav')

@section('title', 'Home')

@section('page-title', 'Home')

@section('main-content')
<style>
    body, html {
        height: 100%;
        overflow-y: auto; 
    }

    .container-fluid {
        min-height: 100vh;
    }

    .community-image-container {
        width: 140px; 
        height: 132px;
    }

    .community-card {
        border-top: 0.75px solid #CCCCCC; 
        border-bottom: 0.75px solid #CCCCCC; 
        border-left: none; 
        border-right: none; 
        border-radius: 0;
    }

    .community-card + .community-card {
        margin-top: -2px; 
    }

    .community-card .card-body {
        min-height: 120px; 
    }

    .community-card .members-text {
        font-size: 16px;
        font-weight: 600;
    }

    .form-control {
        border-right: 0; 
        border-radius: 30px 0 0 30px; 
        border: 1px solid #CCCCCC;
        background-color: #EEEEEE; 
    }

    /* .btn {
        border-radius: 0 30px 30px 0;
        border: 1px solid #CCCCCC; 
        border-left: 0; 
        background-color: #EEEEEE; 
        color: black; 
    }
    .btn:hover, .btn:focus {
        border-color: #CCCCCC;
        background-color: #EEEEEE; 
        color: black; 
    } */
    /* .btn i {
        padding-right: 10px; 
    } */
    .btn-create-community {
        background-color: white;
        border: 1px solid black;
        color: black;
        font-weight: bold;
        border-radius: 30px; 
    }

    .btn-create-community:hover {
        background-color: #E6E3FF; 
        color: black; 
    }

    .btn-create-community:focus, .btn-create-community:active {
        background-color: white !important; 
        border-color: black !important;     
        color: black !important;            
    }

    .btn-create-community:focus:hover, .btn-create-community:active:hover {
        background-color: #E6E3FF !important; 
        color: black !important;
    }

    .btnCom {
        border-radius: 30px; 
        border: 1px solid #CCCCCC;
    }
    .hover-gray {
        transition: background-color 0.3s ease; /* Smooth transition */
    }

    .hover-gray:hover {
        background-color: var(--gray); /* Light gray color */
        cursor: pointer;
    }

    .com_header{
        position: sticky;
        top: 0;
    }
    .container-fluid {
        position: relative;
    }

</style>

<div class="container-fluid mt-5">
    <div class="row justify-content-start align-items-center">
        <div class="col-12 d-flex">
            <button type="button" class="btn btn-create-community mb-3" data-bs-toggle="modal" data-bs-target="#createCommunityModal" onclick="checkLoginBeforeModal()">
                + Create Community
            </button>

            <div class="ms-3 flex-grow-1">
                <form method="GET" action="{{ route('home') }}" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search communities..." aria-label="Search communities">
                        <button class="btn" type="submit">
                            <i class="bi bi-search"></i>
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

    @if($communities->isEmpty())
        <div class="alert alert-warning mt-3">
            No communities found for your search.
        </div>
    @endif

    <div id="communityList">
        @foreach ($communities as $community)
            <a href="{{ route('community.show', $community->id) }}" class="text-decoration-none text-dark">
                <div class="card mb-0 community-card position-relative hover-gray">
                    <div class="row g-0">
                        <!-- Community Profile Image -->
                        @if ($community->profile_image)
                            <div class="col-md-1 align-items-center justify-content-center p-3 community-image-container">
                                <img 
                                    src="{{ $community->profile_image }}" 
                                    alt="{{ $community->name }}" 
                                    class="img-fluid rounded-circle" 
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                        @endif
    
                        <!-- Community Details -->
                        <div class="col-md-10 d-flex align-items-center justify-content-center">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 25px; font-weight: bold;">
                                    {{ $community->name }}
                                </h5>
                                <p class="card-text" style="font-size: 16px;">
                                    {{ Illuminate\Support\Str::limit($community->description, 150) }}
                                </p>
                            </div>
                        </div>
                    </div>
    
                    <!-- Members Count -->
                    <p class="card-text position-absolute top-0 end-0 p-3 members-text text-muted">
                        Members: {{ $community->members->count() }}
                    </p>
                </div>
            </a>
        @endforeach
    </div>
    
    
</div>

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
                    <button type="submit" class="btn btnCom w-100">Create Community</button>
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
        alert('You need to log in first to create a community.');
        window.location.href = '{{ route('login') }}?error=true';
    } else {
        $('#createCommunityModal').modal('show');
    }
}
</script>
@endpush