<style>
    #group-link.deleted {
        text-decoration: none;
        pointer-events: none;
        cursor: default;
    }
</style>
<div class="post {{ $post->deleted_at ? 'deleted' : '' }}" id='{{ $post->id }}'>
    <small>
        <a href="/group/{{ $post->group->id }}" id='group-link-{{ $post->group->id }}' class="username-link">{{ '#' . $post->group->name }}</a> | <a href="/user/{{ $post->user_id }}" class="username-link">{{ '@' . $post->user->name }}</a> | {{ $post->created_at->format('F j, Y \a\t g:i a') }}.
        @if($post->updated_at != $post->created_at && !$post->deleted_at)
            <span class='edit-indicator'>Edited on {{ $post->updated_at->format('F j, Y \a\t g:i a') }}</span>
        @elseif($post->deleted_at)
            <span class='edit-indicator'>Deleted on {{ $post->updated_at->format('F j, Y \a\t g:i a') }}</span>
        @endif
    </small>
    <h2>{{ $post->title }}</h2>
    <p style='white-space: pre-wrap;'>{{ $post->content }}</p>
    <div class="post-bottom">
        <div class='vote-container' id="vote-container">
            <form action="/home/upvote/{{ $post->id }}" method="POST">
                @csrf
                <button type="submit" {{ $post->deleted_at ? 'disabled' : '' }}>
                    <img src="{{ asset('/icons/up-arrow' . ($post->userVote == 1 ? '-alt' : '') . '.png') }}" alt="upvote">
                </button>
            </form>
            <p>{{ $post->votes }}</p>
            <form action="/home/downvote/{{ $post->id }}" method='POST'>
                @csrf
                <button type="submit" {{ $post->deleted_at ? 'disabled' : '' }}>
                    <img src="{{ asset('/icons/down-arrow' . ($post->userVote == -1 ? '-alt' : '') . '.png') }}" alt="downvote">
                </button>
            </form>
        </div>
        @if($post->comments_count > 0)
            <p class="comment-count">{{ $post->comments_count }} Comments</p>
        @endif
        @if($post->deleted_at === NULL)
            <button type="button" class='post-share-button' id='post-share-button-{{ $post->id }}'>Share</button>
        @else
            <form action='/restore-post/{{ $post->id }}' METHOD="POST" style='display: inline;'>
                @csrf
                <button type="submit" class='post-restore-button' id='post-restore-button-{{ $post->id }}'>Restore</button>
            </form>
        @endif
    </div>
</div>
<script>
    (function() {
        const groupExists = ('{{ $post->group->deleted_at }}') ? '0' : '1';
        const groupLink = document.querySelector('#group-link-{{ $post->group->id }}');
        if(!groupExists && groupLink){
            groupLink.textContent = '#DELETED GROUP';
            groupLink.href = '#';
            groupLink.classList.add('deleted');
        }
    })();
</script>