   // VARIABLES
    const userID = document.body.dataset.userId;

    const posts = document.querySelectorAll('#posts-column .post');
    const postContainer = document.querySelector('#posts-column');
    const postLoader = document.querySelector('#post-loader');

    const comments = document.querySelectorAll('#comments-column .profile-comment');
    const commentContainer = document.querySelector('#comments-column');
    const commentLoader = document.querySelector('#comment-loader');

    const overviewContainer = document.querySelector('#overview-column');
    const overviewLoader = document.querySelector('#overview-loader');

    const repliesContainer = document.querySelector('#replies-column');
    const repliesLoader = document.querySelector('#replies-loader');

    const deletedOverviewContainer = document.querySelector('#deleted-overview-column');
    const deletedOverviewLoader = document.querySelector('#deleted-overview-loader');

    const deletedPostsContainer = document.querySelector('#deleted-posts-column');
    const deletedPostsLoader = document.querySelector('#deleted-post-loader');

    const deletedCommentsContainer = document.querySelector('#deleted-comments-column');
    const deletedCommentsLoader = document.querySelector('#deleted-comment-loader');

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
    // Profile Edit Button
    const profileEditButton = document.querySelector('.user-info-row-6 button');
    profileEditButton.addEventListener('click', () => {
        window.location.href = `${window.location.origin}/user/${userID}/settings`;
    })
    // Groups
    function addRightGroupEventListeners() {
        // CREATED GROUPS
        const createdGroups = document.querySelectorAll('#groups-created .group-info-minimal');
        // Create Group Button
        const createGroupBtn = document.querySelector('.create-group-button');
        createGroupBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = `/groups/create`;
        })
        createdGroups.forEach(group => {
            // Onclick go to group page
            const groupid = group.dataset.groupid;
            group.addEventListener('click', () => {
                window.location.href = `/group/${groupid}`;
            })
            // Star and unstar
            const starForm = group.querySelector('form');
            const starBtn = starForm.querySelector('.star')
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
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({
                                _token: document.querySelector('meta[name="csrf-token"]').content,
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            starImg.src = data.starValue ?
                                '{{ asset("/icons/star.png") }}' :
                                '{{ asset("/icons/star-alt.png") }}';
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                })
            }
        })
        // MODERATED GROUPS
        const moderatedGroups = document.querySelectorAll('#groups-moderated .group-info-minimal');
        moderatedGroups.forEach(group => {
            // Onclick go to group page
            const groupid = group.dataset.groupid;
            group.addEventListener('click', () => {
                window.location.href = `/group/${groupid}`;
            })
            // Star and unstar
            const starForm = group.querySelector('form');
            const starBtn = starForm.querySelector('.star')
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
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({
                                _token: document.querySelector('meta[name="csrf-token"]').content,
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            starImg.src = data.starValue ?
                                '{{ asset("/icons/star.png") }}' :
                                '{{ asset("/icons/star-alt.png") }}';
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                })
            }
        })
        // JOINED GROUPS
        // const joinedGroups = document.querySelectorAll('#groups-joined .group-info-minimal');
    }
    addRightGroupEventListeners();
    // LEFT SIDE
    // Navbar
    // Variables
    const leftnav = document.querySelector('.left-side .nav');
    const leftsubnav = document.querySelector('.left-side #nav-2');

    const overviewForm = leftnav.querySelector('#overview-form');
    const overviewBtn = leftnav.querySelector('#overview-form button');
    const overviewCol = document.querySelector('.left-side .overview-column');

    const postsForm = leftnav.querySelector('#posts-form');
    const postsBtn = leftnav.querySelector('#posts-form button');
    const postsCol = document.querySelector('.left-side .posts-column');

    const commentsForm = leftnav.querySelector('#comments-form');
    const commentsBtn = leftnav.querySelector('#comments-form button');
    const commentsCol = document.querySelector('.left-side .comments-column');

    const deletedForm = leftnav.querySelector('#deleted-form');
    const deletedBtn = leftnav.querySelector('#deleted-form button');
    const deletedContainer = document.querySelector('.deleted-container');

    const deletedOverviewForm = leftsubnav.querySelector('#deleted-overview-form');
    const deletedOverviewBtn = leftsubnav.querySelector('#deleted-overview-form button');
    const deletedOverviewCol = document.querySelector('.deleted-container .deleted-overview-column');

    const deletedPostsForm = leftsubnav.querySelector('#deleted-posts-form');
    const deletedPostsBtn = leftsubnav.querySelector('#deleted-posts-form button');
    const deletedPostsCol = document.querySelector('.deleted-container .deleted-posts-column');

    const deletedCommentsForm = leftsubnav.querySelector('#deleted-comments-form');
    const deletedCommentsBtn = leftsubnav.querySelector('#deleted-comments-form button');;
    const deletedCommentsCol = document.querySelector('.deleted-container .deleted-comments-column');

    // Overview
    overviewForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!overviewBtn.classList.contains('active')) {
            overviewBtn.classList.add('active');
            overviewCol.style.display = 'flex';
            attachOverviewEventListeners();
        }
        postsBtn.classList.remove('active');
        postsCol.style.display = 'none';
        commentsBtn.classList.remove('active');
        commentsCol.style.display = 'none';
        deletedBtn.classList.remove('active');
        deletedContainer.style.display = 'none';
        leftsubnav.style.display = 'none';
    });
    // Posts
    postsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        overviewBtn.classList.remove('active');
        overviewCol.style.display = 'none';
        if (!postsBtn.classList.contains('active')) {
            postsBtn.classList.add('active');
            postsCol.style.display = 'flex';
            attachPostEventListeners('#posts-column'); // POST
        }
        commentsBtn.classList.remove('active');
        commentsCol.style.display = 'none';
        deletedBtn.classList.remove('active');
        deletedContainer.style.display = 'none';
        leftsubnav.style.display = 'none';
    });
    // Comments
    commentsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        overviewBtn.classList.remove('active');
        overviewCol.style.display = 'none';
        postsBtn.classList.remove('active');
        postsCol.style.display = 'none';
        if (!commentsBtn.classList.contains('active')) {
            commentsBtn.classList.add('active');
            commentsCol.style.display = 'flex';
            attachCommentEventListeners('#comments-column'); // COMMENTS
            attachReplyEventListeners('#comments-column'); // REPLIES
        }
        deletedBtn.classList.remove('active');
        deletedContainer.style.display = 'none';
        leftsubnav.style.display = 'none';
    });
    // Deleted
    deletedForm.addEventListener('submit', (e) => {
        e.preventDefault();
        overviewBtn.classList.remove('active');
        overviewCol.style.display = 'none';
        postsBtn.classList.remove('active');
        postsCol.style.display = 'none';
        commentsBtn.classList.remove('active');
        commentsCol.style.display = 'none';
        if (!deletedBtn.classList.contains('active')) {
            deletedBtn.classList.add('active');
            deletedContainer.style.display = 'flex';
            leftsubnav.style.display = 'flex';
        }
    })
    // Deleted Overview
    deletedOverviewForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (!deletedOverviewBtn.classList.contains('active')) {
            deletedOverviewBtn.classList.add('active');
            deletedOverviewCol.style.display = 'flex';
        }
        deletedPostsBtn.classList.remove('active');
        deletedPostsCol.style.display = 'none';
        deletedCommentsBtn.classList.remove('active');
        deletedCommentsCol.style.display = 'none';
    });
    // Deleted Posts
    deletedPostsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        deletedOverviewBtn.classList.remove('active');
        deletedOverviewCol.style.display = 'none';
        if (!deletedPostsBtn.classList.contains('active')) {
            deletedPostsBtn.classList.add('active');
            deletedPostsCol.style.display = 'flex';
        }
        deletedCommentsBtn.classList.remove('active');
        deletedCommentsCol.style.display = 'none';
    })
    // Deleted Comments
    deletedCommentsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        deletedOverviewBtn.classList.remove('active');
        deletedOverviewCol.style.display = 'none';
        deletedPostsBtn.classList.remove('active');
        deletedPostsCol.style.display = 'none';
        if (!deletedCommentsBtn.classList.contains('active')) {
            deletedCommentsBtn.classList.add('active');
            deletedCommentsCol.style.display = 'flex';
        }
    })
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
    // Deleted Overview
    let deletedOverviewNextPage = 2;
    let deletedOverviewLoading = false;
    // Deleted Posts
    let deletedPostsNextPage = 2;
    let deletedPostsLoading = false;
    // Deleted Comments
    let deletedCommentsNextPage = 2;
    let deletedCommentsLoading = false;
    document.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
            // Overview
            if (overviewBtn.classList.contains('active') && !overviewLoading && overviewNextPage) {
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

                        if (!overviewNextPage) {
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
            if (postsBtn.classList.contains('active') && !postLoading && postNextPage) {
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

                        if (!postNextPage) {
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
            if (commentsBtn.classList.contains('active') && !commentLoading && commentNextPage) {
                commentLoading = true;
                commentLoader.style.display = 'block';
                fetch(`/user/${userID}/comments-and-replies?page=${commentNextPage}`, {
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
                        attachReplyEventListeners('#comments-column');

                        if (!commentNextPage) {
                            document.querySelector('#comment-column-bottom').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error: ', error);
                        commentLoading = false;
                        commentLoader.style.display = 'none';
                    });
            }
            // Deleted Overview
            if (
                deletedOverviewBtn.classList.contains('active') &&
                deletedOverviewCol.style.display === 'flex' &&
                !deletedOverviewLoading &&
                deletedOverviewNextPage
            ) {
                deletedOverviewLoading = true;
                deletedOverviewLoader.style.display = 'block';
                fetch(`/user/${userID}/deleted-overview?page=${deletedOverviewNextPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        deletedOverviewLoader.insertAdjacentHTML('beforebegin', data.html);
                        deletedOverviewNextPage = data.next_page;
                        deletedOverviewLoading = false;
                        deletedOverviewLoader.style.display = 'none';

                        if (!deletedOverviewNextPage) {
                            document.querySelector('#deleted-overview-column-bottom').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error: ', error);
                        deletedOverviewLoading = false;
                        deletedOverviewLoader.style.display = 'none';
                    });
            }
            // Deleted Posts
            if (deletedPostsBtn.classList.contains('active') && !deletedPostsLoading && deletedPostsNextPage) {
                deletedPostsLoading = true;
                deletedPostsLoader.style.display = 'block';
                fetch(`/user/${userID}/deleted-posts?page=${deletedPostsNextPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        deletedPostsLoader.insertAdjacentHTML('beforebegin', data.html);
                        deletedPostsNextPage = data.next_page;
                        deletedPostsLoading = false;
                        deletedPostsLoader.style.display = 'none';

                        if (!deletedPostsNextPage) {
                            document.querySelector('#deleted-post-column-bottom').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error: ', error);
                        deletedPostsLoading = false;
                        deletedPostsLoader.style.display = 'none';
                    });
            }
            // Deleted Comments And Replies
            if (deletedCommentsBtn.classList.contains('active') && !deletedCommentsLoading && deletedCommentsNextPage) {
                deletedCommentsLoading = true;
                deletedCommentsLoader.style.display = 'block';
                fetch(`/user/${userID}/deleted-comments-and-replies?page=${deletedCommentsNextPage}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        deletedCommentsLoader.insertAdjacentHTML('beforebegin', data.html);
                        deletedCommentsNextPage = data.next_page;
                        deletedCommentsLoading = false;
                        deletedCommentsLoader.style.display = 'none';

                        if (!deletedCommentsNextPage) {
                            document.querySelector('#deleted-comment-column-bottom').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error: ', error);
                        deletedCommentsLoading = false;
                        deletedCommentsLoader.style.display = 'none';
                    });
            }
        }
    });
    // Scroll Event Listeners
    // Overview
    function attachOverviewEventListeners() {
        attachPostEventListeners('#overview-column');
        attachCommentEventListeners('#overview-column');
        attachReplyEventListeners('#overview-column');
    }
    // Posts
    function attachPostEventListeners(column = '#posts-column') {
        const posts = document.querySelectorAll(`${column} .post`);

        posts.forEach(post => {
            if (!post.dataset.listenersAttached) {
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
                const voteContainer = post.querySelector('.vote-container');
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
                                "{{ asset('/icons/up-arrow-alt.png') }}" :
                                "{{ asset('/icons/up-arrow.png') }}";

                            const downArrow = downvoteForm.querySelector('img');
                            if (data.voteValue == 1) {
                                downArrow.src = "{{ asset('/icons/down-arrow.png') }}";
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
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
                                "{{ asset('/icons/down-arrow-alt.png') }}" :
                                "{{ asset('/icons/down-arrow.png') }}";

                            const upArrow = upvoteForm.querySelector('img');
                            if (data.voteValue == -1) {
                                upArrow.src = "{{ asset('/icons/up-arrow.png') }}";
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                })
            }
        });
    };
    // Profile-comments
    function attachCommentEventListeners(column = '#comments-column') {
        const comments = document.querySelectorAll(`${column} .profile-comment:not(.profile-reply)`);
        comments.forEach(comment => {
            if (!comment.dataset.listenersAttached) {
                comment.dataset.listenersAttached = 'true';
                // Profile-comment cards linking to comment pages
                const originalPostLinkElement = comment.querySelector('.original-post-link');
                if (!originalPostLinkElement) {
                    return; // Skip this comment if no original post link found
                }

                const isPostDeleted = originalPostLinkElement.classList.contains('deleted');

                // Only add click listener if post is NOT deleted
                if (!isPostDeleted) {
                    const originalPostLink = originalPostLinkElement.href;
                    const originalPostID = originalPostLink.split('/').pop();
                    const commentID = comment.id.split('-').pop();

                    comment.addEventListener('click', () => {
                        window.location.href = `/post/${originalPostID}#comment-${commentID}`;
                    });
                }

                const shareButton = comment.querySelector('.profile-comment-share-button');
                if (shareButton) {
                    shareButton.addEventListener('click', (e) => {
                        e.stopPropagation();
                        if (!isPostDeleted) {
                            const originalPostLink = originalPostLinkElement.href;
                            const originalPostID = originalPostLink.split('/').pop();
                            const commentID = comment.id.split('-').pop();
                            commentUrl = `${window.location.origin}/post/${originalPostID}#comment-${commentID}`;
                            navigator.clipboard.writeText(commentUrl)
                                .then(() => {
                                    shareButton.textContent = 'Copied!';
                                    setTimeout(() => {
                                        shareButton.textContent = 'Share';
                                    }, 1200)
                                });
                        }
                    });
                }
                // Upvote and downvote logic
                const voteContainer = comment.querySelector('.vote-container');
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
                                'Content-Type': 'application/x-www-form-urlencoded',
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
                                "{{ asset('/icons/up-arrow-alt.png') }}" :
                                "{{ asset('/icons/up-arrow.png') }}";

                            if (data.voteValue == 1) {
                                const downArrow = downvoteForm.querySelector('img');
                                downArrow.src = "{{ asset('/icons/down-arrow.png') }}";
                            }
                        }
                    } catch (error) {
                        console.error('Error: ', error);
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
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({
                                _token: document.querySelector('meta[name="csrf-token"]').content,
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            voteCount.textContent = data.voteCount;
                            const downArrow = downvoteForm.querySelector('img');
                            downArrow.src = data.voteValue == -1 ?
                                "{{ asset('/icons/down-arrow-alt.png') }}" :
                                "{{ asset('/icons/down-arrow.png') }}";

                            if (data.voteValue == -1) {
                                const upArrow = upvoteForm.querySelector('img');
                                upArrow.src = "{{ asset('/icons/up-arrow.png') }}";
                            }
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                });
            }
        });
    };
    // Profile-replies
    function attachReplyEventListeners(column = '#comments-column') {
        const replies = document.querySelectorAll(`${column} .profile-reply`);

        replies.forEach(reply => {
            if (!reply.dataset.listenersAttached) {
                reply.dataset.listenersAttached = 'true';
                // Profile-reply cards linking to reply pages
                const originalPostLinkElement = reply.querySelector('.original-post-link');
                if (!originalPostLinkElement) return;

                const isCommentDeleted = reply.querySelector('.original-comment-content em') !== null;

                if (!isCommentDeleted) {
                    const originalPostLink = originalPostLinkElement.href;
                    const originalPostID = originalPostLink.split('/').pop();
                    const commentID = reply.querySelector('.original-comment-content').href.split('-').pop();
                    const replyID = reply.id.split('-').pop();

                    reply.addEventListener('click', () => {
                        window.location.href = `/post/${originalPostID}#reply-${replyID}`;
                    });
                    // Share Button
                    const shareButton = reply.querySelector('.profile-reply-share-button');
                    if (shareButton) {
                        shareButton.addEventListener('click', (e) => {
                            e.stopPropagation();
                            if (originalPostLinkElement) {
                                const replyUrl = `${window.location.origin}/post/${originalPostID}#reply-${replyID}`;
                                navigator.clipboard.writeText(replyUrl)
                                    .then(() => {
                                        shareButton.textContent = 'Copied!';
                                        setTimeout(() => {
                                            shareButton.textContent = 'Share';
                                        }, 1200);
                                    });
                            }
                        });
                    }
                }
                // Upvote and downvote
                const voteContainer = reply.querySelector('.reply-vote-container');
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
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({
                                _token: document.querySelector('meta[name="csrf-token"]').content,
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            voteCount.textContent = data.voteCount;
                            const upArrow = upvoteForm.querySelector('img');
                            upArrow.src = data.voteValue == 1 ?
                                "{{ asset('/icons/up-arrow-alt.png') }}" :
                                "{{ asset('/icons/up-arrow.png') }}";

                            if (data.voteValue == 1) {
                                const downArrow = downvoteForm.querySelector('img');
                                downArrow.src = "{{ asset('/icons/down-arrow.png') }}";
                            }
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                });
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
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            credentials: 'same-origin',
                            body: new URLSearchParams({
                                _token: document.querySelector('meta[name="csrf-token"]').content,
                            }),
                        });

                        if (response.ok) {
                            const data = await response.json();

                            voteCount.textContent = data.voteCount;
                            const downArrow = downvoteForm.querySelector('img');
                            downArrow.src = data.voteValue == -1 ?
                                "{{ asset('/icons/down-arrow-alt.png') }}" :
                                "{{ asset('/icons/down-arrow.png') }}";

                            if (data.voteValue == -1) {
                                const upArrow = upvoteForm.querySelector('img');
                                upArrow.src = "{{ asset('/icons/up-arrow.png') }}";
                            }
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                });
            }
        });
    }

    // First Page Event Listeners
    attachOverviewEventListeners();
    // attachPostEventListeners('#posts-column'); // POST
    // attachCommentEventListeners('#comments-column'); // COMMENTS
    // attachReplyEventListeners('#comments-column'); // REPLIES
    // attachDeletedPostEventListeners('#deleted-posts-column'); // DELETED POSTS