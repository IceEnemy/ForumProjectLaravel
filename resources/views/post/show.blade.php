@extends('layouts.nav')

@section('page-title', $post->title)

@section('main-content')
<div class="container mt-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('community.show', $post->community_id) }}" class="btn text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <!-- Post Content Section -->
    <div class="row">
        <!-- Left Column: Post Details -->
        <div class="col-lg-9">
            <!-- Author Info -->
            <div class="d-flex align-items-center mb-3">
                <img 
                    src="{{ $post->author->profile_picture ?? 'https://via.placeholder.com/50' }}" 
                    class="rounded-circle me-3" 
                    style="width: 50px; height: 50px; object-fit: cover;"
                >
                <h5 class="mb-0 fw-bold">{{ $post->author->username }}</h5>
            </div>

            <!-- Post Title and Date -->
            <h2 class="fw-bold">{{ $post->title }}</h2>
            <p class="text-muted">{{ $post->created_at->format('d/m/Y') }}</p>

            <!-- Post Image -->
            @if ($post->image)
                <img 
                    src="{{ $post->image }}" 
                    alt="Post Image" 
                    class="img-fluid mb-4 rounded" 
                    style="width: 100%; object-fit: cover;"
                >
            @endif

            <!-- Post Body -->
            <p>{{ $post->content }}</p>

            <!-- Upvote and Downvote Buttons -->
            <div class="d-flex align-items-center my-4">
                <form action="{{ route('post.toggleUpvote', $post->id) }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-sm p-0 {{ $post->upvotes->contains(auth()->id()) ? 'text-primary' : '' }}">
                        <i class="bi bi-arrow-up" style="font-size: 1.5rem;"></i>
                    </button>
                </form>

                <span class="fw-bold mx-2">{{ $post->upvotes->count() - $post->downvotes->count() }}</span>

                <form action="{{ route('post.toggleDownvote', $post->id) }}" method="POST" class="me-2">
                    @csrf
                    <button type="submit" class="btn btn-sm p-0 {{ $post->downvotes->contains(auth()->id()) ? 'text-danger' : '' }}">
                        <i class="bi bi-arrow-down" style="font-size: 1.5rem;"></i>
                    </button>
                </form>
            </div>

            <!-- Comment Section Placeholder -->
            <!-- Comment Input Form -->
            <div class="d-flex mb-4">
                <img src="{{ auth()->user()->profile_picture ?? 'https://via.placeholder.com/50' }}" 
                    class="rounded-circle me-2" style="width: 50px; height: 50px; object-fit: cover;">
                <form method="POST" action="{{ route('post.comment', $post->id) }}" class="flex-grow-1">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="content" class="form-control border-0 border-bottom" placeholder="Add a comment...">
                        <button type="submit" class="btn"><i class="bi bi-send"></i></button>
                    </div>
                </form>
            </div>

            <!-- Comments List -->
            @foreach ($comments as $comment)
                @include('partials.comment', ['comment' => $comment])
            @endforeach

        </div>

        <!-- Right Column: Other Posts in Community -->
        <div class="col-lg-3">
            <div class="card shadow-sm sticky-box">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Other Posts</h5>
                    @forelse ($communityPosts as $communityPost)
                        <a href="{{ route('post.show', $communityPost->id) }}" class="text-decoration-none">
                            <div class="mb-3 position-relative" style="height: 120px; border-radius: 8px; overflow: hidden;">
                                <img 
                                    src="{{ $communityPost->image ?? 'https://via.placeholder.com/200' }}" 
                                    alt="Post Image" 
                                    class="w-100 h-100" 
                                    style="object-fit: cover; filter: brightness(50%);"
                                >
                                <div class="position-absolute text-white p-2" style="bottom: 0; left: 0; right: 0;">
                                    <p class="mb-0 fw-bold">{{ $communityPost->title }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-muted">No other posts available.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bi-arrow-up, .bi-arrow-down {
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }

    .text-primary .bi-arrow-up, 
    .text-danger .bi-arrow-down {
        color: inherit;
    }

    .position-relative img:hover {
        filter: brightness(70%);
    }

    /* Scrollable posts box with sticky positioning */
    .sticky-box {
        position: sticky;
        top: 20px; /* Sticks below the top margin */
        height: 70vh;
        /* overflow-y: auto; */
    }

    /* Custom scrollbar styling */
    .sticky-box::-webkit-scrollbar {
        width: 8px;
    }

    .sticky-box::-webkit-scrollbar-thumb {
        background-color: #cccccc;
        border-radius: 4px;
    }

    .sticky-box::-webkit-scrollbar-thumb:hover {
        background-color: #aaaaaa;
    }
    .row, .container, .col-lg-3 {
        overflow-y: visible !important;
        position: relative;
        height: 100%;
    }
</style>
@endpush
