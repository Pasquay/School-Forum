<div class="profile-comment" id='profile-comment-{{ $comment->id }}'>
    <span class='original-post-details'>
        <p><a href="/user/{{ $comment->post->user_id }}" class='username-link'>{{ '@' . $comment->post->user->name}}</a> | 
        <a href="/post/{{ $comment->post->id }}" class='original-post-link'>{{ $comment->post->title}}</a></p>
    </span>
    <small>
        <a href="/user/{{ $comment->user_id }}" class='username-link'>{{ '@' . $comment->user->name }}</a> commented on {{ $comment->created_at->format('F j, Y \a\t g:i a') }}
        <p>{{ $comment->content }}</p>
        <div class="profile-comment-bottom">
            <div class='vote-container' id="vote-container">
                <form action="/comment/upvote/{{ $comment->id }}" method="POST">
                    @csrf
                    <button type="submit">
                        <img src="{{ asset('storage/icons/up-arrow' . ($comment->userVote == 1 ? '-alt' : '') . '.png') }}" alt="Upvote">
                    </button>
                </form>
                <p>{{ $comment->votes }}</p>
                <form action="/comment/downvote/{{ $comment->id }}" method="POST">
                    @csrf
                    <button type="submit">
                        <img src="{{ asset('storage/icons/down-arrow' . ($comment->userVote == -1 ? '-alt' : '') . '.png') }}" alt="Downvote">
                    </button>
                </form>
            </div>
            <button type="button" class='profile-comment-share-button' id='profile-comment-share-button-{{ $comment->id }}'>Share</button>
        </div>
    </small>
</div>