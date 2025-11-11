<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Post</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
</head>

<body>
    @include('components.navbar', ['active' => ''])
    @if(session()->has('success'))
    <div class="success-message">
        <div style="background-color: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if (session()->has('error'))
    <div class="error-message">
        <div style="background-color: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;">
            {{ session('error') }}
        </div>
    </div>
    @endif
    <div class="post-column">
        <a href="/home" class="back-button">← Return</a>
        <div class="post" id='post-{{ $post->id }}'>
            <div class="post-top">
                <small>
                    <a href="/group/{{ $post->group->id }}" class="username-link">{{ '#' . $post->group->name }}</a> | <a href="/user/{{ $post->user_id }}" class="username-link">{{ '@' . $post->user->name }}</a> | {{ $post->created_at->format('F j, Y \a\t g:i a') }}.
                    @if($post->updated_at != $post->created_at)
                    <span class='edit-indicator'>Edited on {{ $post->updated_at->format('F j, Y \a\t g:i a') }}.</span>
                    @endif
                </small>
                @auth
                @if (Auth::id() === $post->user_id)
                <div class="settings-container">
                    <button class="settings-button" id='settings-button'>
                        <img src="{{ asset('/icons/dots.png') }}" alt='Settings' id='dots-icon'>
                    </button>
                    <div class="dropdown-menu" id='settings-dropdown-menu'>
                        @if($post->group->id != 1 && $homeAdmin->pluck('id')->contains(Auth::id()))
                        @if($post->isPinnedHome)
                        <button class="dropdown-item" id="pin-post-home-toggle-button">Unpin Home</button>
                        @else
                        <button class="dropdown-item" id="pin-post-home-toggle-button">Pin Home</button>
                        @endif
                        @endif
                        @if(
                        $post->group->members->where('id', Auth::id())->first() &&
                        in_array($post->group->members->where('id', Auth::id())->first()->pivot->role, ['owner', 'moderator'])
                        )
                        @if($post->isPinnedHome)
                        <button class="dropdown-item" id='pin-post-toggle-button'>Unpin</button>
                        @else
                        <button class="dropdown-item" id="pin-post-toggle-button">Pin</button>
                        @endif
                        @endif
                        <button class="dropdown-item" id='edit-post-button'>Edit</button>
                        <button class="dropdown-item" id='delete-post-button'>Delete</button>
                    </div>
                </div>
                @elseif((
                $post->group->members->where('id', Auth::id())->first() &&
                in_array($post->group->members->where('id', Auth::id())->first()->pivot->role, ['owner', 'moderator'])
                ) ||
                $homeAdmin->pluck('id')->contains(Auth::id()))
                <div class="settings-container">
                    <button class="settings-button" id="settings-button">
                        <img src="{{ asset('/icons/dots.png') }}" alt="Settings" id='dots-icon'>
                    </button>
                    <div class="dropdown-menu" id="settings-dropdown-menu">
                        @if($post->group->id != 1 && $homeAdmin->pluck('id')->contains(Auth::id()))
                        @if($post->isPinnedHome)
                        <button class="dropdown-item" id="pin-post-home-toggle-button">Unpin Home</button>
                        @else
                        <button class="dropdown-item" id="pin-post-home-toggle-button">Pin Home</button>
                        @endif
                        @endif
                        @if(
                        $post->group->members->where('id', Auth::id())->first() &&
                        in_array($post->group->members->where('id', Auth::id())->first()->pivot->role, ['owner', 'moderator'])
                        )
                        @if($post->isPinned)
                        <button class="dropdown-item" id='pin-post-toggle-button'>Unpin</button>
                        @else
                        <button class="dropdown-item" id="pin-post-toggle-button">Pin</button>
                        @endif
                        @endif
                        </button>
                        <button class="dropdown-item" id="delete-post-button">Delete</button>
                    </div>
                </div>
                @endif
                @endauth
            </div>
            <div id='post-content-container' class="post-content" style='display:block;'>
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
                    <div id="vote-container">
                        <form action="/post/upvote/{{ $post->id }}" method="POST">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('/icons/up-arrow' . ($post->userVote == 1 ? '-alt' : '') . '.png') }}" alt="upvote">
                            </button>
                        </form>
                        <p>{{ $post->votes }}</p>
                        <form action="/post/downvote/{{ $post->id }}" method="POST">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('/icons/down-arrow' . ($post->userVote == -1 ? '-alt' : '') . '.png') }}" alt="downvote">
                            </button>
                        </form>
                    </div>
                    @if($post->comments_count > 0)
                    <p class="commentCount">{{ $post->comments_count }} Comments</p>
                    @endif
                    <button type="button" class='share-button' id='post-share-button-{{ $post->id }}'>Share</button>
                </div>
            </div>
            <div id="pin-home-post-form" class="pin-home-post-form" style="display:none;">
                <h2>
                    {{ $post->title }}
                    @if($post->isPinned)
                    <img src="{{ asset('/icons/pin.png') }}" alt="Pinned" title="Pinned" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
                    @endif
                    @if($post->isPinnedHome)
                    <img src="{{ asset('/icons/pin-home.png') }}" alt="Pinned Home" title="Pinned Home" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
                    @endif
                </h2>
                <p style='white-space:pre-wrap;'>{{ $post->content }}</p>
                <form action="/pin-post-home/{{ $post->id }}" , method="POST">
                    @csrf
                    <div class="pin-home-form-buttons">
                        <button class="pin-post-home-cancel" id="pin-home-cancel-button">Cancel</button>
                        <button type="submit" class="pin-home-toggle-confirm-button">Pin/Unpin Home</button>
                    </div>
                </form>
            </div>
            <div id="pin-post-form" class="pin-post-form" style="display:none;">
                <h2>
                    {{ $post->title }}
                    @if($post->isPinned)
                    <img src="{{ asset('/icons/pin.png') }}" alt="Pinned" title="Pinned" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
                    @endif
                    @if($post->isPinnedHome)
                    <img src="{{ asset('/icons/pin-home.png') }}" alt="Pinned Home" title="Pinned Home" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
                    @endif
                </h2>
                <p style='white-space:pre-wrap;'>{{ $post->content }}</p>
                <form action="/pin-post/{{ $post->id }}" method='POST'>
                    @csrf
                    <div class="pin-form-buttons">
                        <button class="pin-post-cancel" id="pin-cancel-button">Cancel</button>
                        <button type="submit" class="pin-toggle-confirm-button">Pin/Unpin Post</button>
                    </div>
                </form>
            </div>
            <div id="edit-post-form" class="edit-post-form" style='display:none;'>
                <form action="/edit-post/{{ $post->id }}" method='POST'>
                    @csrf
                    <input
                        type="text"
                        name="edit-post-title"
                        id="edit-post-title"
                        value="{{ $post->title }}"
                        placeholder="Post title..."
                        required>
                    <textarea
                        name="edit-post-content"
                        id="edit-post-content"
                        placeholder="Post content..."
                        required>{{ $post->content }}</textarea>
                    <div class="edit-form-buttons">
                        <button type="button" id="edit-post-cancel" class="edit-cancel-btn">Cancel</button>
                        <button type="submit" class="edit-confirm-btn">Save Changes</button>
                    </div>
                </form>
            </div>
            <div id="delete-post-form" style='display:none;'>
                <h2>
                    {{ $post->title }}
                    @if($post->isPinned)
                    <img src="{{ asset('/icons/pin.png') }}" alt="Pinned" title="Pinned" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
                    @endif
                    @if($post->isPinnedHome)
                    <img src="{{ asset('/icons/pin-home.png') }}" alt="Pinned Home" title="Pinned Home" style="width: 20px; height: 20px; vertical-align: middle; margin-left: 2px; margin-bottom: 4px">
                    @endif
                </h2>
                <p style='white-space:pre-wrap'>{{ $post->content }}</p>
                <form action="/delete-post/{{ $post->id }}" method='POST'>
                    @csrf
                    <div class="delete-form-buttons">
                        <button id='delete-post-cancel' class="delete-cancel-btn">Cancel</button>
                        <button type="submit" class="delete-confirm-btn">Delete Post</button>
                    </div>
                </form>
            </div>
        </div>

        <div id='comment-column' class='comment-column'>
            <div class="create-comment-form" id="create-comment-form">
                <form action="/post/{{ $post->id }}/create-comment" method="POST">
                    @csrf
                    <textarea name="create-comment-content" id="create-comment-content" placeholder="Share a comment..." required></textarea>
                </form>
            </div>
            @foreach($comments as $comment)
            <div class="comment" id='comment-{{ $comment->id }}'>
                <div class='comment-top'>
                    <small class='comment-metadata'>
                        <a href="/user/{{ $comment->user_id }}" class="username-link">{{ '@' . $comment->user->name }}</a> | {{ $comment->created_at->format('F j, Y \a\t g:i a') }}.
                        @if($comment->updated_at != $comment->created_at)
                        <span class='edit-indicator'> Edited on {{ $comment->updated_at->format('F j, Y \a\t g:i a') }}</span>
                        @endif
                    </small>
                    @auth
                    @if(Auth::id() == $comment->user_id)
                    <div class="comment-settings-container">
                        <button class="settings-button" id='settings-button-{{ $comment->id }}'>
                            <img src="{{ asset('/icons/dots.png') }}" alt="Settings" id='dots-icon-{{ $comment->id }}'>
                        </button>
                        <div class="dropdown-menu" id='settings-dropdown-menu-{{ $comment->id }}'>
                            <button class="dropdown-item" id='edit-comment-button-{{ $comment->id }}'>Edit</button>
                            <button class="dropdown-item" id='delete-comment-button-{{ $comment->id }}'>Delete</button>
                        </div>
                    </div>
                    @endif
                    @endauth
                </div>
                <div class="comment-content">
                    <p style='white-space: pre-wrap;'>{{ $comment->content }}</p>
                </div>
                <div class="comment-bottom">
                    <div class='comment-vote-container' id="vote-container-{{ $comment->id }}">
                        <form action="/comment/upvote/{{ $comment->id }}" method="POST">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('/icons/up-arrow' . ($comment->userVote == 1 ? '-alt' : '') . '.png') }}" alt="Upvote">
                            </button>
                        </form>
                        <p>{{ $comment->votes }}</p>
                        <form action="/comment/downvote/{{ $comment->id }}" method="POST">
                            @csrf
                            <button type="submit">
                                <img src="{{ asset('/icons/down-arrow' . ($comment->userVote == -1 ? '-alt' : '') . '.png') }}" alt="Downvote">
                            </button>
                        </form>
                    </div>
                    <form id='replies-form-{{ $comment->id }}' action="/comment/{{ $comment->id }}/replies" method='GET'>
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('/icons/chat.png') }}" alt="">
                            @if($comment->replies_count > 0)
                            Replies ({{$comment->replies_count}})
                            @else
                            Replies
                            @endif
                        </button>
                    </form>
                    <button type="button" class='share-button' id='comment-share-button-{{ $comment->id }}'>Share</button>
                </div>
                <div class="comment-edit-form" style='display:none;'>
                    <form action="{{ $post->id }}/edit-comment/{{ $comment->id }}" method="POST">
                        @csrf
                        <textarea name="edit-comment-content-{{ $comment->id }}"
                            id="edit-comment-content-{{ $comment->id }}"
                            placeholder="Comment content..."
                            required>{{ $comment->content }}</textarea>
                        <div class="edit-form-buttons">
                            <button type='button' class="edit-cancel-button">Cancel</button>
                            <button type="submit" class="edit-confirm-button">Save Changes</button>
                        </div>
                    </form>
                </div>
                <div class="comment-delete-form" style='display:none;'>
                    <p style='white-space:pre-wrap'>{{ $comment->content }}</p><br>
                    <div class="delete-form-buttons">
                        <form action="{{ $post->id }}/delete-comment/{{ $comment->id }}" method='POST' class='delete-buttons-container'>
                            @csrf
                            <button type='button' class="delete-cancel-button">Cancel</button>
                            <button type="submit" class="delete-confirm-button">Delete Comment</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="create-reply-form" id="create-reply-form-{{ $comment->id }}" style='display:none;'>
                <form action="/post/{{ $post->id }}/comment/{{ $comment->id }}/create-reply" method='POST'>
                    @csrf
                    <textarea
                        name="create-reply-content-{{ $comment->id }}"
                        id="create-reply-content-{{ $comment->id }}"
                        placeholder="Write a reply..."
                        required></textarea>
                </form>
            </div>
            <div class="reply-container" id='reply-container-{{ $comment->id }}' style='display:none;'></div>
            @endforeach
        </div>
    </div>

    <div class="templates" style='display:none;'>
        <template id='reply-template'>
            <div class="reply">
                <div class="reply-top">
                    <small class="reply-metadata">
                        <a href="#" class="username-link">@</a>
                        <p></p>
                        <span class="edit-indicator" style='display:none;'></span>
                    </small>
                    <div class="reply-settings"></div>
                </div>
                <div class="reply-content">
                    <p style='white-space:pre-wrap;'>Content</p>
                </div>
                <div class='reply-bottom'>
                    <div class="reply-vote-container">
                        <form action="" method='POST'>
                            @csrf
                            <button type="submit">
                                <img src="" alt="Upvote">
                            </button>
                        </form>
                        <p class='reply-vote-count'>Vote count</p>
                        <form action="" method='POST'>
                            @csrf
                            <button type="submit">
                                <img src="" alt="Downvote">
                            </button>
                        </form>
                    </div>
                    <button type="button" class='share-button'>Share</button>
                </div>
                <div class="reply-edit-form" style='display:none;'>
                    <form action="" method='POST'>
                        @csrf
                        <textarea
                            name=""
                            id=""
                            placeholder='Reply content...'
                            required></textarea>
                        <div class="edit-form-buttons">
                            <button type='button' class="edit-cancel-button">Cancel</button>
                            <button type="submit" class="edit-confirm-button">Save Changes</button>
                        </div>
                    </form>
                </div>
                <div class="reply-delete-form" style='display:none;'>
                    <p style='white-space:pre-wrap'>Reply Content</p>
                    <div class="delete-form-buttons">
                        <form action="" method='POST' class='delete-buttons-container'>
                            @csrf
                            <button type="button" class='delete-cancel-button'>Cancel</button>
                            <button type="submit" class='delete-confirm-button'>Delete Reply</button>
                        </form>
                    </div>
                </div>
            </div>
        </template>
    </div>
    @include('components.back-to-top-button')
</body>
<script src="{{ asset('js/post.js') }}"></script>

</html>