function showLeaveConfirmationModal(leaveGroups, onConfirm) {
        let modal = document.querySelector('#leave-confirmation-modal');
        modal.style.display = 'block';

        let groupList = modal.querySelector('#group-list');
        groupList.innerHTML = '';

        let toLeaveGroups = Array.isArray(leaveGroups) ? leaveGroups : [leaveGroups];

        let modalHeader = modal.querySelector('#modal-header');

        if (toLeaveGroups.length === 1) {
            modalHeader.textContent = "Are you sure you want to leave the following group?";
            let p = document.createElement('p');
            let groupName = toLeaveGroups[0].querySelector('.group-info p').textContent;
            p.textContent = groupName;
            groupList.appendChild(p);
            groupList.style.textAlign = 'center';
        } else {
            modalHeader.textContent = "Are you sure you want to leave the following groups?";
            let ul = document.createElement('ul');
            toLeaveGroups.forEach(leaveGroup => {
                let li = document.createElement('li');
                li.textContent = leaveGroup.querySelector('.group-info p').textContent;
                ul.appendChild(li);
            });
            groupList.appendChild(ul);
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
            if (typeof onConfirm === 'function') onConfirm();
        }

        cancelBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            modal.style.display = 'none';
        };
    }

    function addGroupCardEventListeners() {
        const groups = document.querySelectorAll('.group-info-manager');
        groups.forEach(group => {
            // VARIABLES
            const groupid = group.dataset.groupid;
            // Onclick go to group page
            group.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = `/group/${groupid}`;
            });
            // Star Toggle
            const starForm = group.querySelector('.star-toggle-form');
            const starBtn = starForm.querySelector('.star');
            if (starBtn) {
                let starImg = starBtn.querySelector('img');

                starBtn.addEventListener('click', async (e) => {
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

                        if (response.ok) {
                            const data = await response.json();

                            starImg.src = data.starValue ?
                                '{{ asset("/icons/star.png") }}' :
                                '{{ asset("/icons/star-alt.png") }}';
                        }
                    } catch (e) {
                        console.error("Error: ", e);
                    }
                });
            }
            // Mute Toggle
            const muteForm = group.querySelector('.mute-toggle-form');
            const muteBtn = muteForm.querySelector('.mute');
            if (muteBtn) {
                let muteImg = muteBtn.querySelector('img');

                muteBtn.addEventListener('click', async (e) => {
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

                        if (response.ok) {
                            const data = await response.json();

                            muteImg.src = data.muteValue ?
                                '{{ asset("/icons/mute.png") }}' :
                                '{{ asset("/icons/mute-alt.png") }}';
                        }
                    } catch (e) {
                        console.error("Error: ", e);
                    }
                })
            }
            // Manage Button
            const manageButton = group.querySelector('.manage-button');
            if (manageButton) {
                manageButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    window.location.href = `/group/${groupid}?settings=1`;
                });
            }
            // Leave Button
            const leaveForm = group.querySelector('.leave-group-form');
            if (leaveForm) {
                const leaveBtn = leaveForm.querySelector('.leave-button');

                leaveBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    showLeaveConfirmationModal(group,
                        function onConfirm() {
                            leaveForm.requestSubmit();
                        }
                    );
                })

                leaveForm.addEventListener('submit', async (e) => {
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

                        if (response.ok) {
                            const data = await response.json();
                            group.remove();
                        }
                    } catch (e) {
                        console.error("Error: ", e);
                    }
                })
            }
        });
    }

    addGroupCardEventListeners();