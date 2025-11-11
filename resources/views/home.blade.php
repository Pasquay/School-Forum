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
    <div class="success-message">
        <div
            style="background-color: #d4edda;
                     color: #155724;
                     padding: 1rem; 
                     border-radius: 8px; 
                     margin-top: -0.5rem;
                     margin-bottom: 1rem; 
                     text-align: center;">
            {{ session('success') }}
        </div>
    </div>
    @endif
    @if ($errors->any())
    <div
        style="background-color: #f8d7da; 
            color: #721c24; 
            padding: 1rem; 
            border-radius: 8px; 
            margin-top: -0.5rem;
            margin-bottom: 1rem;
            text-align: center;">
        <ul style="margin: 0; padding-left: 1rem;">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </ul>
    </div>
    @endif
    <main>
        <div class="left-side">
            <div class="create-post-form" id='create-post-form'>
                <form action="/create-post/1" method='POST'>
                    @csrf
                    <input type="text" name="create-post-title" id='create-post-title' placeholder="What's on your mind?" required>
                    <textarea name="create-post-content" id='create-post-content' placeholder='Share your thoughts' style='display:none;' required></textarea>
                    <button type="submit" id='create-post-submit' style='display:none;'>Post</button>
                </form>
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