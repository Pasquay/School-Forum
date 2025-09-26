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

                        <h4>Members</h4>
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
</body>
<script src="{{ asset('js/group-script.js') }}"></script>
<script src="{{ asset('js/group-settings.js') }}"></script>

</html>