<nav class="navbar">
    <a href="/home" class="brand">Social Media</a>
    <div class="nav-links">
        <a href="/home" class="nav-link">Home</a>
        <a href="/groups" class="nav-link">Groups</a>
        <a href="/user/{{ Auth::id() }}" class="nav-link" id='profile-nav-link'>Profile</a>
        <form action="/logout" method="POST" style="margin: 0">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</nav>