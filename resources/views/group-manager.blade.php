<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | Group Manager</title>
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
                background: f5f5f5;
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
            /* GROUP BULK ACTIONS */
                #group-bulk-actions {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
                    padding: 0.8rem 0.8rem;
                    margin-bottom: 1rem;
                    border: 1px solid #f0f0f0;
                    gap: 1rem;
                    transition: background-color 0.2s;
                }

                #group-bulk-actions .group-bulk-select {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                #group-bulk-actions .group-bulk-select button {
                    background: #4a90e2;
                    color: #fff;
                    border: none;
                    border-radius: 6px;
                    padding: 0.3rem 1rem;
                    font-size: 0.9rem;
                    font-weight: 500;
                    cursor: pointer;
                    transition: background 0.18s;
                }
                #group-bulk-actions .group-bulk-select button:hover {
                    background: #357abd;
                }

                #group-bulk-actions .bulk-actions {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    gap: 0.2rem;
                    flex-wrap: nowrap;
                }
                #group-bulk-actions .bulk-actions.right-actions {
                    align-items: center;
                    margin-left: auto;
                    gap: 0.2rem;
                }
                #group-bulk-actions .bulk-actions form,
                #group-bulk-actions .bulk-actions button {
                    display: inline-flex;
                    align-items: center;
                    padding: 0;
                    height: 12px;
                }
                #group-bulk-actions .bulk-actions button {
                    background: none;
                    border: none;
                    padding: 0.25rem;
                    margin: 0 0.1rem;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                #group-bulk-actions .bulk-actions img {
                    width: 20px;
                    height: 20px;
                    object-fit: contain;
                    display: block;
                    background: none;
                    border: none;
                    box-shadow: none;
                    padding: 0;
                    margin: 16px 0 0 0;
                }
                #group-bulk-actions #bulk-leave-form {
                    position: relative;
                    top: -5px;
                }
                #group-bulk-actions .bulk-actions .leave-button {
                    align-items: center;
                    background-color: #dc3545;
                    color: white;
                    padding: 0.8rem 1.5rem;
                    margin: 0 0 0 0.2rem;
                    border-radius: 6px;
                    width: 68px;
                    height: 20px;
                    text-align: center;
                    border: none;
                    cursor: pointer;
                    font-size: 0.8rem;
                    font-weight: 500;
                    transition: background-color 0.2s;
                    align-items: center;
                    justify-content: center;
                }
                #group-bulk-actions .bulk-actions .leave-button:hover {
                    background-color: #c82333;
                }
        /* RIGHT SIDE */
            .right-side {
                flex: 0 0 340px;
                max-width: 340px;
                min-width: 340px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.10);
                padding: 2rem 1.5rem;
                margin-bottom: 2rem;
                position: sticky;
                top: 88px;
                height: fit-content;
                max-height: calc(100vh - 88px);
                overflow-y: auto;
            }
        

        /* CONFIRMATION MODAL */
            #leave-confirmation-modal {
                position: fixed;
                z-index: 1000;
                left: 0; top: 0; width: 100vw; height: 100vh;
            }
            #leave-confirmation-modal .modal-content {
                background: #fff;
                border-radius: 6px;
                box-shadow: 0 4px 32px rgba(0,0,0,0.18);
                padding: 1rem;
                min-width: 320px;
                max-width: 90vw;
                text-align: center;
                position: absolute;
                left: 50%; top: 50%;
                transform: translate(-50%, -50%);
            }
            #leave-confirmation-modal::before {
                content: "";
                position: fixed;
                left: 0; top: 0; width: 100vw; height: 100vh;
                background: rgba(0,0,0,0.25);
                z-index: -1;
            }
            #leave-confirmation-modal p {
                margin: 0.5rem 0.5rem 0 0.5rem;
                font-size: 0.8rem;
                font-size: 1.05rem;
                color: #333;
            }

            #leave-confirmation-modal #group-list {
                color: #333;
                font-weight: 500;
                font-size: 1.05rem;
                text-align: left;
            }
            #leave-confirmation-modal #group-list p {
                margin-bottom: 0.5rem;
                border-radius: 5px;
                color: #444;
                font-size: 1rem;
                font-weight: 700;
                display: inline-block;
                min-width: 120px;
            }
            #leave-confirmation-modal #modal-actions {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }
            #leave-confirmation-modal #modal-leave-btn,
            #leave-confirmation-modal #modal-cancel-btn {
                width: 100%;
                box-sizing: border-box;
                display: block;
            }
            #leave-confirmation-modal #modal-leave-btn {
                background: #dc3545;
                color: #fff;
                border: none;
                border-radius: 6px;
                padding: 0.55rem 2.2rem;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                transition: background 0.18s;
            }
            #leave-confirmation-modal #modal-leave-btn:hover {
                background: #b52a37;
            }
            #leave-confirmation-modal #modal-cancel-btn {
                background: #e9ecef;
                color: #333;
                border: none;
                border-radius: 6px;
                padding: 0.55rem 2.2rem;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                transition: background 0.18s;
            }
            #leave-confirmation-modal #modal-cancel-btn:hover {
                background: #d6d8db;
            }
    </style>
</head>
<body>
    @include('components.navbar', ['active' => ''])
    @include('components.success-header')
    @include('components.error-header')
    <main>
        <div class="left-side">
            <div class="search-section">
                <input type="text" placeholder="Search groups..." id="group-search">
            </div>

            <div id="group-bulk-actions">
                <div class="group-bulk-select">
                    <button id="select-all-button">Select all</button>
                    <button id="select-none-button">Select none</button>
                </div>
                <div class="bulk-actions" style="display:none;">
                    <form id="bulk-star-form" action="{{ route('group.set.star') }}" method="post">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('/icons/star.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-unstar-form" action="{{ route('group.set.star') }}" method="post">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('/icons/star-alt.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-unmute-form" action="{{ route('group.set.mute') }}" method="post">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('/icons/mute-alt.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-mute-form" action="{{ route('group.set.mute') }}" method="post">
                        @csrf
                        <button type="submit">
                            <img src="{{ asset('/icons/mute.png') }}" alt="">
                        </button>
                    </form>
                    <form id="bulk-leave-form" action="#" method="post">
                        @csrf
                        <button type="button" class="leave-button">Leave</button>
                    </form>
                </div>
            </div>

            @if($groups->count()>0) 
                @foreach($groups as $group)
                    @include('components.group-info-manager', ['group' => $group])
                @endforeach
            @endif

        </div>
        <div class="right-side">
            Sticky group overview card
            Group Overview
            <div class="row-1">
                no. of groups joined
                no. of groups moderated
                no. of groups created
            </div>
            <div class="row-2">
                no. of educational groups 
                no. of social groups 
                no. of private groups 
            </div>
            <div class="row-3">
                no. of groups starred
                no. of groups muted
            </div>
            maybe some chart.js shit here idk if naa pay extra space
        </div>
    </main>

    <div id="leave-confirmation-modal" style="display: none;">
        <div class="modal-content">
            <p id="modal-header"></p>
            <div id="group-list"></div>
            <div id="modal-actions">
                <button id="modal-leave-btn">Leave</button>
                <button id="modal-cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    @include('components.back-to-top-button')
</body>
<script>
    function showLeaveConfirmationModal(leaveGroups, onConfirm){
        let modal = document.querySelector('#leave-confirmation-modal');
        modal.style.display = 'block';
        
        let groupList = modal.querySelector('#group-list');
        groupList.innerHTML = '';

        let toLeaveGroups = Array.isArray(leaveGroups) ? leaveGroups : [leaveGroups];

        let modalHeader = modal.querySelector('#modal-header');

        if(toLeaveGroups.length === 1){
            modalHeader.textContent = "Are you sure you want to leave the following group?";
            let p = document.createElement('p');
            let groupName = toLeaveGroups[0].querySelector('.group-info p').textContent;
            p.textContent = groupName;
            groupList.appendChild(p);
            groupList.style.textAlign = 'center';
        } else {
            modalHeader.textContent = "Are you sure you want to leave the following groups?";
            let p = document.createElement('p');
            let groupNames = toLeaveGroups.map(leaveGroup => 
                '• ' + leaveGroup.querySelector('.group-info p').textContent
            );
            p.innerHTML = groupNames.join('<br>');
            groupList.appendChild(p);
            groupList.style.textAlign = 'left';
        }

        let leaveBtn = modal.querySelector('#modal-leave-btn');
        let cancelBtn = modal.querySelector('#modal-cancel-btn');

        leaveBtn.onclick = null;
        cancelBtn.onclick = null;

        leaveBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.style.display = 'none';
            if(typeof onConfirm === 'function') onConfirm();
        }

        cancelBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.style.display = 'none';
        };
    }

    function addGroupCardEventListeners(){
        const groups = document.querySelectorAll('.group-info-manager');
        groups.forEach(group => {
            // VARIABLES
                const groupid = group.dataset.groupid;
            // Group Select Checkbox
                const groupSelect = group.querySelector('.group-select');
                if(groupSelect){
                    groupSelect.addEventListener('click', (e) => {
                        e.stopPropagation();
                    });
                }
            // Onclick go to group page
                group.addEventListener('click', (e) => {
                    if(!document.querySelectorAll('.group-select:checked').length){
                        e.preventDefault();
                        window.location.href = `/group/${groupid}`;
                    }
                });
            // Star Toggle
                const starForm = group.querySelector('.star-toggle-form');
                const starBtn = starForm.querySelector('.star');
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
                                }),
                            });

                            if(response.ok){
                                const data = await response.json();

                                starImg.src = data.starValue ?
                                    '{{ asset("/icons/star.png") }}' :
                                    '{{ asset("/icons/star-alt.png") }}' ;
                            }
                        } catch (e){
                            console.error("Error: ", e);
                        }
                    });
                }
            // Mute Toggle
                const muteForm = group.querySelector('.mute-toggle-form');
                const muteBtn = muteForm.querySelector('.mute');
                if(muteBtn){
                    let muteImg = muteBtn.querySelector('img');

                    muteBtn.addEventListener('click', async(e) => {
                        e.preventDefault();
                        e.stopPropagation();

                        muteBtn.disabled = true;
                        muteBtn.style.opacity = '0.5';
                        muteBtn.style.curos = 'default';

                        setTimeout(() => {
                            muteBtn.disabled = false;
                            muteBtn.style.opacity = '1';
                            muteBtn.style.curos = 'pointer';
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
                        } catch(e) {
                            console.error("Error: ", e);
                        }
                    })
                }
            // Manage Button
                const manageButton = group.querySelector('.manage-button');
                if(manageButton){
                    manageButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        window.location.href = `/group/${groupid}?settings=1`;
                    });
                }
            // Leave Button
                const leaveForm = group.querySelector('.leave-group-form');
                if(leaveForm){
                    const leaveBtn = leaveForm.querySelector('.leave-button');

                    leaveBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        showLeaveConfirmationModal(group, 
                            function onConfirm(){ 
                                leaveForm.requestSubmit(); 
                            }
                        );
                    })

                    leaveForm.addEventListener('submit', async(e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        try {
                            const response = await fetch(leaveForm.action, {
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
                                group.remove();
                            }
                        } catch(e){
                            console.error("Error: ", e);
                        }
                    })
                }
        });
    }

    function toggleRightActions(shouldDisable){
        const bulkActions = document.querySelector('.bulk-actions');
        const rightActions = [
            '.right-actions button',
            '.right-actions input[type="submit"]',
        ];

        document.querySelectorAll(rightActions.join(',')).forEach(btn => {
            btn.disabled = shouldDisable;
            if(shouldDisable){
                btn.classList.add('disabled-action');
                bulkActions.style.display = 'inline';
            } else {
                btn.classList.remove('disabled-action');
                bulkActions.style.display = 'none';
            }
        });

    }

    function updateRightActions(){
        const groupSelects = document.querySelectorAll('.group-select');
        const anyChecked = Array.from(groupSelects).some(cb => cb.checked);
        toggleRightActions(anyChecked);
    }

    function addBulkActionEventListeners(){
        // Variables
        const bulkForms = Array.from(document.querySelectorAll('.bulk-actions form')).filter(
            form => form.id !== 'bulk-leave-form'
        );
        const groupSelects = document.querySelectorAll('.group-select');
        const selectAllBtn = document.querySelector('#select-all-button');
        const selectNoneBtn = document.querySelector('#select-none-button');
        const bulkLeaveForm = document.querySelector('#bulk-leave-form');
        const bulkLeaveBtn = bulkLeaveForm.querySelector('.leave-button');

        // Select All Button
        selectAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            groupSelects.forEach(cb => cb.checked = true);
            updateRightActions();
        });

        // Select None Button
        selectNoneBtn.addEventListener('click', (e) => {
            e.preventDefault();
            groupSelects.forEach(cb => cb.checked = false);
            updateRightActions();
        })

        // Star/Unstar & Mute/Unmute
        bulkForms.forEach(form => {
            form.addEventListener('submit', async(e) => {
                e.preventDefault();

                form.querySelectorAll('input[name="group_ids[]"]').forEach(el => el.remove());

                const checked = Array.from(groupSelects).filter(cb => cb.checked);

                if(checked.length === 0){
                    alert('Select at least one group.');
                    return;
                }

                const formData = new URLSearchParams();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                checked.forEach(cb => {
                    formData.append('group_ids[]', cb.value);
                });

                let value = 1;
                if(form.id === 'bulk-unstar-form' || form.id === 'bulk-unmute-form'){
                    value = 0;
                }
                formData.append('value', value);

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        credentials: 'same-origin',
                        body: formData.toString(),
                    });

                    if(response.ok){
                        const data = await response.json();
                        alert(data.message || 'Message not found');

                        checked.forEach(cb => {
                            const group = document.querySelector(`.group-info-manager[data-groupid="${cb.value}"]`);
                            if(group){
                                if(data.action_type === 'star'){
                                    const starImg = group.querySelector('.star-toggle-form .star img');
                                    if(starImg){
                                        starImg.src = (data.action_value == 1)
                                            ? "{{ asset('/icons/star.png') }}"                                        
                                            : "{{ asset('/icons/star-alt.png') }}";                                        
                                    }
                                } else if(data.action_type === 'mute'){
                                    const muteImg = group.querySelector('.mute-toggle-form .mute img');
                                    if(muteImg){
                                        muteImg.src = (data.action_value == 1)
                                            ? "{{ asset('/icons/mute.png') }}"
                                            : "{{ asset('/icons/mute-alt.png') }}";
                                    }
                                }
                            }
                            cb.checked = false;
                            toggleRightActions(0);
                        });
                    } else {
                        alert('An error occurred while processing the bulk action');
                    }
                } catch(e){
                    console.error(e);
                    alert('An error occurred while processing the bulk action');
                }
            });
        });

        // Bulk Leave Button Confirmation Modal
        bulkLeaveBtn.addEventListener('click', (e) => {
            const checked = Array.from(groupSelects).filter(cb => cb.checked);
            let groups = '';
            if(checked.length === 1){
                groups = document.querySelector(`.group-info-manager[data-groupid="${checked[0].value}"]`);
            } else {
                groups = checked.map(cb => document.querySelector(`.group-info-manager[data-groupid="${cb.value}"]`));
            }
            e.preventDefault();
            showLeaveConfirmationModal(groups,
                function onConfirm(){
                    bulkLeaveForm.requestSubmit();
                }
            )
        })

        // Bulk Leave Form
        bulkLeaveForm.addEventListener('submit', async(e) => {
            e.preventDefault();

            bulkLeaveForm.querySelectorAll('input[name="group_ids[]"]').forEach(el => el.remove());

            const checked = Array.from(groupSelects).filter(cb => cb.checked);

            if(checked.length === 0){
                alert('Select at least one group');
                return;
            } else if(checked.length === 1){
                const groupId = checked[0].value;
                bulkLeaveForm.action = `/group/${groupId}/leave-alt`;
            } else {
                const groupIds = checked.map(cb => cb.value).join(',');
                bulkLeaveForm.action = `/group/${groupIds}/leave-alt`;
            }
            
            const formData = new URLSearchParams();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            checked.forEach(cb => {
                formData.append('group_ids[]', cb.value);
            });

            try {
                const response = await fetch(bulkLeaveForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    credentials: 'same-origin',
                });

                if(response.ok){
                    const data = await response.json();
                    alert(data.message || 'Message not found');

                    checked.forEach(cb => {
                        const group = document.querySelector(`.group-info-manager[data-groupid="${cb.value}"]`);
                        if(group){
                            group.remove();
                        }
                        cb.checked = false;
                        toggleRightActions(0);
                    })
                }
            } catch(e){
                console.error("Error: ", e);
            }
        });
    }

    document.addEventListener('DOMContentLoaded',() => {
        addGroupCardEventListeners();
        addBulkActionEventListeners();
        const groupSelects = document.querySelectorAll('.group-select');
        groupSelects.forEach(cb => {
            cb.addEventListener('change', updateRightActions);
        })
        const groups = document.querySelectorAll('.group-info-manager');
        if(groups.length > 0){
            groups[0].classList.add('top-group');
            groups[groups.length-1].classList.add('bottom-group');
        }
    });
</script>
</html>