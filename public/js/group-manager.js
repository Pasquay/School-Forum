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

                                starImg.src = data.starValue 
                                    ? window.STAR_ICON 
                                    : window.STAR_ALT_ICON;
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

                                muteImg.src = data.muteValue 
                                    ? window.MUTE_ICON 
                                    : window.MUTE_ALT_ICON;
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
        const bulkBtns = bulkActions.querySelectorAll('button');
        const rightActions = [
            '.right-actions button',
            '.right-actions input[type="submit"]',
        ];

        document.querySelectorAll(rightActions.join(',')).forEach(btn => {
            btn.disabled = shouldDisable;
            if(shouldDisable){
                btn.classList.add('disabled-action');
                bulkBtns.forEach(bBtn => {
                    bBtn.style.cursor = 'pointer';
                    bBtn.style.opacity = '1';
                    bBtn.disabled = false;
                });
            } else {
                btn.classList.remove('disabled-action');
                bulkBtns.forEach(bBtn => {
                    bBtn.style.cursor = 'default';
                    bBtn.style.opacity = '0.5';
                    bBtn.disabled = true;
                });
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
                                            ? window.STAR_ICON 
                                            : window.STAR_ALT_ICON;                                        
                                    }
                                } else if(data.action_type === 'mute'){
                                    const muteImg = group.querySelector('.mute-toggle-form .mute img');
                                    if(muteImg){
                                        muteImg.src = (data.action_value == 1)
                                            ? window.MUTE_ICON 
                                            : window.MUTE_ALT_ICON;
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
                        if(!data.owned_groups.map(String).includes(String(cb.value))){
                            const group = document.querySelector(`.group-info-manager[data-groupid="${cb.value}"]`);
                            if(group) group.remove();
                            console.log("MEMBER: ", cb.value, '-', data.owned_groups);
                        } else {
                            console.log("OWNED: ", cb.value, '-', data.owned_groups);
                        }
                        cb.checked = false;
                        toggleRightActions(0);
                    });
                }
            } catch(e){
                console.error("Error: ", e);
            }
        });
    }

    function reloadGroupPagination(data){
        const pagination = document.querySelector('.group-pagination');
        if(pagination){
            pagination.innerHTML = '';
            if(data.last_page > 1){
                const nav = document.createElement('nav');
                nav.className = 'pagination-nav';

                // Previous Button
                    if(data.curr_page > 1){
                        const prev = document.createElement('a');
                        prev.href = '#';
                        prev.className = 'pagination-btn';
                        prev.textContent = 'Prev';
                        prev.addEventListener('click', (e) => {
                            e.preventDefault();
                            goToPage(data.curr_page - 1);
                        });
                        nav.appendChild(prev);
                    }

                // Page Numbers
                    for(let i=1; i<=data.last_page; i++){
                        if(i === data.curr_page){
                            const span = document.createElement('span');
                            span.className = 'pagination-btn';
                            span.classList.add('active');
                            span.textContent = i;
                            nav.appendChild(span);
                        } else {
                            const page = document.createElement('a');
                            page.href = '#';
                            page.className = 'pagination-btn';
                            page.textContent = i;
                            page.addEventListener('click', (e) => {
                                e.preventDefault();
                                goToPage(i);
                            });
                            nav.appendChild(page);
                        }
                    }
                    
                // Next Button
                    if(data.curr_page < data.last_page){
                        const next = document.createElement('a');
                        next.href = '#';
                        next.className = 'pagination-btn';
                        next.textContent = 'Next';
                        next.addEventListener('click', (e) => {
                            e.preventDefault();
                            goToPage(data.curr_page + 1);
                        });
                        nav.appendChild(next);
                    }

                pagination.appendChild(nav);
            }
        }
    }

    function goToPage(page){
        const searchForm = document.querySelector('#group-search-form');
        const groupListContainer = document.querySelector('#group-list-container');
        const sortValue = document.querySelector('.sort-btn.active').getAttribute('data-sort');

        const formData = new FormData(searchForm);
        formData.set('sort', sortValue);
        
        const params = new URLSearchParams(formData);
        params.set('page', page);

        fetch(searchForm.action + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(res => res.json())
        .then(data => {
            groupListContainer.innerHTML= data.html || '<p class="empty">No groups found.</p>';
            addGroupCardEventListeners();
            reloadGroupPagination({
                curr_page: data.curr_page,
                last_page: data.last_page
            });
        });
    }

    function addSearchSectionEventListeners(){
        // Variables
            const searchSection = document.querySelector('.search-bar-row');
            const searchForm = document.querySelector('#group-search-form');
            const searchBtn = document.querySelector('#search-btn');

            const filterBtn = searchSection.querySelector('#toggle-filters-btn');
            const filtersPanel = document.querySelector('#search-filters-panel');
            const clearFiltersBtn = document.querySelector('#clear-filters-btn');

            const sortSection = document.querySelector('.sort-buttons');
            const sortBtns = sortSection.querySelectorAll('.sort-btn');
        // Search Bar

        // Search Button
            searchBtn.addEventListener('click', (e) => {
                e.preventDefault();

                const sortValue = document.querySelector('.sort-btn.active').getAttribute('data-sort');
                
                const groupListContainer = document.querySelector('#group-list-container');
                const formData = new FormData(searchForm);
                formData.set('sort', sortValue);

                const params = new URLSearchParams(formData).toString();

                fetch(searchForm.action + '?' + params, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    groupListContainer.innerHTML = data.html || '<p class="empty">No groups found.</p>';
                    addGroupCardEventListeners();
                    reloadGroupPagination(data);
                })
            });
        // Filter Button
            filterBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if(filtersPanel.style.display === 'none'){
                    filtersPanel.style.display = 'inline';
                } else {
                    filtersPanel.style.display = 'none';
                }
            });
        // Sorts
            sortBtns.forEach(btn => {
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    sortBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            })
        // Clear Filter
            clearFiltersBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                document.querySelectorAll('#search-filters-panel input[type="checkbox"]').forEach(cb => cb.checked = false);

                document.querySelectorAll('.sort-btn').forEach(btn => btn.classList.remove('active'));
                document.querySelector('.sort-btn[data-sort="membership"]').classList.add('active');
            });
    }

    document.addEventListener('DOMContentLoaded',() => {
        addGroupCardEventListeners();
        addBulkActionEventListeners();
        addSearchSectionEventListeners()
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