// Global utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('en-us', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

// Comment collapse/expand functionality
function toggleCommentCollapse(commentId) {
    const comment = document.getElementById(`comment-${commentId}`);
    if (comment) {
        comment.classList.toggle('collapsed');
    }
}

// Make function available globally
window.toggleCommentCollapse = toggleCommentCollapse;

// Toast message handling
document.addEventListener('DOMContentLoaded', () => {
    const successToast = document.querySelector('.success-message');
    const errorToast = document.querySelector('.error-message');

    // Auto-dismiss after 5 seconds
    if (successToast) {
        setTimeout(() => {
            successToast.style.opacity = '0';
            successToast.style.transform = 'translateX(100px)';
            setTimeout(() => successToast.remove(), 300);
        }, 5000);

        // Click to dismiss
        successToast.addEventListener('click', () => {
            successToast.style.opacity = '0';
            successToast.style.transform = 'translateX(100px)';
            setTimeout(() => successToast.remove(), 300);
        });
    }

    if (errorToast) {
        setTimeout(() => {
            errorToast.style.opacity = '0';
            errorToast.style.transform = 'translateX(100px)';
            setTimeout(() => errorToast.remove(), 300);
        }, 5000);

        // Click to dismiss
        errorToast.addEventListener('click', () => {
            errorToast.style.opacity = '0';
            errorToast.style.transform = 'translateX(100px)';
            setTimeout(() => errorToast.remove(), 300);
        });
    }
});

// Initialize toggle buttons function
function initializeToggleButtons() {
    // Comment toggle button
    const commentToggleBtn = document.getElementById('comment-toggle-btn');
    const createCommentForm = document.getElementById('create-comment-form');
    
    if (commentToggleBtn && createCommentForm) {
        commentToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (createCommentForm.style.display === 'none' || !createCommentForm.style.display) {
                createCommentForm.style.display = 'block';
                commentToggleBtn.textContent = 'Cancel';
                const textarea = document.getElementById('create-comment-content');
                if (textarea) {
                    setTimeout(() => textarea.focus(), 50);
                }
            } else {
                createCommentForm.style.display = 'none';
                commentToggleBtn.textContent = 'Comment';
            }
        });
    }

    // Reply toggle buttons
    const replyToggleBtns = document.querySelectorAll('.reply-toggle-btn');
    replyToggleBtns.forEach(btn => {
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const commentId = btn.getAttribute('data-comment-id');
            const createReplyContainer = document.querySelector(`#create-reply-form-${commentId}`);
            const replyContainer = document.querySelector(`#reply-container-${commentId}`);
            
            if (!createReplyContainer || !replyContainer) {
                return;
            }
            
            const isExpanded = replyContainer.getAttribute('data-expanded') === 'true';
            
            if (isExpanded) {
                replyContainer.style.display = 'none';
                createReplyContainer.style.display = 'none';
                replyContainer.setAttribute('data-expanded', 'false');
            } else {
                try {
                    if (!replyContainer.hasChildNodes()) {
                        const response = await fetch(`/comment/${commentId}/replies`, {
                            method: "GET",
                            headers: { 'Accept': 'application/json' },
                            credentials: 'same-origin'
                        });

                        if (response.ok) {
                            const data = await response.json();
                            window.populateReplies(data.replies, commentId);
                        }
                    }
                    replyContainer.style.display = 'flex';
                    createReplyContainer.style.display = 'block';
                    replyContainer.setAttribute('data-expanded', 'true');
                    
                    const replyTextarea = createReplyContainer.querySelector('textarea');
                    if (replyTextarea) {
                        setTimeout(() => replyTextarea.focus(), 50);
                    }
                } catch (error) {
                    console.error('Error loading replies:', error);
                }
            }
        });
    });
}

// Function to populate replies (GLOBAL - defined outside DOMContentLoaded)
window.populateReplies = function(replies, commentId) {
        const replyTemplate = document.querySelector('#reply-template');
        const replyContainer = document.querySelector(`#reply-container-${commentId}`);
        
        Array.from(replies).forEach(reply => {
            const clone = replyTemplate.content.cloneNode(true);

            // clean the data
            const createdAt = formatDate(reply.created_at);
            const updatedAt = formatDate(reply.updated_at);

            // apply the data
            // reply top
            clone.querySelector('.reply').id = `reply-${reply.id}`;
            
            // Add collapse functionality
            const collapseLine = clone.querySelector('.comment-collapse-line');
            collapseLine.onclick = () => {
                const replyElement = document.getElementById(`reply-${reply.id}`);
                if (replyElement) {
                    replyElement.classList.toggle('collapsed');
                }
            };
            
            // Profile picture
            const profilePicContainer = clone.querySelector('.profile-pic-reply');
            if (reply.user.photo) {
                profilePicContainer.style.backgroundImage = `url(/storage/${reply.user.photo})`;
                profilePicContainer.style.backgroundSize = 'cover';
                profilePicContainer.style.backgroundPosition = 'center';
            } else {
                profilePicContainer.textContent = reply.user.name.charAt(0).toUpperCase();
                profilePicContainer.style.background = '#2d4a2b';
                profilePicContainer.style.color = 'white';
                profilePicContainer.style.display = 'flex';
                profilePicContainer.style.alignItems = 'center';
                profilePicContainer.style.justifyContent = 'center';
                profilePicContainer.style.fontWeight = '600';
                profilePicContainer.style.fontSize = '0.8rem';
            }
            
            clone.querySelector('.username-link').href = `/user/${reply.user_id}`;
            clone.querySelector('.username-link').textContent = `@${reply.user.name}`;
            clone.querySelector('p').textContent = ` | ${createdAt}`;
            const editIndicator = clone.querySelector('.edit-indicator');
            if (reply.created_at != reply.updated_at) {
                editIndicator.textContent = `Edited on ${updatedAt}`;
                editIndicator.style = 'inline';
            }
            if (reply.user_id == window.currentUserId) {
                const settingsContainer = clone.querySelector('.reply-settings');
                settingsContainer.innerHTML =
                    `<div class='reply-settings-container'>
                <button class='settings-button' id='reply-settings-button-${reply.id}'>
                    <img src='/icons/dots.png' alt='Settings' id='reply-dots-icon-${reply.id}'>
                </button>
                <div class='dropdown-menu' id='reply-settings-dropdown-menu-${reply.id}'>
                    <button class='dropdown-item' id='edit-reply-button-${reply.id}'>Edit</button>
                    <button class='dropdown-item' id='delete-reply-button-${reply.id}'>Delete</button>
                </div>
            </div>`;
            }
            clone.querySelector('.reply-content p').textContent = reply.content;
            // reply bottom
            const cloneVote = clone.querySelector('.reply-bottom .reply-vote-container');
            const replyVoteCount = cloneVote.querySelector('p');
            const replyUpvoteForm = cloneVote.querySelector('form:first-child');
            const replyDownvoteForm = cloneVote.querySelector('form:last-child');
            const replyUpArrow = cloneVote.querySelector('form:first-child img');
            const replyDownArrow = cloneVote.querySelector('form:last-child img');
            replyUpvoteForm.action = `/reply/upvote/${reply.id}`;
            replyUpArrow.src = (reply.userVote == 1) ?
                "/icons/up-arrow-alt.png" :
                "/icons/up-arrow.png";
            replyVoteCount.textContent = reply.votes;
            replyDownvoteForm.action = `/reply/downvote/${reply.id}`;
            replyDownArrow.src = (reply.userVote == -1) ?
                "/icons/down-arrow-alt.png" :
                "/icons/down-arrow.png";
        const replyShareButton = clone.querySelector('.share-button');
        replyShareButton.id = `reply-share-button-${reply.id}`;

        // reply edit form
        const replyEditForm = clone.querySelector('.reply-edit-form form');
        const replyEditInput = replyEditForm.querySelector('textarea');
        replyEditForm.action = `/post/${window.postId}/edit-reply/${reply.id}`;
        replyEditInput.value = reply.content;
        replyEditInput.name = `edit-reply-content-${reply.id}`;
        replyEditInput.id = `edit-reply-content-${reply.id}`;
        // reply delete form
        const replyDeleteText = clone.querySelector('.reply-delete-form p');
        const replyDeleteForm = clone.querySelector('.reply-delete-form form');
        replyDeleteText.textContent = reply.content;
        replyDeleteForm.action = `/post/${window.postId}/delete-reply/${reply.id}`;
        
        // Append to container
        replyContainer.appendChild(clone);
    });
    
    // After appending, set up event listeners for the newly added replies
    setupReplyEventListeners(commentId);
}

// Function to set up event listeners for replies (GLOBAL - defined outside DOMContentLoaded)
window.setupReplyEventListeners = function(commentId) {
        const replyContainer = document.querySelector(`#reply-container-${commentId}`);
        const replies = replyContainer.querySelectorAll('.reply');
        
        replies.forEach(reply => {
            const replyId = reply.id.split('-')[1];
            
            // Vote handlers
            const replyVoteContainer = reply.querySelector('.reply-vote-container');
            const replyUpvoteForm = replyVoteContainer.querySelector('form:first-child');
            const replyDownvoteForm = replyVoteContainer.querySelector('form:last-child');
            const replyVoteCount = replyVoteContainer.querySelector('p');
            
            // Already set up in populateReplies, but add actual vote functionality
            replyUpvoteForm.addEventListener('submit', async (e) => {
                e.stopPropagation();
                e.preventDefault();

                try {
                    const response = await fetch(replyUpvoteForm.action, {
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
                        replyVoteCount.textContent = data.voteCount;
                        const upArrow = replyUpvoteForm.querySelector('img');
                        upArrow.src = data.voteValue == 1 ?
                            "/icons/up-arrow-alt.png" :
                            "/icons/up-arrow.png";

                        if (data.voteValue == 1) {
                            const downArrow = replyDownvoteForm.querySelector('img');
                            downArrow.src = "/icons/down-arrow.png";
                        }
                    }
                } catch (error) {
                    console.error('Error: ', error);
                }
            });

            replyDownvoteForm.addEventListener('submit', async (e) => {
                e.stopPropagation();
                e.preventDefault();

                try {
                    const response = await fetch(replyDownvoteForm.action, {
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
                        replyVoteCount.textContent = data.voteCount;
                        const downArrow = replyDownvoteForm.querySelector('img');
                        downArrow.src = data.voteValue == -1 ?
                            "/icons/down-arrow-alt.png" :
                            "/icons/down-arrow.png";

                        if (data.voteValue == -1) {
                            const upArrow = replyUpvoteForm.querySelector('img');
                            upArrow.src = "/icons/up-arrow.png";
                        }
                    }
                } catch (error) {
                    console.error('Error: ', error);
                }
            });
            
            // Share button
            const replyShareButton = reply.querySelector('.share-button');
            if (replyShareButton) {
                replyShareButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const replyUrl = `${window.location.origin}/post/${window.postId}#reply-${replyId}`;
                    navigator.clipboard.writeText(replyUrl);
                    replyShareButton.textContent = 'Copied';
                    setTimeout(() => {
                        replyShareButton.textContent = 'Share';
                    }, 1200);
                });
            }
        });
    }

    // Post burger menu
    document.addEventListener('DOMContentLoaded', () => {
        // Initialize toggle buttons after DOM is ready
        initializeToggleButtons();
        
        // Auto-load replies for comments that have replies
        document.querySelectorAll('.reply-container[data-has-replies="true"]').forEach(replyContainer => {
            const commentId = replyContainer.id.replace('reply-container-', '');
            const createReplyContainer = document.querySelector(`#create-reply-form-${commentId}`);
            
            // Load replies automatically
            fetch(`/comment/${commentId}/replies`, {
                method: "GET",
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.replies && data.replies.length > 0) {
                    window.populateReplies(data.replies, commentId);
                    replyContainer.style.display = 'flex';
                    replyContainer.setAttribute('data-expanded', 'true');
                }
            })
            .catch(error => console.error('Error loading replies:', error));
        });
        
        // Loading in replies
        const urlHash = window.location.hash;

        if (urlHash.startsWith('#reply-')) {
            const replyId = urlHash.replace('#reply-', '');

            // Since replies aren't loaded yet, we need to load ALL replies and then find the target
            // Get all comments on the page
            const allComments = document.querySelectorAll('.comment');
            let targetCommentFound = false;

            // For each comment, try to load its replies
            allComments.forEach((comment, index) => {
                const commentId = comment.id.split('-')[1];
                const repliesForm = comment.querySelector(`#replies-form-${commentId}`);

                if (repliesForm) {
                    // Add a delay for each comment to avoid overwhelming the server
                    setTimeout(() => {
                        repliesForm.dispatchEvent(new Event('submit'));
                    }, index * 200); // 200ms delay between each request
                }
            });

            // Check for the target reply multiple times with increasing delays
            const checkForTargetReply = (attempt = 1) => {
                const targetReply = document.querySelector(`#reply-${replyId}`);

                if (targetReply) {
                    targetReply.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    // Blue border highlight effect
                    targetReply.style.border = '2px solid #4a90e2';
                    targetReply.style.borderRadius = '8px';
                    setTimeout(() => {
                        targetReply.style.border = '';
                        targetReply.style.borderRadius = '6px'; // Reset to original border radius
                    }, 3000);
                    targetCommentFound = true;
                } else if (attempt < 5) {
                    // Try again with longer delay
                    setTimeout(() => checkForTargetReply(attempt + 1), 1000 * attempt);
                }
            };

            // Start checking after initial delay
            setTimeout(() => checkForTargetReply(), 2000);
        }

        // Post share buttons
        const postShareButton = document.querySelector(`#post-share-button-${window.postId}`);
        if (postShareButton) {
            postShareButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                postUrl = `${window.location.origin}/post/${window.postId}`;
                navigator.clipboard.writeText(postUrl);
                postShareButton.textContent = 'Copied!';
                setTimeout(() => {
                    postShareButton.textContent = 'Share';
                }, 1200);
            });
        }
        // Post Settings button functions
        const settingsButton = document.getElementById('settings-button');
        const dotsIcon = document.getElementById('dots-icon');
        const originalSrc = dotsIcon.src;
        const hoverSrc = originalSrc.replace('dots.png', 'dots-alt.png');
        const settingsDropdown = document.getElementById('settings-dropdown-menu');

        const pinPostHomeButton = document.getElementById('pin-post-home-toggle-button');
        const pinPostHomeCancel = document.getElementById('pin-home-cancel-button');
        const pinPostHomeConfirm = document.querySelector('.pin-home-toggle-confirm-button');
        const pinPostButton = document.getElementById('pin-post-toggle-button');
        const pinPostCancel = document.getElementById('pin-cancel-button');
        const pinPostConfirm = document.querySelector('.pin-toggle-confirm-button');
        const editPostButton = document.getElementById('edit-post-button');
        const editPostCancel = document.getElementById('edit-post-cancel');
        const deletePostButton = document.getElementById('delete-post-button');
        const deletePostCancel = document.getElementById('delete-post-cancel');

        const postContentContainer = document.getElementById('post-content-container');
        const pinPostHomeForm = document.getElementById('pin-home-post-form');
        const pinPostForm = document.getElementById('pin-post-form');
        const editPostForm = document.getElementById('edit-post-form');
        const deletePostForm = document.getElementById('delete-post-form');

        settingsButton.addEventListener('mouseenter', () => dotsIcon.src = hoverSrc);
        settingsButton.addEventListener('mouseleave', () => dotsIcon.src = originalSrc);
        settingsButton.addEventListener('click', (e) => {
            e.stopPropagation();
            settingsDropdown.classList.toggle('show-dropdown');
        });
        document.addEventListener('click', () => {
            if (settingsDropdown.classList.contains('show-dropdown')) {
                settingsDropdown.classList.remove('show-dropdown');
            }
        });

        // Pin post home
        if (pinPostHomeButton) {
            pinPostHomeButton.addEventListener('click', (e) => {
                e.stopPropagation();
                postContentContainer.style.display = 'none';
                pinPostHomeForm.style.display = 'block'
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'none';
                if (settingsDropdown.classList.contains('show-dropdown')) {
                    settingsDropdown.classList.remove('show-dropdown');
                }

                pinPostHomeConfirm.textContent = (parseInt('{{ $post->isPinnedHome }}')) ?
                    'Unpin From Home' : 'Pin To Home';
            })
            pinPostHomeCancel.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                postContentContainer.style.display = 'block';
                pinPostHomeForm.style.display = 'none';
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'none';
            });
        }

        // Pin post
        if (pinPostButton) {
            pinPostButton.addEventListener('click', (e) => {
                e.stopPropagation();
                postContentContainer.style.display = 'none';
                pinPostHomeForm.style.display = 'none'
                pinPostForm.style.display = 'block';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'none';
                if (settingsDropdown.classList.contains('show-dropdown')) {
                    settingsDropdown.classList.remove('show-dropdown');
                }
                const groupId = parseInt('{{ $post->group->id }}');
                if (groupId != 1) pinPostConfirm.textContent = (parseInt('{{ $post->isPinned }}')) ? 'Unpin Post' : 'Pin Post';
                else pinPostConfirm.textContent = (parseInt('{{ $post->isPinnedHome }}')) ? 'Unpin From Home' : 'Pin To Home';
            });
            pinPostCancel.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                postContentContainer.style.display = 'block';
                pinPostHomeForm.style.display = 'none';
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'none';
            });
        }

        // Edit post
        if (editPostButton) {
            editPostButton.addEventListener('click', (e) => {
                e.stopPropagation();
                postContentContainer.style.display = 'none';
                pinPostHomeForm.style.display = 'none'
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'block';
                deletePostForm.style.display = 'none';
                if (settingsDropdown.classList.contains('show-dropdown')) {
                    settingsDropdown.classList.remove('show-dropdown');
                }
            });
            editPostCancel.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                postContentContainer.style.display = 'block';
                pinPostHomeForm.style.display = 'none';
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'none';
            });
        }

        // Delete post
        if (deletePostButton) {
            deletePostButton.addEventListener('click', (e) => {
                e.stopPropagation();
                postContentContainer.style.display = 'none';
                pinPostHomeForm.style.display = 'none'
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'block';
                if (settingsDropdown.classList.contains('show-dropdown')) {
                    settingsDropdown.classList.remove('show-dropdown');
                }
            });
            deletePostCancel.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                postContentContainer.style.display = 'block';
                pinPostHomeForm.style.display = 'none';
                pinPostForm.style.display = 'none';
                editPostForm.style.display = 'none';
                deletePostForm.style.display = 'none';
            });
        }

    });

    document.addEventListener('DOMContentLoaded', () => {
        // Comment Settings button functions
        const comments = document.getElementsByClassName('comment');
        Array.from(comments).forEach(comment => {
            const commentSettingsButton = comment.querySelector('.settings-button');
            if (commentSettingsButton) {
                const commentId = commentSettingsButton.id.split('-')[2];
                const commentDotsIcon = commentSettingsButton.querySelector('img');
                const originalDotsIconSrc = commentDotsIcon.src;
                const hoverDotsIconSrc = originalDotsIconSrc.replace('dots.png', 'dots-alt.png');
                const commentDropdown = comment.querySelector('.dropdown-menu');

                // Settings icon
                commentSettingsButton.addEventListener('mouseenter', () => {
                    commentDotsIcon.src = hoverDotsIconSrc;
                });
                commentSettingsButton.addEventListener('mouseleave', () => {
                    commentDotsIcon.src = originalDotsIconSrc;
                });

                // Settings dropdown
                commentSettingsButton.addEventListener('click', (e) => {
                    e.stopPropagation();
                    commentDropdown.classList.toggle('show-dropdown');
                })
                document.addEventListener('click', () => {
                    if (commentDropdown.classList.contains('show-dropdown')) {
                        commentDropdown.classList.remove('show-dropdown');
                    }
                })

                // Settings edit
                const commentContent = comment.querySelector('.comment-content');
                const commentBottom = comment.querySelector('.comment-bottom');
                const commentEditFormContainer = comment.querySelector('.comment-edit-form');
                const commentDeleteFormContainer = comment.querySelector('.comment-delete-form');

                const editButton = commentDropdown.querySelector(`#edit-comment-button-${commentId}`);
                editButton.addEventListener('click', () => {
                    commentContent.style.display = 'none';
                    commentBottom.style.display = 'none';
                    commentEditFormContainer.style.display = 'flex';
                    commentDeleteFormContainer.style.display = 'none';
                });
                const commentEditForm = commentEditFormContainer.querySelector('form');
                const editCancelButton = commentEditForm.querySelector('.edit-cancel-button');
                editCancelButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    commentContent.style.display = 'block';
                    commentBottom.style.display = 'flex';
                    commentEditFormContainer.style.display = 'none';
                    commentDeleteFormContainer.style.display = 'none';
                })

                // Settings delete
                const deleteButton = commentDropdown.querySelector(`#delete-comment-button-${commentId}`);
                deleteButton.addEventListener('click', () => {
                    commentContent.style.display = 'none';
                    commentBottom.style.display = 'none';
                    commentEditFormContainer.style.display = 'none';
                    commentDeleteFormContainer.style.display = 'flex';
                });
                const commentDeleteForm = commentDeleteFormContainer.querySelector('form');
                const deleteCancelButton = commentDeleteForm.querySelector('.delete-cancel-button');
                deleteCancelButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    commentContent.style.display = 'block';
                    commentBottom.style.display = 'flex';
                    commentEditFormContainer.style.display = 'none';
                    commentDeleteFormContainer.style.display = 'none';
                });
            }
        });
        // Create Comment Form
        const createCommentContainer = document.getElementById("create-comment-form");
        const createCommentForm = createCommentContainer.querySelector('form');
        const createCommentInput = createCommentContainer.querySelector('textarea');

        createCommentInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                createCommentForm.submit();
            }
        })
        // Post Voting
        const post = document.querySelector(`#post-${window.postId}`);
        if (post) {
            const postVoteContainer = post.querySelector("#vote-container");
            const postUpvoteForm = postVoteContainer.querySelector("form:first-child");
            const postDownvoteForm = postVoteContainer.querySelector("form:last-child");
            const upArrow = postUpvoteForm.querySelector('img');
            const downArrow = postDownvoteForm.querySelector('img');
            const postVoteCount = postVoteContainer.querySelector('p')

            // UPVOTE 
            postUpvoteForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const response = await fetch(postUpvoteForm.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        credentials: "same-origin",
                        body: new URLSearchParams({
                            _token: document.querySelector('meta[name="csrf-token"]').content
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();

                        postVoteCount.textContent = data.voteCount;

                        upArrow.src = (data.voteValue == 1) ?
                            "/icons/up-arrow-alt.png" :
                            "/icons/up-arrow.png";

                        if (data.voteValue == 1) {
                            downArrow.src = "/icons/down-arrow.png";
                        }
                    }
                } catch (error) {
                    console.error('Error: ', error);
                }
            });
            
            // DOWNVOTE
            postDownvoteForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const response = await fetch(postDownvoteForm.action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        credentials: "same-origin",
                        body: new URLSearchParams({
                            _token: document.querySelector('meta[name="csrf-token"]').content,
                        })
                    });

                    if (response.ok) {
                        const data = await response.json();

                        postVoteCount.textContent = data.voteCount;

                        downArrow.src = (data.voteValue == -1) ?
                            "/icons/down-arrow-alt.png" :
                            "/icons/down-arrow.png";

                        if (data.voteValue == -1) {
                            upArrow.src = "/icons/up-arrow.png";
                        }
                    }
                } catch (error) {
                    console.error("Error: ", error);
                }
            });
        }

        // Group Links
        const groupLinks = document.querySelectorAll('.group-info-minimal');
        groupLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // Don't redirect if clicking the star button
                if (e.target.closest('.star') || e.target.closest('form')) {
                    return;
                }
                const groupId = link.getAttribute('data-groupid');
                if (groupId) {
                    window.location.href = `/group/${groupId}`;
                }
            });
        });

        // Comment Voting
        // const comments = document.getElementsByClassName('comment');
        Array.from(comments).forEach(comment => {
            const commentId = comment.id.split('-')[1];
            const commentVoteContainer = comment.querySelector(`#vote-container-${commentId}`);
            const commentUpvoteForm = commentVoteContainer.querySelector('form:first-child');
            const commentDownvoteForm = commentVoteContainer.querySelector('form:last-child');
            const upArrow = commentUpvoteForm.querySelector('img');
            const downArrow = commentDownvoteForm.querySelector('img');
            const commentVoteCount = commentVoteContainer.querySelector('p');

            // UPVOTE
            commentUpvoteForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const response = await fetch(commentUpvoteForm.action, {
                        method: "POST",
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

                        commentVoteCount.textContent = data.voteCount;

                        upArrow.src = (data.voteValue == 1) ?
                            "/icons/up-arrow-alt.png" :
                            "/icons/up-arrow.png";

                        if (data.voteValue == 1) {
                            downArrow.src = "/icons/down-arrow.png";
                        }
                    }
                } catch (error) {
                    console.error("Error: ", error);
                }
            });
            // DOWNVOTE
            commentDownvoteForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                try {
                    const response = await fetch(commentDownvoteForm.action, {
                        method: "POST",
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

                        commentVoteCount.textContent = data.voteCount;

                        downArrow.src = (data.voteValue == -1) ?
                            "/icons/down-arrow-alt.png" :
                            "/icons/down-arrow.png";

                        if (data.voteValue == -1) {
                            upArrow.src = "/icons/up-arrow.png";
                        }
                    }
                } catch (error) {
                    console.error("Error: ", error);
                }
            })

            const commentShareButton = comment.querySelector('.share-button');
            if (commentShareButton) {
                commentShareButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const commentUrl = `${window.location.origin}/post/${window.postId}#comment-${commentId}`;
                    navigator.clipboard.writeText(commentUrl);
                    commentShareButton.textContent = 'Copied';
                    setTimeout(() => {
                        commentShareButton.textContent = 'Share';
                    }, 1200);
                });
            }

            // Reply Button (old code - kept for backwards compatibility)
            const repliesForm = comment.querySelector(`#replies-form-${commentId}`);
            if (repliesForm) {
                const createReplyContainer = document.querySelector(`#create-reply-form-${commentId}`);
                const createReplyForm = createReplyContainer.querySelector('form');
                const replyTemplate = document.querySelector('#reply-template');
                const replyContainer = document.querySelector(`#reply-container-${commentId}`);
                // Replies Button
                repliesForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                e.stopPropagation();

                if (replyContainer.getAttribute('data-expanded') === 'true') {
                    replyContainer.style.display = 'none';
                    createReplyContainer.style.display = 'none';
                    replyContainer.setAttribute('data-expanded', 'false');
                } else {
                    try {
                        if (!replyContainer.hasChildNodes()) {
                            const response = await fetch(repliesForm.action, {
                                method: "GET",
                                headers: {
                                    'Accept': 'application/json'
                                },
                                credentials: 'same-origin'
                            });

                            if (response.ok) {
                                const data = await response.json();
                                Array.from(data.replies).forEach(reply => {
                                    const clone = replyTemplate.content.cloneNode(true);

                                    // clean the data
                                    const createdAt = formatDate(reply.created_at);
                                    const updatedAt = formatDate(reply.updated_at);

                                    // apply the data
                                    // reply top
                                    clone.querySelector('.reply').id = `reply-${reply.id}`;
                                    
                                    // Add collapse functionality
                                    const collapseLine = clone.querySelector('.comment-collapse-line');
                                    collapseLine.onclick = () => {
                                        const replyElement = document.getElementById(`reply-${reply.id}`);
                                        if (replyElement) {
                                            replyElement.classList.toggle('collapsed');
                                        }
                                    };
                                    
                                    // Profile picture
                                    const profilePicContainer = clone.querySelector('.profile-pic-reply');
                                    if (reply.user.photo) {
                                        profilePicContainer.style.backgroundImage = `url(/storage/${reply.user.photo})`;
                                        profilePicContainer.style.backgroundSize = 'cover';
                                        profilePicContainer.style.backgroundPosition = 'center';
                                    } else {
                                        profilePicContainer.textContent = reply.user.name.charAt(0).toUpperCase();
                                        profilePicContainer.style.background = '#2d4a2b';
                                        profilePicContainer.style.color = 'white';
                                        profilePicContainer.style.display = 'flex';
                                        profilePicContainer.style.alignItems = 'center';
                                        profilePicContainer.style.justifyContent = 'center';
                                        profilePicContainer.style.fontWeight = '600';
                                        profilePicContainer.style.fontSize = '0.8rem';
                                    }
                                    
                                    clone.querySelector('.username-link').href = `/user/${reply.user_id}`;
                                    clone.querySelector('.username-link').textContent = `@${reply.user.name}`;
                                    clone.querySelector('p').textContent = ` | ${createdAt}`;
                                    const editIndicator = clone.querySelector('.edit-indicator');
                                    if (reply.created_at != reply.updated_at) {
                                        editIndicator.textContent = `Edited on ${updatedAt}`;
                                        editIndicator.style = 'inline';
                                    }
                                    if (reply.user_id == window.currentUserId) {
                                        const settingsContainer = clone.querySelector('.reply-settings');
                                        settingsContainer.innerHTML =
                                            `<div class='reply-settings-container'>
                                        <button class='settings-button' id='reply-settings-button-${reply.id}'>
                                            <img src='/icons/dots.png' alt='Settings' id='reply-dots-icon-${reply.id}'>
                                        </button>
                                        <div class='dropdown-menu' id='reply-settings-dropdown-menu-${reply.id}'>
                                            <button class='dropdown-item' id='edit-reply-button-${reply.id}'>Edit</button>
                                            <button class='dropdown-item' id='delete-reply-button-${reply.id}'>Delete</button>
                                        </div>
                                    </div>`;
                                    }
                                    clone.querySelector('.reply-content p').textContent = reply.content;
                                    // reply bottom
                                    const cloneVote = clone.querySelector('.reply-bottom .reply-vote-container');
                                    const replyVoteCount = cloneVote.querySelector('p');
                                    const replyUpvoteForm = cloneVote.querySelector('form:first-child');
                                    const replyDownvoteForm = cloneVote.querySelector('form:last-child');
                                    const replyUpArrow = cloneVote.querySelector('form:first-child img');
                                    const replyDownArrow = cloneVote.querySelector('form:last-child img');
                                    replyUpvoteForm.action = `/reply/upvote/${reply.id}`;
                                    replyUpArrow.src = (reply.userVote == 1) ?
                                        "/icons/up-arrow-alt.png" :
                                        "/icons/up-arrow.png";
                                    replyVoteCount.textContent = reply.votes;
                                    replyDownvoteForm.action = `/reply/downvote/${reply.id}`;
                                    replyDownArrow.src = (reply.userVote == -1) ?
                                        "/icons/down-arrow-alt.png" :
                                        "/icons/down-arrow.png";
                                    const replyShareButton = clone.querySelector('.share-button');
                                    replyShareButton.id = `reply-share-button-${reply.id}`;



                                    // reply edit form
                                    const replyEditForm = clone.querySelector('.reply-edit-form form');
                                    const replyEditInput = replyEditForm.querySelector('textarea');
                                    replyEditForm.action = `/post/{{ $post->id }}/edit-reply/${reply.id}`;
                                    replyEditInput.value = reply.content;
                                    replyEditInput.name = `edit-reply-content-${reply.id}`;
                                    replyEditInput.id = `edit-reply-content-${reply.id}`;
                                    // reply delete form
                                    const replyDeleteText = clone.querySelector('.reply-delete-form p');
                                    const replyDeleteForm = clone.querySelector('.reply-delete-form form');
                                    replyDeleteText.textContent = reply.content;
                                    replyDeleteForm.action = `/post/{{ $post->id }}/delete-reply/${reply.id}`;
                                    // Reply Voting
                                    // UPVOTING
                                    replyUpvoteForm.addEventListener('submit', async (e) => {
                                        e.stopPropagation();
                                        e.preventDefault();

                                        try {
                                            const response = await fetch(replyUpvoteForm.action, {
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

                                                replyVoteCount.textContent = data.voteCount;

                                                replyUpArrow.src = (data.voteValue == 1) ?
                                                    "/icons/up-arrow-alt.png" :
                                                    "/icons/up-arrow.png";

                                                if (data.voteValue == 1) {
                                                    replyDownArrow.src = "/icons/down-arrow.png";
                                                }
                                            }
                                        } catch (error) {
                                            console.error("Error: ", error);
                                        }
                                    });

                                    // DOWNVOTING
                                    replyDownvoteForm.addEventListener('submit', async (e) => {
                                        e.stopPropagation();
                                        e.preventDefault();

                                        try {
                                            const response = await fetch(replyDownvoteForm.action, {
                                                method: "POST",
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

                                                replyVoteCount.textContent = data.voteCount;

                                                replyDownArrow.src = (data.voteValue == -1) ?
                                                    "/icons/down-arrow-alt.png" :
                                                    "/icons/down-arrow.png";

                                                if (data.voteValue == -1) {
                                                    replyUpArrow.src = "/icons/up-arrow.png";
                                                }
                                            }
                                        } catch (error) {
                                            console.error("Error: ", error);
                                        }
                                    });
                                    // Reply Share Button
                                    replyShareButton.addEventListener('click', () => {
                                        replyUrl = `${window.location.origin}/post/{{ $post->id }}#reply-${reply.id}`;
                                        navigator.clipboard.writeText(replyUrl);
                                        replyShareButton.textContent = 'Copied!';
                                        setTimeout(() => {
                                            replyShareButton.textContent = 'Share';
                                        }, 1200);
                                    });
                                    // Appending
                                    replyContainer.appendChild(clone);
                                    // Reply Settings
                                    if (reply.user_id == window.currentUserId) {
                                        const clonedReply = replyContainer.querySelector('.reply:last-child');
                                        const clonedSettings = clonedReply.querySelector('.reply-settings-container');
                                        const clonedContent = clonedReply.querySelector('.reply-content');
                                        const clonedBottom = clonedReply.querySelector('.reply-bottom');
                                        const clonedEditContainer = clonedReply.querySelector('.reply-edit-form');
                                        const clonedDeleteContainer = clonedReply.querySelector('.reply-delete-form')
                                        // Reply Settings Dropdown
                                        // Settings Button
                                        const clonedSettingsButton = clonedSettings.querySelector('.settings-button');
                                        const clonedSettingsButtonImg = clonedSettings.querySelector('.settings-button img');
                                        const clonedSettingsDropdown = clonedSettings.querySelector('.dropdown-menu');
                                        clonedSettingsButton.addEventListener('mouseenter', () => {
                                            clonedSettingsButtonImg.src = '/icons/dots-alt.png';
                                        });
                                        clonedSettingsButton.addEventListener('mouseleave', () => {
                                            clonedSettingsButtonImg.src = '/icons/dots.png';
                                        });
                                        clonedSettingsButton.addEventListener('click', (e) => {
                                            e.stopPropagation();
                                            clonedSettingsDropdown.classList.toggle('show-dropdown');
                                        });
                                        document.addEventListener('click', (e) => {
                                            if (clonedSettingsDropdown.classList.contains('show-dropdown')) {
                                                clonedSettingsDropdown.classList.remove('show-dropdown');
                                            }
                                        })
                                        // Edit Button
                                        const clonedSettingsEdit = clonedSettingsDropdown.querySelector(`#edit-reply-button-${reply.id}`);
                                        clonedSettingsEdit.addEventListener('click', () => {
                                            clonedContent.style.display = 'none';
                                            clonedBottom.style.display = 'none';
                                            clonedEditContainer.style.display = 'flex';
                                            clonedDeleteContainer.style.display = 'none';
                                        });
                                        const clonedEditCancel = clonedEditContainer.querySelector('.edit-cancel-button');
                                        clonedEditCancel.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            e.stopPropagation();

                                            clonedContent.style.display = 'flex';
                                            clonedBottom.style.display = 'flex';
                                            clonedEditContainer.style.display = 'none';
                                            clonedDeleteContainer.style.display = 'none';
                                        })
                                        // Delete Button
                                        const clonedSettingsDelete = clonedSettingsDropdown.querySelector(`#delete-reply-button-${reply.id}`);
                                        clonedSettingsDelete.addEventListener('click', () => {
                                            clonedContent.style.display = 'none';
                                            clonedBottom.style.display = 'none';
                                            clonedEditContainer.style.display = 'none';
                                            clonedDeleteContainer.style.display = 'flex';
                                        });
                                        const clonedDeleteCancel = clonedDeleteContainer.querySelector('.delete-cancel-button');
                                        clonedDeleteCancel.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            e.stopPropagation();

                                            clonedContent.style.display = 'flex';
                                            clonedBottom.style.display = 'flex';
                                            clonedEditContainer.style.display = 'none';
                                            clonedDeleteContainer.style.display = 'none';
                                        })
                                    }
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                    replyContainer.style.display = 'block';
                    createReplyContainer.style.display = 'block';
                    replyContainer.setAttribute('data-expanded', 'true');
                }
            });
            // Create Reply Form
            createReplyForm.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    createReplyForm.submit();
                }
            })
            }
        })

    })

     // Set global variables from Blade
    window.currentUserId = {
        {
            Auth::id() ?? 'null'
        }
    };
    window.postId = {
        {
            $post - > id
        }
    };
    window.isPinnedHome = {
        {
            $post - > isPinnedHome ? 1 : 0
        }
    };
    window.isPinned = {
        {
            $post - > isPinned ? 1 : 0
        }
    };
    window.groupId = {
        {
            $post - > group - > id
        }
    };


