<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>

    @include('components.navbar', ['active' => 'home'])

    @if (session()->has('success'))
    <div class="success-message" onclick="this.style.display='none'">
        <div>
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if ($errors->any())
    <div class="error-message" onclick="this.style.display='none'">
        <div>
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
    </div>
    @endif
    <main>
        <div class="left-side">
            <div class="action-bar">
                <button type="button" class="create-post-btn" id="create-post-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Create Post
                </button>
                <input type="text" class="search-input" id="search-input" placeholder="Search posts...">
                <select class="filter-select" id="filter-select">
                    <option value="all">All Posts</option>
                    <option value="new">Newest</option>
                    <option value="top">Top Rated</option>
                    <option value="discussed">Most Discussed</option>
                </select>
            </div>

            <!-- Create Post Modal -->
            <div class="create-post-modal" id="create-post-modal" style="display: none;">
                <div class="modal-backdrop" id="modal-backdrop"></div>
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Create a Post</h3>
                        <button type="button" class="modal-close" id="modal-close">&times;</button>
                    </div>
                    <form action="/create-post/1" method="POST" id="create-post-form">
                        @csrf
                        <input type="text" name="create-post-title" id="create-post-title" placeholder="Post title" required>
                        <textarea name="create-post-content" id="create-post-content" placeholder="What's on your mind?" required></textarea>
                        <button type="submit" class="submit-post-btn">Post</button>
                    </form>
                </div>
            </div>
            <div class="posts-column" id='posts-column'>
                <!-- PINNED POSTS -->
                @foreach($pinned as $post)
                @include('components.post', ['post' => $post])
                @endforeach
                <!-- POSTS -->
                @foreach($posts as $post)
                @include('components.post', ['post' => $post])
                @endforeach
            </div>
            @include('components.back-to-top-button')
            <div class="loader" id='loader'
                style='
                    text-align:center;
                    margin:5rem;
                    display:none;'>
                Loading...
            </div>
            <p class="home-bottom" id='home-bottom' style='display:none;'>
                You're all caught up!
            </p>
        </div>

        <div class="right-side">
            <div class="user-info" id="user-info">
                <div class="groups-created">
                    <p class="main-header">Your Groups</p>
                    <div class="section-header">
                        <p>Groups Created</p>
                    </div>
                    @if($createdGroups->count() > 0)
                    @foreach($createdGroups as $group)
                    @include('components.group-info-minimal', ['group' => $group])
                    @endforeach
                    @else
                    <p class="empty">No groups created yet...</p>
                    @endif
                </div>
                <div class="groups-moderated">
                    <div class="section-header">
                        <p>Groups Moderated</p>
                    </div>
                    @if($moderatedGroups->count() > 0)
                    @foreach($moderatedGroups as $group)
                    @include('components.group-info-minimal', ['group' => $group])
                    @endforeach
                    @else
                    <p class="empty">No groups to moderate...</p>
                    @endif
                </div>
                <div class="groups-joined">
                    <div class="section-header">
                        <p>Groups Joined</p>
                    </div>
                    @if($joinedGroups->count() > 0)
                    @foreach($joinedGroups as $group)
                    @include('components.group-info-minimal', ['group' => $group])
                    @endforeach
                    @else
                    <p class="empty">No groups joined yet...</p>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
<script src="{{ asset('js/home.js') }}"></script>

</html>