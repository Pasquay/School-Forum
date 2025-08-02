<div class="profile-comment {{ $comment->deleted_at ? 'deleted' : '' }}" id='profile-comment-{{ $comment->id }}'>
    <span class='original-post-details'>
        <a href="/user/{{ $comment->post->user_id }}" class='username-link'>{{ '@' . $comment->post->user->name}}</a> | 
        @if($comment->post->deleted_at === NULL)
            <a href='/post/{{ $comment->post->id }}' class='original-post-link'>{{ $comment->post->title}}</a>
        @else
            <a href='#' class='original-post-link deleted'><span><em>DELETED POST</em></span></a>
        @endif
    </span>
    <small>
        <a href="/user/{{ $comment->user_id }}" class='username-link'>{{ '@' . $comment->user->name }}</a> commented on {{ $comment->created_at->format('F j, Y \a\t g:i a') }}
        @if($comment->updated_at != $comment->created_at && !$comment->deleted_at)
            <span class='edit-indicator'>Edited on {{ $comment->updated_at->format('F j, Y \a\t g:i a') }}</span>
        @elseif($comment->deleted_at)
            <span class='edit-indicator'>Deleted on {{ $comment->updated_at->format('F j, Y \a\t g:i a') }}</span>
        @endif
        <p style='white-space: pre-wrap;'>{{ $comment->content }}</p>
        <div class="profile-comment-bottom">
            <div class='vote-container' id="vote-container">
                <form action="/comment/upvote/{{ $comment->id }}" method="POST">
                    @csrf
                    <button type="submit" {{ $comment->deleted_at ? 'disabled' : '' }}>
                        <img src="{{ asset('storage/icons/up-arrow' . ($comment->userVote == 1 ? '-alt' : '') . '.png') }}" alt="Upvote">
                    </button>
                </form>
                <p>{{ $comment->votes }}</p>
                <form action="/comment/downvote/{{ $comment->id }}" method="POST">
                    @csrf
                    <button type="submit" {{ $comment->deleted_at ? 'disabled' : '' }}>
                        <img src="{{ asset('storage/icons/down-arrow' . ($comment->userVote == -1 ? '-alt' : '') . '.png') }}" alt="Downvote">
                    </button>
                </form>
            </div>
            <div class='reply-count-container'>
                <img src="{{ asset('storage/icons/chat.png') }}" alt="" class='reply-image'>
                @if($comment->replyCount === 0)
                    <p class='reply-count'>Replies</p>
                @else
                    <p class='reply-count'>Replies ({{ $comment->replyCount }})</p>
                @endif
            </div>
            @if($comment->deleted_at === NULL)
                <button type="button" class='profile-comment-share-button' id='profile-comment-share-button-{{ $comment->id }}'>
                    Share
                </button>
            @elseif($comment->post->deleted_at === NULL)
                <form action='/restore-comment/{{ $comment->id }}' METHOD="POST" style='display: inline;'>
                    @csrf
                    <button type="submit" class='comment-restore-button' id='comment-restore-button-{{ $comment->id }}'>Restore</button>
                </form>
            @else
                <button type="button" class='disabled-comment-restore-button' 
                        id='disabled-comment-restore-button-{{ $comment->id }}'
                        title='Restore unavailable due to original post being deleted'>
                    Restore Unavailable
                </button>
            @endif
        </div>
    </small>
</div>