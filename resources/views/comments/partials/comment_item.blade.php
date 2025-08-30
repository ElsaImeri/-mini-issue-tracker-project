{{-- resources/views/comments/partials/comment_item.blade.php --}}
<div class="comment-item" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <strong>{{ $comment->author_name }}</strong>
        <span class="comment-date">{{ $comment->created_at->format('M j, Y \a\t g:i a') }}</span>
    </div>
    <div class="comment-body">
        {{ $comment->body }}
    </div>
</div>