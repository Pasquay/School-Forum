<nav class="navbar">
    <a href="/home" class="brand">CAROLINK</a>
    <div class="nav-links">
        <a href="/home" class="nav-link {{ $active === 'home' ? 'active' : '' }}">Home</a>
        <a href="/groups" class="nav-link {{ $active === 'groups' ? 'active' : '' }}">Groups</a>
        <a href="/user/{{ Auth::id() }}" class="nav-link {{ $active === 'profile' ? 'active' : '' }}">Profile</a>
        <a href="/inbox" class="nav-link inbox-link {{ $active==='inbox' ? 'active' : '' }}">
            <img src="{{ asset('/icons/inbox.png') }}" alt="Inbox" class="inbox-main">
            <img src="{{ asset('/icons/inbox-alt.png') }}" alt="Inbox" class="inbox-alt">
        </a>
        <form action="/logout" method="POST" style="margin: 0">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>
<style>
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
</style>