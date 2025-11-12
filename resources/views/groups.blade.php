<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Groups</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groups.css') }}">
</head>

<body>
    @include('components.navbar', ['active' => 'groups'])
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <div class="search-section">
                <input type="text" placeholder="Search groups..." id="group-search">
            </div>

            <div class="nav">
                <button type="button" class="active first" data-sort='members' id='most-members-btn'>Most Members</button>
                <button type="button" data-sort='new' id='new-btn'>New</button>
                <div class="dropdown-wrapper">
                    <button type="button" class="dropdown-toggle" data-sort="active" id="most-active-btn">
                        Most Active
                        <span style="margin-left: 0.5em;">▼</span>
                    </button>
                    <div class="dropdown-menu" id="active-dropdown">
                        <button type="button" data-time="today">Today</button>
                        <button type="button" data-time="week">This Week</button>
                        <button type="button" data-time="month">This Month</button>
                        <button type="button" data-time="year">This Year</button>
                        <button type="button" data-time="all">All Time</button>
                    </div>
                </div>
                <div style="margin-left:auto;">
                    <x-toggle-switch
                        name='show_joined'
                        id='show_joined'
                        value='1'
                        :checked='true'
                        label='Show Joined' />
                </div>
            </div>

            <div class="groups-list">
                @if($groups->count() > 0)
                @foreach($groups as $group)
                @include('components.group-info', ['group' => $group])
                @endforeach
                @endif
                <p class="empty">No groups yet...</p>
            </div>
        </div>

        <div class="right-side">
            <div class="user-info" id="user-info">
                <button class="manage-group-button">Manage Groups</button>
                <div class="groups-created">
                    <div class="section-header">
                        <p>Groups Created</p>
                        <button class="create-group-button">Create Group</button>
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
    @include('components.back-to-top-button')
</body>
<script>
    window.STAR_ICON = "{{ asset('/icons/star.png') }}";
    window.STAR_ALT_ICON = "{{ asset('/icons/star-alt.png') }}";
    window.MUTE_ICON = "{{ asset('/icons/mute.png') }}";
    window.MUTE_ALT_ICON = "{{ asset('/icons/mute-alt.png') }}";
</script>
<script src="{{ asset('js/groups.js') }}"></script>
</html>