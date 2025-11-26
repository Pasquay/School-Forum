<nav class="navbar">
    <a href="/home" class="brand">CAROLINK</a>
    <div class="nav-links">
        <a href="/home" class="nav-link {{ $active === 'home' ? 'active' : '' }}">Home</a>
        <a href="/groups" class="nav-link {{ $active === 'groups' ? 'active' : '' }}">Groups</a>
        <a href="/user/{{ Auth::id() }}" class="nav-link {{ $active === 'profile' ? 'active' : '' }}">Profile</a>
        <a class="nav-link inbox-link {{ $active==='inbox' ? 'active' : '' }}" id="inbox">
            <img src="{{ asset('/icons/inbox.png') }}" alt="Inbox" class="inbox-main">
            <img src="{{ asset('/icons/inbox-alt.png') }}" alt="Inbox" class="inbox-alt">
        </a>
        <form action="/logout" method="POST" style="margin: 0">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>

<div class="notification-overlay" id="notification-overlay"></div>

<div class="notification-container" id="notification-container">
    <div class="notifications-header">
        <h2>Notifications</h2>
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="unread">Unread</button>
        </div>
    </div>
    <div class="notification-list">
        <div class="unread-messages" data-section="unread">
            @foreach($unreadMessages as $message)
            @include('components.inbox-message', ['message' => $message])
            @endforeach
        </div>

        <div class="read-messages" data-section="read">
            @foreach($readMessages as $message)
            @include('components.inbox-message', ['message' => $message])
            @endforeach
        </div>
    </div>
</div>


<style>
    .notification-container {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .notifications-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
        background-color: #fff;
    }

    .notifications-header h2 {
        font-size: 20px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .notifications-header .filter-buttons {
        display: flex;
        gap: 10px;
    }

    .notifications-header .filter-buttons button {
        background: none;
        border: none;
        font-size: 14px;
        color: #888;
        cursor: pointer;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .notifications-header .filter-buttons button:hover {
        background-color: #f0f2f5;
    }

    .notifications-header .filter-buttons button.active {
        color: #133c06;
        font-weight: 600;
        background-color: #e8f5e9;
    }

    .notification-list {
        max-height: 550px;
        overflow-y: auto;
    }

    .unread-messages,
    .read-messages {
        display: block;
    }

    .nav-link img {
        width: 20px;
        height: 20px;
        transition: filter 0.4s;
    }

    .nav-link.inbox-link img {
        transition: opacity 0.2s;
        position: relative;
        z-index: 1;
    }

    .nav-link.inbox-link {
        position: relative;
        display: inline-block;
    }

    .nav-link.inbox-link img.inbox-alt {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 2;
        pointer-events: none;
    }

    .nav-link.inbox-link:hover img.inbox-alt {
        opacity: 1;
    }

    .nav-link.inbox-link:hover img.inbox-main {
        opacity: 0;
    }

    .nav-link.inbox-link.active img.inbox-alt {
        opacity: 1;
    }

    .nav-link.inbox-link.active img.inbox-main {
        opacity: 0;
    }
</style>

<!-- Include GSAP Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<script>
    function openInbox() {
        const inboxLink = document.getElementById('inbox');
        const notificationContainer = document.getElementById('notification-container');
        const overlay = document.getElementById('notification-overlay');
        const filterButtons = document.querySelectorAll('.filter-btn');
        let isOpen = false;

        // Set initial state
        gsap.set(notificationContainer, {
            opacity: 0,
            y: -20,
            scale: 0.95,
            display: 'none'
        });
        gsap.set(overlay, {
            opacity: 0,
            display: 'none'
        });

        inboxLink.addEventListener('click', function(event) {
            event.preventDefault();

            if (!isOpen) {
                // Open animation
                notificationContainer.style.display = 'block';
                overlay.style.display = 'block';

                gsap.to(overlay, {
                    opacity: 1,
                    duration: 0.3,
                    ease: 'power2.out'
                });

                gsap.to(notificationContainer, {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 0.4,
                    ease: 'back.out(1.2)',
                    onComplete: () => {
                        notificationContainer.classList.add('show');
                        overlay.classList.add('show');
                    }
                });

                inboxLink.classList.add('active');
                isOpen = true;
            } else {
                // Close animation
                gsap.to(notificationContainer, {
                    opacity: 0,
                    y: -20,
                    scale: 0.95,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => {
                        notificationContainer.style.display = 'none';
                        notificationContainer.classList.remove('show');
                    }
                });

                gsap.to(overlay, {
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => {
                        overlay.style.display = 'none';
                        overlay.classList.remove('show');
                    }
                });

                inboxLink.classList.remove('active');
                isOpen = false;
            }
        });

        document.addEventListener('click', function(event) {
            if (isOpen && !notificationContainer.contains(event.target) && !inboxLink.contains(event.target)) {
                // Close animation
                gsap.to(notificationContainer, {
                    opacity: 0,
                    y: -20,
                    scale: 0.95,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => {
                        notificationContainer.style.display = 'none';
                        notificationContainer.classList.remove('show');
                    }
                });

                gsap.to(overlay, {
                    opacity: 0,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => {
                        overlay.style.display = 'none';
                        overlay.classList.remove('show');
                    }
                });

                inboxLink.classList.remove('active');
                isOpen = false;
            }
        });

        overlay.addEventListener('click', function() {
            // Close animation
            gsap.to(notificationContainer, {
                opacity: 0,
                y: -20,
                scale: 0.95,
                duration: 0.3,
                ease: 'power2.in',
                onComplete: () => {
                    notificationContainer.style.display = 'none';
                    notificationContainer.classList.remove('show');
                }
            });

            gsap.to(overlay, {
                opacity: 0,
                duration: 0.3,
                ease: 'power2.in',
                onComplete: () => {
                    overlay.style.display = 'none';
                    overlay.classList.remove('show');
                }
            });

            inboxLink.classList.remove('active');
            isOpen = false;
        });

        // Filter functionality
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.dataset.filter;

                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Show/hide sections
                const unreadSection = document.querySelector('[data-section="unread"]');
                const readSection = document.querySelector('[data-section="read"]');

                if (filter === 'unread') {
                    unreadSection.style.display = 'block';
                    readSection.style.display = 'none';
                } else {
                    unreadSection.style.display = 'block';
                    readSection.style.display = 'block';
                }
            });
        });
    }

    openInbox();
</script>