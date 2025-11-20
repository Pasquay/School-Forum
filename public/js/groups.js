  // Left Side
    let activeSearch = false;
    // Search Bar
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('#group-search');
        const groupsListContainer = document.querySelector('.groups-list');
        let searchTimeout = null;

        searchInput.addEventListener('input', function() {
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
                if (sort === 'active') {
                    const timeButton = document.querySelector('.dropdown-menu button.active');
                    time = timeButton ? timeButton.getAttribute('data-time') : 'all';
                }
                const showJoined = document.querySelector('#show_joined');

                fetch(`groups?search=${encodeURIComponent(query)}&sort=${sort}&time=${time}&show_joined=${showJoined}`, {
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

        function setActiveNav(button) {
            document.querySelectorAll('.left-side .nav button, .dropdown-toggle').forEach(button => {
                button.classList.remove('active');
            });
            button.classList.add('active');
            dropdownMenu.style.display = 'none';
        }

        function fetchGroups(sort, time = 'all') {
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
        navShowJoined.addEventListener('click', function() {
            const activeSort = document.querySelector('.left-side .nav button.active, .dropdown-toggle.active').getAttribute('data-sort');
            if (activeSort === 'active') {
                activeTime = document.querySelector('#active-dropdown button.active').getAttribute('data-time');
                fetchGroups(activeSort, activeTime);
            }
            fetchGroups(activeSort);
        });
    });
    // Event Listeners
    function addJoinLeaveEventListeners() {
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
            // Request Button
            if (button.classList.contains('request-button')) {
                newForm.action = `/group/${groupid}/request`;
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
                                _token: document.querySelector('meta[name="csrf-token"]').content,
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();
                            if (data.requested) {
                                newForm.innerHTML =
                                    `<input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                                    <button type="submit" class="request-button" disabled>Request to Join</button>`;
                                addJoinLeaveEventListeners();
                            }
                        }
                    } catch (error) {
                        console.error("Error: ", error);
                    }
                })
            }
        });
    }

    function addGroupEventListeners() {
        const groups = document.querySelectorAll('.groups-list .group-info');
        groups.forEach(group => {
            if (group.getAttribute('data-listeners-attached') !== '1') {
                // Onclick lead to Group Page
                const groupid = group.dataset.groupid;
                group.addEventListener('click', (e) => {
                    if (
                        e.target.tagName === 'BUTTON' ||
                        e.target.tagName === 'FORM' ||
                        e.target.closest('form')
                    ) {
                        return;
                    }
                    window.location.href = `/group/${groupid}`;
                })
                // Button
                const form = group.querySelector('form');
                const button = group.querySelector('.join-leave button');
                // Join Button
                if (button.classList.contains('join-button')) {
                    form.action = `/group/${groupid}/join`;
                    form.addEventListener('submit', async (e) => {
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

                            if (response.ok) {
                                const data = await response.json();
                                if (data.membership === 1) {
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
                        } catch (error) {
                            console.error('Error: ', error);
                        }
                    })
                }
                // Leave Button
                if (button.classList.contains('leave-button')) {
                    form.action = `/group/${groupid}/leave`;
                    form.addEventListener('submit', async (e) => {
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

                            if (response.ok) {
                                const data = await response.json();
                                if (data.membership === 0) {
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
                        } catch (error) {
                            console.error('Error: ', error);
                        }
                    })
                }

                // Mark Listener
                group.setAttribute('data-listeners-attached', '1');
            }
        })
    }

    addGroupEventListeners();
    addJoinLeaveEventListeners();
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
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
            const searchInput = document.querySelector('#group-search');
            const query = (searchInput.value || '').trim();
            // Most Members
            if (
                navMostMembers.classList.contains('active') &&
                !membersLoading &&
                membersNextPage
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

                        if (!membersNextPage) {
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
            if (
                navNew.classList.contains('active') &&
                !newLoading &&
                newNextPage
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

                        if (!newNextPage) {
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
            if (
                navActiveToday.classList.contains('active') &&
                !activeTodayLoading &&
                activeTodayNextPage
            ) {
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

                        if (!activeTodayNextPage) {
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
            if (
                navActiveWeek.classList.contains('active') &&
                !activeWeekLoading &&
                activeWeekNextPage
            ) {
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

                        if (!activeWeekNextPage) {
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
            if (
                navActiveMonth.classList.contains('active') &&
                !activeMonthLoading &&
                activeMonthNextPage
            ) {
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

                        if (!activeMonthNextPage) {
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
            if (
                navActiveYear.classList.contains('active') &&
                !activeYearLoading &&
                activeYearNextPage
            ) {
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

                        if (!activeYearNextPage) {
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
            if (
                navActiveAll.classList.contains('active') &&
                !activeAllLoading &&
                activeAllNextPage
            ) {
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

                        if (!activeAllNextPage) {
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
    function addRightGroupEventListeners() {
        const rightGroups = document.querySelectorAll('.group-info-minimal');
        rightGroups.forEach(group => {
            // Manage Group Button
            const manageGroupButton = document.querySelector('.manage-group-button');
            manageGroupButton.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = window.GROUP_MANAGER_ROUTE;
            });
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
                            })
                        });

                        if (response.ok) {
                            const data = await response.json();

                            starImg.src = data.starValue 
                                ? window.STAR_ICON 
                                : window.STAR_ALT_ICON;
                        }
                    } catch (error) {
                        console.error('Error: ', error);
                    }
                })
            }
        })
    }
    addRightGroupEventListeners();