// Modal handling
const createPostBtn = document.getElementById('create-post-btn');
const createPostModal = document.getElementById('create-post-modal');
const modalBackdrop = document.getElementById('modal-backdrop');
const modalClose = document.getElementById('modal-close');

// Auto-dismiss toast messages
const toastMessages = document.querySelectorAll('.success-message, .error-message');
toastMessages.forEach(toast => {
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 300);
    }, 5000);
});

createPostBtn.addEventListener('click', () => {
    createPostModal.style.display = 'block';
    document.body.style.overflow = 'hidden';
});

modalClose.addEventListener('click', () => {
    createPostModal.style.display = 'none';
    document.body.style.overflow = 'auto';
});

modalBackdrop.addEventListener('click', () => {
    createPostModal.style.display = 'none';
    document.body.style.overflow = 'auto';
});

// Search and filter (placeholder functionality)
const searchInput = document.getElementById('search-input');
const filterSelect = document.getElementById('filter-select');
const postsColumn = document.getElementById('posts-column');

let allPosts = [];
let currentFilter = 'all';
let currentSearch = '';

// Collect all posts data on page load
function initializePosts() {
    const postElements = document.querySelectorAll('.post');
    allPosts = Array.from(postElements).map(post => {
        return {
            element: post,
            title: post.querySelector('.post-title')?.textContent.toLowerCase() || '',
            content: post.querySelector('.post-excerpt')?.textContent.toLowerCase() || '',
            author: post.querySelector('.post-author')?.textContent.toLowerCase() || '',
            votes: parseInt(post.querySelector('.vote-count')?.textContent) || 0,
            comments: parseInt(post.querySelector('.comment-count')?.textContent) || 0,
            isPinned: post.querySelector('.post-badge') !== null
        };
    });
}

// Apply search filter
function applySearch() {
    allPosts.forEach(post => {
        const matchesSearch = currentSearch === '' || 
            post.title.includes(currentSearch) || 
            post.content.includes(currentSearch) || 
            post.author.includes(currentSearch);
        
        if (matchesSearch) {
            post.element.style.display = 'flex';
        } else {
            post.element.style.display = 'none';
        }
    });
}

// Apply sorting filter
function applyFilter() {
    const visiblePosts = allPosts.filter(post => post.element.style.display !== 'none');
    
    let sortedPosts = [...visiblePosts];
    
    switch(currentFilter) {
        case 'new':
            // Keep original order (newest first)
            break;
        case 'top':
            sortedPosts.sort((a, b) => b.votes - a.votes);
            break;
        case 'discussed':
            sortedPosts.sort((a, b) => b.comments - a.comments);
            break;
        default:
            // 'all' - keep original order
            break;
    }
    
    // Separate pinned and regular posts
    const pinnedPosts = sortedPosts.filter(p => p.isPinned);
    const regularPosts = sortedPosts.filter(p => !p.isPinned);
    
    // Reorder in DOM: pinned first, then sorted regular posts
    [...pinnedPosts, ...regularPosts].forEach(post => {
        postsColumn.appendChild(post.element);
    });
}

searchInput.addEventListener('input', (e) => {
    currentSearch = e.target.value.toLowerCase().trim();
    applySearch();
});

filterSelect.addEventListener('change', (e) => {
    currentFilter = e.target.value;
    applyFilter();
});

// Initialize on page load
initializePosts();

const createPostForm = document.getElementById('create-post-form');
    const createPostTitle = document.getElementById('create-post-title');
    const createPostContent = document.getElementById('create-post-content');
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
                        
                        // Reinitialize posts array with new posts
                        initializePosts();
                        
                        //attach event listeners again
                        // Post cards link to post pages
                        Array.from(posts).forEach(post => {
                            post.addEventListener('click', () => {
                                window.location.href = `/post/${post.id}`;
                            });
                            // Post share buttons
                            const shareButton = post.querySelector('.post-share-button');
                            if (shareButton) {
                                shareButton.addEventListener('click', (e) => {
                                    e.stopPropagation();
                                    postUrl = `${window.location.origin}/post/${post.id}`;
                                    navigator.clipboard.writeText(postUrl)
                                        .then(() => {
                                            const svg = shareButton.querySelector('svg');
                                            shareButton.innerHTML = 'Copied!';
                                            setTimeout(() => {
                                                shareButton.innerHTML = '';
                                                shareButton.appendChild(svg);
                                                shareButton.appendChild(document.createTextNode('Share'));
                                            }, 1200);
                                        })
                                })
                            }
                        });
                        // Upvote and downvote logic
                        Array.from(posts).forEach(post => {
                            const voteInline = post.querySelector('.vote-inline');
                            if (!voteInline) return;
                            
                            const upvoteForm = voteInline.querySelector('form:first-child');
                            const downvoteForm = voteInline.querySelector('form:last-child');
                            const voteCount = voteInline.querySelector('.vote-count');

                            if (!upvoteForm || !downvoteForm || !voteCount) return;

                            voteInline.addEventListener('click', (e) => {
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

                                    const upButton = upvoteForm.querySelector('button');
                                    const downButton = downvoteForm.querySelector('button');
                                    
                                    upButton.setAttribute('data-voted', data.voteValue == 1 ? 'true' : 'false');
                                    downButton.setAttribute('data-voted', 'false');
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

                                    const upButton = upvoteForm.querySelector('button');
                                    const downButton = downvoteForm.querySelector('button');
                                    
                                    downButton.setAttribute('data-voted', data.voteValue == -1 ? 'true' : 'false');
                                    upButton.setAttribute('data-voted', 'false');
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
    // Post cards link to post pages
    Array.from(posts).forEach(post => {
        post.addEventListener('click', () => {
            window.location.href = `/post/${post.id}`;
        });
        // Post share buttons
        const shareButton = post.querySelector('.post-share-button');
        if (shareButton) {
            shareButton.addEventListener('click', (e) => {
                e.stopPropagation();
                postUrl = `${window.location.origin}/post/${post.id}`;
                navigator.clipboard.writeText(postUrl)
                    .then(() => {
                        const svg = shareButton.querySelector('svg');
                        shareButton.innerHTML = 'Copied!';
                        setTimeout(() => {
                            shareButton.innerHTML = '';
                            shareButton.appendChild(svg);
                            shareButton.appendChild(document.createTextNode('Share'));
                        }, 1200);
                    })
            })
        }
    });
    // Upvote and downvote logic
    Array.from(posts).forEach(post => {
        const voteInline = post.querySelector('.vote-inline');
        if (!voteInline) return;
        
        const upvoteForm = voteInline.querySelector('form:first-child');
        const downvoteForm = voteInline.querySelector('form:last-child');
        const voteCount = voteInline.querySelector('.vote-count');

        if (!upvoteForm || !downvoteForm || !voteCount) return;

        voteInline.addEventListener('click', (e) => {
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

                    const upButton = upvoteForm.querySelector('button');
                    const downButton = downvoteForm.querySelector('button');
                    
                    upButton.setAttribute('data-voted', data.voteValue == 1 ? 'true' : 'false');
                    downButton.setAttribute('data-voted', 'false');
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

                    const upButton = upvoteForm.querySelector('button');
                    const downButton = downvoteForm.querySelector('button');
                    
                    downButton.setAttribute('data-voted', data.voteValue == -1 ? 'true' : 'false');
                    upButton.setAttribute('data-voted', 'false');
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