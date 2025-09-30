// Show Group Settings Modal
function showGroupSettingsModal() {
    document.getElementById('groupSettingsModal').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

// Close Group Settings Modal
function closeGroupSettingsModal() {
    document.getElementById('groupSettingsModal').style.display = 'none';
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            // Add active class to clicked tab and corresponding content
            btn.classList.add('active');
            const tabId = btn.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Close modal when clicking outside
    document.getElementById('groupSettingsModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeGroupSettingsModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeGroupSettingsModal();
        }
    });
});

// Transfer modal functions (if needed later)
function showTransferModal() {
    document.getElementById('transferModal').style.display = 'flex';
}

function hideTransferModal() {
    document.getElementById('transferModal').style.display = 'none';
}

// Member search functionality - NAME ONLY
document.addEventListener('DOMContentLoaded', function() {
    const memberSearch = document.getElementById('memberSearch');

    if (memberSearch) {
        // Add data attributes to member items for name searching only
        const memberItems = document.querySelectorAll('#members .member-list:last-of-type .member-item');
        memberItems.forEach(function(item) {
            const nameElement = item.querySelector('h5');

            if (nameElement) {
                const memberName = nameElement.textContent.toLowerCase();
                item.setAttribute('data-member-name', memberName);
            }
        });

        // Create no results message
        const memberList = document.querySelector('#members .member-list:last-of-type');
        const noResultsDiv = document.createElement('div');
        noResultsDiv.className = 'no-search-results';
        noResultsDiv.textContent = 'No members found matching your search.';
        memberList.appendChild(noResultsDiv);

        // Search functionality - NAME ONLY
        memberSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            const memberItems = document.querySelectorAll('#members .member-list:last-of-type .member-item');
            const noResults = document.querySelector('.no-search-results');
            let visibleCount = 0;

            memberItems.forEach(function(item) {
                const memberName = item.getAttribute('data-member-name') || '';

                if (searchTerm === '' || memberName.includes(searchTerm)) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            // Show/hide no results message
            if (visibleCount === 0 && searchTerm !== '') {
                noResults.classList.add('show');
            } else {
                noResults.classList.remove('show');
            }
        });

        // Clear search when modal is closed
        const modal = document.getElementById('groupSettingsModal');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    if (modal.style.display === 'none') {
                        memberSearch.value = '';
                        memberItems.forEach(function(item) {
                            item.classList.remove('hidden');
                        });
                        document.querySelector('.no-search-results').classList.remove('show');
                    }
                }
            });
        });
        observer.observe(modal, { attributes: true });
    }
});

// User search with API call - Updated
let searchTimeout;
let currentSearchTerm = '';
let selectedUsers = [];

// Update the showAddMembersModal function
function showAddMembersModal() {
    document.getElementById('addMembersModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';

    // Clear previous results and show empty state
    const memberList = document.querySelector('#addMembersModal .member-list');
    const userSearch = document.getElementById('userSearch');

    memberList.innerHTML = '<div class="search-prompt">Start typing to search for users...</div>';
    userSearch.value = '';
    userSearch.focus();

    // Reset selected users
    selectedUsers = [];
    updateSelectedCount();
}

// Add search functionality
document.addEventListener('DOMContentLoaded', function() {
    const userSearch = document.getElementById('userSearch');

    if (userSearch) {
        userSearch.addEventListener('input', function() {
            const searchTerm = this.value.trim();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // If search is empty, show prompt
            if (searchTerm === '') {
                showSearchPrompt();
                return;
            }

            // Only search if term is at least 2 characters
            if (searchTerm.length < 2) {
                showMinimumCharactersMessage();
                return;
            }

            // Debounce the search (wait 300ms after user stops typing)
            searchTimeout = setTimeout(() => {
                if (searchTerm !== currentSearchTerm) {
                    currentSearchTerm = searchTerm;
                    searchUsers(searchTerm);
                }
            }, 300);
        });
    }
});

function showSearchPrompt() {
    const memberList = document.querySelector('#addMembersModal .member-list');
    memberList.innerHTML = '<div class="search-prompt">Start typing to search for users...</div>';
}

function showMinimumCharactersMessage() {
    const memberList = document.querySelector('#addMembersModal .member-list');
    memberList.innerHTML = '<div class="search-prompt">Type at least 2 characters to search...</div>';
}

function showLoadingState() {
    const memberList = document.querySelector('#addMembersModal .member-list');
    memberList.innerHTML = '<div class="loading-state">Searching users...</div>';
}

async function searchUsers(searchTerm) {
    showLoadingState();

    // Get group ID from the current page URL or a data attribute
    const groupId = getGroupIdFromPage();

    try {
        const response = await fetch(`/groups/${groupId}/search-users?q=${encodeURIComponent(searchTerm)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        });

        const data = await response.json();

        if (data.success) {
            displayUsers(data.users);
        } else {
            showErrorMessage(data.message || 'Failed to search users');
        }
    } catch (error) {
        console.error('Search error:', error);
        showErrorMessage('Network error. Please try again.');
    }
}

function displayUsers(users) {
    const memberList = document.querySelector('#addMembersModal .member-list');

    if (users.length === 0) {
        memberList.innerHTML = '<div class="no-users-found">No users found matching your search.</div>';
        return;
    }

    const usersHtml = users.map(user => `
        <label class="user-item ${selectedUsers.includes(user.id) ? 'selected' : ''}" data-user-id="${user.id}">
            <div class="user-info">
                <div class="user-avatar">${user.name.charAt(0).toUpperCase()}</div>
                <div>
                    <h5>${user.name}</h5>
                    <p>${user.email}</p>
                </div>
            </div>
            <input type="checkbox" 
                   name="user_ids[]" 
                   value="${user.id}" 
                   ${selectedUsers.includes(user.id) ? 'checked' : ''} 
                   onchange="toggleUserCheckbox(${user.id}, this)"
                   class="user-checkbox">
            <span class="checkbox-label"></span>
        </label>
    `).join('');

    memberList.innerHTML = usersHtml;
}

function showErrorMessage(message) {
    const memberList = document.querySelector('#addMembersModal .member-list');
    memberList.innerHTML = `<div class="error-message">${message}</div>`;
}

function toggleUserCheckbox(userId, checkbox) {
    const userItem = checkbox.closest('.user-item');

    if (checkbox.checked) {
        // Add to selection
        if (!selectedUsers.includes(userId)) {
            selectedUsers.push(userId);
        }
        userItem.classList.add('selected');
    } else {
        // Remove from selection
        selectedUsers = selectedUsers.filter(id => id !== userId);
        userItem.classList.remove('selected');
    }
    updateSelectedCount();
}

function updateSelectedCount() {
    const sendBtn = document.getElementById('inviteSubmitBtn');

    if (sendBtn) {
        sendBtn.disabled = selectedUsers.length === 0;
        if (selectedUsers.length > 0) {
            sendBtn.textContent = `Invite ${selectedUsers.length} User${selectedUsers.length === 1 ? '' : 's'}`;
        } else {
            sendBtn.textContent = 'Invite Selected';
        }
    }

    // Log selected users for debugging
    console.log('Selected users:', selectedUsers);
}

// Helper function to get group ID from the current page
function getGroupIdFromPage() {
    // Method 1: Get from URL path (assumes URL is like /group/123)
    const path = window.location.pathname;
    const groupIdMatch = path.match(/\/group\/(\d+)/);

    if (groupIdMatch) {
        return groupIdMatch[1];
    }

    // Method 2: Get from a data attribute on the modal or another element
    const modal = document.getElementById('addMembersModal');
    if (modal && modal.dataset.groupId) {
        return modal.dataset.groupId;
    }

    // Method 3: Get from a hidden input or global variable
    if (window.groupId) {
        return window.groupId;
    }

    console.error('Could not determine group ID');
    return null;
}

// Update closeAddMembersModal to reset search
function closeAddMembersModal() {
    document.getElementById('addMembersModal').style.display = 'none';
    document.body.style.overflow = 'auto';

    // Reset search and selections
    selectedUsers = [];
    currentSearchTerm = '';
    const userSearch = document.getElementById('userSearch');
    if (userSearch) {
        userSearch.value = '';
    }
    showSearchPrompt();
}

document.addEventListener('DOMContentLoaded', function() {
            // Main content tab functionality
            const mainTabBtns = document.querySelectorAll('.main-tab-btn');
            const mainTabContents = document.querySelectorAll('.main-tab-content');
            let assignmentsLoaded = false;

            mainTabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;

                    // Remove active class from all main tabs and contents
                    mainTabBtns.forEach(b => b.classList.remove('active'));
                    mainTabContents.forEach(c => c.classList.remove('active'));

                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab + '-content').classList.add('active');

                    // Load assignments if assignments tab is clicked and not loaded yet
                    if (targetTab === 'assignments' && !assignmentsLoaded) {
                        loadAssignments();
                    }
                });
            });

            function loadAssignments() {
                const assignmentsContent = document.getElementById('assignments-content');

                // Show loading state
                assignmentsContent.innerHTML = `
            <div class="assignments-container">
                <div class="loading" style="text-align: center; padding: 40px; color: #6b7280;">
                    Loading assignments...
                </div>
            </div>
        `;

                // Check if groupData is available
                if (!window.groupData || !window.groupData.id) {
                    console.error('Group data is not available:', window.groupData);
                    assignmentsContent.innerHTML = `
                        <div class="assignments-container">
                            <div class="no-assignments">
                                <p>Error: Unable to load assignments - Group data not found</p>
                            </div>
                        </div>
                    `;
                    return;
                }

                console.log('Loading assignments for group:', window.groupData.id);

                fetch(`/group/${window.groupData.id}/assignments`, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 403) {
                                throw new Error('You do not have permission to view assignments in this group.');
                            } else if (response.status === 404) {
                                throw new Error('Group not found.');
                            } else {
                                throw new Error(`Server error: ${response.status}`);
                            }
                        }
                        return response.json();
                    })
                    .then(data => {
                        assignmentsLoaded = true;
                        console.log('Assignments data:', data); // Debug log

                        if (data.assignments && data.assignments.length > 0) {
                            displayAssignments(data.assignments);
                        } else {
                            // Explicitly handle the case where there are no assignments
                            assignmentsContent.innerHTML = `
                    <div class="assignments-container">
                        <div style="text-align: center; padding: 60px;">
                            <div style="font-size: 48px; margin-bottom: 16px;">📚</div>
                            <h3 style="color: #6b7280; margin-bottom: 8px;">No assignments yet</h3>
                            <p style="color: #9ca3af;">Your teacher hasn't created any assignments for this group.</p>
                        </div>
                    </div>
                `;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading assignments:', error);
                        assignmentsContent.innerHTML = `
                <div class="assignments-container">
                    <div style="text-align: center; padding: 40px; color: #dc2626;">
                        <div style="font-size: 48px; margin-bottom: 16px;">⚠️</div>
                        <p class="error">${error.message}</p>
                        <button onclick="assignmentsLoaded = false; document.querySelector('.main-tab-btn[data-tab=\\'assignments\\']').click();" style="margin-top: 10px; padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">
                            Try Again
                        </button>
                    </div>
                </div>
            `;
                    });
            }

            function displayAssignments(assignments) {
                const assignmentsContent = document.getElementById('assignments-content');

                let assignmentsHTML = '<div class="assignments-container"><div class="assignments-list">';

                assignments.forEach(assignment => {
                    assignmentsHTML += createAssignmentCardHTML(assignment);
                });

                assignmentsHTML += '</div></div>';
                assignmentsContent.innerHTML = assignmentsHTML;
            }

            function createAssignmentCardHTML(assignment) {
                const statusBadges = [];
                if (assignment.visibility === 'draft') {
                    statusBadges.push('<span class="status-badge status-draft">Draft</span>');
                } else {
                    statusBadges.push('<span class="status-badge status-published">Published</span>');
                }

                if (assignment.is_overdue) {
                    statusBadges.push('<span class="status-badge status-overdue">Overdue</span>');
                }

                if (assignment.is_closed) {
                    statusBadges.push('<span class="status-badge status-overdue">Closed</span>');
                }

                return `
            <div class="assignment-card">
                <div class="assignment-header">
                    <h3 class="assignment-title">${assignment.assignment_name}</h3>
                    <span class="assignment-type">${assignment.assignment_type}</span>
                </div>
                <div class="assignment-meta">
                    <span> ${assignment.creator_name}</span>
                    <span> ${assignment.submission_type}</span>
                    <span> ${assignment.max_points} points</span>
                    ${assignment.date_assigned ? `<span> Assigned: ${assignment.date_assigned}</span>` : ''}
                    <span> Due: ${assignment.date_due}</span>
                    ${assignment.close_date ? `<span> Closes: ${assignment.close_date}</span>` : ''}
                </div>
                ${assignment.description ? `<div class="assignment-description">${assignment.description}</div>` : ''}
                <div class="assignment-status">
                    ${statusBadges.join('')}
                </div>
            </div>
        `;
        }
    });

// For create assignemnts modal

// Show and close Assignment Modal
function openCreateAssignmentModal() {
    document.getElementById('createAssignmentModal').style.display = 'flex';
    document.body.style.overflow = 'hidden'; 
}

function closeCreateAssignmentModal() {
    document.getElementById('createAssignmentModal').style.display = 'none';
    document.body.style.overflow = 'auto'; 
    // Reset form if it exists
    const form = document.getElementById('createAssignmentForm');
    if (form) {
        form.reset();
    }
}