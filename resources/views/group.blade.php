<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAROLINK | {{ $group->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/group-styles.css') }}">
</head>

<body>
    @include('components.navbar', ['active' => ''])
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
                                    alt="star">
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
                                    alt="Mute">
                            </button>
                        </form>
                        @endif
                    </div>
                    <form action="" method="POST" id="join-leave-form">
                        @csrf
                        @if($group->owner_id === Auth::id() || $group->isModerator(Auth::user()))
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
                @if($group->owner_id === Auth::id())
                <button type="button" onclick="openCreateAssignmentModal()">Create Assignment</button>
                @endif

                <h3>Assignments</h3>
                <div class="assignments-list" id="sidebar-assignments-list">
                    <div class="loading">Loading assignments...</div>
                </div>
            </div>
            <div class="content">
                <div class="menu">
                    <a href="#" class="link active" id="search-btn">
                        <span class="link-icon">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="192"
                                height="192"
                                fill="currentColor"
                                viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none"></rect>
                                <circle
                                    cx="116"
                                    cy="116"
                                    r="84"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="16"></circle>
                                <line
                                    x1="175.39356"
                                    y1="175.40039"
                                    x2="223.99414"
                                    y2="224.00098"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="16"></line>
                            </svg>
                        </span>
                        <span class="link-title">Search</span>
                    </a>
                    <a href="#" class="link" id="add-btn">
                        <span class="link-icon">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="192"
                                height="192"
                                fill="currentColor"
                                viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none"></rect>
                                <line
                                    x1="128"
                                    y1="40"
                                    x2="128"
                                    y2="216"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="16"></line>
                                <line
                                    x1="40"
                                    y1="128"
                                    x2="216"
                                    y2="128"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="16"></line>
                            </svg>
                        </span>
                        <span class="link-title">Add</span>
                    </a>
                    <a href="#" class="link" id="filter-btn">
                        <span class="link-icon">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="192"
                                height="192"
                                fill="currentColor"
                                viewBox="0 0 256 256">
                                <rect width="256" height="256" fill="none"></rect>
                                <path
                                    d="M200,128a8,8,0,0,1-8,8H64a8,8,0,0,1,0-16H192A8,8,0,0,1,200,128ZM64,72H192a8,8,0,0,0,0-16H64a8,8,0,0,0,0,16ZM192,184H64a8,8,0,0,0,0,16H192a8,8,0,0,0,0-16Z"
                                    fill="currentColor"></path>
                                <path
                                    d="M192,64H64a8,8,0,0,0-8,8V184a8,8,0,0,0,8,8H192a8,8,0,0,0,8-8V72A8,8,0,0,0,192,64ZM184,176H72V80H184Z"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="16"></path>
                            </svg>
                        </span>
                        <span class="link-title">Filter</span>
                    </a>
                </div>

                <!-- Create Post Form -->
                <div id="create-post-container">
                    @include('components.create-post-form', ['group' => $group])
                </div>

                <!-- Search Form (Initially Visible) -->
                <div id="search-container" style="display: block;">
                    <div class="search-form">
                        <input type="text" id="post-search" placeholder="Search posts by title..." />
                        <button id="search-submit">Search</button>
                        <button id="clear-search">Clear</button>
                    </div>
                </div>

                <!-- Filter Form (Initially Hidden) -->
                <div id="filter-container" style="display: none;">
                    <div class="filter-form">
                        <select id="sort-by">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="most-voted">Most Voted</option>
                            <option value="most-commented">Most Commented</option>
                        </select>
                        <select id="filter-by">
                            <option value="all">All Posts</option>
                            <option value="pinned">Pinned Only</option>
                            <option value="regular">Regular Posts Only</option>
                        </select>
                        <button id="apply-filter">Apply</button>
                        <button id="clear-filter">Clear</button>
                    </div>
                </div>

                <!-- Content Tabs -->
                <div class="content-tabs">
                    <button class="main-tab-btn active" data-tab="posts">Posts</button>
                    <button class="main-tab-btn" data-tab="assignments">Assignments</button>
                </div>

                <!-- Posts Tab Content -->
                <div id="posts-content" class="main-tab-content active">
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

                <!-- Assignments Tab Content -->
                <div id="assignments-content" class="main-tab-content">
                    <div class="assignments-container">
                        <div class="loading" id="assignments-loading">Loading assignments...</div>
                        <div class="assignments-list" id="assignments-list"></div>
                        <div class="no-assignments" id="no-assignments" style="display: none;">
                            <p class="empty">No assignments yet...</p>
                        </div>
                    </div>
                </div>
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

    <!-- Group Settings Modal -->
    <div id="groupSettingsModal" class="modal" style="display: none;">
        <div class="modal-content settings-modal">
            <div class="modal-header">
                <h2>{{ $group->name }} Settings</h2>
                <button class="close-modal" onclick="closeGroupSettingsModal()">&times;</button>
            </div>

            <div class="modal-body">
                <!-- Tab Navigation -->
                <nav class="settings-nav">
                    <button class="tab-btn active" data-tab="general">General</button>
                    <button class="tab-btn" data-tab="members">Members</button>
                    <button class="tab-btn" data-tab="permissions">Permissions</button>
                    <button class="tab-btn" data-tab="danger">Danger Zone</button>
                </nav>

                <!-- General Settings Tab -->
                <div class="tab-content active" id="general">
                    <h3>General Settings</h3>
                    <form action="{{ route('group.update', $group->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Group Name</label>
                            <input type="text" id="name" name="name" value="{{ $group->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4">{{ $group->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="is_private">Privacy Setting</label>
                            <select id="is_private" name="is_private">
                                <option value="0" {{ !$group->is_private ? 'selected' : '' }}>Public</option>
                                <option value="1" {{ $group->is_private ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>

                <!-- Members Management Tab -->
                <div class="tab-content" id="members">
                    <h3>Member Management</h3>

                    <div class="members-section">
                        <h4>Owner</h4>
                        <div class="member-list">
                            @foreach($memberList as $member)
                            @if($member->pivot->role === 'owner')
                            <div class="member-item owner">
                                <div class="member-info">
                                    <div class="member-avatar">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h5>{{ $member->name }}</h5>
                                        <p>{{ $member->email }}</p>
                                    </div>
                                </div>
                                <span class="role-badge owner">Owner</span>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <h4>Moderators</h4>
                        <div class="member-list">
                            @forelse($memberList->where('pivot.role', 'moderator') as $moderator)
                            <div class="member-item moderator">
                                <div class="member-info">
                                    <div class="member-avatar">
                                        {{ strtoupper(substr($moderator->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h5>{{ $moderator->name }}</h5>
                                        <p>{{ $moderator->email }}</p>
                                    </div>
                                </div>
                                <div class="member-actions">
                                    <span class="role-badge moderator">Moderator</span>
                                    @if($group->owner_id === Auth::id())
                                    <form action="{{ route('group.demote', [$group->id, $moderator->id]) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Demote this moderator?')">Demote</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <p class="no-moderators">No moderators assigned</p>
                            @endforelse
                        </div>

                        <div class="members-header-with-invite">
                            <h4>Members</h4>
                            <button type="button" class="invite-members-btn" onclick="showAddMembersModal()">
                                Invite Members
                            </button>
                        </div>
                        <div class="member-search-bar">
                            <input type="text" id="memberSearch" placeholder="Search members..." class="member-search-input">
                        </div>
                        <div class="member-list">
                            @foreach($memberList->where('pivot.role', 'member') as $member)
                            <div class="member-item">
                                <div class="member-info">
                                    <div class="member-avatar">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h5>{{ $member->name }}</h5>
                                        <p>{{ $member->email }}</p>
                                    </div>
                                </div>
                                <div class="member-actions">
                                    <span class="role-badge member">Member</span>
                                    @if($group->owner_id === Auth::id())
                                    <form action="{{ route('group.promote', [$group->id, $member->id]) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Promote</button>
                                    </form>
                                    <form action="{{ route('group.removeMember', [$group->id, $member->id]) }}" method="POST" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this member?')">Remove</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Permissions Tab -->
                <div class="tab-content" id="permissions">
                    <h3>Group Permissions</h3>
                    <form action="{{ route('group.updatePermissions', $group->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="permission-group">
                            <h4>Member Permissions</h4>
                            <label><input type="checkbox" name="members_can_post" {{ $group->members_can_post ?? true ? 'checked' : '' }}> Members can create posts</label>
                            <label><input type="checkbox" name="members_can_comment" {{ $group->members_can_comment ?? true ? 'checked' : '' }}> Members can comment</label>
                            <label><input type="checkbox" name="members_can_invite" {{ $group->members_can_invite ?? false ? 'checked' : '' }}> Members can invite others</label>
                            <label><input type="checkbox" name="auto_approve_posts" {{ $group->auto_approve_posts ?? true ? 'checked' : '' }}> Auto-approve posts</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Permissions</button>
                    </form>
                </div>

                <!-- Danger Zone Tab -->
                <div class="tab-content" id="danger">
                    <h3>Danger Zone</h3>
                    <div class="danger-actions">
                        @if($group->owner_id === Auth::id())
                        <div class="danger-item">
                            <h4>Delete Group</h4>
                            <p>Permanently delete this group and all its content</p>
                            <form action="{{ route('group.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure? This action cannot be undone!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Group</button>
                            </form>
                        </div>
                        @else
                        <div class="danger-item">
                            <h4>Leave Group</h4>
                            <p>Leave this group permanently</p>
                            <form action="{{ route('group.leave', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this group?')">
                                @csrf
                                <button type="submit" class="btn btn-danger">Leave Group</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Members Modal -->
    <div class="modal" id="addMembersModal" style="display:none;">
        <div class="modal-content add-members-modal">
            <div class="modal-header">
                <h2>Invite members</h2>
                <button class="close-modal" onclick="closeAddMembersModal()">&times;</button>
            </div>
            <div class="user-search-bar">
                <input type="text" id="userSearch" placeholder="Search users..." class="user-search-input">
            </div>
            <!-- {{ route('group.removeMember', [$group->id, $member->id]) }} -->
            <form action="{{ route('group.invite', [$group->id]) }}" method="post">
                @csrf
                <div class="member-list">
                    <!-- Users will be loaded here with checkboxes -->
                </div>
                <div class="add-member-form-buttons">
                    <button type="button" onclick="closeAddMembersModal()">Cancel</button>
                    <button type="submit" id="inviteSubmitBtn" disabled>Invite Selected</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Assignments Modal -->
    <div id="createAssignmentModal" class="modal" style="display: none;">
        <div class="modal-content settings-modal">
            <div class="modal-header">
                <h2>Create New Assignment</h2>
                <button class="close-modal" onclick="closeCreateAssignmentModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('group.createAssignment', $group->id) }}" method="POST" id="createAssignmentForm">
                    @csrf

                    <div class="form-group">
                        <label for="assignment_name">Assignment Name *</label>
                        <input type="text" id="assignment_name" name="assignment_name" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Assignment instructions and details..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="assignment_type">Assignment Type *</label>
                        <select id="assignment_type" name="assignment_type" required>
                            <option value="">Select type...</option>
                            <option value="essay">Essay</option>
                            <option value="quiz">Quiz</option>
                            <option value="project">Project</option>
                            <option value="homework">Homework</option>
                            <option value="exam">Exam</option>
                            <option value="presentation">Presentation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="submission_type">Submission Type *</label>
                        <select id="submission_type" name="submission_type" required>
                            <option value="">Select submission type...</option>
                            <option value="text">Text Submission</option>
                            <option value="file">File Upload</option>
                            <option value="link">External Link</option>
                            <option value="none">No Submission Required</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="max_points">Maximum Points *</label>
                        <input type="number" id="max_points" name="max_points" min="1" max="1000" required>
                    </div>

                    <div class="form-group">
                        <label for="date_assigned">Date Assigned</label>
                        <input type="datetime-local" id="date_assigned" name="date_assigned" value="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>

                    <div class="form-group">
                        <label for="date_due">Due Date *</label>
                        <input type="datetime-local" id="date_due" name="date_due" required>
                    </div>

                    <div class="form-group">
                        <label for="date_close">Close Date</label>
                        <input type="datetime-local" id="date_close" name="date_close">
                        <small class="form-text">Optional: When to stop accepting submissions</small>
                    </div>

                    <div class="form-group">
                        <label for="external_link">External Link</label>
                        <input type="url" id="external_link" name="external_link" placeholder="https://example.com">
                        <small class="form-text">Optional: Link to external resources</small>
                    </div>

                    <div class="form-group">
                        <label for="visibility">Visibility *</label>
                        <select id="visibility" name="visibility" required>
                            <option value="draft">Draft (Only visible to you)</option>
                            <option value="published">Published (Visible to all members)</option>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <button type="button" onclick="closeCreateAssignmentModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Edit Assingment Modal -->
    <div id="editAssignmentModal" class="modal" style="display: none;">
        <div class="modal-content settings-modal">
            <div class="modal-header">
                <h2>EditAssignment</h2>
                <button class="close-modal" onclick="closeEditAssignmentModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editAssignmentForm">
                    @csrf
                    <input type="hidden" name="assignment_id" id="edit_assignment_id" value="">

                    <div class="form-group">
                        <label for="edit_assignment_name">Assignment Name *</label>
                        <input type="text" id="edit_assignment_name" name="assignment_name" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_description">Description</label>
                        <textarea id="edit_description" name="description" rows="4" placeholder="Assignment instructions and details..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="edit_assignment_type">Assignment Type *</label>
                        <select id="edit_assignment_type" name="assignment_type" required>
                            <option value="">Select type...</option>
                            <option value="essay">Essay</option>
                            <option value="quiz">Quiz</option>
                            <option value="project">Project</option>
                            <option value="homework">Homework</option>
                            <option value="exam">Exam</option>
                            <option value="presentation">Presentation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_submission_type">Submission Type *</label>
                        <select id="edit_submission_type" name="submission_type" required>
                            <option value="">Select submission type...</option>
                            <option value="text">Text Submission</option>
                            <option value="file">File Upload</option>
                            <option value="link">External Link</option>
                            <option value="none">No Submission Required</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_max_points">Maximum Points *</label>
                        <input type="number" id="edit_max_points" name="max_points" min="1" max="1000" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_date_assigned">Date Assigned</label>
                        <input type="datetime-local" id="edit_date_assigned" name="date_assigned">
                    </div>

                    <div class="form-group">
                        <label for="edit_date_due">Due Date *</label>
                        <input type="datetime-local" id="edit_date_due" name="date_due" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_date_close">Close Date</label>
                        <input type="datetime-local" id="edit_date_close" name="date_close">
                        <small class="form-text">Optional: When to stop accepting submissions</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_external_link">External Link</label>
                        <input type="url" id="edit_external_link" name="external_link" placeholder="https://example.com">
                        <small class="form-text">Optional: Link to external resources</small>
                    </div>

                    <div class="form-group">
                        <label for="edit_visibility">Visibility *</label>
                        <select id="edit_visibility" name="visibility" required>
                            <option value="draft">Draft (Only visible to you)</option>
                            <option value="published">Published (Visible to all members)</option>
                        </select>
                    </div>

                    <div class="form-buttons">
                        <button type="button" onclick="closeEditAssignmentModal()" class="btn btn-secondary">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Assignment</button>
                        <button type="button" class="btn btn-primary-del" onclick="deleteAssignment()">Delete Assignment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



</body>

<script>
    // Pass group ID to JavaScript - using a more robust approach
    window.groupData = JSON.parse('{!! json_encode(["id" => $group->id, "name" => $group->name]) !!}');
    console.log('Group data set:', window.groupData);
</script>
<script src="{{ asset('js/group-script.js') }}"></script>
<script src="{{ asset('js/group-settings.js') }}"></script>

</html>