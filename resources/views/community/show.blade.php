@extends('layouts.nav')

@section('page-title', $community->name)

@section('main-content')
<div class="container mt-4">
    <!-- Back Button -->
    <div class="mt-3 mb-3">
        <a href="{{ route('home') }}" class="btn text-decoration-none">
            <i class="bi bi-arrow-left"></i> Back
        </a>
        <!-- Options Button for Admins/Owner -->
        @if(auth()->id() === $community->owner_id || $community->administrators->contains(auth()->user()))
            <a href="{{ route('community.settings', $community->id) }}" class="btn btn-secondary btn-action">
                <i class="bi bi-gear"></i> Settings
            </a>
        @endif
    </div>



    <!-- Community Header Section -->
    <div class="card mb-4 shadow-sm">
        @if($community->header_image)
            <img src="{{ $community->header_image }}" 
                alt="Community Header" 
                class="card-img-top" 
                style="height: 150px; object-fit: cover;">
        @endif

        <div class="d-flex p-4 align-items-center">
            @if($community->profile_image)
                <img src="{{ $community->profile_image }}" 
                    alt="Community Profile" 
                    class="rounded-circle me-4" 
                    style="width: 100px; height: 100px; object-fit: cover;">
            @endif

            <div class="flex-grow-1">
                <h2 class="mb-1 fw-bold">{{ $community->name }}</h2>
                <p class="text-muted mb-1">Created {{ $community->created_at->format('d F Y') }}</p>
                <p class="text-muted">{{ $community->members->count() }} Members</p>
            </div>
            <!-- Join Button -->
            @if(auth()->id() === $community->owner_id)
                <button class="btn btn-secondary rounded-pill" disabled>Owned</button>
            @else
                @if($community->members->contains(auth()->user()))
                    <form action="{{ route('community.leave', $community->id) }}" method="POST" id="leaveCommunityForm">
                        @csrf
                        <button 
                            type="submit" 
                            class="btn btn-secondary rounded-pill" 
                            id="joinedButton" 
                            onmouseover="toggleLeaveButtonText(this)" 
                            onmouseout="resetButtonText(this)">
                            Joined
                        </button>
                    </form>
                @else
                    <form action="{{ route('community.join', $community->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-primary rounded-pill">Join</button>
                    </form>
                @endif
            @endif

        </div>
    </div>

    <!-- Add Post, Search, and Sort -->
    <div class="border-top border-bottom py-3 mb-4 sticky-header">
        <div class="d-flex justify-content-between align-items-center gap-5">
            <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#createPostModal">+ Add Post</button>
            {{-- <form method="GET" action="{{ route('community.show', $community->id) }}" class="d-flex">
                <input type="text" name="search" class="form-control rounded-start" placeholder="Search posts...">
                <button class="btn btn-light rounded-end" type="submit"><i class="bi bi-search"></i></button>
            </form> --}}
            <form method="GET" action="{{ route('community.show', $community->id) }}" class="d-flex">
                <input 
                    type="text" 
                    name="search" 
                    class="form-control" 
                    placeholder="Search posts..." 
                    value="{{ request('search')  }}">
                <button class="btn btn-light" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
            
            {{-- <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown">Sort By</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="?sort=latest">Latest</a></li>
                    <li><a class="dropdown-item" href="?sort=oldest">Oldest</a></li>
                    <li><a class="dropdown-item" href="?sort=most_upvoted">Most Upvoted</a></li>
                </ul>
            </div> --}}
            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown">Sort By</button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item {{ request('sort') === 'latest' ? 'active' : '' }}"
                            href="{{ route('community.show', ['id' => $community->id, 'sort' => 'latest', 'search' => request('search')]) }}">
                            Latest
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('sort') === 'oldest' ? 'active' : '' }}"
                            href="{{ route('community.show', ['id' => $community->id, 'sort' => 'oldest', 'search' => request('search')]) }}">
                            Oldest
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('sort') === 'most_upvoted' ? 'active' : '' }}"
                            href="{{ route('community.show', ['id' => $community->id, 'sort' => 'most_upvoted', 'search' => request('search')]) }}">
                            Most Upvoted
                        </a>
                    </li>
                </ul>
            </div>
            
        </div>
    </div>

    <!-- Scrollable Posts Section -->
    <div class="posts-container">
        @forelse($communityPosts as $post)
            <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none text-dark">
                <div class="card mb-3 hover-gray">
                    <div class="card-body d-flex">
                        @if($post->image)
                            <img src="{{ $post->image }}" 
                                class="rounded me-3" 
                                style="width: 120px; height: 120px; object-fit: cover;">
                        @endif

                        <div class="flex-grow-1">
                            <h5 class="fw-bold">{{ $post->title }}</h5>
                            <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                            <p class="mb-2">{{ Illuminate\Support\Str::limit($post->content, 100) }}</p>
                            <div class="d-flex">
                                <span class="text-muted me-3 d-inline-flex align-items-center flex-shrink-0">
                                    <i class="bi bi-chat me-1"></i> Comments ({{ $post->comments->count() }})
                                </span>
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
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <p>No posts yet.</p>
        @endforelse
    </div>
    
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
.sticky-header {
    position: sticky;
    top: 0;
    background-color: white;
    z-index: 10;
}
.posts-container {
    flex-grow: 1;
    overflow-y: auto;
    min-height: 0;
}

.posts-container::-webkit-scrollbar {
    width: 8px;
}
.posts-container::-webkit-scrollbar-thumb {
    background-color: #ddd;
    border-radius: 4px;
}
.posts-container::-webkit-scrollbar-thumb:hover {
    background-color: #bbb;
}
</style>
@endpush

@push('scripts')
<script>
    function toggleLeaveButtonText(button) {
        button.innerText = "Leave";
        button.classList.remove('btn-secondary');
        button.classList.add('btn-danger');
    }

    function resetButtonText(button) {
        button.innerText = "Joined";
        button.classList.remove('btn-danger');
        button.classList.add('btn-secondary');
    }
</script>

@endpush
