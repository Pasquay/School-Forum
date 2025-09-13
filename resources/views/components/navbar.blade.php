<nav class="navbar">
    <a href="/home" class="brand">CAROLINK</a>
    <div class="nav-links">
        <a href="/home" class="nav-link {{ $active === 'home' ? 'active' : '' }}">Home</a>
        <a href="/groups" class="nav-link {{ $active === 'groups' ? 'active' : '' }}">Groups</a>
        <a href="/user/{{ Auth::id() }}" class="nav-link {{ $active === 'profile' ? 'active' : '' }}">Profile</a>
        <form action="/logout" method="POST" style="margin: 0">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>