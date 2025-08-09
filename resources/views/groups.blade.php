<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | Groups</title>
    <meta name='csrf-token' content='{{ csrf_token() }}'>
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

        .navbar {
            background-color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #4a90e2;
            text-decoration: none;
            transition: color 0.2s;
        }

        .brand:hover {
            color: #357abd;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: #666;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #4a90e2;
        }

        #groups-nav-link {
            color: #4a90e2;
        }

        #groups-nav-link:hover {
            color: #357abd;
        }

        .logout-btn {
            background-color: #4a90e2;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .logout-btn:hover {
            background-color: #357abd;
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
                padding: 1rem; 
                border-radius: 8px; 
                margin-top: -0.5rem;
                margin-bottom: 1rem; 
                text-align: center;
            }

        /* ERROR HEADER */
            .error-message {
                background-color: #f8d7da; 
                color: #721c24; 
                padding: 1rem; 
                border-radius: 8px; 
                margin-top: -0.5rem;
                margin-bottom: 1rem;
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

    /* GROUPS LIST */
        .groups-list {
            max-width: 800px;
            margin: 0;
            padding: 0 0.7rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
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
    @include('components.navbar')
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <div class="search-section">
                <input type="text" placeholder="Search groups..." id="group-search">
            </div>

            <div class="nav">
                <button type="button" class="active first">Most Members</button>
                <button type="button">Most Active</button>
                <button type="button">New</button>
                <select class="sort-dropdown">
                    <option>Today</option>
                    <option>This Week</option>
                    <option>This Month</option>
                    <option>This Year</option>
                    <option>All Time</option>
                </select>
            </div>

            <div class="groups-list">
                @if($groups->count() > 0)
                    @foreach($groups as $group)
                        @include('components.group-info', ['group' => $group])
                    @endforeach
                @else
                    <p class="empty">No groups yet...</p>
                @endif
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
    // Right Side
        // Variables
            const rightGroups = document.querySelectorAll('.group-info-minimal');
        // Events
            rightGroups.forEach(group => {
            // Create Group button
                const createGroupBtn = document.querySelector('.create-group-button');
                createGroupBtn.addEventListener('click', () => {
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
                                    '{{ asset("storage/icons/star.png") }}' :
                                    '{{ asset("storage/icons/star-alt.png") }}' ;
                            }
                        } catch (error){
                            console.error('Error: ', error);
                        }
                    })
                }
            })
</script>
</html>