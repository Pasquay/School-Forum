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
            padding: 1.5rem;
            transition: transform 0.2s ease;
        }
    /* POSTS */
        .left-side {
            flex: 1 1 800px;
            max-width: 800px;
        }

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

        .user-bottom {
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
            align-items: center;
            gap: 1.2rem;
            width: 100%;
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
                NAV UNDER CONSTRUCTION...
            </div>
            <div class="posts-column" id='posts-column'>
                @foreach($posts as $post)
                    @include('components.post', ['post' => $post])
                @endforeach
            </div>
            <div class="loader" id='loader' 
                style=
                    'display:none;
                    margin:5rem;
                    text-align:center;'
            >
                Loading...
            </div>
            <div class="user-bottom" id='user-bottom' style='display:none;'>
                You're all caught up!
            </div>
        </div>
        <div class="right-side">
            <div class="user-info" id='user-info'>
                <img src="" alt="Banner">
                <p>Username</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Number of Posts</p>
                <p>Number of Comments</p>
                <p>Post likes received</p>
                <p>Comment likes received</p>
                <p>Date created</p>
            </div>
            <div class="user-settings">
                
            </div>
        </div>
    </main>
</body>
<script>
    const userID = document.body.dataset.userId;
    const posts = document.getElementsByClassName('post');
    const postContainer = document.querySelector('#posts-column');
    const loader = document.querySelector('#loader');

    // Scrolling
        let nextPage = 2;
        let loading = false;
        document.addEventListener('scroll', () => {
            if(!loading && nextPage){
                if(window.innerHeight + window.scrollY >= document.body.offsetHeight - 300){
                    loading = true;
                    loader.style.display = 'block';
                    fetch(`/user/${userID}/posts?page=${nextPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        postContainer.insertAdjacentHTML('beforeend', data.html);
                        nextPage = data.next_page;
                        loading = false;
                        loader.style.display = 'none';
                        //attach event listeners again
                            // Post cards link to post pages
                                Array.from(posts).forEach(post => {
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
                                });
                            // Upvote and downvote logic
                                Array.from(posts).forEach(post => {
                                    const voteContainer = post.querySelector('#vote-container');
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
                                    })

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
                                });
                    })
                }
            }
            if(!nextPage){
                document.getElementById('user-bottom').style.display = 'block';
            }
        })

    // Post cards link to post pages
        Array.from(posts).forEach(post => {
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
        });
    // Upvote and downvote logic
        Array.from(posts).forEach(post => {
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
        })
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
        });
</script>
</html>