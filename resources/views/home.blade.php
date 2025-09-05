<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
            color: #4a90e2; 
            text-decoration: none;
            transition: color 0.2s;
        }

        .brand:hover {
            color: #357abd;
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

        .nav-link.active {
            color: #4a90e2;
        }

        .nav-link.active:hover {
            color: #357abd;
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
            align-items: flex-start;
            justify-content: center;
            gap: 2rem; /* optional: adds space between left and right */
        }

        .search {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 2rem;
        }

        .search form {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 1rem;
            width: 100%;
            max-width: 600px;
        }

        .search input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .search input:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        .search button {
            width: auto;
            background-color: #4a90e2;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            white-space: nowrap;
        }

        .search button:hover {
            background-color: #357abd;
        }

        .posts-column {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .post {
            background-color: white;
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

        .create-post-form {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .create-post-form form {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .create-post-form input,
        .create-post-form textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
            margin-bottom: 0;
        }

        .create-post-form button {
            width: 100%;
            background-color: #4a90e2;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 0;
        }

        .create-post-form input:focus,
        .create-post-form textarea:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
        }

        .create-post-form textarea {
            min-height: 120px;
            resize: vertical;
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

        .home-bottom {
            display: block;
            text-align: center;
            margin: 3rem auto 2rem auto;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.5;
            transition: background 0.2s;
        }
        /* RIGHT SIDE */
            .right-side {
                flex: 0 0 340px;
                max-width: 340px;
                min-width: 340px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                position: sticky;
                top: 88px;
                height: fit-content;
                max-height: calc(100vh - 88px);
                overflow-y: auto;
            }

            /* RIGHT SIDE SCROLLBAR */
                .right-side::-webkit-scrollbar {
                    width: 8px;
                }

                .right-side::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }

                .right-side::-webkit-scrollbar-thumb {
                    background: #c1c1c1;
                    border-radius: 4px;
                    transition: background 0.2s;
                }

                .right-side::-webkit-scrollbar-thumb:hover {
                    background: #a8a8a8;
                }

                /* For Firefox */
                .right-side {
                    scrollbar-width: thin;
                    scrollbar-color: #c1c1c1 #f1f1f1;
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

            .groups-created, .groups-moderated, .groups-joined {
                width: 100%;
            }

            .main-header {
                text-align: center;
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: #333;
                letter-spacing: 0.01em;
            }

            .section-header {
                display: flex;
                justify-content: space-between;
                border-bottom: 1px solid #f0f0f0;
                padding-bottom: 10px;
                width: 100%;
            }

            .section-header p {
                margin: 0;
                font-size: 18px; 
                flex: 1;
                line-height: 1.5;
                font-weight: 500;
                text-decoration: none;
                transition: color 0.2s;
            }

            .section-header button:hover {
                background-color: #357abd;
            }

            .user-info .empty {
                color: #666;
                font-style: italic;
                text-align: center;
                padding: 1rem 0 1rem 0;
                margin: 0;
                font-size: 0.9rem;
                width: 100%;
                border-bottom: 1px solid #f0f0f0;
                display: block;
            }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/home" class="brand">Social Media</a>
        <div class="nav-links">
            <a href="/home" class="nav-link active">Home</a>
            <a href="/groups" class="nav-link">Groups</a>
            <a href="/user/{{ Auth::id() }}" class="nav-link">Profile</a>
            <form action="/logout" method="POST" style="margin: 0">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>
    @if (session()->has('success'))
        <div class="success-message">
            <div 
                style=
                    "background-color: #d4edda;
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
                <form action="/create-post" method='POST'>
                    @csrf
                    <input type="text" name="create-post-title" id='create-post-title' placeholder="What's on your mind?" required>
                    <textarea name="create-post-content" id='create-post-content' placeholder='Share your thoughts' style='display:none;' required></textarea>
                    <button type="submit" id='create-post-submit' style='display:none;'>Post</button>
                </form>
            </div>
            <div class="posts-column" id='posts-column'>
                @foreach($posts as $post)
                    @include('components.post', ['post' => $post])
                @endforeach
            </div>
            @include('components.back-to-top-button')
            <div class="loader" id='loader' 
                style='
                    text-align:center;
                    margin:5rem;
                    display:none;'
            >
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
<script>
    const createPostForm = document.getElementById('create-post-form');
    const createPostTitle = document.getElementById('create-post-title');
    const createPostContent = document.getElementById('create-post-content');
    const createPostSubmit = document.getElementById('create-post-submit');
    const posts = document.getElementsByClassName('post');
    let nextPage = 2;
    let loading = false;

    // Scrolling
    window.addEventListener('scroll', () => {
        const postContainer = document.getElementById('posts-column');
        const loader = document.getElementById('loader');
        if(!loading && nextPage){
            if(window.innerHeight + window.scrollY >= document.body.offsetHeight - 300){
                loading = true;
                loader.style.display = 'block';
                fetch(`home?page=${nextPage}`, {
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
                                                "{{ asset('/icons/up-arrow-alt.png') }}" :
                                                "{{ asset('/icons/up-arrow.png') }}" ;
                                            
                                            const downArrow = downvoteForm.querySelector('img');
                                            if (data.voteValue == 1){
                                                downArrow.src = "{{ asset('/icons/down-arrow.png') }}";
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
                                                "{{ asset('/icons/down-arrow-alt.png') }}" :
                                                "{{ asset('/icons/down-arrow.png') }}" ;
                            
                                            const upArrow = upvoteForm.querySelector('img');
                                            if(data.voteValue == -1){
                                                upArrow.src = "{{ asset('/icons/up-arrow.png') }}";
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
            document.getElementById('home-bottom').style.display = 'block';
        }
    })
    // Dynamic create post form
        createPostForm.addEventListener('click', () => {
            createPostContent.style.display = 'block';
            createPostSubmit.style.display = 'block';
        });
        document.addEventListener('click', (e) => {
            if(!createPostForm.contains(e.target) && createPostTitle.value === ''){
                createPostContent.style.display = 'none';
                createPostSubmit.style.display = 'none';
            }
        });

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
                            "{{ asset('/icons/up-arrow-alt.png') }}" :
                            "{{ asset('/icons/up-arrow.png') }}" ;
                        
                        const downArrow = downvoteForm.querySelector('img');
                        if (data.voteValue == 1){
                            downArrow.src = "{{ asset('/icons/down-arrow.png') }}";
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
                            "{{ asset('/icons/down-arrow-alt.png') }}" :
                            "{{ asset('/icons/down-arrow.png') }}" ;
        
                        const upArrow = upvoteForm.querySelector('img');
                        if(data.voteValue == -1){
                            upArrow.src = "{{ asset('/icons/up-arrow.png') }}";
                        }
                    }
                } catch(error){
                    console.error('Error:', error);
                }
            })
        });
    // Right Side
        function addRightGroupEventListeners(){
            const rightGroups = document.querySelectorAll('.group-info-minimal');
            rightGroups.forEach(group => {
            // Onclick lead to group Page
                const groupid = group.dataset.groupid;
                group.addEventListener('click', () => {
                    window.location.href = `/group/${groupid}`;
                })
            // Star and Unstar Group
                const starForm = group.querySelector('form');
                const starBtn = group.querySelector('.star');
                if(starBtn){
                    let starImg = starBtn.querySelector('img');

                    starBtn.addEventListener('click', async(e) => {
                        e.preventDefault();
                        e.stopPropagation();

                        starBtn.disabled = true;
                        starBtn.style.opacity = '0.5';
                        starBtn.style.cursor = 'default';

                        setTimeout(() => {
                            starBtn.disabled = false;
                            starBtn.style.opacity = '1';
                            starBtn.style.cursor = 'pointer';
                        }, 400);

                        try {
                            const response = await fetch(starForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'Content-Type': 'x-www-form-urlencoded',
                                },
                                credentials: 'same-origin',
                                body: new URLSearchParams({
                                    _token: document.querySelector('meta[name="csrf-token"]').content,
                                })
                            });

                            if(response.ok){
                                const data = await response.json();
                                starImg.src = data.starValue ?
                                    '{{ asset("/icons/star.png") }}' :
                                    '{{ asset("/icons/star-alt.png") }}' ;
                            }
                        } catch(error){
                            console.error('Error: ', error);
                        }
                    })
                }
            })
        }
        addRightGroupEventListeners();
</script>
</html>