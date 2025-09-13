<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Groups</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <style>
    /* MAIN */
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

        main {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: flex-start;
            padding: 2rem;
            gap: 2rem;
        } 

        /* SUCCESS HEADER */
            .success-message {
                background-color: #d4edda;
                color: #155724;
                margin-top: -0.5rem;
                text-align: center;
            }

        /* ERROR HEADER */
            .error-message {
                background-color: #f8d7da; 
                color: #721c24; 
                margin-top: -0.5rem;
                text-align: center;
            }
            
            .error-message ul {
                margin: 0; 
                padding-left: 1rem;
            }
            
            .error-message p {
                margin: 0;
            }

    /* LEFT SIDE */
        .left-side {
            flex: 1 1 800px;
            max-width: 800px;
        }

    /* SEARCH BAR */
        .search-section {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .search-section input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e1e1e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .search-section input:focus {
            outline: none;
            border-color: #4a90e2;
        }

    /* LEFT NAV (SORT OPTIONS) */
        .left-side .nav {
            background-color: white;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            display: flex;
            transition: transform 0.2s ease;
            margin-bottom: 1.5rem;
        }

        .left-side .nav button.first {
            margin-left: 0;
        }

        .left-side .nav button {
            border: 0;
            cursor: pointer;
            color: #666;
            background-color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: medium;
            transition: color 0.2s;
            padding: 0.5rem 1rem;
            margin: 0 0.6rem;
            flex: 0 0 auto;
            text-align: left;
        }

        .left-side .nav button:hover {
            color: #4a90e2;
        }

        .left-side .nav button.active {
            border: 0;
            border-radius: 0.5rem;
            cursor: pointer;
            color: #4a90e2;
            background-color: #eaf4fb;
            text-decoration: none;
            font-weight: 500;
            font-size: medium;
            transition: color 0.2s;
            text-align: left;
        }

        .left-side .nav button.active:hover {
            color: #666;
            background-color: #e9eef3;
        }

        .sort-dropdown {
            border: 0;
            cursor: pointer;
            color: #666;
            background-color: white;
            font-weight: 500;
            font-size: medium;
            padding: 0.5rem 1rem;
            margin: 0 0.6rem;
            border-radius: 6px;
            transition: color 0.2s, background-color 0.2s;
            display: none; /* Initially hidden */
            min-width: 120px;
        }

        .sort-dropdown:hover {
            color: #4a90e2;
            background-color: #f8f9fa;
        }

        .sort-dropdown:focus {
            outline: none;
            color: #4a90e2;
            background-color: #eaf4fb;
        }

        .sort-dropdown option {
            background-color: white;
            color: #333;
            padding: 0.5rem;
        }

        .sort-dropdown option:hover {
            background-color: #f8f9fa;
        }

        .dropdown-wrapper {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-toggle {
            border: 0;
            cursor: pointer;
            color: #666;
            background-color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: medium;
            transition: color 0.2s;
            padding: 0.5rem 1rem;
            margin: 0 0.6rem;
            border-radius: 0.5rem;
        }
        
        .dropdown-toggle.active,
        .dropdown-toggle:focus {
            color: #4a90e2;
            background-color: #eaf4fb;
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            left: 0;
            top: 110%;
            min-width: 160px;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 8px;
            z-index: 10;
            flex-direction: column;
            padding: 0.5rem 0;
        }
        
        .dropdown-menu button {
            width: 100%;
            background: none;
            border: none;
            color: #333;
            text-align: left;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        
        .dropdown-menu button:hover,
        .dropdown-menu button.active {
            background-color: #eaf4fb;
            color: #4a90e2;
        }

    /* GROUPS LIST */
        .groups-list {
            max-width: 800px;
            margin: 0;
            padding: 0 0.7rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .groups-list .empty {
            text-align: center;
        }

        .group-card {
            cursor: pointer;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            transition: transform 0.2s ease;
        }

        .group-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .group-card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .group-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            background-color: #4a90e2;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .group-info h3 {
            margin: 0;
            color: #333;
            font-size: 1.2rem;
        }

        .group-info p {
            margin: 0.5rem 0 0 0;
            color: #666;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .group-stats {
            display: flex;
            gap: 2rem;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #666;
        }

        .group-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        .join-btn, .leave-btn {
            background-color: #4a90e2;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .join-btn:hover, .leave-btn:hover {
            background-color: #357abd;
        }

        .leave-btn {
            background-color: #dc3545;
        }

        .leave-btn:hover {
            background-color: #c82333;
        }

    /* RIGHT SIDE */
        .right-side {
            flex: 0 0 340px;
            max-width: 340px;
            min-width: 340px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            position: sticky;
            top: 88px;
            height: fit-content;
            max-height: calc(100vh - 88px);
            overflow-y: auto;
        }

        /* RIGHT SIDE SCROLLBAR */
            .right-side::-webkit-scrollbar {
                width: 8px;
            }

            .right-side::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }

            .right-side::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 4px;
                transition: background 0.2s;
            }

            .right-side::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }

            /* For Firefox */
            .right-side {
                scrollbar-width: thin;
                scrollbar-color: #c1c1c1 #f1f1f1;
            }

        .user-info {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.10);
            padding: 2rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: start;
            gap: 1rem;
            width: 100%;
        }

        .groups-created, .groups-moderated, .groups-joined {
            width: 100%;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 10px;
            width: 100%;
        }

        .section-header p {
            margin: 0;
            font-size: 20px; 
            flex: 1;
            line-height: 1.5;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s;
        }

        .section-header button {
            min-width: 5rem;
            background-color: #4a90e2;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .section-header button:hover {
            background-color: #357abd;
        }

        .user-info .empty {
            color: #666;
            font-style: italic;
            text-align: center;
            padding: 1rem 0 1rem 0;
            margin: 0;
            font-size: 0.9rem;
            width: 100%;
            border-bottom: 1px solid #f0f0f0;
            display: block;
        }

    </style>
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
                        label='Show Joined'
                    />
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
    // Left Side
        let activeSearch = false;
        // Search Bar
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.querySelector('#group-search');
                const groupsListContainer = document.querySelector('.groups-list');
                let searchTimeout = null;

                searchInput.addEventListener('input', function(){
                    clearTimeout(searchTimeout);
                    const query = (this.value || '').trim();

                    searchTimeout = setTimeout(() => {
                        groupsListContainer.innerHTML = '<p class="empty">Searching...</p>';
                        
                        activeSearch = (query === '') ? false : true;
                        membersNextPage = 2;
                        newNextPage = 2;
                        activeTodayNextPage = 2;
                        activeWeekNextPage = 2;
                        activeMonthNextPage = 2;
                        activeYearNextPage = 2;
                        activeAllNextPage = 2;

                        const sortButton = document.querySelector('.left-side .nav button.active, .dropdown-toggle.active');
                        const sort = sortButton ? sortButton.getAttribute('data-sort') : 'members';
                        let time = 'all';
                        if(sort === 'active'){
                            const timeButton = document.querySelector('.dropdown-menu button.active');
                            time = timeButton ? timeButton.getAttribute('data-time') : 'all';
                        }
                        const showJoined = document.querySelector('#show_joined');

                        fetch(`groups?search=${encodeURIComponent(query)}&sort=${sort}&time=${time}&show_joined=${showJoined}`,{
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            groupsListContainer.innerHTML = data.html;
                            addGroupEventListeners();
                        })
                        .catch((error) => {
                            groupsListContainer.innerHTML = `<p class="empty">Error: ${error}</p>`;
                        });
                    }, 200);
                })
            })   
        // Navbar dropdown functionality
            document.addEventListener('DOMContentLoaded', function() {
                const navMostMembers = document.querySelector('#most-members-btn');
                const navNew = document.querySelector('#new-btn');
                const dropdownToggle = document.getElementById('most-active-btn');
                const dropdownMenu = document.getElementById('active-dropdown');
                const navShowJoined = document.querySelector('#show_joined');
                let selectedTime = 'all';

                const groupsListContainer = document.querySelector('.groups-list');

                function setActiveNav(button){
                    document.querySelectorAll('.left-side .nav button, .dropdown-toggle').forEach(button => {
                        button.classList.remove('active');
                    });
                    button.classList.add('active');
                    dropdownMenu.style.display = 'none';
                }

                function fetchGroups(sort, time = 'all'){
                    groupsListContainer.innerHTML = 
                        '<p class="empty">Loading...</p>';

                    const showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                    const searchInput = document.querySelector('#group-search');
                    const query = (searchInput.value || '').trim();                    
                    activeSearch = (query === '') ? false : true;
                    const fetchRoute = (activeSearch) ? 
                        `/groups?search=${encodeURIComponent(query)}&sort=${sort}&time=${time}&show_joined=${showJoined}` : 
                        `/groups?sort=${sort}&time=${time}&show_joined=${showJoined}`;
                    console.log(fetchRoute);
                    fetch(fetchRoute, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        groupsListContainer.innerHTML = data.html;
                        addGroupEventListeners();
                    })
                    .catch((error) => {
                        groupsListContainer.innerHTML = 
                        `<p class="empty">Error: ${error}</p>`;
                    });
                }

                // Most Members
                    navMostMembers.addEventListener('click', function() {
                        setActiveNav(this);
                        membersNextPage = 2;
                        fetchGroups('members');
                    })
                
                // New
                    navNew.addEventListener('click', function() {
                        setActiveNav(this);
                        newNextPage = 2;
                        fetchGroups('new')
                    })

                // Most Active Dropdown
                    dropdownToggle.addEventListener('click', function(e) {
                        e.stopPropagation();
                        activeTodayNextPage = 2;
                        activeWeekNextPage = 2;
                        activeMonthNextPage = 2;
                        activeYearNextPage = 2;
                        activeAllNextPage = 2;
                        setActiveNav(this);
                        dropdownMenu.style.display = 
                            dropdownMenu.style.display === 'none' ? 'block' : 'none';
                    });
                
                // Most Active Options
                    dropdownMenu.querySelectorAll('button').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.stopPropagation();
                            dropdownMenu.querySelectorAll('button').forEach(btn => {
                                btn.classList.remove('active');
                            })
                            this.classList.add('active');
                            selectedTime = this.getAttribute('data-time');
                            dropdownMenu.style.display = 'none';
                            fetchGroups('active', selectedTime);
                        });
                    });
            
                // Hide dropdown if clicking outside
                    document.addEventListener('click', function() {
                        dropdownMenu.style.display = 'none';
                    });

                // Show Joined Toggle
                navShowJoined.addEventListener('click', function(){
                    const activeSort = document.querySelector('.left-side .nav button.active, .dropdown-toggle.active').getAttribute('data-sort');
                    if (activeSort === 'active'){
                        activeTime = document.querySelector('#active-dropdown button.active').getAttribute('data-time');
                        fetchGroups(activeSort, activeTime);
                    }
                    fetchGroups(activeSort);
                });
            });
        // Event Listeners
            function addJoinLeaveEventListeners(){
                const groups = document.querySelectorAll('.groups-list .group-info');
                    groups.forEach(group => {
                        const groupid = group.dataset.groupid;
                        const form = group.querySelector('form');
                        const button = group.querySelector('.join-leave button');
                        if (!form || !button) return;

                        // Remove previous event listeners by replacing the form node
                        const newForm = form.cloneNode(true);
                        form.parentNode.replaceChild(newForm, form);

                        // Join Button
                        if (button.classList.contains('join-button')) {
                            newForm.action = `/group/${groupid}/join`;
                            newForm.addEventListener('submit', async (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                try {
                                    const response = await fetch(newForm.action, {
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
                                        if (data.membership === 1) {
                                            newForm.innerHTML =
                                                `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <button type="submit" class="leave-button">Leave</button>`;
                                            addJoinLeaveEventListeners();
                                            const groupsJoinedContainer = document.querySelector('.user-info .groups-joined');
                                            groupsJoinedContainer.innerHTML = data.joinedGroupsHTML;
                                            addRightGroupEventListeners();
                                        }
                                    }
                                } catch (error) {
                                    console.error('Error: ', error);
                                }
                            });
                        }

                        // Leave Button
                        if (button.classList.contains('leave-button')) {
                            newForm.action = `/group/${groupid}/leave`;
                            newForm.addEventListener('submit', async (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                try {
                                    const response = await fetch(newForm.action, {
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
                                        if (data.membership === 0) {
                                            newForm.innerHTML =
                                                `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                <button type="submit" class="join-button">Join</button>`;
                                            addJoinLeaveEventListeners();
                                            const groupsJoinedContainer = document.querySelector('.user-info .groups-joined');
                                            groupsJoinedContainer.innerHTML = data.joinedGroupsHTML;
                                            addRightGroupEventListeners();
                                        }
                                    }
                                } catch (error) {
                                    console.error('Error: ', error);
                                }
                            });
                        }
                    });
            }

            function addGroupEventListeners(){
                const groups = document.querySelectorAll('.groups-list .group-info');
                groups.forEach(group => {
                    if(group.getAttribute('data-listeners-attached') !== '1'){
                    // Onclick lead to Group Page
                        const groupid = group.dataset.groupid;
                        group.addEventListener('click', (e) => {
                            if(
                                e.target.tagName === 'BUTTON' ||
                                e.target.tagName === 'FORM' ||
                                e.target.closest('form')
                            ){
                                return;
                            }
                            window.location.href = `/group/${groupid}`;
                        })
                    // Button
                        const form = group.querySelector('form');
                        const button = group.querySelector('.join-leave button');
                        // Join Button
                            if(button.classList.contains('join-button')){
                                form.action = `/group/${groupid}/join`;
                                form.addEventListener('submit', async(e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    try {
                                        const response = await fetch(form.action, {
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
                                            if(data.membership === 1){
                                                form.innerHTML = 
                                                    `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <button type="submit" class="leave-button">Leave</button>`;
                                                addJoinLeaveEventListeners();
                                                // addGroupEventListeners();
                                                const groupsJoinedContainer = document.querySelector('.user-info .groups-joined');
                                                groupsJoinedContainer.innerHTML = data.joinedGroupsHTML;
                                                addRightGroupEventListeners();
                                            }
                                        }
                                    } catch(error){
                                        console.error('Error: ', error);
                                    }
                                })
                            }
                        // Leave Button
                            if(button.classList.contains('leave-button')){
                                form.action = `/group/${groupid}/leave`;
                                form.addEventListener('submit', async(e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    try {
                                        const response = await fetch(form.action, {
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
                                            if(data.membership === 0){
                                                form.innerHTML = 
                                                    `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <button type="submit" class="join-button">Join</button>`;
                                                addJoinLeaveEventListeners();
                                                // addGroupEventListeners();
                                                // update joined groups
                                                const groupsJoinedContainer = document.querySelector('.user-info .groups-joined');
                                                groupsJoinedContainer.innerHTML = data.joinedGroupsHTML;
                                                // add joined groups event listeners
                                                addRightGroupEventListeners();
                                            }
                                        }
                                    } catch(error){
                                        console.error('Error: ', error);
                                    }
                                })
                            }
                        // Manage Button
                            if(button.classList.contains('manage-button')){
                                form.method = 'GET';
                                form.action = `/group/${groupid}/settings`;
                                form.addEventListener('submit', (e) => {
                                    e.stopPropagation();
                                })
                            }
                        // Request Button
                            
                    // Mark Listener
                        group.setAttribute('data-listeners-attached', '1');
                    }
                })
            }

            addGroupEventListeners();
        // Pagination
            // Variables
                let membersNextPage = 2;
                let membersLoading = false;
                const navMostMembers = document.querySelector('#most-members-btn');

                let newNextPage = 2;
                let newLoading = false;
                const navNew = document.querySelector('#new-btn');

                const navMostActive = document.querySelector('#most-active-btn');

                let activeTodayNextPage = 2;
                let activeTodayLoading = false;
                const navActiveToday = document.querySelector('#active-dropdown button[data-time="today"]');

                let activeWeekNextPage = 2;
                let activeWeekLoading = false;
                const navActiveWeek = document.querySelector('#active-dropdown button[data-time="week"]');
                
                let activeMonthNextPage = 2;
                let activeMonthLoading = false;
                const navActiveMonth = document.querySelector('#active-dropdown button[data-time="month"]');

                let activeYearNextPage = 2;
                let activeYearLoading = false
                const navActiveYear = document.querySelector('#active-dropdown button[data-time="year"]');

                let activeAllNextPage = 2;
                let activeAllLoading = false;
                const navActiveAll = document.querySelector('#active-dropdown button[data-time="all"]');

                const groupsListContainer = document.querySelector('.groups-list');

                const loader = document.querySelector('.groups-list p.empty');
            document.addEventListener('scroll', () => {
                if(window.innerHeight + window.scrollY >= document.body.offsetHeight - 300){
                    const searchInput = document.querySelector('#group-search');
                    const query = (searchInput.value || '').trim();
                    // Most Members
                        if(
                            navMostMembers.classList.contains('active')
                            && !membersLoading
                            && membersNextPage
                        ) {
                            // membersNextPage = 2;
                            newNextPage = 2;
                            activeTodayNextPage = 2;
                            activeWeekNextPage = 2;
                            activeMonthNextPage = 2;
                            activeYearNextPage = 2;
                            activeAllNextPage = 2;

                            membersLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';
                            //
                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${membersNextPage}?search=${encodeURIComponent(query)}&sort=members&time=all&show_joined=${showJoined}` : 
                                `/groups/${membersNextPage}?sort=members&time=all&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                            //
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                loader.insertAdjacentHTML('beforebegin', data.html);
                                membersNextPage = data.next_page;
                                membersLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!membersNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                membersLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                    // New
                        if(
                            navNew.classList.contains('active')
                            && !newLoading
                            && newNextPage
                        ) {
                            membersNextPage = 2;
                            // newNextPage = 2;
                            activeTodayNextPage = 2;
                            activeWeekNextPage = 2;
                            activeMonthNextPage = 2;
                            activeYearNextPage = 2;
                            activeAllNextPage = 2;

                            newLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';

                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${newNextPage}?search=${encodeURIComponent(query)}&sort=new&time=all&show_joined=${showJoined}` : 
                                `/groups/${newNextPage}?sort=new&time=all&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const loader = document.querySelector('.groups-list p.empty');
                                loader.insertAdjacentHTML('beforebegin', data.html + "<p class='empty'></p>");
                                newNextPage = data.next_page;
                                newLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!newNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                newLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                    // Most Active - Today
                        if(
                            navActiveToday.classList.contains('active')
                            && !activeTodayLoading
                            && activeTodayNextPage
                        ){
                            membersNextPage = 2;
                            newNextPage = 2;
                            // activeTodayNextPage = 2;
                            activeWeekNextPage = 2;
                            activeMonthNextPage = 2;
                            activeYearNextPage = 2;
                            activeAllNextPage = 2;

                            activeTodayLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';

                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${activeTodayNextPage}?search=${encodeURIComponent(query)}&sort=active&time=today&show_joined=${showJoined}` : 
                                `/groups/${activeTodayNextPage}?sort=active&time=today&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const loader = document.querySelector('.groups-list p.empty');
                                loader.insertAdjacentHTML('beforebegin', data.html + "<p class='empty'></p>");
                                activeTodayNextPage = data.next_page;
                                activeTodayLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!activeTodayNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                activeTodayLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                    // Most Active - Week
                        if(
                            navActiveWeek.classList.contains('active')
                            && !activeWeekLoading
                            && activeWeekNextPage
                        ){
                            membersNextPage = 2;
                            newNextPage = 2;
                            activeTodayNextPage = 2;
                            // activeWeekNextPage = 2;
                            activeMonthNextPage = 2;
                            activeYearNextPage = 2;
                            activeAllNextPage = 2;

                            activeWeekLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';

                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${activeWeekNextPage}?search=${encodeURIComponent(query)}&sort=active&time=week&show_joined=${showJoined}` : 
                                `/groups/${activeWeekNextPage}?sort=active&time=week&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const loader = document.querySelector('.groups-list p.empty');
                                loader.insertAdjacentHTML('beforebegin', data.html + "<p class='empty'></p>");
                                activeWeekNextPage = data.next_page;
                                activeWeekLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!activeWeekNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                activeWeekLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                    // Most Active - Month
                        if(
                            navActiveMonth.classList.contains('active')
                            && !activeMonthLoading
                            && activeMonthNextPage
                        ){
                            membersNextPage = 2;
                            newNextPage = 2;
                            activeTodayNextPage = 2;
                            activeWeekNextPage = 2;
                            // activeMonthNextPage = 2;
                            activeYearNextPage = 2;
                            activeAllNextPage = 2;

                            activeMonthLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';

                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${activeMonthNextPage}?search=${encodeURIComponent(query)}&sort=active&time=month&show_joined=${showJoined}` : 
                                `/groups/${activeMonthNextPage}?sort=active&time=month&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const loader = document.querySelector('.groups-list p.empty');
                                loader.insertAdjacentHTML('beforebegin', data.html + "<p class='empty'></p>");
                                activeMonthNextPage = data.next_page;
                                activeMonthLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!activeMonthNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                activeMonthLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                    // Most Active - Year
                        if(
                            navActiveYear.classList.contains('active')
                            && !activeYearLoading
                            && activeYearNextPage
                        ){
                            membersNextPage = 2;
                            newNextPage = 2;
                            activeTodayNextPage = 2;
                            activeWeekNextPage = 2;
                            activeMonthNextPage = 2;
                            // activeYearNextPage = 2;
                            activeAllNextPage = 2;

                            activeYearLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';

                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${activeYearNextPage}?search=${encodeURIComponent(query)}&sort=active&time=year&show_joined=${showJoined}` : 
                                `/groups/${activeYearNextPage}?sort=active&time=year&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const loader = document.querySelector('.groups-list p.empty');
                                loader.insertAdjacentHTML('beforebegin', data.html + "<p class='empty'></p>");
                                activeYearNextPage = data.next_page;
                                activeYearLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!activeYearNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                activeYearLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                    // Most Active - All
                        if(
                            navActiveAll.classList.contains('active')
                            && !activeAllLoading
                            && activeAllNextPage
                        ){
                            membersNextPage = 2;
                            newNextPage = 2;
                            activeTodayNextPage = 2;
                            activeWeekNextPage = 2;
                            activeMonthNextPage = 2;
                            activeYearNextPage = 2;
                            // activeAllNextPage = 2;

                            activeAllLoading = true;

                            const loader = document.querySelector('.groups-list p.empty');
                            loader.style.display = 'block';
                            loader.textContent = 'Loading...';

                            let showJoined = document.querySelector('#show_joined').checked ? '1' : '0';
                            activeSearch = (query === '') ? false : true;
                            const fetchRoute = (activeSearch) ? 
                                `/groups/${activeAllNextPage}?search=${encodeURIComponent(query)}&sort=active&time=all&show_joined=${showJoined}` : 
                                `/groups/${activeAllNextPage}?sort=active&time=all&show_joined=${showJoined}`;

                            fetch(fetchRoute, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                const loader = document.querySelector('.groups-list p.empty');
                                loader.insertAdjacentHTML('beforebegin', data.html + "<p class='empty'></p>");
                                activeAllNextPage = data.next_page;
                                activeAllLoading = false;
                                loader.style.display = 'none';

                                // attach event listeners
                                addGroupEventListeners();

                                if(!activeAllNextPage){
                                    loader.textContent = 'No more groups';
                                    loader.style.display = 'block';
                                }
                            })
                            .catch(error => {
                                console.error('Error: ', error);
                                activeAllLoading = false;
                                loader.style.display = 'none';
                            });
                        }
                }
            })

    // Right Side
        // Events
            function addRightGroupEventListeners(){
                const rightGroups = document.querySelectorAll('.group-info-minimal');
                rightGroups.forEach(group => {
                // Create Group button
                    const createGroupBtn = document.querySelector('.create-group-button');
                    createGroupBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        window.location.href = `/groups/create`;
                    })
                // Onclick go to group page
                    const groupid = group.dataset.groupid;
                    group.addEventListener('click', () => {
                        window.location.href = `/group/${groupid}`;
                    })
                // Star and unstar
                    const starForm = group.querySelector('form');
                    const starBtn = starForm.querySelector('.star')
                    if(starBtn){
                        let starImg = starBtn.querySelector('img');
                        
                        starBtn.addEventListener('click', async(e) => {
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

                                if(response.ok){
                                    const data = await response.json();

                                    starImg.src = data.starValue ?
                                        '{{ asset("/icons/star.png") }}' :
                                        '{{ asset("/icons/star-alt.png") }}' ;
                                }
                            } catch (error){
                                console.error('Error: ', error);
                            }
                        })
                    }
                })
            }
            addRightGroupEventListeners();
</script>
</html>