<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media | {{ $group->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                color: #4a90e2;  /* Changed from #333 to match the blue theme */
                text-decoration: none;
                transition: color 0.2s;  /* Added transition for hover effect */
            }

            .brand:hover {
                color: #357abd;  /* Added hover state to match other interactive elements */
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
        /* Group Info Top */
            .group-info.top {
                width: 100%;
                background: #fff;
                border: 1px solid #e1e1e1;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                padding: 2rem 2rem 1.5rem 2rem;
                margin-bottom: 2rem;
                display: flex;
                align-items: center;
                gap: 2rem;
                position: relative;
            }

            .group-info.top #banner {
                width: 100%;
                max-height: 180px;
                object-fit: cover;
                border-radius: 10px 10px 0 0;
                margin-bottom: 1rem;
            }

            .group-info.top #photo,
            .group-info.top #group-default-photo {
                width: 64px;
                height: 64px;
                border-radius: 12px;
                object-fit: cover;
                flex-shrink: 0;
                margin-right: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 2rem;
                background: #4a90e2;
                color: #fff;
            }

            .group-info.top .group-name {
                font-size: 2rem;
                font-weight: 600;
                color: #333;
                margin: 0;
                letter-spacing: 0.01em;
            }
            /* ACTION BUTTONS */
                #join-leave-form {
                    flex-shrink: 0;
                    display: flex;
                    align-items: flex-start;
                }

                .join-button, .leave-button {
                    background-color: #4a90e2;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 6px;
                    width: 68px;
                    text-align: center;
                    border: none;
                    cursor: pointer;
                    font-weight: 500;
                    transition: background-color 0.2s;
                }

                .join-button {
                    background-color: #28a745;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 6px;
                    width: 68px;
                    text-align: center;
                    border: none;
                    cursor: pointer;
                    font-weight: 500;
                    transition: background-color 0.2s;
                }
                
                .join-button:hover {
                    background-color: #218838;
                }

                .leave-button {
                    background-color: #dc3545;
                }

                .leave-button:hover {
                    background-color: #c82333;
                }

                .manage-button {
                    background-color: #4a90e2;
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 6px;
                    width: 68px;
                    text-align: center;
                    border: none;
                    cursor: pointer;
                    font-weight: 500;
                    transition: background-color 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center; 
                }

                .manage-button:hover {
                    background-color: #357abd;
                }
        /*  */
        /*  */
        /*  */
        /*  */
    </style>
</head>
<body>
    @include('components.navbar')
    @include('components.success-header')
    @include('components.error-header')
    <main> 
        <div class="group-info top">
            @if($group->banner)
                <img src="{{ asset('storage/' . $group->banner) }}" id="banner" alt="Group Banner">
            @endif
            @if($group->photo)
                <img src="{{ asset('storage/' . $group->photo) }}" id="photo" alt="Group Photo">
            @else
                <div id="group-default-photo">
                    {{ strtoupper(substr($group->name, 0, 1)) }}
                </div>
            @endif
            <div class="group-actions">
                <p class="group-name">{{ $group->name }}</p>
                <div class="right-actions">
                    <div id="star-mute-forms">
                        @if($membership)  
                            <form action="/group/toggleStar/{{ $group->id }}" method="POST" id="star-form">
                                @csrf
                                <button class="star">
                                    <img 
                                        src="
                                            @if($membership->pivot->is_starred === 1)
                                                {{ asset('/icons/star.png') }}
                                            @else
                                                {{ asset('/icons/star-alt.png') }}
                                            @endif
                                        " 
                                        alt="star"
                                    >
                                </button>
                            </form>
                            <form action="/group/toggleMute/{{ $group->id }}" method="POST" id="mute-form">
                                @csrf
                                <button class="mute">
                                    <img 
                                        src="
                                            @if($membership->pivot->is_muted === 1)
                                                {{ asset('/icons/mute.png') }}
                                            @else
                                                {{ asset('/icons/mute-alt.png') }}
                                            @endif
                                        " 
                                        alt="Mute"
                                    >
                                </button>
                            </form>
                        @endif
                    </div>
                    <form action="" method="POST" id="join-leave-form">
                        @csrf
                        @if($group->owner_id === Auth::id())
                            <button type="submit" class="manage-button">Manage</button>
                        @elseif($membership)
                            <button type="submit" class="leave-button">Leave</button>
                        @elseif(!$membership && !$group->is_private)
                            <button type="submit" class="join-button">Join</button>
                        @else
                            <button type="submit" disabled>Invite only</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="content">
            [POSTS/ANNOUNCEMENTS GO HERE]
        </div>
        <div class="group-info right">
            <div class="rules">
                <h2>Rules:</h2>
                @php $rules = $group->rules ? : []; @endphp
                <ul class="rules-list">
                    @if(!empty($rules))
                        @foreach($rules as $rule)
                            <li class="rule-item">
                                <h3>{{ $rule['title'] }}</h3>
                                <p>{{ $rule['description'] }}</p>
                            </li>
                        @endforeach
                    @else
                        <div class="no-rules">No rules set for this group.</div>
                    @endif
                </ul>
            </div>
            <div class="resources">
                <h2>Resources</h2>
                @php $resources = $group->resources ? : []; @endphp
                <ul class="resources-list">
                    @if(!empty($resources))
                        @foreach($resources as $resource)
                            <li class="resource-item">
                                <h3>{{ $resource['title'] }}</h3>
                                <p>{{ $rule['description'] }}</p>
                            </li>
                        @endforeach
                    @else
                        <div class="no-resources">No resources set for this group.</div>
                    @endif
                </ul>
            </div>
            <div class="member-list">
                <div class="owner">
                    <h4>Owner</h4>
                    @foreach($memberList as $member)
                        @if($member->pivot->role === 'owner')
                            <div class="member">
                                <!-- <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"> -->
                                <span>{{ $member->name }}</span> <!-- ADD THE staff-check.png TO OWNER -->
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="moderators">
                    <h4>Moderators</h4>
                    @foreach($memberList as $member)
                        @if($member->pivot->role === 'moderator')
                            <div class="member">
                                <!-- <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"> -->
                                <span>{{ $member->name }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="members">
                    <h4>Members</h4>
                    @foreach($memberList as $member)
                        @if($member->pivot->role === 'member')
                            <div class="member">
                                <!-- <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}"> -->
                                <span>{{ $member->name }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </main>
    @include('components.back-to-top-button')
</body>
<script>
    // TOP ACTIONS
        // VARIABLES
            const actionButtons = document.querySelector('.right-actions');
            const starForm = actionButtons ? actionButtons.querySelector('#star-form') : null;
            const starBtn = starForm ? starForm.querySelector('button') : null;
            const muteForm = actionButtons ? actionButtons.querySelector('#mute-form') : null;
            const muteBtn = muteForm ? muteForm.querySelector('button') : null;
        function addStarMuteEventListeners(){
        // Star Toggle
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
        // Mute Toggle
            if(muteBtn){
                let muteImg = muteBtn.querySelector('img');

                muteBtn.addEventListener('click', async(e) => {
                    e.preventDefault();
                    e.stopPropagation();

                    muteBtn.disabled = true;
                    muteBtn.style.opacity = '0.5';
                    muteBtn.style.cursor = 'default';

                    setTimeout(() => {
                        muteBtn.disabled = false;
                        muteBtn.style.opacity = '1';
                        muteBtn.style.cursor = 'pointer';
                    }, 400);

                    try {
                        const response = await fetch(muteForm.action, {
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

                            muteImg.src = data.muteValue ?
                                '{{ asset("/icons/mute.png") }}' :
                                '{{ asset("/icons/mute-alt.png") }}' ;
                        }
                    } catch(error){
                        console.error('Error: ', error);
                    }
                })
            }
        }
        function addJoinLeaveEventListeners(){
        // Join/Leave Toggle
            const JLForm = document.querySelector('#join-leave-form');
            if(!JLForm) return;

            // Remove previous event listeners by replacing the form node
            const newJLForm = JLForm.cloneNode(true);
            JLForm.parentNode.replaceChild(newJLForm, JLForm);

            // Reselect button from new form
            const JLBtn = newJLForm.querySelector('button');
            if(!JLBtn) return;

            // Join Group
                if(JLBtn.classList.contains('join-button')){
                    newJLForm.action = `/group/{{ $group->id }}/join`;
                    newJLForm.addEventListener('submit', async(e) => {
                        console.log(newJLForm.action);
                        e.preventDefault();
                        e.stopPropagation();
                        try{
                            const response = await fetch(newJLForm.action, {
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
                                    newJLForm.innerHTML = 
                                        `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="leave-button">Leave</button>`;
                                    addJoinLeaveEventListeners();

                                    // Show star/mute forms
                                    document.querySelector('#star-mute-forms').innerHTML = `
                                        <form action="/group/toggleStar/{{ $group->id }}" method="POST" id="star-form">
                                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                            <button class="star">
                                                <img src="{{ asset('/icons/star-alt.png') }}" alt="star">
                                            </button>
                                        </form>
                                        <form action="/group/toggleMute/{{ $group->id }}" method="POST" id="mute-form">
                                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                            <button class="mute">
                                                <img src="{{ asset('/icons/mute-alt.png') }}" alt="Mute">
                                            </button>
                                        </form>
                                    `;
                                    addStarMuteEventListeners();
                                }
                            }
                        } catch(error){
                            console.error('Error: ', error);
                        }
                    });
                }

            // Leave Group
                if(JLBtn.classList.contains('leave-button')){
                    newJLForm.action = `/group/{{ $group->id }}/leave`;
                    newJLForm.addEventListener('submit', async(e) => {
                        console.log(newJLForm.action);
                        e.preventDefault();
                        e.stopPropagation();
                        try{
                            const response = await fetch(newJLForm.action, {
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
                                    newJLForm.innerHTML = 
                                        `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="join-button">Join</button>`;
                                    addJoinLeaveEventListeners();

                                    document.querySelector('#star-mute-forms').innerHTML = ``;
                                }
                            }
                        } catch(error){
                            console.error('Error: ', error);
                        }
                    });
                }

            // Manage Group
                if(JLBtn.classList.contains('manage-button')){
                    newJLForm.method = 'GET';
                    newJLForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        window.location.href = `/group/{{ $group->id }}/settings`;
                    })
                }
        }
        // ACTIVATE!!!
            if(starForm && muteForm) addStarMuteEventListeners();
            addJoinLeaveEventListeners();
    // RIGHT ACTIONS
    // LEFT ACTIONS
    // MAIN CONTENT ACTIONS
</script>
</html>