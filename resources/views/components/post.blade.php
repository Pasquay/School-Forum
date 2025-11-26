<style>
    #group-link.deleted {
        text-decoration: none;
        pointer-events: none;
        cursor: default;
    }

    .post {
        display: flex;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e8e8e8;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        cursor: pointer;
        transition: all 0.2s ease;
        overflow: hidden;
        margin-bottom: 0;
    }

    .post.deleted {
        opacity: 0.85;
    }

    .post:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .vote-inline {
        width: 56px;
        min-width: 56px;
        background: #fafafa;
        border-right: 1px solid #e8e8e8;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding: 1.5rem 0;
        gap: 0.5rem;
        cursor: default;
    }

    .vote-inline form {
        margin: 0;
        display: flex;
        pointer-events: auto;
    }

    .vote-inline button {
        width: 32px;
        height: 32px;
        background: transparent;
        border: none;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        opacity: 0.5;
        filter: grayscale(0);
        pointer-events: auto;
    }

    .vote-inline button:hover {
        opacity: 0.8;
    }

    .vote-inline button:disabled {
        opacity: 0.2;
        cursor: not-allowed;
    }

    .vote-inline button img {
        display: none;
    }

    .vote-inline button::before {
        content: '';
        width: 0;
        height: 0;
        display: block;
    }

    /* Upvote arrow (outlined) */
    .vote-inline form:first-child button::before {
        width: 0;
        height: 0;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        border-bottom: 10px solid #878a8c;
        position: relative;
    }

    .vote-inline form:first-child button::after {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 7px solid #fafafa;
        top: 2px;
        left: 50%;
        transform: translateX(-50%);
    }

    .vote-inline form:first-child button {
        position: relative;
    }

    .vote-inline form:first-child button:hover::before {
        border-bottom-color: #1a1a1b;
    }

    .vote-inline form:first-child button:hover::after {
        border-bottom-color: #fafafa;
    }

    .vote-inline form:first-child button[data-voted="true"]::before {
        border-bottom-color: #ffc107;
    }

    .vote-inline form:first-child button[data-voted="true"]::after {
        border-bottom-color: #fafafa;
    }

    .vote-inline form:first-child button[data-voted="true"] {
        opacity: 1;
    }

    /* Downvote arrow (outlined) */
    .vote-inline form:last-child button::before {
        width: 0;
        height: 0;
        border-left: 7px solid transparent;
        border-right: 7px solid transparent;
        border-top: 10px solid #878a8c;
        position: relative;
    }

    .vote-inline form:last-child button::after {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 7px solid #fafafa;
        bottom: 2px;
        left: 50%;
        transform: translateX(-50%);
    }

    .vote-inline form:last-child button {
        position: relative;
    }

    .vote-inline form:last-child button:hover::before {
        border-top-color: #1a1a1b;
    }

    .vote-inline form:last-child button:hover::after {
        border-top-color: #fafafa;
    }

    .vote-inline form:last-child button[data-voted="true"]::before {
        border-top-color: #7193ff;
    }

    .vote-inline form:last-child button[data-voted="true"]::after {
        border-top-color: #fafafa;
    }

    .vote-inline form:last-child button[data-voted="true"] {
        opacity: 1;
    }

    .vote-inline .vote-count {
        margin: 0;
        font-weight: 700;
        color: #1a1a1a;
        font-size: 1rem;
        text-align: center;
        line-height: 1;
    }

    .post-body {
        flex: 1;
        padding: 1.5rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .post-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0.75rem;
    }

    .post-meta-lines {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        flex: 1;
    }

    .profile-pic-home,
    .profile-pic-home-default {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-right: 0.75rem;
    }

    .profile-pic-home {
        object-fit: cover;
        border: 2px solid #e8e8e8;
    }

    .profile-pic-home-default {
        background: #2d4a2b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
    }

    .post-meta-primary {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        flex-wrap: wrap;
        font-size: 0.875rem;
        color: #666;
    }

    .post-author {
        color: #000;
        font-weight: 600;
        text-decoration: none;
    }

    .post-author:hover {
        text-decoration: underline;
    }

    .post-time {
        color: #999;
    }

    .meta-dot {
        margin: 0 0.15rem;
    }

    .post-group-label {
        color: #999;
    }

    .post-group-pill {
        color: #333;
        font-weight: 600;
        text-decoration: none;
    }

    .post-group-pill:hover {
        text-decoration: underline;
    }

    .post-group-pill.deleted {
        color: #999;
        pointer-events: none;
    }

    .edit-indicator {
        color: #a0a7b2;
        font-style: italic;
        font-size: 0.8rem;
    }

    .post-badges {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .post-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        background: #2d4a2b;
        color: #ffffff;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .post-title {
        margin: 0;
        color: #000;
        font-size: 1.125rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .post-excerpt {
        margin: 0;
        color: #555;
        font-size: 0.9rem;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    .post-bottom {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-top: 0.25rem;
        color: #666;
        flex-wrap: wrap;
        font-size: 0.875rem;
    }

    .post-footer-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #666;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .post-footer-item:hover {
        color: #333;
    }

    .post-footer-item img {
        width: 18px;
        height: 18px;
        display: block;
        opacity: 0.7;
    }

    .post-share-button {
        background: none;
        border: none;
        padding: 0;
        color: #666;
        font: inherit;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .post-share-button svg {
        width: 18px;
        height: 18px;
        opacity: 0.7;
    }

    .post-share-button:hover {
        color: #333;
    }

    .post-restore-button {
        font-size: 0.82rem;
        background: #2d4a2b;
        color: #ffffff;
        padding: 0.45rem 0.95rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .post-restore-button:hover {
        background: #133c06;
    }

    @media (max-width: 768px) {
        .post {
            flex-direction: column;
        }

        .vote-inline {
            width: 100%;
            min-width: unset;
            flex-direction: row;
            border-right: none;
            border-bottom: 1px solid #e8e8e8;
            padding: 0.75rem;
            gap: 1rem;
            justify-content: center;
        }

        .post-body {
            padding: 1.25rem;
        }

        .post-title {
            font-size: 1rem;
        }

        .post-excerpt {
            font-size: 0.875rem;
        }
    }
</style>
<div class="post {{ $post->deleted_at ? 'deleted' : '' }}" id='{{ $post->id }}'>
    <div class="vote-inline">
        <form action="/home/upvote/{{ $post->id }}" method="POST">
            @csrf
            <button type="submit" {{ $post->deleted_at ? 'disabled' : '' }} data-voted="{{ $post->userVote == 1 ? 'true' : 'false' }}">
                <img src="{{ asset('/icons/up-arrow' . ($post->userVote == 1 ? '-alt' : '') . '.png') }}" alt="upvote">
            </button>
        </form>
        <span class="vote-count">{{ $post->votes }}</span>
        <form action="/home/downvote/{{ $post->id }}" method='POST'>
            @csrf
            <button type="submit" {{ $post->deleted_at ? 'disabled' : '' }} data-voted="{{ $post->userVote == -1 ? 'true' : 'false' }}">
                <img src="{{ asset('/icons/down-arrow' . ($post->userVote == -1 ? '-alt' : '') . '.png') }}" alt="downvote">
            </button>
        </form>
    </div>
    <div class="post-body">
        <div class="post-header">
            @if($post->user->photo)
            <img src="{{ asset('storage/' . $post->user->photo) }}" alt="{{ $post->user->name }}" class="profile-pic-home">
            @else
            <div class="profile-pic-home-default">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
            @endif
            <div class="post-meta-lines">
                <div class="post-meta-primary">
                    <a href="/user/{{ $post->user_id }}" class="post-author">{{ $post->user->name }}</a>
                    <span class="meta-dot">·</span>
                    <span class="post-time">{{ $post->created_at->diffForHumans() }}</span>
                    <span class="meta-dot">·</span>
                    <span class="post-group-label">in</span>
                    <a href="/group/{{ $post->group->id }}" id='group-link-{{ $post->group->id }}' class="post-group-pill">{{ $post->group->name }}</a>
                    @if($post->updated_at != $post->created_at && !$post->deleted_at)
                    <span class="meta-dot">·</span>
                    <span class='edit-indicator'>Edited</span>
                    @elseif($post->deleted_at)
                    <span class="meta-dot">·</span>
                    <span class='edit-indicator'>Deleted</span>
                    @endif
                </div>
            </div>
            @if($post->isPinned || $post->isPinnedHome)
            <div class="post-badges">
                @if($post->isPinnedHome)
                <span class="post-badge">PINNED</span>
                @endif
                @if($post->isPinned)
                <span class="post-badge">PINNED</span>
                @endif
            </div>
            @endif
        </div>
        <div class="post-body-content">
            <h2 class="post-title">{{ $post->title }}</h2>
            <p class="post-excerpt">{{ $post->content }}</p>
        </div>
        <div class="post-bottom">
            <span class="post-footer-item comment-count">
                <img src="{{ asset('/icons/chat.png') }}" alt="comments icon">
                {{ $post->comments_count ?? 0 }} Comments
            </span>
            @if($post->deleted_at === NULL)
            <button type="button" class='post-share-button' id='post-share-button-{{ $post->id }}'>
                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.5 9.167L15 4.167V15.833L7.5 10.833V9.167Z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M5.833 15.833C6.75348 15.833 7.5 15.0865 7.5 14.1667C7.5 13.2462 6.75348 12.5 5.833 12.5C4.91252 12.5 4.166 13.2462 4.166 14.1667C4.166 15.0865 4.91252 15.833 5.833 15.833Z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M5.833 7.5C6.75348 7.5 7.5 6.75348 7.5 5.833C7.5 4.91252 6.75348 4.166 5.833 4.166C4.91252 4.166 4.166 4.91252 4.166 5.833C4.166 6.75348 4.91252 7.5 5.833 7.5Z" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Share
            </button>
            @else
            <form action='/restore-post/{{ $post->id }}' method="POST" style='display: inline;'>
                @csrf
                <button type="submit" class='post-restore-button' id='post-restore-button-{{ $post->id }}'>Restore</button>
            </form>
            @endif
        </div>
    </div>
</div>
<script>
    // DELETED GROUP CHECK
    (function() {
        const groupExists = ('{{ $post->group->deleted_at }}') ? '0' : '1';
        const groupLink = document.querySelector('#group-link-{{ $post->group->id }}');
        if (!groupExists && groupLink) {
            groupLink.textContent = 'Deleted Group';
            groupLink.href = '#';
            groupLink.classList.add('deleted');
        }
    })();
</script>