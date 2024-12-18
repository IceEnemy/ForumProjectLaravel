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
        @if($community->header_image)
            <img src="{{ $community->header_image }}" alt="Community Header" class="card-img-top" style="height: 150px; object-fit: cover;">
        @else
            <div class="bg-light" style="height: 150px;"></div>
        @endif
        <div class="d-flex p-4 align-items-center">
            <img src="{{ $community->profile_image ?? 'https://via.placeholder.com/100' }}" 
                 alt="Community Profile" class="rounded-circle me-4" style="width: 100px; height: 100px; object-fit: cover;">
            <div class="flex-grow-1">
                <h2 class="mb-1 fw-bold">{{ $community->name }}</h2>
                <p class="text-muted mb-1">Created {{ $community->created_at->format('d F Y') }}</p>
                <p class="text-muted">{{ $community->members->count() }} Members</p>
            </div>
            <!-- Join Button -->
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

    <!-- Add Post, Search, and Sort -->
    <div class="border-top border-bottom py-3 mb-4">
        <div class="d-flex justify-content-between align-items-center gap-5">
            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createPostModal">+ Add Post</button>
            <form method="GET" action="{{ route('community.show', $community->id) }}" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start" placeholder="Search posts...">
                <button class="btn btn-light rounded-end" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown">Sort By</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?sort=latest">Latest</a></li>
                    <li><a class="dropdown-item" href="?sort=oldest">Oldest</a></li>
                    <li><a class="dropdown-item" href="?sort=most_upvoted">Most Upvoted</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Posts Section -->
    @forelse($community->posts as $post)
        <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none text-dark">
            <div class="card mb-3 hover-gray">
                <div class="card-body d-flex">
                    <!-- Post Image -->
                    @if($post->image)
                        <img src="{{ $post->image }}" class="rounded me-3" style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded me-3" style="width: 120px; height: 80px;"></div>
                    @endif
                    <!-- Post Content -->
                    <div class="flex-grow-1">
                        <h5 class="fw-bold">{{ $post->title }}</h5>
                        <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                        <p class="mb-2">{{ Illuminate\Support\Str::limit($post->content, 100) }}</p>
                        <div>
                            <span class="text-muted me-3"><i class="bi bi-chat"></i> Comments ({{ $post->comments->count() }})</span>
                            <span class="text-muted d-flex align-items-center">
                                <!-- Upvote Button -->
                                <form action="{{ route('post.toggleUpvote', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 {{ $post->upvotes->contains(auth()->id()) ? 'text-primary' : '' }}">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                </form>
                            
                                <!-- Vote Count -->
                                <span class="mx-2 fw-bold">
                                    {{ $post->upvotes->count() - $post->downvotes->count() }}
                                </span>
                            
                                <!-- Downvote Button -->
                                <form action="{{ route('post.toggleDownvote', $post->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm p-0 {{ $post->downvotes->contains(auth()->id()) ? 'text-danger' : '' }}">
                                        <i class="bi bi-arrow-down"></i>
                                    </button>
                                </form>
                            </span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <p>No posts yet.</p>
    @endforelse
</div>

<!-- Add Post Modal -->
<div class="modal fade" id="createPostModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('post.store', $community->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="title" class="form-control mb-3" placeholder="Title" required>
                <textarea name="content" class="form-control mb-3" rows="3" placeholder="Content" required></textarea>
                <label for="image" class="form-label">Image (Optional)</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Post</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
.hover-gray:hover {
    background-color: #f8f9fa;
    transition: background-color 0.3s;
    cursor: pointer;
}
</style>
@endpush
