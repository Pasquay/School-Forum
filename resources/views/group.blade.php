<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | {{ $group->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                flex-direction: column;
                align-items: center;
                padding: 2.5rem 2rem 2rem 2rem;
                width: 100%;
                box-sizing: border-box;
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
        /* LEFT */
            .left {
                flex: 0 0 220px;
                max-width: 220px;
                min-width: 180px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.06);
                padding: 2rem 1.2rem;
                margin-right: 0.5rem;
                min-height: 300px;
                display: flex;
                flex-direction: column;
                gap: 1.2rem;
            }
        /* CENTER */
            .center {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: flex-start;
                gap: 2.5rem;
                width: 100%;
                max-width: 1400px;
                margin: 0 auto;
            }

            .content {
                flex: 1 1 700px;
                max-width: 900px;
                min-width: 0;
                padding: 2.5rem 2rem;
                min-height: 300px;
                margin-bottom: 2rem;
                margin-right: 0.5rem;
            }
        /* GROUP INFO RIGHT */
            .group-info.right {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                padding: 2rem 1.5rem;
                min-width: 320px;
                max-width: 340px;
                width: 100%;
                display: flex;
                flex-direction: column;
                gap: 2rem;
                align-self: flex-start;
                position: sticky;
                top: 88px;
            }
        /*  */
        /*  */
    </style>
</head>
<body>
    @include('components.navbar', ['active' => 'groups'])
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
                            <button type="submit" class="request-button"
                                @if($group->requested)
                                    disabled
                                @endif
                            >Request to Join</button>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="center">
            <div class="left">
                [LEFT NAV GOES HERE]
            </div>
            <div class="content">
                [POST SEARCH BAR]<br>
                [POST SEARCH FILTER NAVBAR]<br>
                @include('components.create-post-form', ['group' => $group])
                @if($pinned->count() > 0)
                    @foreach($pinned as $post)
                        @include('components.post', ['post' => $post])
                    @endforeach
                @endif
                @if($posts->count() > 0)
                    @foreach($posts as $post)
                        @include('components.post', ['post' => $post])
                    @endforeach
                @else
                    <p class="empty">No posts yet...</p>
                @endif
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
            // Request Button
                if(JLBtn.classList.contains('request-button')){
                    newJLForm.action = `/group/{{ $group->id }}/request`;
                    newJLForm.addEventListener('submit', async(e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        try {
                            const response = await fetch(newJLForm.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                credentials: 'same-origin',
                                body: new URLSearchParams({
                                    _token: document.querySelector('meta[name="csrf-token"]').content,
                                })
                            });

                            if(response.ok){
                                const data = await response.json();
                                if(data.requested){
                                    newJLForm.innerHTML = 
                                        `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                        <button type="submit" class="request-button" disabled>Request to Join</button>`;
                                        addJoinLeaveEventListeners();
                                }
                            }
                        } catch(error){
                            console.error("Error: ", error);
                        }
                    })
                }
        }
        // ACTIVATE!!!
            if(starForm && muteForm) addStarMuteEventListeners();
            addJoinLeaveEventListeners();
    // RIGHT ACTIONS
    // LEFT ACTIONS
    // MAIN CONTENT ACTIONS
        // POST
            // Variables
                const posts = document.querySelectorAll('.post');
            function addPostEventListeners(){
            // Link post cards to post pages
                Array.from(posts).forEach(post => {
                    post.addEventListener('click', () => {
                        window.location.href = `/post/${post.id}`;
                    });
            // Post share  buttons
                    const shareBtn = post.querySelector('.post-share-button');
                    shareBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        postUrl = `${window.location.origin}/post/${post.id}`;
                        navigator.clipboard.writeText(postUrl)
                            .then(() => {
                                shareBtn.textContent = 'Copied!';
                                setTimeout(() => {
                                    shareBtn.textContent = 'Share';
                                }, 1200);
                            })
                    })
                // Upvote & Downvote Function)
                    const voteContainer = post.querySelector('#vote-container');
                    const upvoteForm = voteContainer.querySelector('form:first-child');
                    const downvoteForm = voteContainer.querySelector('form:last-child');
                    const voteCount = voteContainer.querySelector('form:first-child + p');

                    voteContainer.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                    // Upvote Logic
                        upvoteForm.addEventListener('submit', async(e) => {
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

                            if(response.ok){
                                const data = await response.json();
                                
                                voteCount.textContent = data.voteCount;

                                const upArrow = upvoteForm.querySelector('img');
                                upArrow.src = data.voteValue == 1 ?
                                    "{{ asset('/icons/up-arrow-alt.png') }}" :
                                    "{{ asset('/icons/up-arrow.png') }}" ;
                                
                                const downArrow = downvoteForm.querySelector('img');
                                if (data.voteValue == 1){
                                    downArrow.src = "{{ asset('/icons/down-arrow.png') }}";
                                }
                            }
                            } catch(error){
                                console.error("Error: ", error);
                            }
                        })
                    // Downvote Logic
                    downvoteForm.addEventListener('submit', async(e) => {
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
                
                            if(response.ok){
                                const data = await response.json();
                
                                voteCount.textContent = data.voteCount;
                
                                const downArrow = downvoteForm.querySelector('img');
                                downArrow.src = data.voteValue == -1 ?
                                    "{{ asset('/icons/down-arrow-alt.png') }}" :
                                    "{{ asset('/icons/down-arrow.png') }}" ;
                
                                const upArrow = upvoteForm.querySelector('img');
                                if(data.voteValue == -1){
                                    upArrow.src = "{{ asset('/icons/up-arrow.png') }}";
                                }
                            }
                        } catch(error){
                            console.error('Error:', error);
                        }
                    })
                })
            }
                
            // ACTIVATE!!!
                addPostEventListeners();
    //
</script>
</html>