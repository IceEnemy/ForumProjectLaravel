<div class="mb-3 ms-{{ $comment->parent_id ? 4 : 0 }}">
    <!-- Comment Header -->
    <div class="d-flex justify-content-between align-items-start">
        <div class="d-flex align-items-center">
            <img src="{{ $comment->poster->profile_picture ?? 'https://via.placeholder.com/50' }}" 
                class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
            <div>
                <strong>{{ $comment->poster->username }}</strong>
                <small class="text-muted d-block">{{ $comment->created_at->format('d/m/Y') }}</small>
            </div>
        </div>
    </div>

    <!-- Comment Body -->
    <p class="mt-2">{{ $comment->content }}</p>

    <!-- Reply and Toggle Replies -->
    <div>
        <a href="javascript:void(0);" class="text-decoration-none text-primary" onclick="toggleReplyForm({{ $comment->id }})">
            Reply
        </a>
        @if ($comment->replies->count())
            <a href="javascript:void(0);" class="ms-3 text-decoration-none text-secondary" 
               onclick="toggleReplies({{ $comment->id }})">
                {{ $comment->replies->count() }} replies
            </a>
        @endif
    </div>

    <!-- Reply Form -->
    <div id="reply-form-{{ $comment->id }}" class="d-none mt-2">
        <form method="POST" action="{{ route('post.comment', $comment->post_id) }}">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <div class="input-group">
                <input type="text" name="content" class="form-control border-0 border-bottom" placeholder="Write a reply...">
                <button type="submit" class="btn"><i class="bi bi-send"></i></button>
            </div>
        </form>
    </div>

    <!-- Recursive Replies -->
    <div id="replies-{{ $comment->id }}" class="d-none">
        @foreach ($comment->replies as $reply)
            @include('partials.comment', ['comment' => $reply])
        @endforeach
    </div>
</div>

@push('scripts')
<script>
function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    replyForm.classList.toggle('d-none');
}

function toggleReplies(commentId) {
    const replies = document.getElementById(`replies-${commentId}`);
    replies.classList.toggle('d-none');
}
</script>
@endpush
