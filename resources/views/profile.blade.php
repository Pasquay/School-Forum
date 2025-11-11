<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>

<body data-user-id='{{ Auth::id() }}'>
    @include('components.navbar', ['active' => 'profile'])
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <div class="nav" id='nav-1'>
                <form action="#" method='GET' id='overview-form'>
                    @csrf
                    <button type="submit" class='active first'>Overview</button>
                </form>
                <form action="#" method='GET' id='posts-form'>
                    @csrf
                    <button type="submit">Posts</button>
                </form>
                <form action="#" method='GET' id='comments-form'>
                    @csrf
                    <button type="submit">Comments</button>
                </form>
                <form action="#" method='GET' id='deleted-form'>
                    @csrf
                    <button type="submit">Deleted</button>
                </form>
            </div>
            <div class="nav deleted" id='nav-2' style='display:none;'>
                <form action="#" method='GET' id='deleted-overview-form'>
                    @csrf
                    <button type='submit' class='active first'>Deleted Overview</button>
                </form>
                <form action="#" method='GET' id='deleted-posts-form'>
                    @csrf
                    <button type='submit'>Deleted Posts</button>
                </form>
                <form action="#" method='GET' id='deleted-comments-form'>
                    @csrf
                    <button type='submit'>Deleted Comments</button>
                </form>
            </div>
            <div class="overview-column" id='overview-column'>
                @foreach($overview as $item)
                @if($item->type === 'post')
                @include('components.post', ['post' => $item])
                @elseif($item->type === 'comment')
                @include('components.profile-comment', ['comment' => $item])
                @elseif($item->type === 'reply')
                @include('components.profile-reply', ['reply' => $item])
                @endif
                @endforeach
                <div class="loader" id='overview-loader'
                    style='display:none;
                        margin:3rem;
                        text-align:center;'>
                    Loading...
                </div>
                <div class="overview-column-bottom" id='overview-column-bottom' style='display:none;'>
                    You're all caught up!
                </div>
            </div>
            <div class="posts-column" id='posts-column' style='display:none;'>
                @foreach($posts as $post)
                @include('components.post', ['post' => $post])
                @endforeach
                <div class="loader" id='post-loader'
                    style='display:none;
                        margin:3rem;
                        text-align:center;'>
                    Loading...
                </div>
                <div class="post-column-bottom" id='post-column-bottom' style='display:none;'>
                    You're all caught up!
                </div>
            </div>
            <div class="comments-column" id='comments-column' style='display:none;'>
                @foreach($comments as $comment)
                @if($comment->type === 'comment')
                @include('components.profile-comment', ['comment' => $comment])
                @elseif($comment->type === 'reply')
                @include('components.profile-reply', ['reply' => $comment])
                @endif
                @endforeach
                <div class="loader" id='comment-loader'
                    style='display:none;
                        margin:3rem;
                        text-align:center;'>
                    Loading...
                </div>
                <div class="comment-column-bottom" id='comment-column-bottom' style='display:none;'>
                    You're all caught up!
                </div>
            </div>
            <div class='deleted-container' style='display:none;'>
                <div class="deleted-overview-column" id='deleted-overview-column'>
                    @foreach($deletedOverview as $item)
                    @if($item->type === 'post')
                    @include('components.post', ['post' => $item])
                    @elseif($item->type === 'comment')
                    @include('components.profile-comment', ['comment' => $item])
                    @elseif($item->type === 'reply')
                    @include('components.profile-reply', ['reply' => $item])
                    @endif
                    @endforeach
                    <div class="loader" id='deleted-overview-loader'
                        style='display:none;
                            margin:3rem;
                            text-align:center'>
                        Loading...
                    </div>
                    <div class="deleted-overview-column-bottom" id='deleted-overview-column-bottom' style='display:none;'>
                        You're all caught up!
                    </div>
                </div>
                <div class="deleted-posts-column" id='deleted-posts-column' style='display:none;'>
                    @foreach($deletedPosts as $deletedPost)
                    @include('components.post', ['post' => $deletedPost])
                    @endforeach
                    <div class="loader" id='deleted-post-loader'
                        style='display:none;
                            margin:3rem;
                            text-align:center'>
                        Loading...
                    </div>
                    <div class="deleted-post-column-bottom" id='deleted-post-column-bottom' style='display:none;'>
                        You're all caught up!
                    </div>
                </div>
                <div class="deleted-comments-column" id='deleted-comments-column' style='display:none;'>
                    @foreach($deletedCommentsAndReplies as $item)
                    @if($item->type === 'comment')
                    @include('components.profile-comment', ['comment' => $item])
                    @elseif($item->type === 'reply')
                    @include('components.profile-reply', ['reply' => $item])
                    @endif
                    @endforeach
                    <div class="loader" id='deleted-comment-loader'
                        style='display:none;
                            margin:3rem;
                            text-align:center'>
                        Loading...
                    </div>
                    <div class="deleted-comment-column-bottom" id='deleted-comment-column-bottom' style='display:none;'>
                        You're all caught up!
                    </div>
                </div>
            </div>
        </div>
        <div class="right-side">
            <div class="user-info" id='user-info'>
                <!-- PROFILE INFORMATION -->
                <div class="user-info-row-1">
                    <div class="user-name-role">
                        <p>{{ '@' . $user->name }}</p>
                        @if($user->role === "staff")
                        <img src="{{ asset('/icons/staff-check.png') }}" alt="| Teacher">
                        @endif
                    </div>
                    <button class='profile-share-button' id='profile-share-button'>Share</button>
                </div>
                <div class="user-info-row-2">
                    <p><span>About:</span><br>
                        {{ $user->bio }}
                    </p>
                </div>
                @if($user->social_links && count($user->social_links)>0)
                <div class="user-info-row-2-5">
                    @foreach($user->social_links as $link)
                    <a href="{{ $link }}" class="social-link" target="_blank" rel="noopener noreferrer">{{ $link }}</a>
                    @endforeach
                </div>
                @endif
                <div class="user-info-row-3">
                    <div class="contributionContainer">
                        <p><span>Contributions:</span><br>{{ $postCount + $commentCount + $replyCount }}</p>
                    </div>
                    <div class="reputationContainer">
                        <p><span>Reputation:</span><br>{{ $likeCount }}</p>
                    </div>
                </div>
                <div class="user-info-row-4">
                    <p><span>Joined:</span><br>{{ $user->created_at->format('F j, Y') }}</p>
                </div>
                <!-- GROUPS INFORMATION -->
                <div class="groups" id="groups-created">
                    <div class="section-header">
                        <p>Groups Created</p>
                        <button class="create-group-button">Create Group</button>
                    </div>
                    @if($createdGroups->count() > 0)
                    @foreach($createdGroups as $group)
                    @include('components.group-info-minimal', ['group' => $group])
                    @endforeach
                    @else
                    <p class="empty">No groups created yet...</p>
                    @endif
                </div>
                <div class="groups" id="groups-moderated">
                    <div class="section-header">
                        <p>Groups Moderated</p>
                    </div>
                    @if($moderatedGroups->count() > 0)
                    @foreach($moderatedGroups as $group)
                    @include('components.group-info-minimal', ['group' => $group])
                    @endforeach
                    @else
                    <p class="empty">No groups moderated yet...</p>
                    @endif
                </div>
                <!-- PROFILE SETTINGS -->
                <div class="user-info-row-5">
                    <p>Settings:</p>
                </div>
                <div class="user-info-row-6">
                    <div class="row-6-left">
                        <span>Profile</span>
                        <p>Customize your profile</p>
                    </div>
                    <button>Edit</button>
                </div>
            </div>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>
<script src="{{ asset('js/profile.js') }}"></script>


</html>