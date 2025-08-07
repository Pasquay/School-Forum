<div class="profile-reply {{ $reply->deleted_at ? 'deleted' : '' }}" id='profile-reply-{{ $reply->id }}'>
    <span class="original-post-details">
        @if(!$reply->comment->post->deleted_at)
            <a href="/user/{{ $reply->comment->post->user_id }}" class="username-link">{{ '@' . $reply->comment->post->user->name }}</a> | 
            <a href="/post/{{ $reply->comment->post->id }}" class='original-post-link'>{{ $reply->comment->post->title }}</a>
        @else
            <a href="/user/{{ $reply->comment->post->user_id }}" class="username-link">{{ '@' . $reply->comment->post->user->name }}</a> | <span><em>DELETED POST</em></span>
        @endif
    </span>
    <small>
        <a href="/user/{{ $reply->user_id }}" class="username-link">{{ '@' . $reply->user->name }}</a> replied to <a href="/user/{{ $reply->comment->user_id }}" class="username-link">{{ '@' . $reply->comment->user->name }}</a> on {{ $reply->created_at->format('F j, Y \a\t g:i a') }}
        @if($reply->created_at != $reply->updated_at && !$reply->deleted_at)
            <span class='edit-indicator'>Edited on {{ $reply->updated_at->format('F j, Y \a\t g:i a') }}</span>
        @elseif($reply->deleted_at)
            <span class="edit-indicator">Deleted on {{ $reply->deleted_at->format('F j, Y \a\t g:i a') }}</span>
        @endif
        @if(!$reply->comment->deleted_at)
            <p class='original-comment-content-p'><a href='/post/{{ $reply->comment->post->id }}#comment-{{ $reply->comment->id }}' class='original-comment-content' style='white-space:pre-wrap;'>{{ $reply->comment->content }}</a></p>
        @else
            <p><a href='#' class='original-comment-content deleted' style='white-space:pre-wrap;'><em>DELETED COMMENT</em></a></p>
        @endif
        <p class='reply-content' style='white-space:pre-wrap;'>{{ $reply->content }}</p>
        <div class="profile-reply-bottom">
            <div class="reply-vote-container" id='reply-vote-container-{{ $reply->id }}'>
                <form action="/reply/upvote/{{ $reply->id }}" method='POST'>
                    @csrf
                    <button type="submit" {{ $reply->deleted_at ? 'disabled' : '' }}>
                        <img src="{{ asset('storage/icons/up-arrow' . ($reply->userVote == 1 ? '-alt' : '') . '.png') }}" alt="Upvote">
                    </button> 
                </form>
                <p>{{ $reply->votes }}</p>
                <form action="/reply/downvote/{{ $reply->id }}" method='POST'>
                    @csrf
                    <button type="submit" {{ $reply->deleted_at ? 'disabled' : ''}}>
                        <img src="{{ asset('storage/icons/down-arrow' . ($reply->userVote == -1 ? '-alt' : '') . '.png') }}" alt="Downvote">
                    </button>
                </form>
            </div>
            @if(!$reply->deleted_at)
                <button type="button" class='profile-reply-share-button' id='profile-reply-share-button-{{ $reply->id }}'>
                    Share
                </button>
            @elseif(!$reply->comment->post->deleted_at && !$reply->comment->deleted_at)
                <form action='/restore-reply/{{ $reply->id }}' METHOD="POST" style='display: inline;'>
                    @csrf
                    <button type="submit" class='reply-restore-button' id='reply-restore-button-{{ $reply->id }}'>Restore</button>
                </form>
            @else
                <button type="button" class='disabled-reply-restore-button' 
                        id='disabled-reply-restore-button-{{ $reply->id }}'
                        title='Restore unavailable due to original post and/or comment being deleted'>
                    Restore Unavailable
                </button>
            @endif
        </div>
    </small>
</div>