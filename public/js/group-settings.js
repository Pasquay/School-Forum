// Helper function to parse datetime as local time (not UTC)
function parseLocalDateTime(dateString) {
    if (!dateString) return null;
    try {
        // Remove timezone markers if present (Z or +00:00)
        let cleanDate = dateString.replace(/Z$/, '').replace(/\.\d{6}$/, '').replace(/[+-]\d{2}:\d{2}$/, '');
        
        // dateString format: "2025-10-14T18:07:00" or "2025-10-14T18:07"
        const [datePart, timePart] = cleanDate.split('T');
        if (!datePart || !timePart) return new Date(dateString); // Fallback
        
        const [year, month, day] = datePart.split('-');
        const timeComponents = timePart.split(':');
        const hour = timeComponents[0];
        const minute = timeComponents[1];
        const second = timeComponents[2] || 0;
        
        return new Date(year, month - 1, day, hour, minute, second);
    } catch (e) {
        console.error('Error parsing date:', dateString, e);
        return new Date(dateString); // Fallback to default parsing
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.get('settings') === '1') showGroupSettingsModal();
});

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
    // Load sidebar assignments on page load
    loadSidebarAssignments();
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Close modal when clicking outside
    const groupSettingsModal = document.getElementById('groupSettingsModal');
    if (groupSettingsModal) {
        groupSettingsModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeGroupSettingsModal();
            }
        });
    }

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

// Load sidebar assignments
function loadSidebarAssignments() {
    const sidebarList = document.getElementById('sidebar-assignments-list');
    
    if (!sidebarList) return;
    
    sidebarList.innerHTML = '<div class="loading">Loading assignments...</div>';
    
    if (!window.groupData || !window.groupData.id) {
        console.error('Group data not available');
        sidebarList.innerHTML = '<div class="error-message">Unable to load assignments</div>';
        return;
    }
    
    fetch(`/group/${window.groupData.id}/assignments`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Failed to load assignments: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.assignments && data.assignments.length > 0) {
            displaySidebarAssignments(data.assignments);
        } else {
            sidebarList.innerHTML = '<div class="no-assignments"><p>No assignments yet...</p></div>';
        }
    })
    .catch(error => {
        console.error('Error loading assignments:', error);
        sidebarList.innerHTML = '<div class="error-message">Failed to load assignments</div>';
    });
}

function displaySidebarAssignments(assignments) {
    const sidebarList = document.getElementById('sidebar-assignments-list');
    
    if (!sidebarList) return;
    
    // Filter out assignments that are 10+ days late or closed
    const now = new Date();
    const tenDaysAgo = new Date(now.getTime() - (10 * 24 * 60 * 60 * 1000));
    
    const filteredAssignments = assignments.filter(assignment => {
        // If assignment is closed, don't show it
        if (assignment.is_closed) return false;
        
        // If assignment has a close_date and it's passed, don't show it
        if (assignment.close_date) {
            const closeDate = new Date(assignment.close_date);
            if (closeDate < now) return false;
        }
        
        // If assignment is more than 10 days overdue, don't show it
        if (assignment.date_due) {
            const dueDate = new Date(assignment.date_due);
            if (dueDate < tenDaysAgo) return false;
        }
        
        return true;
    });
    
    if (filteredAssignments.length === 0) {
        sidebarList.innerHTML = '<div class="no-assignments"><p>No active assignments...</p></div>';
        return;
    }
    
    const assignmentsHtml = filteredAssignments.map(assignment => createSidebarAssignmentHTML(assignment)).join('');
    sidebarList.innerHTML = assignmentsHtml;
}

function createSidebarAssignmentHTML(assignment) {
    const statusClasses = [];
    if (assignment.is_overdue) statusClasses.push('overdue');
    if (assignment.is_closed) statusClasses.push('closed');
    
    const statusBadges = [];
    if (assignment.is_closed) {
        statusBadges.push('<span class="status-badge closed">Closed</span>');
    } else if (assignment.is_overdue) {
        statusBadges.push('<span class="status-badge overdue">Overdue</span>');
    } else {
        statusBadges.push('<span class="status-badge active">Active</span>');
    }
    
    if (assignment.visibility === 'draft') {
        statusBadges.push('<span class="status-badge draft">Draft</span>');
    }
    
    // Add submission status badge for students
    if (assignment.submission_status) {
        const statusText = {
            'not_submitted': 'Not Submitted',
            'draft': 'Draft Saved',
            'submitted': 'Submitted',
            'submitted_late': 'Submitted (Late)',
            'graded': 'Graded'
        }[assignment.submission_status] || 'Unknown';
        
        const statusClass = {
            'not_submitted': 'not-submitted',
            'draft': 'draft',
            'submitted': 'submitted',
            'submitted_late': 'late',
            'graded': 'graded'
        }[assignment.submission_status] || 'unknown';
        
        statusBadges.push(`<span class="status-badge ${statusClass}">${statusText}</span>`);
    }
    
    // Show edit button for teachers/moderators only
    let actionButtons = '';
    if (assignment.can_edit) {
        actionButtons = `<button class="edit-assignment-btn" onclick="openEditAssignmentModal(${assignment.id})">Edit</button>`;
    }
    
    // Make entire card clickable for students
    const clickHandler = assignment.can_edit ? 
        '' : 
        `onclick="openStudentAssignmentModal(${assignment.id})" style="cursor: pointer;"`;
    
    return `
        <div class="assignment-item ${statusClasses.join(' ')}" data-assignment-id="${assignment.id}" ${clickHandler}>
            <div class="assignment-header">
                <h4 class="assignment-name">${assignment.assignment_name}</h4>
                <span class="assignment-type">${assignment.assignment_type.charAt(0).toUpperCase() + assignment.assignment_type.slice(1)}</span>
            </div>
            <div class="assignment-details">
                <div class="assignment-due">
                    <strong>Due:</strong>
                    <span class="due-date">${new Date(assignment.date_due).toLocaleString()}</span>
                </div>
                ${assignment.max_points ? `
                    <div class="assignment-points">
                        <strong>Points:</strong> ${assignment.max_points}
                    </div>
                ` : ''}
            </div>
            <div class="assignment-status">
                ${statusBadges.join('')}
                ${actionButtons}
            </div>
        </div>
    `;
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
                const editButton = assignment.can_edit ? 
                `<button class="edit-assignment-btn" onclick="event.stopPropagation(); openEditAssignmentModal(${assignment.id})">Edit</button>` : '';

                // Determine click handler based on user role
                const clickHandler = assignment.can_edit ? 
                    `onclick="openEditAssignmentModal(${assignment.id})"` : 
                    `onclick="openStudentAssignmentModal(${assignment.id})"`;

                return `
            <div class="assignment-card" ${clickHandler} style="cursor: pointer;">
                <div class="assignment-header">
                    <h3 class="assignment-title">${assignment.assignment_name}</h3>
                    <span class="assignment-type">${assignment.assignment_type}</span>
                </div>
                <div class="assignment-meta">
                    <span> ${assignment.submission_type}</span>
                    <span> ${assignment.max_points} points</span>
                </div>
                ${assignment.description ? `<div class="assignment-description">${assignment.description}</div>` : ''}
                
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
    const form = document.getElementById('editAssignmentForm');
    if (form) {
        form.reset();
    }
}

function openEditAssignmentModal(assignmentId){
    console.log('openEditAssignmentModal called with ID:', assignmentId);
    console.log('Type of assignmentId:', typeof assignmentId);
    
    if (!assignmentId) {
        console.error('Assignment ID is required');
        alert('Assignment ID is missing!');
        return;
    }

    if (!window.groupData || !window.groupData.id) {
        console.error('Group data not available:', window.groupData);
        alert('Group data not available. Please refresh the page.');
        return;
    }

    document.getElementById('editAssignmentModal').style.display = "flex";
    document.body.style.overflow = 'hidden'; 

    loadAssignmentData(assignmentId);
}

function loadAssignmentData(assignmentId) {
    console.log('Loading assignment data for ID:', assignmentId);
    console.log('Group data:', window.groupData);
    
    // Show loading state in modal
    const form = document.getElementById('editAssignmentForm');
    if (form) {
        // Disable form while loading
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => input.disabled = true);
    }
    
    const url = `/group/${window.groupData.id}/assignments/${assignmentId}`;
    console.log('Fetching from URL:', url);
    
    fetch(url, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response body:', text);
                throw new Error(`Failed to load assignment: ${response.status} - ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Assignment data received:', data);
        console.log('Quiz questions in response:', data.assignment?.quiz_questions);
        populateEditForm(data.assignment);
    })
    .catch(error => {
        console.error('Error loading assignment:', error);
        alert(`Failed to load assignment details: ${error.message}`);
        closeEditAssignmentModal();
    });
}

function populateEditForm(assignment) {
    const form = document.getElementById('editAssignmentForm');
    if (!form) {
        console.error('Edit form not found');
        return;
    }
    
    // Store current assignment globally
    currentEditAssignment = assignment;
    
    // Show/hide tabs based on assignment state
    const questionsTabBtn = document.getElementById('edit-questions-tab-btn');
    const analyticsTabBtn = document.querySelector('[data-tab="edit-analytics"]');
    const submissionsTabBtn = document.querySelector('[data-tab="edit-submissions"]');
    
    console.log('Assignment type:', assignment.assignment_type);
    console.log('Quiz questions from API:', assignment.quiz_questions);
    
    // Show Questions tab only for quiz/exam types
    if (assignment.assignment_type === 'quiz' || assignment.assignment_type === 'exam') {
        questionsTabBtn.style.display = 'block';
        // Load quiz questions if available
        if (assignment.quiz_questions && assignment.quiz_questions.length > 0) {
            console.log('Loading', assignment.quiz_questions.length, 'questions into editor');
            editQuizQuestions = assignment.quiz_questions;
            renderEditQuizQuestions();
        } else {
            console.log('No quiz questions found, showing empty state');
            editQuizQuestions = [];
            renderEditQuizQuestions();
        }
    } else {
        questionsTabBtn.style.display = 'none';
    }
    
    // Hide Analytics tab if no graded submissions exist
    const hasGradedSubmissions = assignment.graded_count && assignment.graded_count > 0;
    if (!hasGradedSubmissions) {
        analyticsTabBtn.style.display = 'none';
        analyticsTabBtn.title = 'Analytics available after grading submissions';
    } else {
        analyticsTabBtn.style.display = 'block';
        analyticsTabBtn.title = '';
    }
    
    // Hide Submissions tab if assignment is draft or has no submissions
    const hasSubmissions = assignment.submission_count && assignment.submission_count > 0;
    const isPublished = assignment.visibility === 'published';
    if (!isPublished || !hasSubmissions) {
        submissionsTabBtn.style.display = 'none';
        if (!isPublished) {
            submissionsTabBtn.title = 'Publish assignment to see submissions';
        } else {
            submissionsTabBtn.title = 'No submissions yet';
        }
    } else {
        submissionsTabBtn.style.display = 'block';
        submissionsTabBtn.title = '';
    }
    
    // Set assignment ID in hidden field
    form.querySelector('#edit_assignment_id').value = assignment.id;
    
    // Set form action to include assignment ID
    form.action = `/group/${window.groupData.id}/assignments/${assignment.id}`;
    console.log('Form action set to:', form.action);
    
    // Populate form fields with updated IDs
    form.querySelector('#edit_assignment_name').value = assignment.assignment_name || '';
    form.querySelector('#edit_description').value = assignment.description || '';
    form.querySelector('#edit_assignment_type').value = assignment.assignment_type || '';
    form.querySelector('#edit_submission_type').value = assignment.submission_type || '';
    form.querySelector('#edit_max_points').value = assignment.max_points || '';
    form.querySelector('#edit_visibility').value = assignment.visibility || 'draft';
    // Removed: assignments don't have external_link field (it's on submissions only)
    
    // Handle datetime fields - convert from server format to input format
    if (assignment.date_assigned) {
        const dateAssigned = parseLocalDateTime(assignment.date_assigned);
        form.querySelector('#edit_date_assigned').value = formatDateTimeForInput(dateAssigned);
    }
    
    if (assignment.date_due) {
        const dateDue = parseLocalDateTime(assignment.date_due);
        form.querySelector('#edit_date_due').value = formatDateTimeForInput(dateDue);
    }
    
    if (assignment.close_date) {
        const closeDate = parseLocalDateTime(assignment.close_date);
        form.querySelector('#edit_date_close').value = formatDateTimeForInput(closeDate);
    }
    
    // Set late submission fields
    const allowLateCheckbox = form.querySelector('#edit_allow_late_submissions');
    if (allowLateCheckbox) {
        // Explicitly convert to boolean - handles 0, 1, false, true, null
        allowLateCheckbox.checked = assignment.allow_late_submissions === 1 || assignment.allow_late_submissions === true;
        toggleEditLatePenalty();
    }
    
    const latePenaltyInput = form.querySelector('#edit_late_penalty_percentage');
    if (latePenaltyInput) {
        latePenaltyInput.value = assignment.late_penalty_percentage || 0;
    }
    
    // Set resubmission control fields
    const allowResubmissionsCheckbox = form.querySelector('#edit_allow_resubmissions');
    if (allowResubmissionsCheckbox) {
        allowResubmissionsCheckbox.checked = assignment.allow_resubmissions === 1 || assignment.allow_resubmissions === true;
        toggleEditResubmissions();
    }
    
    const maxAttemptsSelect = form.querySelector('#edit_max_attempts');
    if (maxAttemptsSelect) {
        maxAttemptsSelect.value = assignment.max_attempts ?? -1;
    }
    
    // Re-enable form inputs
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => input.disabled = false);
}

function formatDateTimeForInput(date) {
    // Format: YYYY-MM-DDTHH:MM
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    
    return `${year}-${month}-${day}T${hours}:${minutes}`;
}

function closeEditAssignmentModal() {
    document.getElementById('editAssignmentModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    const form = document.getElementById('editAssignmentForm');
    if (form) {
        form.reset();
        // Reset form action
        form.action = '';
    }
}

// Delete assignment function
function confirmDeleteAssignment() {
    const assignmentId = document.getElementById('edit_assignment_id').value;
    
    console.log('Delete function called, assignment ID:', assignmentId);
    console.log('Group data:', window.groupData);
    
    if (!assignmentId) {
        alert('No assignment selected');
        return;
    }
    
    if (!confirm('Are you sure you want to delete this assignment? This action cannot be undone.')) {
        return;
    }
    
    // Set the form action to the correct delete URL
    const deleteUrl = `/group/${window.groupData.id}/assignments/${assignmentId}`;
    console.log('Submitting DELETE form to:', deleteUrl);
    
    const deleteForm = document.getElementById('delete-assignment-form');
    deleteForm.action = deleteUrl;
    deleteForm.submit();
}

// Keep old function name for backwards compatibility if needed elsewhere
function deleteAssignment() {
    confirmDeleteAssignment();
}

// Handle edit assignment form submission
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editAssignmentForm');
    
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.textContent;
            
            // Disable submit button and show loading state
            submitButton.disabled = true;
            submitButton.textContent = 'Updating...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to update assignment');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Assignment updated:', data);
                
                // Close modal
                closeEditAssignmentModal();
                
                // Reload sidebar assignments to show updated data
                loadSidebarAssignments();
                
                // // Show success message (you can customize this with a nicer notification)
                // alert(data.message || 'Assignment updated successfully!');
                
                // Redirect to group page to see changes
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Reload page to show updated assignment
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Error updating assignment:', error);
                alert('Failed to update assignment: ' + error.message);
                
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = originalButtonText;
            });
        });
    }
});

// ==================== CREATE ASSIGNMENT - QUIZ BUILDER ====================

let createQuizQuestions = [];
let createQuestionIdCounter = 1;

// Handle assignment type change in create modal
function handleAssignmentTypeChange() {
    const assignmentType = document.getElementById('assignment_type').value;
    const submissionTypeGroup = document.getElementById('submission_type_group');
    const submissionTypeSelect = document.getElementById('submission_type');
    const submissionTypeRequired = document.getElementById('submission_type_required');
    const quizNote = document.getElementById('quiz_submission_note');
    const quizBuilderSection = document.getElementById('create_quiz_builder_section');
    const timeLimitGroup = document.getElementById('time_limit_group');
    const maxPointsInput = document.getElementById('max_points');
    const autoPointsNote = document.getElementById('auto_points_note');
    const questionsTabBtn = document.getElementById('create-questions-tab-btn');
    
    if (assignmentType === 'quiz' || assignmentType === 'exam') {
        // Show Questions tab
        if (questionsTabBtn) {
            questionsTabBtn.style.display = 'block';
        }
        // Grey out and set submission type to quiz
        submissionTypeSelect.value = 'quiz';
        submissionTypeSelect.disabled = true;
        submissionTypeSelect.style.opacity = '0.6';
        submissionTypeSelect.style.cursor = 'not-allowed';
        submissionTypeSelect.required = false; // Remove required validation
        submissionTypeSelect.removeAttribute('required'); // Ensure attribute is removed
        submissionTypeRequired.style.display = 'none';
        quizNote.style.display = 'block';
        quizBuilderSection.style.display = 'block';
        timeLimitGroup.style.display = 'block'; // Show time limit field
        
        // Disable max points and show auto-calc note
        maxPointsInput.disabled = true;
        maxPointsInput.style.opacity = '0.6';
        maxPointsInput.style.cursor = 'not-allowed';
        maxPointsInput.required = false;
        maxPointsInput.removeAttribute('required');
        autoPointsNote.style.display = 'block';
        
        // Calculate points from questions
        updateMaxPointsFromQuestions();
    } else {
        // Hide Questions tab
        if (questionsTabBtn) {
            questionsTabBtn.style.display = 'none';
            // If currently on Questions tab, switch to Details
            const questionsTab = document.getElementById('create-questions-tab');
            if (questionsTab && questionsTab.classList.contains('active')) {
                switchCreateTab('create-details');
            }
        }
        
        // Enable submission type
        submissionTypeSelect.disabled = false;
        submissionTypeSelect.style.opacity = '1';
        submissionTypeSelect.style.cursor = 'pointer';
        submissionTypeSelect.required = true;
        submissionTypeSelect.setAttribute('required', 'required');
        submissionTypeRequired.style.display = 'inline';
        quizNote.style.display = 'none';
        quizBuilderSection.style.display = 'none';
        timeLimitGroup.style.display = 'none'; // Hide time limit field
        
        // Enable max points
        maxPointsInput.disabled = false;
        maxPointsInput.style.opacity = '1';
        maxPointsInput.style.cursor = 'text';
        maxPointsInput.required = true;
        maxPointsInput.setAttribute('required', 'required');
        autoPointsNote.style.display = 'none';
        
        createQuizQuestions = []; // Clear questions
    }
}

// Update max points from quiz questions
function updateMaxPointsFromQuestions() {
    const totalPoints = createQuizQuestions.reduce((sum, q) => sum + (parseInt(q.points) || 0), 0);
    const maxPointsInput = document.getElementById('max_points');
    // Set to total points, or 1 if no questions yet (minimum for form validation)
    maxPointsInput.value = totalPoints > 0 ? totalPoints : 1;
}

// Add question in create modal
function addCreateQuizQuestion() {
    const newQuestion = {
        id: null,
        question_text: '',
        question_type: 'multiple_choice',
        points: 1,
        order: createQuizQuestions.length + 1,
        options: [{ option_text: '', is_correct: false }]
    };
    
    createQuizQuestions.push(newQuestion);
    renderCreateQuizQuestions();
}

// Render create quiz questions
function renderCreateQuizQuestions() {
    const container = document.getElementById('create_quiz_questions_list');
    
    if (createQuizQuestions.length === 0) {
        container.innerHTML = '<div class="no-questions-message">No questions yet. Click "Add Question" to get started.</div>';
        updateMaxPointsFromQuestions(); // Update even when empty
        return;
    }
    
    container.innerHTML = createQuizQuestions.map((question, index) => {
        return createQuestionHTMLForCreate(question, index);
    }).join('');
    
    // Update max points whenever questions are rendered
    updateMaxPointsFromQuestions();
}

// Create question HTML for create modal
function createQuestionHTMLForCreate(question, index) {
    const questionNumber = index + 1;
    const tempId = question.id || `temp-${createQuestionIdCounter++}`;
    
    let optionsHTML = '';
    
    if (question.question_type === 'multiple_choice' || question.question_type === 'true_false') {
        const options = question.options || (question.question_type === 'true_false' ? 
            [{option_text: 'True', is_correct: false}, {option_text: 'False', is_correct: false}] : 
            [{option_text: '', is_correct: false}]);
        
        optionsHTML = `
            <div class="question-options">
                <h5>Answer Options:</h5>
                <div class="options-list">
                    ${options.map((opt, optIndex) => `
                        <div class="option-item">
                            <input type="radio" 
                                   name="correct-create-${tempId}" 
                                   ${opt.is_correct ? 'checked' : ''}
                                   onchange="markCreateCorrectOption(${index}, ${optIndex})">
                            <input type="text" 
                                   value="${opt.option_text || ''}" 
                                   placeholder="Option ${optIndex + 1}"
                                   onchange="updateCreateOptionText(${index}, ${optIndex}, this.value)"
                                   ${question.question_type === 'true_false' ? 'readonly' : ''}>
                            ${question.question_type !== 'true_false' ? `
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeCreateOption(${index}, ${optIndex})">✕</button>
                            ` : ''}
                        </div>
                    `).join('')}
                </div>
                ${question.question_type !== 'true_false' ? `
                    <button type="button" class="btn btn-secondary btn-sm add-option-btn" onclick="addCreateOption(${index})">
                        ➕ Add Option
                    </button>
                ` : ''}
            </div>
        `;
    }
    
    return `
        <div class="quiz-question-item">
            <div class="question-header">
                <span class="question-number">Question ${questionNumber}</span>
                <button type="button" class="btn btn-danger btn-sm" onclick="deleteCreateQuestion(${index})">Delete</button>
            </div>
            
            <div class="question-body">
                <div class="form-row">
                    <div class="form-group-inline">
                        <label>Question Text *</label>
                        <textarea 
                            onchange="updateCreateQuestion(${index}, 'question_text', this.value)"
                            placeholder="Enter your question here..."
                        >${question.question_text || ''}</textarea>
                    </div>
                    
                    <div class="form-group-inline">
                        <label>Question Type *</label>
                        <select onchange="updateCreateQuestion(${index}, 'question_type', this.value)">
                            <option value="multiple_choice" ${question.question_type === 'multiple_choice' ? 'selected' : ''}>Multiple Choice</option>
                            <option value="true_false" ${question.question_type === 'true_false' ? 'selected' : ''}>True/False</option>
                            <option value="short_answer" ${question.question_type === 'short_answer' ? 'selected' : ''}>Short Answer</option>
                            <option value="essay" ${question.question_type === 'essay' ? 'selected' : ''}>Essay</option>
                        </select>
                    </div>
                    
                    <div class="form-group-inline">
                        <label>Points *</label>
                        <input type="number" 
                               min="0" 
                               step="0.5" 
                               value="${question.points || 1}"
                               onchange="updateCreateQuestion(${index}, 'points', this.value)">
                    </div>
                </div>
                
                ${optionsHTML}
            </div>
        </div>
    `;
}

// Update create question
function updateCreateQuestion(index, field, value) {
    if (createQuizQuestions[index]) {
        createQuizQuestions[index][field] = value;
        
        if (field === 'question_type') {
            if (value === 'true_false') {
                createQuizQuestions[index].options = [
                    { option_text: 'True', is_correct: false },
                    { option_text: 'False', is_correct: false }
                ];
            } else if (value === 'multiple_choice' && !createQuizQuestions[index].options) {
                createQuizQuestions[index].options = [{ option_text: '', is_correct: false }];
            } else if (value === 'short_answer' || value === 'essay') {
                createQuizQuestions[index].options = [];
            }
            renderCreateQuizQuestions();
        } else if (field === 'points') {
            // Update max points when points change
            updateMaxPointsFromQuestions();
        }
    }
}

function deleteCreateQuestion(index) {
    if (confirm('Delete this question?')) {
        createQuizQuestions.splice(index, 1);
        renderCreateQuizQuestions();
    }
}

function addCreateOption(questionIndex) {
    if (!createQuizQuestions[questionIndex].options) {
        createQuizQuestions[questionIndex].options = [];
    }
    createQuizQuestions[questionIndex].options.push({ option_text: '', is_correct: false });
    renderCreateQuizQuestions();
}

function removeCreateOption(questionIndex, optionIndex) {
    if (createQuizQuestions[questionIndex].options && createQuizQuestions[questionIndex].options.length > 1) {
        createQuizQuestions[questionIndex].options.splice(optionIndex, 1);
        renderCreateQuizQuestions();
    } else {
        alert('A question must have at least one option.');
    }
}

function updateCreateOptionText(questionIndex, optionIndex, value) {
    if (createQuizQuestions[questionIndex].options && createQuizQuestions[questionIndex].options[optionIndex]) {
        createQuizQuestions[questionIndex].options[optionIndex].option_text = value;
    }
}

function markCreateCorrectOption(questionIndex, optionIndex) {
    if (createQuizQuestions[questionIndex].options) {
        createQuizQuestions[questionIndex].options.forEach((opt, idx) => {
            opt.is_correct = (idx === optionIndex);
        });
    }
}

// ==================== EDIT ASSIGNMENT - TAB SWITCHING ====================

let currentEditAssignment = null;
let editQuizQuestions = [];

function switchEditTab(tabName) {
    // Remove active class from all tabs
    document.querySelectorAll('#editAssignmentModal .tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelectorAll('#editAssignmentModal .tab-content').forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active');
    });
    
    // Activate clicked tab
    document.querySelector(`#editAssignmentModal .tab-btn[data-tab="${tabName}"]`).classList.add('active');
    const tabContent = document.getElementById(`${tabName}-tab`);
    tabContent.style.display = 'block';
    tabContent.classList.add('active');
    
    // Load content based on tab
    if (tabName === 'edit-questions' && currentEditAssignment) {
        // Questions are already loaded in populateEditForm, just render them
        renderEditQuizQuestions();
    } else if (tabName === 'edit-rubrics' && currentEditAssignment) {
        loadRubrics(currentEditAssignment.id);
    } else if (tabName === 'edit-analytics' && currentEditAssignment) {
        loadAnalytics(currentEditAssignment.id);
    } else if (tabName === 'edit-submissions' && currentEditAssignment) {
        loadSubmissions(currentEditAssignment.id);
    }
}

// Switch tabs in Create Assignment Modal
window.switchCreateTab = function(tabName) {
    // Remove active class from all tabs
    document.querySelectorAll('#createAssignmentModal .tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelectorAll('#createAssignmentModal .tab-content').forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active');
    });
    
    // Activate clicked tab
    document.querySelector(`#createAssignmentModal .tab-btn[data-tab="${tabName}"]`).classList.add('active');
    const tabContent = document.getElementById(`${tabName}-tab`);
    tabContent.style.display = 'block';
    tabContent.classList.add('active');
}

// Render edit quiz questions
window.renderEditQuizQuestions = function() {
    console.log('renderEditQuizQuestions called');
    console.log('editQuizQuestions:', editQuizQuestions);
    
    const container = document.getElementById('edit_quiz_questions_list');
    
    if (!container) {
        console.error('Edit quiz questions container not found');
        return;
    }
    
    if (!editQuizQuestions || editQuizQuestions.length === 0) {
        container.innerHTML = '<div class="no-questions-message">No questions yet. Add questions to this quiz/exam.</div>';
        return;
    }
    
    console.log('Starting to render questions...');
    console.log('First question:', editQuizQuestions[0]);
    
    const html = editQuizQuestions.map((question, index) => {
        return `
            <div class="quiz-question-card" data-question-id="${question.id}">
                <div class="question-header">
                    <span class="question-number">Question ${index + 1}</span>
                    <span class="question-points">${question.points} point${question.points !== 1 ? 's' : ''}</span>
                </div>
                <div class="question-text">${question.question_text}</div>
                <div class="question-type-badge">${formatQuestionType(question.question_type)}</div>
                ${question.options && question.options.length > 0 ? `
                    <div class="question-options">
                        ${question.options.map(opt => `
                            <div class="option-item ${opt.is_correct ? 'correct-answer' : ''}">
                                ${opt.is_correct ? '✓ ' : ''}${opt.option_text}
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
        `;
    }).join('');
    
    console.log('Generated HTML length:', html.length);
    console.log('Setting innerHTML...');
    container.innerHTML = html;
    console.log('Questions rendered successfully');
};

// Format question type for display
function formatQuestionType(type) {
    const types = {
        'multiple_choice': 'Multiple Choice',
        'true_false': 'True/False',
        'short_answer': 'Short Answer',
        'essay': 'Essay'
    };
    return types[type] || type;
}

// Load submissions for grading
async function loadSubmissions(assignmentId) {
    try {
        const response = await fetch(`/group/${window.groupData.id}/assignments/${assignmentId}/submissions`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error('Failed to load submissions');
        }
        
        const data = await response.json();
        renderSubmissions(data.submissions, data.stats);
    } catch (error) {
        console.error('Error loading submissions:', error);
        document.getElementById('submissions-list').innerHTML = '<div class="no-submissions">Failed to load submissions</div>';
    }
}

// Render submissions list
function renderSubmissions(submissions, stats) {
    const statsContainer = document.getElementById('submissions-stats');
    const listContainer = document.getElementById('submissions-list');
    
    // Render stats
    statsContainer.innerHTML = `
        <strong>${stats.submitted}</strong> submitted | 
        <strong>${stats.graded}</strong> graded | 
        <strong>${stats.not_submitted}</strong> not submitted
    `;
    
    // Render submissions
    if (submissions.length === 0) {
        listContainer.innerHTML = '<div class="no-submissions">No submissions yet</div>';
        return;
    }
    
    listContainer.innerHTML = submissions.map(submission => {
        const statusClass = submission.status === 'graded' ? 'graded' : 
                          submission.status === 'submitted' ? 'submitted' : 'not-submitted';
        const statusText = submission.status === 'graded' ? 'Graded' :
                          submission.status === 'submitted' ? 'Submitted' : 'Not Submitted';
        
        return `
            <div class="submission-item" onclick="openGradeModal(${submission.student_id}, ${currentEditAssignment.id})">
                <div class="submission-student-info">
                    <div class="submission-student-avatar">
                        ${submission.student_name.charAt(0)}
                    </div>
                    <div class="submission-student-details">
                        <h4>${submission.student_name}</h4>
                        <p>Submitted: ${submission.submitted_at || 'Not submitted'}</p>
                    </div>
                </div>
                <div class="submission-meta">
                    <span class="submission-status-indicator ${statusClass}">${statusText}</span>
                    ${submission.grade !== null ? `<span class="submission-grade">${submission.grade}/${currentEditAssignment.max_points}</span>` : ''}
                </div>
            </div>
        `;
    }).join('');
}

// Open grade modal - defined in grading.js
// Function openGradeModal() is loaded from grading.js

// Handle form submission for create assignment
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createAssignmentForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const assignmentType = document.getElementById('assignment_type').value;
            const maxPointsInput = document.getElementById('max_points');
            
            // If it's a quiz or exam, populate the hidden quiz_questions field
            if (assignmentType === 'quiz' || assignmentType === 'exam') {
                if (createQuizQuestions.length > 0) {
                    document.getElementById('quiz_questions').value = JSON.stringify(createQuizQuestions);
                }
                
                // Ensure max_points has a valid value (at least 1) even if auto-calculated is 0
                if (!maxPointsInput.value || parseInt(maxPointsInput.value) < 1) {
                    maxPointsInput.value = '1';
                }
                
                // Temporarily enable the field for form submission
                maxPointsInput.disabled = false;
            }
            
            // Form will submit normally
        });
    }
});

// Intercept create assignment form submission to add rubrics
document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('createAssignmentForm');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            // Add rubrics data to hidden field before submission
            if (createRubricCriteria && createRubricCriteria.length > 0) {
                document.getElementById('create_rubrics_data').value = JSON.stringify(createRubricCriteria);
            }
        });
    }
});

// Toggle late penalty field visibility
function toggleLatePenalty() {
    const allowLate = document.getElementById('allow_late_submissions').checked;
    const penaltyGroup = document.getElementById('late_penalty_group');
    
    if (allowLate) {
        penaltyGroup.style.display = 'block';
    } else {
        penaltyGroup.style.display = 'none';
        document.getElementById('late_penalty_percentage').value = '0';
    }
}

function toggleEditLatePenalty() {
    const allowLate = document.getElementById('edit_allow_late_submissions').checked;
    const penaltyGroup = document.getElementById('edit_late_penalty_group');
    
    if (allowLate) {
        penaltyGroup.style.display = 'block';
    } else {
        penaltyGroup.style.display = 'none';
        document.getElementById('edit_late_penalty_percentage').value = '0';
    }
}

// Toggle resubmission controls
function toggleResubmissions() {
    const allowResubmissions = document.getElementById('allow_resubmissions').checked;
    const maxAttemptsGroup = document.getElementById('max_attempts_group');
    
    if (allowResubmissions) {
        maxAttemptsGroup.style.display = 'block';
    } else {
        maxAttemptsGroup.style.display = 'none';
        document.getElementById('max_attempts').value = '1'; // Only 1 attempt if resubmissions disabled
    }
}

function toggleEditResubmissions() {
    const allowResubmissions = document.getElementById('edit_allow_resubmissions').checked;
    const maxAttemptsGroup = document.getElementById('edit_max_attempts_group');
    
    if (allowResubmissions) {
        maxAttemptsGroup.style.display = 'block';
    } else {
        maxAttemptsGroup.style.display = 'none';
        document.getElementById('edit_max_attempts').value = '1'; // Only 1 attempt if resubmissions disabled
    }
}

// ==================== Create Modal Rubrics ====================

let createRubricCriteria = [];

window.addCreateRubricCriterion = function() {
    const criterion = {
        id: Date.now(),
        name: '',
        description: '',
        points: 0
    };
    
    createRubricCriteria.push(criterion);
    renderCreateRubrics();
}

function renderCreateRubrics() {
    const container = document.getElementById('create-rubrics-list');
    const noMessage = container.querySelector('.no-rubrics-message');
    
    if (createRubricCriteria.length === 0) {
        if (noMessage) noMessage.style.display = 'block';
        document.getElementById('create-rubric-total').style.display = 'none';
        return;
    }
    
    if (noMessage) noMessage.style.display = 'none';
    
    const html = createRubricCriteria.map((criterion, index) => `
        <div class="rubric-criterion" data-criterion-id="${criterion.id}">
            <div class="rubric-criterion-header">
                <span class="criterion-number">Criterion ${index + 1}</span>
                <button type="button" class="btn-remove-criterion" onclick="removeCreateRubricCriterion(${criterion.id})">
                    ✕ Remove
                </button>
            </div>
            <div class="rubric-criterion-body">
                <div class="form-group">
                    <label>Criterion Name *</label>
                    <input type="text" 
                           class="rubric-criterion-name" 
                           value="${criterion.name}"
                           placeholder="e.g., Content Quality, Organization, Grammar"
                           onchange="updateCreateRubricCriterion(${criterion.id}, 'name', this.value)">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="rubric-criterion-description" 
                              rows="2" 
                              placeholder="Describe what you're looking for in this criterion..."
                              onchange="updateCreateRubricCriterion(${criterion.id}, 'description', this.value)">${criterion.description}</textarea>
                </div>
                <div class="form-group">
                    <label>Maximum Points *</label>
                    <input type="number" 
                           class="rubric-criterion-points" 
                           value="${criterion.points}"
                           min="0" 
                           max="1000" 
                           step="0.5"
                           placeholder="Points"
                           onchange="updateCreateRubricCriterion(${criterion.id}, 'points', parseFloat(this.value) || 0)">
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = (noMessage ? noMessage.outerHTML : '') + html;
    updateCreateRubricTotal();
}

window.removeCreateRubricCriterion = function(criterionId) {
    createRubricCriteria = createRubricCriteria.filter(c => c.id !== criterionId);
    renderCreateRubrics();
}

window.updateCreateRubricCriterion = function(criterionId, field, value) {
    const criterion = createRubricCriteria.find(c => c.id === criterionId);
    if (criterion) {
        criterion[field] = value;
        if (field === 'points') {
            updateCreateRubricTotal();
        }
    }
}

function updateCreateRubricTotal() {
    const total = createRubricCriteria.reduce((sum, c) => sum + (parseFloat(c.points) || 0), 0);
    document.getElementById('create-rubric-total-points').textContent = total;
    document.getElementById('create-rubric-total').style.display = total > 0 ? 'block' : 'none';
    
    // Update max points in Details tab if rubric has points
    if (total > 0) {
        const maxPointsInput = document.getElementById('max_points');
        if (maxPointsInput) {
            maxPointsInput.value = total;
        }
    }
}

// ==================== Create Modal Quiz Questions (in tab) ====================

window.addCreateQuizQuestionInTab = function() {
    // Reuse existing quiz question logic but for the tab
    addCreateQuizQuestion();
    // Also update the tab display
    const tabContainer = document.getElementById('create_quiz_questions_tab_list');
    const mainContainer = document.getElementById('create_quiz_questions_list');
    if (tabContainer && mainContainer) {
        tabContainer.innerHTML = mainContainer.innerHTML;
    }
}
