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
        if (!loading && nextPage) {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
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
                            upvoteForm.addEventListener('submit', async (e) => {
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

                                    if (response.ok) {
                                        const data = await response.json();

                                        voteCount.textContent = data.voteCount;

                                    const upArrow = upvoteForm.querySelector('img');
                                    upArrow.src = data.voteValue == 1 ?
                                        "/icons/up-arrow-alt.png" :
                                        "/icons/up-arrow.png";

                                    const downArrow = downvoteForm.querySelector('img');
                                    if (data.voteValue == 1) {
                                        downArrow.src = "/icons/down-arrow.png";
                                    }
                                    }
                                } catch (error) {
                                    console.error('Error:', error);
                                }
                            })

                            // DOWNVOTE
                            downvoteForm.addEventListener('submit', async (e) => {
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

                                    if (response.ok) {
                                        const data = await response.json();

                                        voteCount.textContent = data.voteCount;

                                    const downArrow = downvoteForm.querySelector('img');
                                    downArrow.src = data.voteValue == -1 ?
                                        "/icons/down-arrow-alt.png" :
                                        "/icons/down-arrow.png";

                                    const upArrow = upvoteForm.querySelector('img');
                                    if (data.voteValue == -1) {
                                        upArrow.src = "/icons/up-arrow.png";
                                    }
                                    }
                                } catch (error) {
                                    console.error('Error:', error);
                                }
                            })
                        });
                    })
            }
        }
        if (!nextPage) {
            document.getElementById('home-bottom').style.display = 'block';
        }
    })
    // Dynamic create post form
    createPostForm.addEventListener('click', () => {
        createPostContent.style.display = 'block';
        createPostSubmit.style.display = 'block';
    });
    document.addEventListener('click', (e) => {
        if (!createPostForm.contains(e.target) && createPostTitle.value === '' && createPostContent.value === '') {
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
        upvoteForm.addEventListener('submit', async (e) => {
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

                if (response.ok) {
                    const data = await response.json();

                    voteCount.textContent = data.voteCount;

                    const upArrow = upvoteForm.querySelector('img');
                    upArrow.src = data.voteValue == 1 ?
                        "/icons/up-arrow-alt.png" :
                        "/icons/up-arrow.png";

                    const downArrow = downvoteForm.querySelector('img');
                    if (data.voteValue == 1) {
                        downArrow.src = "/icons/down-arrow.png";
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        })

        // DOWNVOTE
        downvoteForm.addEventListener('submit', async (e) => {
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

                if (response.ok) {
                    const data = await response.json();

                    voteCount.textContent = data.voteCount;

                    const downArrow = downvoteForm.querySelector('img');
                    downArrow.src = data.voteValue == -1 ?
                        "/icons/down-arrow-alt.png" :
                        "/icons/down-arrow.png";

                    const upArrow = upvoteForm.querySelector('img');
                    if (data.voteValue == -1) {
                        upArrow.src = "/icons/up-arrow.png";
                    }
                }
            } catch (error) {
                console.error('Error:', error);
            }
        })
    });
    // Right Side
    function addRightGroupEventListeners() {
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
            if (starBtn) {
                let starImg = starBtn.querySelector('img');

                starBtn.addEventListener('click', async (e) => {
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

                        if (response.ok) {
                            const data = await response.json();
                            starImg.src = data.starValue ?
                                '/icons/star.png' :
                                '/icons/star-alt.png';
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                })
            }
        })
    }
    addRightGroupEventListeners();