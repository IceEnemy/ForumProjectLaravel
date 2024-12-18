@extends('layouts.nav')

@section('page-title', $community->name)

@section('main-content')
<div class="container mt-4">
    <!-- Back Button -->
    <div>
        <a href="{{ route('home') }}" class="btn text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Community Header Section -->
    <div class="card mb-4 shadow-sm">
        <!-- Header Image -->
        @if($community->header_image)
            <img 
                src="{{ $community->header_image }}" 
                alt="Community Header" 
                class="card-img-top" 
                style="height: 200px; object-fit: cover;"
            >
        @else
            <div class="bg-light" style="height: 200px;"></div>
        @endif

        <!-- Community Details -->
        <div class="d-flex p-4 align-items-center">
            <!-- Community Profile Image -->
            <img 
                src="{{ $community->profile_image ?? 'https://via.placeholder.com/100' }}" 
                alt="Community Profile" 
                class="rounded-circle me-4" 
                style="width: 100px; height: 100px; object-fit: cover;"
            >

            <!-- Community Info -->
            <div class="flex-grow-1">
                <h2 class="mb-1 fw-bold">{{ $community->name }}</h2>
                <p class="text-muted mb-1">Created {{ $community->created_at->format('d F Y') }}</p>
                <p class="text-muted">{{ $community->members->count() }} Members</p>
            </div>

            <!-- Join Button -->
            <div>
                @if($community->members->contains(auth()->user()))
                    <button class="btn btn-secondary rounded-pill" disabled>Joined</button>
                @else
                    <form action="{{ route('community.join', $community->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary rounded-pill">Join</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Left Column: Search and Sort + Posts -->
        <div class="col-lg-8">
            <!-- Search and Sort Section -->
            <div class="border-top border-bottom py-3 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Create Post Button -->
                    <button class="btn btn-primary rounded-pill">+ Create Post</button>

                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('community.show', $community->id) }}" class="me-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control rounded-start-pill" placeholder="Search posts...">
                            <button class="btn btn-light border rounded-end-pill" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Sort Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-light border dropdown-toggle rounded-pill" type="button" id="sortMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort By
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortMenu">
                            <li><a class="dropdown-item" href="?sort=latest">Latest</a></li>
                            <li><a class="dropdown-item" href="?sort=oldest">Oldest</a></li>
                            <li><a class="dropdown-item" href="?sort=most_upvoted">Most Upvoted</a></li>
                            <li><a class="dropdown-item" href="?sort=least_upvoted">Least Upvoted</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Posts Section -->
            <h5 class="mb-3 fw-bold">Posts</h5>
            @forelse ($community->posts as $post)
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center">
                        <img 
                            src="{{ $post->user->profile_picture ?? 'https://via.placeholder.com/50' }}" 
                            class="rounded-circle me-3" 
                            style="width: 50px; height: 50px; object-fit: cover;"
                        >
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $post->title }}</h5>
                            <p class="text-muted mb-1">{{ Illuminate\Support\Str::limit($post->content, 150) }}</p>
                            <small class="text-muted">Comments ({{ $post->comments->count() }}) | Upvotes ({{ $post->upvotes }})</small>
                        </div>
                    </div>
                </div>
            @empty
                <p>No posts yet.</p>
            @endforelse
        </div>

        <!-- Right Column: Description and Rules -->
        <div class="col-lg-4">
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">About {{ $community->name }}</h5>
                    <p class="mb-4">{{ $community->description ?? 'No description provided.' }}</p>
                    
                    <h6 class="fw-bold">Rules</h6>
                    <ul class="ps-3 mb-0">
                        @forelse (explode("\n", $community->rules) as $rule)
                            <li>{{ $rule }}</li>
                        @empty
                            <li>No rules specified.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
