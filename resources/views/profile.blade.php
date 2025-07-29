<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    /* MAIN */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding-top: 72px;
        }

        .navbar {
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4a90e2;  /* Changed from #333 to match the blue theme */
            text-decoration: none;
            transition: color 0.2s;  /* Added transition for hover effect */
        }

        .brand:hover {
            color: #357abd;  /* Added hover state to match other interactive elements */
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: #666;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #4a90e2;
        }

        .logout-btn {
            background-color: #4a90e2;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .logout-btn:hover {
            background-color: #357abd;
        }

        main {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem;
            gap: 2rem;
        }
    /* LEFT NAV */
        .left-side .nav {
            background-color: white;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            display: flex;
            transition: transform 0.2s ease;
        }

        .left-side .nav button.first {
            margin-left: 0;
        }

        .left-side .nav button {
            border: 0;
            cursor: pointer;
            color: #666;
            background-color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: medium;
            transition: color 0.2s;
            padding: 0.5rem 1rem;
            margin: 0 0.6rem;
            flex: 1;
            text-align: center;
        }

        .left-side .nav button:hover {
            color: #4a90e2;
        }

        .left-side .nav button.active {
            border: 0;
            border-radius: 0.5rem;
            cursor: pointer;
            color: #4a90e2;
            background-color: #eaf4fb;
            text-decoration: none;
            font-weight: 500;
            font-size: medium;
            transition: color 0.2s;
        }

        .left-side .nav button.active:hover {
            color: #666;
            background-color: #e9eef3;
        }
    /* OVERVIEW */
        .left-side {
            flex: 1 1 800px;
            max-width: 800px;
        }
        
        .overview-column {
            max-width: 800px;
            margin: 1.5rem 0 1.5rem 0;
            padding: 0 0.7rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .overview-column-bottom {
            display: block;
            text-align: center;
            margin: 3rem auto 2rem auto;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.5;
            transition: background 0.2s;
        }

        .overview-column .profile-comment {
            width: 95%;
            min-width: 200px;
            align-self: flex-end;
        }

        .overview-column-bottom {
            display: block;
            text-align: center;
            margin: 3rem auto 2rem auto;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.5;
            transition: background 0.2s;
        }

    /* POSTS */
        .posts-column {
            max-width: 800px;
            margin: 1.5rem 0 1.5rem 0;
            padding: 0 0.7rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .post {
            cursor: pointer;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
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

        /* Vote container styles */
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

        /* Post bottom (actions) */
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
            transform: scale(1.1);
        }

        .post-share-button:active {
            transform: scale(1.0);
        }

        .post-column-bottom {
            display: block;
            text-align: center;
            margin: 3rem auto 2rem auto;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.5;
            transition: background 0.2s;
        }
    /* COMMENTS */
        .original-post-link {
            color: #111;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .original-post-link:hover {
            color: #357abd;
            text-decoration: underline;
        }

        .comments-column {
            max-width: 800px;
            margin: 1.5rem 0 1.5rem 0;
            padding: 0 0.7rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-comment {
            cursor: pointer;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem 0.5rem 1.5rem;
            transition: transform 0.2s ease;
        }

        .profile-comment:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-comment small {
            color: #666;
            display: block;
            margin-top: 0.2rem;
            margin-bottom: 0.5rem;
        }
    
        .profile-comment p {
            color: #444;
            line-height: 1.6;
            font-size: 16px;
            margin-top: 0.7rem;
            margin-bottom: 0.5rem;
        }

        .profile-comment p:last-child {
            margin-bottom: 0;
        }

        .profile-comment-bottom {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e1e1e1;
        }

        .profile-comment .vote-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-comment .vote-container form {
            margin: 0;
        }

        .profile-comment .vote-container button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .profile-comment .vote-container button:hover {
            transform: scale(1.1);
        }

        .profile-comment .vote-container img {
            width: 16px;
            height: 16px;
            display: block;
            object-fit: contain;
        }

        .profile-comment .vote-container p {
            margin: 0;
            min-width: 1.5rem;
            text-align: center;
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;
        }

        .profile-comment-share-button {
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

        .profile-comment-share-button:hover {
            transform: scale(1.1);
        }

        .profile-comment-share-button:active {
            transform: scale(1.0);
        }
        
        .comment-column-bottom {
            display: block;
            text-align: center;
            margin: 3rem auto 2rem auto;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.5;
            transition: background 0.2s;
        }
    /* USER */
        .right-side {
            flex: 0 0 340px;
            max-width: 340px;
            min-width: 340px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }

        .user-info {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.10);
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: start;
            gap: 1rem;
            width: 100%;
        }

        .user-info-row-1 {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .user-info-row-1 p {
            margin: 0;
            font-size: 20px; 
            flex: 1;
            line-height: 1.5;
            color: #4a90e2;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .user-info-row-1 button {
            min-width: 5rem;
            background-color: #4a90e2;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .user-info-row-1 button:hover {
            background-color: #357abd;
        }

        .user-info-row-2 {
            display: flex;
            flex-direction: row;
            width: 100%;
            padding: 1rem 0;
            border-top: 1px solid #e1e1e1;
            border-bottom: 1px solid #e1e1e1;
            margin: 0.5rem 0;
        }

        .user-info-row-2 p {
            margin: 0;
            line-height: 1.5;
            color: #333;
            font-weight: 500;
            text-align: justify;
        }

        .user-info-row-2 span {
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .user-info-row-3 {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            width: 100%;
        }

        .user-info-row-3 span {
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .contributionContainer, .reputationContainer {
            flex: 1;
            background-color: #f8f9fa;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: box-shadow 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contributionContainer:hover, .reputationContainer:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .contributionContainer p, .reputationContainer p {
            margin: 0;
            line-height: 1.5;
            color: #333;
            font-weight: 500;
            text-align: center;
        }

        .user-info-row-4 {
            display: flex;
            width: 100%;
            flex-direction: row;
            padding-top: 1rem;
            border-top: 1px solid #e1e1e1;
            margin-top: 0.5rem;
        }

        .user-info-row-4 span {
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .user-info-row-4 p {
            margin: 0;
            line-height: 1.5;
            color: #333;
            font-weight: 500;
        }

        .user-info-row-4 {
            display: flex;
            flex-direction: row;
        }
        
        .user-bottom {
            display: block;
            text-align: center;
            margin: 3rem auto 2rem auto;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.5;
            transition: background 0.2s;
        }
    </style>
</head>
<body data-user-id='{{ Auth::id() }}'>
    @include('components.navbar')
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <div class="nav">
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
            </div>
            <div class="overview-column" id='overview-column'>
                @foreach($overview as $item)
                    @if($item->type === 'post')
                        @include('components.post', ['post' => $item])
                    @elseif($item->type === 'comment')
                        @include('components.profile-comment', ['comment' => $item])
                    @endif
                @endforeach
                <div class="loader" id='overview-loader'
                    style=
                        'display:none;
                        margin:3rem;
                        text-align:center;'
                >
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
                    style=
                        'display:none;
                        margin:3rem;
                        text-align:center;'
                >
                    Loading...
                </div>
                <div class="post-column-bottom" id='post-column-bottom' style='display:none;'>
                    You're all caught up!
                </div>
            </div>
            <div class="comments-column" id='comments-column' style='display:none;'>
                @foreach($comments as $comment)
                    @include('components.profile-comment', ['comment' => $comment])
                @endforeach
                <div class="loader" id='comment-loader'
                    style=
                        'display:none;
                        margin:3rem;
                        text-align:center;'
                >
                    Loading...
                </div>
                <div class="comment-column-bottom" id='comment-column-bottom' style='display:none;'>
                    You're all caught up!
                </div>
            </div>
        </div>
        <div class="right-side">
            <div class="user-info" id='user-info'>
                <div class="user-info-row-1">
                    <p>{{ '@' . $user->name }}</p>
                    <button class='profile-share-button' id='profile-share-button'>Share</button>
                </div>
                <div class="user-info-row-2">
                    <p><span>About:</span><br>
                    {{ $user->bio }}</p>
                </div>
                <div class="user-info-row-3">
                    <div class="contributionContainer">
                        <p><span>Contributions:</span><br>{{ $postCount + $commentCount }}</p>
                    </div>
                    <div class="reputationContainer">
                        <p><span>Reputation:</span><br>{{ $likeCount }}</p>
                    </div>
                </div>
                <div class="user-info-row-4">
                    <p><span>Joined:</span><br>{{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
            <div class="user-settings">
                <!--  -->
            </div>
        </div>
    </main>
</body>
<script>
    const userID = document.body.dataset.userId;
    const posts = document.querySelectorAll('#posts-column .post');
    const postContainer = document.querySelector('#posts-column');
    const postLoader = document.querySelector('#post-loader');
    
    const comments = document.querySelectorAll('#comments-column .profile-comment');
    const commentContainer = document.querySelector('#comments-column');
    const commentLoader = document.querySelector('#comment-loader');

    const overviewContainer = document.querySelector('#overview-column');
    const overviewLoader = document.querySelector('#overview-loader');

    const userInfoContainer = document.querySelector('.user-info');

    // RIGHT SIDE   
        // Profile Share Button
            const profileShareButton = userInfoContainer.querySelector('#profile-share-button');
            profileShareButton.addEventListener('click', (e) => {
                e.stopPropagation();
                profileUrl = `${window.location.origin}/user/${userID}`;
                navigator.clipboard.writeText(profileUrl)
                .then(() => {
                    profileShareButton.textContent = 'Copied!';
                    setTimeout(() => {
                        profileShareButton.textContent = 'Share';
                    }, 1200);
                })
            })
    // LEFT SIDE
        const leftnav = document.querySelector('.left-side .nav');
        
        const overviewForm = leftnav.querySelector('#overview-form');
        const postsForm = leftnav.querySelector('#posts-form');
        const commentsForm = leftnav.querySelector('#comments-form');
        
        const overviewBtn = leftnav.querySelector('#overview-form button');
        const postsBtn = leftnav.querySelector('#posts-form button');
        const commentsBtn = leftnav.querySelector('#comments-form button');

        const overviewCol = document.querySelector('.left-side .overview-column');
        const postsCol = document.querySelector('.left-side .posts-column');
        const commentsCol = document.querySelector('.left-side .comments-column');

        // Overview - UNDER CONSTRUCTION
            overviewForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if(!overviewBtn.classList.contains('active')){
                    overviewBtn.classList.add('active');
                    overviewCol.style.display = 'flex';
                }
                if(postsBtn.classList.contains('active')){
                    postsBtn.classList.remove('active');
                    postsCol.style.display = 'none';
                }
                if(commentsBtn.classList.contains('active')){
                    commentsBtn.classList.remove('active');
                    commentsCol.style.display = 'none';
                }
            });
        // Posts
            postsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if(overviewBtn.classList.contains('active')){
                    overviewBtn.classList.remove('active');
                    overviewCol.style.display = 'none';
                }
                if(!postsBtn.classList.contains('active')){
                    postsBtn.classList.add('active');
                    postsCol.style.display = 'flex';
                }
                if(commentsBtn.classList.contains('active')){
                    commentsBtn.classList.remove('active');
                    commentsCol.style.display = 'none';
                }
            });
        // Comments
            commentsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if(overviewBtn.classList.contains('active')){
                    overviewBtn.classList.remove('active');
                    overviewCol.style.display = 'none';
                }
                if(postsBtn.classList.contains('active')){
                    postsBtn.classList.remove('active');
                    postsCol.style.display = 'none';
                }
                if(!commentsBtn.classList.contains('active')){
                    commentsBtn.classList.add('active');
                    commentsCol.style.display = 'flex';
                }
            });

    // Scrolling
        // Variables
            // Overview
                let overviewNextPage = 2;
                let overviewLoading = false;
            // Posts
                let postNextPage = 2;
                let postLoading = false;
            // Comments
                let commentNextPage = 2;
                let commentLoading = false;
            // Deleted Posts - Under Construction
            // Deleted Comments - Under Construction 
        document.addEventListener('scroll', () => {
            if(window.innerHeight + window.scrollY >= document.body.offsetHeight - 300){
                // Overview
                    if(overviewBtn.classList.contains('active') && !overviewLoading && overviewNextPage){
                        overviewLoading = true;
                        overviewLoader.style.display = 'block';
                        fetch(`/user/${userID}/overview?page=${overviewNextPage}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            overviewLoader.insertAdjacentHTML('beforebegin', data.html);
                            overviewNextPage = data.next_page;
                            overviewLoading = false;
                            overviewLoader.style.display = 'none';

                            attachOverviewEventListeners();

                            if(!overviewNextPage){
                                document.getElementById('overview-column-bottom').style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error: ', error);
                            overviewLoading = false;
                            overviewLoader.style.display = 'none';
                        });

                    };
                // Posts
                    if(postsBtn.classList.contains('active') && !postLoading && postNextPage){
                        postLoading = true;
                        postLoader.style.display = 'block';
                        fetch(`/user/${userID}/posts?page=${postNextPage}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            postLoader.insertAdjacentHTML('beforebegin', data.html);
                            postNextPage = data.next_page;
                            postLoading = false;
                            postLoader.style.display = 'none';

                            attachPostEventListeners('#posts-column');

                            if(!postNextPage){
                                document.getElementById('post-column-bottom').style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error: ', error);
                            postLoading = false;
                            postLoader.style.display = 'none';
                        });
                    }
                // Comments
                    if(commentsBtn.classList.contains('active') && !commentLoading && commentNextPage){
                        commentLoading = true;
                        commentLoader.style.display = 'block';
                        fetch(`/user/${userID}/comments?page=${commentNextPage}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            commentLoader.insertAdjacentHTML('beforebegin', data.html);
                            commentNextPage = data.next_page;
                            commentLoading = false;
                            commentLoader.style.display = 'none';

                            attachCommentEventListeners('#comments-column');

                            if(!commentNextPage){
                                document.querySelector('#comment-column-bottom').style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error: ', error);
                            commentLoading = false;
                            commentLoader.style.display = 'none';
                        });
                    }
                // Deleted Posts - Under Construction
                // Deleted Comments - Under Construction 
            }
        })
    // Scroll Event Listeners
        // Overview
        // Posts
            function attachPostEventListeners(column = '#posts-column'){
                const posts = document.querySelectorAll(`${column} .post`);

                posts.forEach(post => {
                    if(!post.dataset.listenersAttached){
                        post.dataset.listenersAttached = 'true';
                // Post cards link to post pages
                        post.addEventListener('click', () => {
                            window.location.href = `/post/${post.id}`;
                        });
                    // Post share buttons
                        const shareButton = post.querySelector('.post-share-button');
                        shareButton.addEventListener('click', (e) => {
                            e.stopPropagation();
                            postUrl = `${window.location.origin}/post/${post.id}`;
                            navigator.clipboard.writeText(postUrl)
                            .then(() => {
                                shareButton.textContent = 'Copied!';
                                setTimeout(() => {
                                    shareButton.textContent = 'Share';
                                }, 1200);
                            })
                        })
                // Upvote and downvote logic
                        const voteContainer = post.querySelector('#vote-container');
                        const upvoteForm = voteContainer.querySelector('form:first-child');
                        const downvoteForm = voteContainer.querySelector('form:last-child');
                        const voteCount = voteContainer.querySelector('form:first-child + p');

                        const postID = post.id;
                        upvoteForm.action = `/post/upvote/${postID}`;
                        downvoteForm.action = `/post/downvote/${postID}`;
                
                        voteContainer.addEventListener('click', (e) => {
                            e.stopPropagation();
                        });
                // UPVOTE
                        upvoteForm.addEventListener('submit', async(e) => {
                            e.preventDefault();
                            e.stopPropagation();

                            try {
                                const response = await fetch(upvoteForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    credentials: 'same-origin',
                                    body: new URLSearchParams({
                                        _token: document.querySelector('meta[name="csrf-token"]').content
                                    })
                                });

                                if(response.ok){
                                    const data = await response.json();
                                    
                                    voteCount.textContent = data.voteCount;

                                    const upArrow = upvoteForm.querySelector('img');
                                    upArrow.src = data.voteValue == 1 ?
                                        "{{ asset('storage/icons/up-arrow-alt.png') }}" :
                                        "{{ asset('storage/icons/up-arrow.png') }}" ;
                                    
                                    const downArrow = downvoteForm.querySelector('img');
                                    if (data.voteValue == 1){
                                        downArrow.src = "{{ asset('storage/icons/down-arrow.png') }}";
                                    }
                                }
                            } catch (error) {
                                console.error('Error:', error);
                            }
                        });
                // DOWNVOTE
                        downvoteForm.addEventListener('submit', async(e) => {
                            e.preventDefault();
                            e.stopPropagation();

                            try {
                                const response = await fetch(downvoteForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/x-www-form-urlencoded'
                                    },
                                    credentials: 'same-origin',
                                    body: new URLSearchParams({
                                        _token: document.querySelector('meta[name="csrf-token"]').content
                                    })
                                });
                        
                                if(response.ok){
                                    const data = await response.json();
                        
                                    voteCount.textContent = data.voteCount;
                                    
                                    const downArrow = downvoteForm.querySelector('img');
                                    downArrow.src = data.voteValue == -1 ?
                                        "{{ asset('storage/icons/down-arrow-alt.png') }}" :
                                        "{{ asset('storage/icons/down-arrow.png') }}" ;
                        
                                    const upArrow = upvoteForm.querySelector('img');
                                    if(data.voteValue == -1){
                                        upArrow.src = "{{ asset('storage/icons/up-arrow.png') }}";
                                    }
                                }
                            } catch(error){
                                console.error('Error:', error);
                            }
                        })        
                    }
                });
            };
        // Profile-comments
            function attachCommentEventListeners(column = '#comments-column'){
                const comments = document.querySelectorAll(`${column} .profile-comment`);

                comments.forEach(comment => {
                    if(!comment.dataset.listenersAttached){
                        comment.dataset.listenersAttached = 'true';
                // Profile-comment cards linking to comment pages
                        const originalPostLink = comment.querySelector('.original-post-link').href;
                        const originalPostID = originalPostLink.split('/').pop();

                        const commentID = comment.id.split('-').pop();
                        comment.addEventListener('click', () => {
                            window.location.href = `/post/${originalPostID}#comment-${commentID}`;
                        });

                        const shareButton = comment.querySelector('.profile-comment-share-button');
                        shareButton.addEventListener('click', (e) => {
                            e.stopPropagation();
                            commentUrl = `${window.location.origin}/post/${originalPostID}#comment-${commentID}`;
                            navigator.clipboard.writeText(commentUrl)
                                .then(() => {
                                    shareButton.textContent = 'Copied!';
                                    setTimeout(() => {
                                        shareButton.textContent = 'Share';
                                    }, 1200)
                                });
                        });
                // Upvote and downvote logic
                        const voteContainer = comment.querySelector('#vote-container');
                        const upvoteForm = voteContainer.querySelector('form:first-child');
                        const downvoteForm = voteContainer.querySelector('form:last-child');
                        const voteCount = voteContainer.querySelector('form:first-child + p');

                        voteContainer.addEventListener('click', (e) => {
                            e.stopPropagation();
                        });
                // UPVOTE
                        upvoteForm.addEventListener('submit', async(e) => {
                            e.preventDefault();
                            e.stopPropagation();

                            try {
                                const response = await fetch(upvoteForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    credentials: 'same-origin',
                                    body: new URLSearchParams({
                                        _token: document.querySelector('meta[name="csrf-token"]').content
                                    })
                                });

                                if(response.ok){
                                    const data = await response.json();

                                    voteCount.textContent = data.voteCount;
                                    const upArrow = upvoteForm.querySelector('img');
                                    upArrow.src = data.voteValue == 1 ?
                                        "{{ asset('storage/icons/up-arrow-alt.png') }}" :
                                        "{{ asset('storage/icons/up-arrow.png') }}" ;

                                    if(data.voteValue == 1){
                                        const downArrow = downvoteForm.querySelector('img');
                                        downArrow.src = "{{ asset('storage/icons/down-arrow.png') }}";
                                    }
                                }
                            } catch(error) {
                                console.error('Error: ', error);
                            }
                        })
                // DOWNVOTE
                        downvoteForm.addEventListener('submit', async(e) => {
                            e.preventDefault();
                            e.stopPropagation();

                            try{
                                const response = await fetch(downvoteForm.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                    },
                                    credentials: 'same-origin',
                                    body: new URLSearchParams({
                                        _token: document.querySelector('meta[name="csrf-token"]').content,
                                    })
                                });

                                if(response.ok){
                                    const data = await response.json();

                                    voteCount.textContent = data.voteCount;
                                    const downArrow = downvoteForm.querySelector('img');
                                    downArrow.src = data.voteValue == -1 ?
                                        "{{ asset('storage/icons/down-arrow-alt.png') }}" :
                                        "{{ asset('storage/icons/down-arrow.png') }}" ;
                                    
                                    if(data.voteValue == -1){
                                        const upArrow = upvoteForm.querySelector('img');
                                        upArrow.src = "{{ asset('storage/icons/up-arrow.png') }}";
                                    }
                                }
                            } catch(error) {
                                console.error('Error: ', error);
                            }
                        });
                    }
                });
            };
    // First page event Listeners
        attachPostEventListeners('#posts-column'); // POST
        attachCommentEventListeners('#comments-column'); // COMMENTS
        function attachOverviewEventListeners(){
            attachPostEventListeners('#overview-column');
            attachCommentEventListeners('#overview-column');
        }
        attachOverviewEventListeners(); // OVERVIEW
</script>
</html>