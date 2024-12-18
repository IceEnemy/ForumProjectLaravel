@extends('layouts.nav')

@section('page-title', $post->title)

@section('main-content')
<div class="container mt-4">
    <!-- Back Button and Actions -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <div>
            <a href="{{ route('community.show', $post->community_id) }}" class="btn text-decoration-none">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
        <div class="d-flex gap-2">
            @if (auth()->id() === $post->user_id)
                <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary btn-action">
                    <i class="bi bi-pencil"></i> Edit
                </a>
            @endif
            @if (
                auth()->id() === $post->user_id || 
                auth()->id() === $post->community->owner_id || 
                $post->community->administrators->contains(auth()->user())
            )
                <form action="{{ route('post.delete', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-action">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            @endif
        </div>
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
                    style="width: 50px; height: 50px; object-fit: cover;">
                <h5 class="mb-0 fw-bold">{{ $post->author->username }}</h5>
            </div>

            <!-- Post Title and Date -->
            <h2 class="fw-bold">{{ $post->title }}</h2>
            <p class="text-muted">
                Created: {{ $post->created_at->format('d/m/Y H:i') }}
                @if ($post->updated_at && $post->updated_at != $post->created_at)
                    <br>Updated: {{ $post->updated_at->format('d/m/Y H:i') }}
                @endif
            </p>

            <!-- Post Image -->
            @if ($post->image)
                <img 
                    src="{{ $post->image }}" 
                    alt="Post Image" 
                    class="img-fluid mb-4 rounded" 
                    style="width: 100%; object-fit: cover;">
            @endif

            <!-- Post Body -->
            <p>{{ $post->content }}</p>

            <!-- Upvote and Downvote Section -->
            <div class="d-flex align-items-center my-4">
                <div class="vote-container d-flex align-items-center">
                    <form action="{{ route('post.toggleUpvote', $post->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm p-0 {{ $post->upvotes->contains(auth()->id()) ? 'voted' : 'not-voted' }}">
                            <span class="mdi--arrow-up-bold"></span>
                        </button>
                    </form>
                    <span class="mx-2 fw-bold">{{ $post->upvotes->count() - $post->downvotes->count() }}</span>
                    <form action="{{ route('post.toggleDownvote', $post->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm p-0 {{ $post->downvotes->contains(auth()->id()) ? 'voted' : 'not-voted' }}">
                            <span class="mdi--arrow-down-bold"></span>
                        </button>
                    </form>
                </div>
            </div>

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

            <!-- Comments Section -->
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
                                @if ($communityPost->image)
                                    <img 
                                        src="{{ $communityPost->image }}" 
                                        alt="Post Image" 
                                        class="w-100 h-100" 
                                        style="object-fit: cover; filter: brightness(50%);">
                                @else
                                    <div 
                                        class="w-100 h-100" 
                                        style="background-color: var(--gray); display: flex; justify-content: center; align-items: center;">
                                        <span class="text-muted">No Image</span>
                                    </div>
                                @endif
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
.hover-gray:hover {
    background-color: #f8f9fa;
    transition: background-color 0.3s;
    cursor: pointer;
}
.not-voted {
    color: var(--black);
}
.voted {
    color: var(--main_purple);
}
.vote-container {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    background-color: var(--tertiary_purple);
    border-radius: 30px;
    padding: 5px 10px;
    max-width: fit-content;
}
.vote-container button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: none;
    color: var(--black);
    font-size: 1.2rem;
    padding: 0;
    transition: color 0.3s ease-in-out;
}
.vote-container button span {
    width: 24px;
    height: 24px;
    display: block;
}
.vote-container .voted {
    color: var(--main_purple);
}
.vote-container button:hover {
    color: var(--main_purple);
}
.sticky-box {
    position: sticky;
    top: 20px;
    max-height: 90vh;
    overflow-y: auto;
}
.sticky-box::-webkit-scrollbar {
    width: 8px;
}
.sticky-box::-webkit-scrollbar-thumb {
    background-color: #ddd;
    border-radius: 4px;
}
.sticky-box::-webkit-scrollbar-thumb:hover {
    background-color: #bbb;
}
.btn-action {
    width: 100px;
    text-align: center;
}
</style>
@endpush
