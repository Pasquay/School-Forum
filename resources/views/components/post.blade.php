<div class="post" id='{{ $post->id }}'>
    <small>
        <a href="/user/{{ $post->user_id }}" class="username-link">{{ '@' . $post->user->name }}</a>  |  {{ $post->created_at->format('F j, Y \a\t g:i a') }}.
        @if($post->updated_at != $post->created_at)
            <span class='edit-indicator'>Edited on {{ $post->updated_at->format('F j, Y \a\t g:i a') }}</span>
        @endif
    </small>
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->content }}</p>
    <div class="post-bottom">
        <div class='vote-container' id="vote-container">
            <form action="/home/upvote/{{ $post->id }}" method="POST">
                @csrf
                <button type="submit"><img src="{{ asset('storage/icons/up-arrow' . ($post->userVote == 1 ? '-alt' : '') . '.png') }}" alt="upvote"></button>
            </form>
            <p>{{ $post->votes }}</p>
            <form action="/home/downvote/{{ $post->id }}" method='POST'>
                @csrf
                <button type="submit"><img src="{{ asset('storage/icons/down-arrow' . ($post->userVote == -1 ? '-alt' : '') . '.png') }}" alt="downvote"></button>
            </form>
        </div>
        @if($post->comments_count > 0)
            <p class="comment-count">{{ $post->comments_count }} Comments</p>
        @endif
        <button type="button" class='post-share-button' id='post-share-button-{{ $post->id }}'>Share</button>
    </div>
</div>