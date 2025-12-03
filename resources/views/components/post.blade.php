<style>
    #group-link.deleted {
        text-decoration: none;
        pointer-events: none;
        cursor: default;
    }

    .post {
        background-color: #FAFAFA;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .post:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .post small {
        color: #666;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .post h2 {
        color: #333;
        font-size: 1.5rem;
        margin-top: 1rem;
        margin-bottom: 0.6rem;
    }
    
    .post p {
        color: #444;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .post p:last-child {
        margin-bottom: 0;
    }
    
    .post strong {
        color: #4a90e2;
    }
    
    .edit-indicator {
        color: #888;
        font-style: italic;
        margin-left: 0.5rem;
    }
    
    #vote-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0;
        padding-top: 0;
        border-top: none;
    }
    
    #vote-container form {
        margin: 0;
    }
    
    #vote-container button {
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    #vote-container button:hover {
        transform: scale(1.1);
    }
    
    #vote-container img {
        width: 16px;
        height: 16px;
        display: block;
        object-fit: contain;
    }
    
    #vote-container p {
        margin: 0;
        min-width: 1.5rem;
        text-align: center;
        font-weight: 500;
        color: #666;
        font-size: 0.9rem;
    }
    
    .post-bottom {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #e1e1e1;
    }
    
    .comment-count {
        margin: 0 !important;
        padding: 0;
        line-height: 1;
        color: #666;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .comment-count:hover {
        transform: scale(1.07);
    }
    
    .username-link {
        color: #4a90e2;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    
    .username-link:hover {
        color: #357abd;
    }
    
    .post-share-button {
        font-size: 0.9rem;
        background-color: white;
        color: #333;
        margin-top: 40px;
        margin: 0;
        padding: 0;
        border: none;
        font-weight: 500;
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .post-share-button:hover {
        transform:scale(1.1);
    }
    
    .post-share-button:active {
        transform: scale(1.0);
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
    <h2>
        {{ $post->title }}
        @if($post->isPinned)
            <img src="{{ asset('/icons/pin.png') }}" alt="Pinned" title="Pinned" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
        @endif
        @if($post->isPinnedHome)
            <img src="{{ asset('/icons/pin-home.png') }}" alt="Pinned Home" title="Pinned Home" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
        @endif
    </h2>
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
    // DELETED GROUP CHECK
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