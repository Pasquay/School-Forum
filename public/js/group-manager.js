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