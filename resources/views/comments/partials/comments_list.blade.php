<div class="comments-list">
    @forelse($comments as $comment)
        <div class="comment-item" id="comment-{{ $comment->id }}">
            <div class="comment-header">
                <strong>{{ $comment->author_name }}</strong>
                <span class="comment-date">{{ $comment->created_at->format('M j, Y \a\t g:i a') }}</span>
            </div>
            <div class="comment-body">
                {{ $comment->body }}
            </div>
        </div>
    @empty
        <div class="no-comments">
            <p>No comments yet. Be the first to comment!</p>
        </div>
    @endforelse
    
    @if($comments->hasMorePages())
        <div class="load-more-comments-container">
            <button class="btn btn-load-more-comments" 
                    data-next-page="{{ $comments->currentPage() + 1 }}"
                    data-issue-id="{{ $issue->id ?? '' }}">
                Load More Comments
            </button>
        </div>
    @endif
</div>