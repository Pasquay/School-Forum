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

        .search {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 80px);
            padding: 2rem;
        }

        .search form {
            display: flex;
            flex-direction: row; /* Changed from column to row */
            align-items: center;
            gap: 1rem;
            width: 100%;
            max-width: 600px; /* Increased to accommodate inline elements */
        }

        .search input {
            flex: 1; /* This makes the input take up remaining space */
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
            width: auto; /* Changed from 100% to auto */
            background-color: #4a90e2;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            white-space: nowrap; /* Prevents button text from wrapping */
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
            padding: 0 1rem; /* Changed from 1.5rem to match posts-column */
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
            margin-bottom: 0; /* Remove margin-bottom since we're using gap */
        }

        .create-post-form button {
            width: 100%; /* Make button full width */
            background-color: #4a90e2;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 0; /* Remove negative margin */
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

        /* Update existing #vote-container style */
        #vote-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0;  /* Remove margin */
            padding-top: 0; /* Remove padding */
            border-top: none; /* Remove border */
        }

        /* Add new styles */
        .post-bottom {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #e1e1e1; /* Move border here */
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
            width: 16px;  /* Reduced from 24px */
            height: 16px;  /* Reduced from 24px */
            display: block;
            object-fit: contain;
        }

        #vote-container p {
            margin: 0;
            min-width: 1.5rem;  /* Reduced from 2rem */
            text-align: center;
            font-weight: 500;
            color: #666;
            font-size: 0.9rem;  /* Reduced from 1rem */
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
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/home" class="brand">Social Media</a>
        <div class="nav-links">
            <a href="/home" class="nav-link">Home</a>
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