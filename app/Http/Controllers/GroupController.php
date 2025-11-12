<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use App\Models\StudentQuizResponse;
use App\Models\InboxMessage;
use App\Models\Rubric;
use App\Models\RubricScore;
use App\Models\SubmissionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    public function showGroups(Request $request)
    {
        $sortBy = $request->get('sort', 'members');
        $timeFrame = $request->get('time', 'all');
        $showJoined = $request->get('show_joined', '1');
        $search = $request->get('search', '');

        $user = User::findOrFail(Auth::id());

        $groups = Group::query();

        if ($search) {
            $groups->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        switch ($sortBy) {
            case 'new':
                $groups->orderBy('created_at', 'desc')
                    ->orderBy('name', 'asc');
                break;
            case 'active':
                if ($timeFrame !== 'all') {
                    $date = match ($timeFrame) {
                        'today' => now()->startOfDay(),
                        'week' => now()->startOfWeek(),
                        'month' => now()->startOfMonth(),
                        'year' => now()->startOfYear(),
                        default => null,
                    };
                    if ($date) {
                        $groups->withCount(['posts' => function ($query) use ($date) {
                            $query->where('created_at', '>=', $date);
                        }])->having('posts_count', '>', 0)
                            ->orderBy('posts_count', 'desc')
                            ->orderBy('name', 'asc');
                    }
                } else {
                    $groups->withCount('posts')
                        ->having('posts_count', '>', 0)
                        ->orderBy('posts_count', 'desc')
                        ->orderBy('name', 'asc');
                }
                break;
            case 'members':
            default:
                $groups->orderBy('member_count', 'desc')
                    ->orderBy('name', 'asc');
        }

        $joinedGroupIds = $user->groups()->pluck('groups.id')->toArray();

        if ($showJoined === '0') {
            $groups->whereNotIn('id', $joinedGroupIds);
        }

        // Pagination

        $perPage = 10;
        $currentPage = request('page', 1);

        $groups = $groups->get();
        $groupsCount = $groups->count();
        $offset = ($currentPage - 1) * $perPage;
        $groupsItems = $groups->slice($offset, $perPage)->values();

        $groups = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupsItems,
            $groupsCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        foreach ($groups as $group) {
            $group->requested = InboxMessage::hasPendingGroupJoinRequest($user->id, $group->id);
        }

        // Right Side Groups    
        $createdGroups = $user->groups()
            ->wherePivot('role', 'owner')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $moderatedGroups = $user->groups()
            ->wherePivot('role', 'moderator')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        $joinedGroups = $user->groups()
            ->wherePivot('role', 'member')
            ->orderBy('is_starred', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        if ($request->ajax()) {
            $html = '';
            foreach ($groups as $group) {
                $html .= view('components.group-info', [
                    'group' => $group,
                ])->render();
            }
            $html .= '<p class="empty"></p>';

            return response()->json([
                'html' => $html,
            ]);
        }

        return view('groups', compact(
            'groups',
            'createdGroups',
            'moderatedGroups',
            'joinedGroups',
        ));
    }

    public function showGroupsPaginated($page, Request $request)
    {
        $sortBy = $request->get('sort', 'members');
        $timeFrame = $request->get('time', 'all');
        $showJoined = $request->get('show_joined', '1');
        $search = $request->get('search', '');

        $user = User::findOrFail(Auth::id());

        $groups = Group::query();

        if ($search) {
            $groups->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        switch ($sortBy) {
            case 'new':
                $groups->orderBy('created_at', 'desc')
                    ->orderBy('name', 'asc');
                break;
            case 'active':
                if ($timeFrame !== 'all') {
                    $date = match ($timeFrame) {
                        'today' => now()->startOfDay(),
                        'week' => now()->startOfWeek(),
                        'month' => now()->startOfMonth(),
                        'year' => now()->startOfYear(),
                        default => null,
                    };
                    if ($date) {
                        $groups->withCount(['posts' => function ($query) use ($date) {
                            $query->where('created_at', '>=', $date);
                        }])->having('posts_count', '>', 0)
                            ->orderBy('posts_count', 'desc')
                            ->orderBy('name', 'asc');
                    }
                } else {
                    $groups->withCount('posts')
                        ->having('posts_count', '>', 0)
                        ->orderBy('posts_count', 'desc')
                        ->orderBy('name', 'asc');
                }
                break;
            case 'members':
            default:
                $groups->orderBy('member_count', 'desc')
                    ->orderBy('name', 'asc');
        }

        $joinedGroupIds = $user->groups()->pluck('groups.id')->toArray();

        if ($showJoined === '0') {
            $groups->whereNotIn('id', $joinedGroupIds);
        }

        // Pagination
        $perPage = 10;
        $currentPage = (int)$page;

        $groups = $groups->paginate($perPage, ['*'], 'page', $currentPage);

        foreach ($groups as $group) {
            $group->requested = InboxMessage::hasPendingGroupJoinRequest($user->id, $group->id);
        }

        $html = '';
        foreach ($groups as $group) {
            $html .= view('components.group-info', ['group' => $group])->render();
        }

        // $html .= '<p class="empty">test</p>';
        return response()->json([
            'html' => $html,
            'next_page' => $groups->hasMorePages() ? $groups->currentPage() + 1 : NULL
        ]);
    }

    public function showCreateGroup()
    {
        return view('create-group');
    }

    public function createGroup(Request $request)
    {
        try {
            $groupData = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'description' => ['required', 'string', 'min:10', 'max:500'],
                'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
                'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
                'is_private' => ['nullable'],
                'type' => ['nullable'],
                'rules' => ['required', 'array', 'min:1'],
                'rules.*.title' => ['required', 'string', 'max:60'],
                'rules.*.description' => ['required', 'string', 'max:500'],
                'resources' => ['nullable', 'array'],
                'resources.*.title' => ['required_with:resources', 'string', 'max:60'],
                'resources.*.description' => ['required_with:resources', 'string', 'max:500'],
            ]);

            $group = Group::create([
                'name' => $groupData['name'],
                'description' => $groupData['description'],
                'is_private' => isset($groupData['is_private']) && $groupData['is_private'] === '1',
                'type' => (isset($groupData['type']) && $groupData['type'] === '1') ? 'academic' : 'social',
                'rules' => $groupData['rules'],
                'resources' => $groupData['resources'] ?? [],
                'owner_id' => Auth::id(),
                'member_count' => 1,
            ]);

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->storeAs(
                    'groups/photos',
                    'group-' . $group->id . '-photo.' . $request->file('photo')->extension(),
                    'public',
                );
                $group->update(['photo' => $photoPath]);
            }

            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->storeAs(
                    'groups/banners',
                    'group-' . $group->id . '-banner.' . $request->file('banner')->extension(),
                    'public',
                );
                $group->update(['banner' => $bannerPath]);
            }

            $user = User::findOrFail(Auth::id());
            $user->groups()->attach($group->id, [
                'role' => 'owner',
                'is_starred' => false,
                'is_muted' => false,
            ]);

            // later change this to redirect to that group's page
            return redirect('/groups')->with('success', 'Group created successfully');
        } catch (\Illuminate\Validation\ValidationException $error) {
            return redirect()->back()->withErrors($error->errors())->withInput();
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error->getMessage())->withInput();
        }
    }

    public function showGroupsManager(Request $request)
    {
        $user = Auth::user();

        // Search //set this up later
        $search = '';

        // Sorting
        $sortBy = 'role'; // change this later
        $asc = 1;
        
        // Filters 
        //SET THESE UP LATER
        $isMember = 1;
        $isModerator = 1; 
        $isOwner = 1; 
        
        $isAcademic = 1; 
        $isSocial = 1;
        $isPrivate = 1;

        $isStarred = 1;
        $isMuted = 1;

        $groups = Group::query()
                       ->join('group_members', function ($join) use ($user) {
                            $join->on('groups.id', '=', 'group_members.group_id')
                                 ->where('group_members.user_id', '=', $user->id);
                       })
                       ->select([
                            'groups.*', 'group_members.role as user_role',
                            'groups.*', 'group_members.is_starred as is_starred',
                            'groups.*', 'group_members.is_muted as is_muted',
                        ]);

        // SEARCH HERE

        // SORT HERE
        switch($sortBy){
            case 'role': default:
                if($asc){
                    $groups->orderByRaw("FIELD(user_role, 'owner', 'moderator', 'member')")
                           ->orderBy('name', 'asc');
                } else {
                    $groups->orderByRaw("FIELD(user_role), 'member', 'moderator', 'owner')")
                           ->orderBy('name', 'desc');
                }
                break;
        }

        // PAGINATION
        $perPage = 10;
        $currentPage = request('page', 1);

        $groups = $groups->get();
        $groupsCount = $groups->count();
        $offset = ($currentPage - 1) * $perPage;
        $groupsItems = $groups->slice($offset, $perPage)->values();

        $groups = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupsItems,
            $groupsCount,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        // RIGHT SIDE INFO

        return view('group-manager', compact(
            'groups',
        ));
    }
    
    public function showGroup($id)
    {
        // Return to home page if $id is home group
        if ($id == 1) return app(\App\Http\Controllers\PostController::class)->getLatest(request());

        // Load all members
        $group = Group::with('members')->findOrFail($id);
        $group->requested = InboxMessage::hasPendingGroupJoinRequest(Auth::id(), $group->id);

        // Find the current user's membership (if any)
        $membership = $group->members->where('id', Auth::id())
            ->first();

        // All members for the member list
        $memberList = $group->members;

        // Pinned Posts
        $pinned = $group->pinnedPosts()
            ->orderByDesc('pinned_post.created_at')
            ->take(5)
            ->get();

        $pinned->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            $post->isPinned = 1;
            return $post;
        });

        // First 15 posts in the group
        $allPosts = Post::where('group_id', $group->id)
            ->whereDoesntHave('pinnedInGroups', function ($query) use ($group) {
                $query->where('group_id', $group->id);
            })
            ->latest()
            ->withCount(['votes', 'comments'])
            ->get();

        $allPosts->transform(function ($post) {
            $post->votes = $post->getVoteCountAttribute();
            $post->userVote = $post->getUserVoteAttribute();
            return $post;
        });

        $perPage = 15;
        $currentPage = request('page', 1);
        $total = $allPosts->count();
        $offset = ($currentPage - 1) * $perPage;
        $items = $allPosts->slice($offset, $perPage)->values();

        $posts = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        // Get assignments for left sidebar
        $assignments = collect();
        if ($membership) {
            $query = $group->assignments()->with(['creator']);

            // Check user role from the membership pivot table
            $userRole = $membership->pivot->role ?? null;

            // If user is not owner/moderator, only show published assignments
            if ($userRole !== 'owner' && $userRole !== 'moderator') {
                $query->where('visibility', 'published');
            }

            $assignments = $query->orderBy('date_due', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('group', compact(
            'group',
            'membership',
            'memberList',
            'pinned',
            'posts',
            'assignments',
        ));
    }


    public function toggleStar($id)
    {
        $user = User::findOrFail(Auth::id());
        $group = $user->groups()->where('group_id', $id)->first();

        if (!$group) return back()->with('error', 'You are not a member of that group');

        $starState = $group->pivot->is_starred;

        $newStarState = $starState ? 0 : 1;

        $user->groups()->updateExistingPivot($id, ['is_starred' => $newStarState]);

        return response()->json([
            'success' => true,
            'starValue' => $newStarState,
        ]);
    }

    public function setStar(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $groupIds = $request->input('group_ids', []);
        $value = $request->input('value');

        $starredGroups = [];

        foreach($groupIds as $groupId){
            $group = $user->groups()->where('group_id', $groupId)->first();
            if($group){
                $user->groups()->updateExistingPivot($groupId, ['is_starred' => $value]);
                $starredGroups[] = $group->name;
            }
        }

        $message = '';
        if($value){
            if(count($starredGroups) > 1) $message = 'Groups starred successfully.';
            else $message = 'Group starred successfully.';
        } else {
            if(count($starredGroups) > 1) $message = 'Groups unstarred successfully.';
            else $message = 'Group unstarred successfully.';
        }

        return response()->json([
            'success' => true,
            'action_type' => 'star',
            'action_value' => $value,
            'starred_groups' => $starredGroups,
            'message' => $message,
        ]);
    }
    
    public function toggleMute($id)
    {
        $user = User::findOrFail(Auth::id());
        $group = $user->groups()->where('group_id', $id)->first();

        if (!$group) return back()->with('error', 'You are not a member of that group');

        $muteState = $group->pivot->is_muted;

        $newMuteState = $muteState ? 0 : 1;

        $user->groups()->updateExistingPivot($id, ['is_muted' => $newMuteState]);

        return response()->json([
            'success' => true,
            'muteValue' => $newMuteState,
        ]);
    }

    public function setMute(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $groupIds = $request->input('group_ids', []);
        $value = $request->input('value');

        $mutedGroups = [];

        foreach($groupIds as $groupId){
            $group = $user->groups()->where('group_id', $groupId)->first();
            if($group){
                $user->groups()->updateExistingPivot($groupId, ['is_muted' => $value]);
                $mutedGroups[] = $group->name;
            }
        }

        $message = '';
        if($value){
            if(count($mutedGroups) > 1) $message = 'Groups muted successfully.';
            else $message = 'Group muted successfully.';
        } else {
            if(count($mutedGroups) > 1) $message = 'Groups unmuted successfully.';
            else $message = 'Group unmuted successfully.';
        }

        return response()->json([
            'success' => true,
            'action_type' => 'mute',
            'action_value' => $value,
            'muted_groups' => $mutedGroups,
            'message' => $message,
        ]);
    }

    public function joinGroup($id)
    {
        $user = User::findOrFail(Auth::id());
        $membership = $user->groups()
            ->where('groups.id', $id)
            ->exists();
        if (!$membership) {
            $user->groups()
                ->attach($id, [
                    'role' => 'member',
                    'is_starred' => false,
                    'is_muted' => false,
                ]);

            $group = Group::findOrFail($id);
            $group->update(['member_count' => $group->getMemberCount()]);

            $joinedGroups = $user->groups()
                ->wherePivot('role', 'member')
                ->orderBy('is_starred', 'desc')
                ->orderBy('name', 'asc')
                ->get();

            $joinedGroupsHTML = '
                <div class="section-header">
                    <p>Groups Joined</p>
                </div>';

            if ($joinedGroups->count() > 0) {
                foreach ($joinedGroups as $joinedGroup) {
                    $joinedGroupsHTML .= view('components.group-info-minimal', ['group' => $joinedGroup])->render();
                }
            } else {
                $joinedGroupsHTML .= '<p class="empty">No groups joined yet...</p>';
            }

            return response()->json([
                'success' => true,
                'membership' => 1,
                'joinedGroupsHTML' => $joinedGroupsHTML,
            ]);
        }
        return response()->json([
            'success' => true,
            'membership' => 1,
            'message' => 'Already a member of this group',
        ]);
    }

    public function leaveGroup($id)
    {
        $user = User::findOrFail(Auth::id());
        $membership = $user->groups()
            ->where('groups.id', $id)
            ->exists();
        if ($membership) {
            $user->groups()
                ->detach($id);

            $group = Group::findOrFail($id);
            $group->update(['member_count' => $group->getMemberCount()]);

            $joinedGroups = $user->groups()
                ->wherePivot('role', 'member')
                ->orderBy('is_starred', 'desc')
                ->orderBy('name', 'asc')
                ->get();

            $joinedGroupsHTML = '
                <div class="section-header">
                    <p>Groups Joined</p>
                </div>';

            if ($joinedGroups->count() > 0) {
                foreach ($joinedGroups as $joinedGroup) {
                    $joinedGroupsHTML .= view('components.group-info-minimal', ['group' => $joinedGroup])->render();
                }
            } else {
                $joinedGroupsHTML .= '<p class="empty">No groups joined yet...</p>';
            }

            return response()->json([
                'success' => true,
                'membership' => 0,
                'joinedGroupsHTML' => $joinedGroupsHTML,
            ]);
        }
        return response()->json([
            'success' => false,
            'membership' => 0,
            'message' => 'Not a member of this group yet'
        ]);
    }

    public function leaveGroupAlt($id){
        $user = User::findOrFail(Auth::id());
        if(!is_array($id)){
            if(strpos($id, ',') !== false) $ids = explode(',', $id);
            else $ids = [$id];
        } else $ids = $id;

        $leftGroups = [];
        foreach($ids as $groupId){
            $membership = $user->groups()->where('group_id', $groupId)->exists();
            if ($membership) {
                $user->groups()->detach($groupId);
                $leftGroups[] = $groupId;
            }
        }

        return response()->json([
            'success' => true,
            'left_groups' => $leftGroups,
            'message' => count($leftGroups) > 1 ? 'Groups left successfully.' : 'Group left successfully.',
        ]);
    }

    public function showGroupSettings($id, Request $request)
    {
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        if ($group->isOwner($user) || $group->isModerator($user)) {
            $moderatorIds = $group->getModeratorIds();
            $moderators = User::whereIn('id', $moderatorIds)
                ->get();

            $memberIds = $group->getMemberIds();
            $members = User::whereIn('id', $memberIds)
                ->get();

            return view('group-settings', compact(
                'group',
                'moderators',
                'members',
            ));
        } else {
            return $this->showGroups($request)->with('error', 'Must be moderator or owner to access group settings');
        }
    }

    public function addModerators($id, Request $request)
    {
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        if ($group->isOwner($user) || $group->isModerator($user)) {
            $data = $request->validate([
                'moderators' => 'required|array|min:1',
                'moderators.*' => 'exists:users,id',
            ]);

            foreach ($data['moderators'] as $moderatorId) {
                $group->members()->updateExistingPivot($moderatorId, ['role' => 'moderator']);

                InboxMessage::create([
                    'sender_id' => $user->id,
                    'recipient_id' => $moderatorId,
                    'type' => 'moderator_action',
                    'title' => "You have been made a moderator for <a href='/group/$group->id'>$group->name</a>",
                    'body' => "You have been promoted to moderator in the group <a href='/group/$group->id'>$group->name</a> by <a href='/user/$user->id'>$user->name</a>.",
                    'group_id' => $group->id,
                ]);
            }

            return back()->with('success', 'Member(s) promoted to moderator.');
        } else {
            return back()->with('error', 'Must be a group moderator/owner to access that feature.');
        }
    }

    public function demoteModerator($groupId, $userId)
    {
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());

        if ($group->isOwner($user) || $group->isModerator($user)) {
            $member = User::findOrFail($userId);
            $group->members()->updateExistingPivot($member->id, ['role' => 'member']);

            InboxMessage::create([
                'sender_id' => $user->id,
                'recipient_id' => $member->id,
                'type' => 'moderator_action',
                'title' => "You have been made a member for <a href='/group/$group->id'>$group->name</a>",
                'body' => "You have been demoted to member in the group <a href='/group/$group->id'>$group->name</a> by <a href='/user/$user->id'>$user->name</a>.",
                'group_id' => $group->id,
            ]);

            return back()->with('success', 'Member demoted to member.');
        } else {
            return back()->with('error', 'Must be a group moderator/owner to access that feature.');
        }
    }

    public function requestToJoinGroup($id)
    {
        $user = User::findOrFail(Auth::id());
        $membership = $user->groups()
            ->where('groups.id', $id)
            ->exists();

        $requested = InboxMessage::hasPendingGroupJoinRequest($user->id, $id);

        if (!$membership && !$requested) {
            $group = Group::findOrFail($id);
            foreach ($group->getModeratorAndOwnerIds() as $recipientId) {
                InboxMessage::create([
                    'sender_id' => $user->id,
                    'recipient_id' => $recipientId,
                    'group_id' => $group->id,
                    'type' => 'group_join_request',
                    'title' => "<a href='/user/$user->id'>$user->name</a> requests to join <a href='/group/$group->id'>$group->name</a>",
                    'body' => "<a href='/user/$user->id'>$user->name</a> has requested to join a group you created/moderated, <a href='/group/$group->id'>$group->name</a>. Accept or reject this request?"
                ]);
            }

            return response()->json([
                'success' => true,
                'requested' => true,
                'message' => 'Requested to join ' . $group->name,
            ]);
        } else {
            $message = $membership ?
                'You are already a member.' :
                'You already requested to join.';
            return response()->json([
                'success' => false,
                'requested' => $requested,
                'message' => $message,
            ]);
        }
    }

    // Group Settings Modal Methods
    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        // Check if user has permission to update group
        if (!$group->isOwner($user) && !$group->isModerator($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to update this group.'
                ], 403);
            }
            return redirect()->back()->with('error', 'You do not have permission to update this group.');
        }

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|min:3|max:50',
                'description' => 'sometimes|required|string|min:10|max:500',
                'is_private' => 'sometimes|in:0,1',
                'type' => 'sometimes|in:academic,social',
                'rules' => 'sometimes|array',
                'rules.*.title' => 'required_with:rules|string|max:60',
                'rules.*.description' => 'required_with:rules|string|max:500',
                'resources' => 'sometimes|array',
                'resources.*.title' => 'required_with:resources|string|max:60',
                'resources.*.description' => 'required_with:resources|string|max:500',
            ]);

            // Convert is_private string to boolean
            if (isset($validatedData['is_private'])) {
                $validatedData['is_private'] = (bool) $validatedData['is_private'];
            }

            $group->update($validatedData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Group updated successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Group updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the group.'
                ], 500);
            }
            return redirect()->back()->with('error', 'An error occurred while updating the group.');
        }
    }

    public function invite(Request $request, $groupId)
    {
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());


        if (!$group->isOwner($user) && !$group->isModerator($user)) {
            return redirect()->back()->with('error', 'Unauthorized');
        } else {
            $userIds = $request->input('user_ids', []);

            foreach ($userIds as $userId) {
                if ($group->isMember(User::findOrFail($userId))) {
                    return redirect()->back()->with('error', 'Already member of group');
                } else {
                    InboxMessage::create([
                        'sender_id' => $user->id,
                        'recipient_id' => $userId,
                        'group_id' => $group->id,
                        'type' => 'group_invitation',
                        'title' => "Invitation to join <a href='/group/{$group->id}'>{$group->name}</a>",
                        'body' => "{$user->name} has invited you to to join <a href='/group/{$group->id}'>{$group->name}</a>.",
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Invitations sent successfully');
        }
    }

    public function promote(Request $request, $groupId, $userId)
    {
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());
        $targetUser = User::findOrFail($userId);

        // Only owners can promote members
        if (!$group->isOwner($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only group owners can promote members.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Only group owners can promote members.');
        }

        // Check if target user is a member
        $membership = $group->members()->where('user_id', $userId)->first();
        if (!$membership) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a member of this group.'
                ], 404);
            }
            return redirect()->back()->with('error', 'User is not a member of this group.');
        }

        // Check if user is already a moderator or owner
        if ($membership->pivot->role !== 'member') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is already a moderator or owner.'
                ], 400);
            }
            return redirect()->back()->with('error', 'User is already a moderator or owner.');
        }

        // Promote to moderator
        $group->members()->updateExistingPivot($userId, ['role' => 'moderator']);

        InboxMessage::create([
            'sender_id' => $user->id,
            'recipient_id' => $targetUser->id,
            'group_id' => $group->id,
            'type' => 'moderator_action',
            'title' => "You have been promoted to moderator in <a href='/group/{$group->id}'>{$group->name}</a>",
            'body' => "{$user->name} has promoted you to moderator in <a href='/group/{$group->id}'>{$group->name}</a>.",
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $targetUser->name . ' has been promoted to moderator.'
            ]);
        }

        return redirect()->back()->with('success', $targetUser->name . ' has been promoted to moderator.');
    }

    public function demote(Request $request, $groupId, $userId)
    {
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());
        $targetUser = User::findOrFail($userId);

        // Only owners can demote moderators
        if (!$group->isOwner($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only group owners can demote moderators.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Only group owners can demote moderators.');
        }

        // Check if target user is a member
        $membership = $group->members()->where('user_id', $userId)->first();
        if (!$membership) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a member of this group.'
                ], 404);
            }
            return redirect()->back()->with('error', 'User is not a member of this group.');
        }

        // Check if user is a moderator
        if ($membership->pivot->role !== 'moderator') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a moderator.'
                ], 400);
            }
            return redirect()->back()->with('error', 'User is not a moderator.');
        }

        // Demote to member
        $group->members()->updateExistingPivot($userId, ['role' => 'member']);

        InboxMessage::create([
            'sender_id' => $user->id,
            'recipient_id' => $targetUser->id,
            'group_id' => $group->id,
            'type' => 'moderator_action',
            'title' => "You have been demoted to member in <a href='/group/{$group->id}'>{$group->name}</a>",
            'body' => "{$user->name} has demoted you to member in <a href='/group/{$group->id}'>{$group->name}</a>.",
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $targetUser->name . ' has been demoted to member.'
            ]);
        }

        return redirect()->back()->with('success', $targetUser->name . ' has been demoted to member.');
    }

    public function removeMember(Request $request, $groupId, $userId)
    {
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());
        $targetUser = User::findOrFail($userId);

        // Owners and moderators can remove members, but only owners can remove moderators
        $membership = $group->members()->where('user_id', $userId)->first();
        if (!$membership) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User is not a member of this group.'
                ], 404);
            }
            return redirect()->back()->with('error', 'User is not a member of this group.');
        }

        $targetRole = $membership->pivot->role;
        $userRole = $group->members()->where('user_id', Auth::id())->first()->pivot->role;

        // Cannot remove owners
        if ($targetRole === 'owner') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove group owner.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Cannot remove group owner.');
        }

        // Only owners can remove moderators
        if ($targetRole === 'moderator' && $userRole !== 'owner') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only owners can remove moderators.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Only owners can remove moderators.');
        }

        // Moderators and owners can remove regular members
        if (!$group->isOwner($user) && !$group->isModerator($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to remove members.'
                ], 403);
            }
            return redirect()->back()->with('error', 'You do not have permission to remove members.');
        }

        // Remove the member
        $group->members()->detach($userId);
        $group->update(['member_count' => $group->getMemberCount()]);

        InboxMessage::create([
            'sender_id' => $user->id,
            'recipient_id' => $targetUser->id,
            'group_id' => $group->id,
            'type' => 'moderator_action',
            'title' => "You have been kicked out of <a href='/group/{$group->id}'>{$group->name}</a>",
            'body' => "{$user->name} has kicked you out of <a href='/group/{$group->id}'>{$group->name}</a>.",
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $targetUser->name . ' has been removed from the group.'
            ]);
        }

        return redirect()->back()->with('success', $targetUser->name . ' has been removed from the group.');
    }

    public function updatePermissions(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        // Only owners can update permissions
        if (!$group->isOwner($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only group owners can update permissions.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Only group owners can update permissions.');
        }

        try {
            $validatedData = $request->validate([
                'members_can_post' => 'sometimes|boolean',
                'members_can_comment' => 'sometimes|boolean',
                'members_can_invite' => 'sometimes|boolean',
                'auto_approve_posts' => 'sometimes|boolean',
            ]);

            $group->update($validatedData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permissions updated successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Permissions updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating permissions.'
                ], 500);
            }
            return redirect()->back()->with('error', 'An error occurred while updating permissions.');
        }
    }

    public function transferOwnership(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        // Only current owner can transfer ownership
        if (!$group->isOwner($user)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the current owner can transfer ownership.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Only the current owner can transfer ownership.');
        }

        try {
            $request->validate([
                'new_owner_id' => 'required|exists:users,id'
            ]);

            $newOwnerId = $request->new_owner_id;
            $newOwner = User::findOrFail($newOwnerId);

            // Check if new owner is a member of the group
            $membership = $group->members()->where('user_id', $newOwnerId)->first();
            if (!$membership) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The selected user is not a member of this group.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'The selected user is not a member of this group.');
            }

            // Transfer ownership
            $group->members()->updateExistingPivot(Auth::id(), ['role' => 'moderator']);
            $group->members()->updateExistingPivot($newOwnerId, ['role' => 'owner']);
            $group->update(['owner_id' => $newOwnerId]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ownership has been transferred to ' . $newOwner->name . '.'
                ]);
            }

            return redirect()->back()->with('success', 'Ownership has been transferred to ' . $newOwner->name . '.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while transferring ownership.'
                ], 500);
            }
            return redirect()->back()->with('error', 'An error occurred while transferring ownership.');
        }
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        // Only owners can delete groups
        if (!$group->isOwner($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Only group owners can delete groups.'
            ], 403);
        }

        try {
            // Delete associated data first
            $group->members()->detach(); // Remove all members
            $group->posts()->delete(); // Delete all posts (you might want to soft delete instead)

            // Delete the group
            $group->delete();

            return response()->json([
                'success' => true,
                'message' => 'Group has been deleted successfully.',
                'redirect' => '/groups'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the group.'
            ], 500);
        }
    }

    public function createAssignment(Request $request, $id)
    {
        try {
            $group = Group::findOrFail($id);

            // Auto-set submission_type for quiz/exam assignments
            if (in_array($request->assignment_type, ['quiz', 'exam']) && empty($request->submission_type)) {
                $request->merge(['submission_type' => 'quiz']);
            }

            $validatedData = $request->validate([
                'assignment_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'max_points' => 'required|integer|min:0',
                'assignment_type' => 'required|in:assignment,quiz,essay,discussion,exam,project,homework,presentation',
                'submission_type' => 'required|in:text,file,external_link,none,quiz',
                'time_limit' => 'nullable|integer|min:1|max:600',
                'visibility' => 'required|in:draft,published',
                'date_assigned' => 'nullable|date',
                'date_due' => 'required|date|after_or_equal:date_assigned',
                'close_date' => 'nullable|date|after_or_equal:date_due',
            ]);

            // Support alternate front-end field name 'date_close'
            if ($request->filled('date_close') && empty($validatedData['close_date'] ?? null)) {
                $validatedData['close_date'] = $request->input('date_close');
            }

            // Convert datetime fields from Pacific time to UTC for storage
            foreach (['date_assigned', 'date_due', 'close_date'] as $dateField) {
                if (isset($validatedData[$dateField]) && !empty($validatedData[$dateField])) {
                    try {
                        $pacificDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validatedData[$dateField], 'America/Los_Angeles');
                        $validatedData[$dateField] = $pacificDate->setTimezone('UTC')->toDateTimeString();
                    } catch (\Exception $e) {
                        Log::error("Error converting {$dateField} in create: " . $e->getMessage());
                    }
                }
            }

            // Handle allow_late_submissions checkbox (same as update method)
            if ($request->has('allow_late_submissions')) {
                $value = $request->input('allow_late_submissions');
                $validatedData['allow_late_submissions'] = in_array($value, [1, '1', 'true', true], true);
            } else {
                $validatedData['allow_late_submissions'] = false;
            }

            $validatedData['created_by'] = Auth::id();
            $validatedData['group_id'] = $group->id;

            $assignment = Assignment::create($validatedData);

            // Handle quiz questions if assignment is quiz/exam
            if (in_array($request->assignment_type, ['quiz', 'exam']) && $request->has('quiz_questions')) {
                $quizQuestions = json_decode($request->quiz_questions, true);
                $totalPoints = 0;

                if ($quizQuestions && is_array($quizQuestions)) {
                    foreach ($quizQuestions as $index => $questionData) {
                        $questionPoints = $questionData['points'] ?? 1;
                        $totalPoints += $questionPoints;

                        $question = $assignment->quizQuestions()->create([
                            'question_text' => $questionData['question_text'],
                            'question_type' => $questionData['question_type'],
                            'points' => $questionPoints,
                            'order' => $index + 1,
                        ]);

                        // Handle options for multiple choice/checkbox questions
                        if (isset($questionData['options']) && is_array($questionData['options'])) {
                            foreach ($questionData['options'] as $optionData) {
                                $question->options()->create([
                                    'option_text' => $optionData['option_text'],
                                    'is_correct' => $optionData['is_correct'] ?? false,
                                ]);
                            }
                        }
                    }

                    // Auto-update max_points based on total question points
                    if ($totalPoints > 0) {
                        $assignment->max_points = $totalPoints;
                        $assignment->save();
                    }
                }
            }

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('assignment_attachments', 'public');

                    $assignment->attachments()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                    ]);
                }
            }

            // Handle rubrics if provided
            if ($request->has('rubrics_data') && !empty($request->rubrics_data)) {
                $rubrics = json_decode($request->rubrics_data, true);
                $totalRubricPoints = 0;

                if ($rubrics && is_array($rubrics)) {
                    foreach ($rubrics as $rubricData) {
                        if (!empty($rubricData['name']) && isset($rubricData['points'])) {
                            $assignment->rubrics()->create([
                                'criterion_name' => $rubricData['name'],
                                'criterion_description' => $rubricData['description'] ?? '',
                                'points' => $rubricData['points'],
                            ]);
                            $totalRubricPoints += $rubricData['points'];
                        }
                    }

                    // Update max_points to match rubric total if rubrics were added
                    if ($totalRubricPoints > 0) {
                        $assignment->max_points = $totalRubricPoints;
                        $assignment->save();
                    }
                }
            }

            return back()->with('success', 'Assignment created successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create assignment: ' . $e->getMessage())->withInput();
        }
    }

    public function getAssignments(Request $request, $id)
    {
        Log::info('getAssignments called with ID: ' . $id);
        try {
            $group = Group::findOrFail($id);
            $user = Auth::user();

            Log::info('User authenticated: ' . $user->id);
            Log::info('Group found: ' . $group->id);

            // Check if user is a member using direct database query
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $id)
                ->first();

            if (!$membership) {
                Log::info('User is not a member of this group');
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            Log::info('User is a member with role: ' . $membership->role);

            // Get assignments based on user role
            $query = $group->assignments()->with(['creator']);

            // Check user role from the pivot table
            $userRole = $membership->role;

            // If user is not owner/moderator, only show published assignments
            if ($userRole !== 'owner' && $userRole !== 'moderator') {
                $query->where('visibility', 'published');
            }

            // Get assignments
            $assignments = $query->orderBy('date_due', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

            // Format assignments for frontend
            $formattedAssignments = $assignments->map(function ($assignment) use ($membership, $group, $user) {
                // Get student submission status if user is a student
                $submissionStatus = null;
                if ($membership->role !== 'owner' && $membership->role !== 'moderator') {
                    $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
                        ->where('student_id', $user->id)
                        ->first();

                    if ($submission) {
                        if ($submission->status === 'graded') {
                            $submissionStatus = 'graded';
                        } elseif ($submission->status === 'submitted') {
                            $submissionStatus = $submission->is_late ? 'submitted_late' : 'submitted';
                        } else {
                            $submissionStatus = 'draft';
                        }
                    } else {
                        $submissionStatus = 'not_submitted';
                    }
                }

                return [
                    'id' => $assignment->id,
                    'assignment_name' => $assignment->assignment_name,
                    'description' => $assignment->description,
                    'assignment_type' => $assignment->assignment_type,
                    'submission_type' => $assignment->submission_type,
                    'max_points' => $assignment->max_points,
                    'visibility' => $assignment->visibility,
                    'date_assigned' => $assignment->date_assigned
                        ? $assignment->date_assigned->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                        : null,
                    'date_due' => $assignment->date_due->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s'),
                    'close_date' => $assignment->close_date
                        ? $assignment->close_date->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                        : null,
                    'creator_name' => $assignment->creator->name,
                    'is_overdue' => $assignment->is_overdue,
                    'is_closed' => $assignment->is_closed,
                    'created_at' => $assignment->created_at->format('M j, Y g:i A'),
                    'can_edit' => $group->owner_id === Auth::id() || $membership->role === 'moderator',
                    'submission_status' => $submissionStatus,
                ];
            });

            return response()->json([
                'success' => true,
                'assignments' => $formattedAssignments,
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Assignment fetch error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['message' => 'Failed to fetch assignments', 'error' => $e->getMessage()], 500);
        }
    }

    public function getAssignment(Request $request, $groupId, $assignmentId)
    {
        Log::info("DEBUG: Getting assignment - Group: {$groupId}, Assignment: {$assignmentId}");

        try {
            // Step 1: Check if assignment exists
            $assignment = Assignment::find($assignmentId);
            if (!$assignment) {
                Log::error("DEBUG: Assignment not found: {$assignmentId}");
                return response()->json(['message' => 'Assignment not found'], 404);
            }

            Log::info("DEBUG: Assignment found: " . $assignment->assignment_name);

            // Step 2: Check group match
            if ($assignment->group_id != $groupId) {
                Log::error("DEBUG: Group mismatch - Assignment group: {$assignment->group_id}, Request group: {$groupId}");
                return response()->json([
                    'message' => 'Assignment does not belong to this group'
                ], 404);
            }

            // Step 3: Check user membership
            $user = Auth::user();
            if (!$user) {
                Log::error("DEBUG: No authenticated user");
                return response()->json(['message' => 'Not authenticated'], 401);
            }

            Log::info("DEBUG: User ID: " . $user->id);

            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                Log::error("DEBUG: User {$user->id} is not a member of group {$groupId}");
                return response()->json([
                    'message' => 'You are not a member of this group'
                ], 403);
            }

            Log::info("DEBUG: User role: " . $membership->role);

            // Step 4: Check visibility permissions
            $userRole = $membership->role;
            if (
                $assignment->visibility === 'draft' &&
                $userRole !== 'owner' &&
                $userRole !== 'moderator'
            ) {
                Log::error("DEBUG: Permission denied - Assignment is draft and user is not owner/moderator");
                return response()->json([
                    'message' => 'This assignment is not available yet'
                ], 403);
            }

            // Step 5: Load relationships safely
            try {
                $assignment->load('creator', 'group');
                Log::info("DEBUG: Relationships loaded successfully");
            } catch (\Exception $e) {
                Log::error("DEBUG: Error loading relationships: " . $e->getMessage());
                return response()->json(['message' => 'Error loading assignment relationships'], 500);
            }

            // Step 6: Count submissions safely
            $submissionCount = 0;
            try {
                $submissionCount = $assignment->submissions()->count();
                Log::info("DEBUG: Submission count: " . $submissionCount);
            } catch (\Exception $e) {
                Log::error("DEBUG: Error counting submissions: " . $e->getMessage());
                // Continue without submission count
            }

            // Step 7: Format response
            $formattedAssignment = [
                'id' => $assignment->id,
                'assignment_name' => $assignment->assignment_name,
                'description' => $assignment->description,
                'assignment_type' => $assignment->assignment_type,
                'submission_type' => $assignment->submission_type,
                'max_points' => $assignment->max_points,
                'time_limit' => $assignment->time_limit,
                'visibility' => $assignment->visibility,
                'date_assigned' => $assignment->date_assigned
                    ? $assignment->date_assigned->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                    : null,
                'date_due' => $assignment->date_due
                    ? $assignment->date_due->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                    : null,
                'close_date' => $assignment->close_date
                    ? $assignment->close_date->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                    : null,
                'creator_name' => $assignment->creator ? $assignment->creator->name : 'Unknown',
                'creator_id' => $assignment->created_by,
                'is_overdue' => $assignment->is_overdue,
                'is_closed' => $assignment->is_closed,
                'created_at' => $assignment->created_at->format('M j, Y g:i A'),
                'submission_count' => $submissionCount,
                'group_name' => $assignment->group ? $assignment->group->name : 'Unknown',
                'allow_late_submissions' => $assignment->allow_late_submissions,
                'late_penalty_percentage' => $assignment->late_penalty_percentage,
            ];

            // Add quiz questions if it's a quiz or exam
            if (in_array($assignment->assignment_type, ['quiz', 'exam'])) {
                $quizQuestions = $assignment->quizQuestions()->with('options')->orderBy('order')->get();
                $formattedAssignment['quiz_questions'] = $quizQuestions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'question_type' => $question->question_type,
                        'points' => $question->points,
                        'order' => $question->order,
                        'options' => $question->options->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'option_text' => $option->option_text,
                                'is_correct' => $option->is_correct,
                            ];
                        })
                    ];
                });
            }

            // Add attachments
            $formattedAssignment['attachments'] = $assignment->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'file_name' => $attachment->file_name,
                    'file_path' => $attachment->file_path,
                    'file_type' => $attachment->file_type,
                    'file_size' => $attachment->file_size,
                    'download_url' => asset('storage/' . $attachment->file_path),
                ];
            });

            Log::info("DEBUG: Successfully formatted assignment response");

            return response()->json([
                'assignment' => $formattedAssignment
            ], 200);
        } catch (\Exception $e) {
            Log::error('DEBUG: Get single assignment error: ' . $e->getMessage());
            Log::error('DEBUG: Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'message' => 'Failed to fetch assignment',
                'error' => $e->getMessage(),
                'debug' => true
            ], 500);
        }
    }

    public function updateAssignment(Request $request, $groupId, $assignmentId)
    {
        Log::info("DEBUG UPDATE: Updating assignment - Group: {$groupId}, Assignment: {$assignmentId}");

        try {
            // Step 1: Find assignment
            $assignment = Assignment::findOrFail($assignmentId);
            Log::info("DEBUG UPDATE: Assignment found: " . $assignment->assignment_name);

            // Step 2: Check group match
            if ($assignment->group_id != $groupId) {
                Log::error("DEBUG UPDATE: Group mismatch");
                return response()->json([
                    'message' => 'Assignment does not belong to this group'
                ], 404);
            }

            // Step 3: Check user permissions
            $user = Auth::user();
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                Log::error("DEBUG UPDATE: User not a member");
                return response()->json([
                    'message' => 'You are not a member of this group'
                ], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;
            Log::info("DEBUG UPDATE: User role: {$userRole}, Is creator: " . ($isCreator ? 'yes' : 'no'));

            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                Log::error("DEBUG UPDATE: Permission denied");
                return response()->json([
                    'message' => 'You do not have the permission to edit this assignment'
                ], 403);
            }

            // Step 4: Log incoming data
            Log::info("DEBUG UPDATE: Request data: " . json_encode($request->all()));
            Log::info("DEBUG UPDATE: allow_late_submissions in request? " . ($request->has('allow_late_submissions') ? 'YES' : 'NO'));
            if ($request->has('allow_late_submissions')) {
                Log::info("DEBUG UPDATE: allow_late_submissions raw value: " . var_export($request->input('allow_late_submissions'), true));
            }

            // Step 5: Validate data (temporarily exclude description to test)
            $validatedData = $request->validate([
                'assignment_name' => 'sometimes|required|string|max:255',
                // 'description' => 'nullable|string|max:1000',  // TEMPORARILY DISABLED
                'max_points' => 'sometimes|required|integer|min:0',
                'assignment_type' => 'sometimes|required|in:assignment,quiz,essay,discussion,exam,project,homework,presentation',
                'submission_type' => 'sometimes|required|in:text,file,external_link,none,quiz',
                'visibility' => 'sometimes|required|in:draft,published',
                'date_assigned' => 'nullable|date',
                'date_due' => 'sometimes|required|date|after_or_equal:date_assigned',
                'close_date' => 'nullable|date|after_or_equal:date_due',
                'late_penalty_percentage' => 'nullable|numeric|min:0|max:100'
            ]);

            // Support alternate front-end field name 'date_close'
            if ($request->filled('date_close') && empty($validatedData['close_date'] ?? null)) {
                $validatedData['close_date'] = $request->input('date_close');
            }

            // Handle allow_late_submissions separately since HTML checkboxes send strings
            if ($request->has('allow_late_submissions')) {
                $value = $request->input('allow_late_submissions');
                $validatedData['allow_late_submissions'] = in_array($value, [1, '1', 'true', true], true);
                Log::info("DEBUG UPDATE: allow_late_submissions - Raw value: {$value}, Converted to: " . ($validatedData['allow_late_submissions'] ? 'true' : 'false'));
            } else {
                // If not present in request at all, explicitly set to false
                $validatedData['allow_late_submissions'] = false;
                Log::info("DEBUG UPDATE: allow_late_submissions not in request, setting to false");
            }

            // Convert datetime fields from Pacific time to UTC for storage
            // The form sends dates without timezone info (e.g., "2025-10-14T18:52")
            // We need to interpret these as Pacific time and convert to UTC
            foreach (['date_assigned', 'date_due', 'close_date'] as $dateField) {
                if (isset($validatedData[$dateField]) && !empty($validatedData[$dateField])) {
                    try {
                        // Parse as Pacific time and convert to UTC
                        $pacificDate = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validatedData[$dateField], 'America/Los_Angeles');
                        $validatedData[$dateField] = $pacificDate->setTimezone('UTC')->toDateTimeString();
                        Log::info("DEBUG UPDATE: Converted {$dateField} from Pacific to UTC: " . $validatedData[$dateField]);
                    } catch (\Exception $e) {
                        Log::error("DEBUG UPDATE: Error converting {$dateField}: " . $e->getMessage());
                    }
                }
            }

            Log::info("DEBUG UPDATE: Validated data: " . json_encode($validatedData));
            Log::info("DEBUG UPDATE: allow_late_submissions in validatedData: " . var_export($validatedData['allow_late_submissions'] ?? 'NOT SET', true));

            // Step 6: Update assignment
            $assignment->update($validatedData);

            // Verify the update was saved
            $assignment->refresh();
            Log::info("DEBUG UPDATE: Assignment updated successfully");
            Log::info("DEBUG UPDATE: After update - allow_late_submissions from model: " . var_export($assignment->allow_late_submissions, true));
            Log::info("DEBUG UPDATE: After update - raw DB value: " . var_export($assignment->getAttributes()['allow_late_submissions'] ?? 'NOT SET', true));

            // Step 7: Load relationships and format response
            $assignment->load(['creator', 'group']);

            $submissionCount = 0;
            try {
                $submissionCount = $assignment->submissions()->count();
            } catch (\Exception $e) {
                Log::error("DEBUG UPDATE: Error counting submissions: " . $e->getMessage());
            }

            $formattedAssignment = [
                'id' => $assignment->id,
                'assignment_name' => $assignment->assignment_name,
                'description' => $assignment->description,
                'assignment_type' => $assignment->assignment_type,
                'submission_type' => $assignment->submission_type,
                'max_points' => $assignment->max_points,
                'visibility' => $assignment->visibility,
                'date_assigned' => $assignment->date_assigned
                    ? $assignment->date_assigned->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                    : null,
                'date_due' => $assignment->date_due
                    ? $assignment->date_due->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                    : null,
                'close_date' => $assignment->close_date
                    ? $assignment->close_date->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s')
                    : null,
                'creator_name' => $assignment->creator ? $assignment->creator->name : 'Unknown',
                'is_overdue' => $assignment->is_overdue,
                'is_closed' => $assignment->is_closed,
                'updated_at' => $assignment->updated_at->format('M j, Y g:i A'),
                'submission_count' => $submissionCount,
            ];

            Log::info("DEBUG UPDATE: Response formatted successfully");

            return response()->json([
                'success' => true,
                'message' => 'Assignment updated successfully!',
                'assignment' => $formattedAssignment
            ], 200);
        } catch (ValidationException $e) {
            Log::error("DEBUG UPDATE: Validation error: " . json_encode($e->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('DEBUG UPDATE: Update assignment error: ' . $e->getMessage());
            Log::error('DEBUG UPDATE: Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update assignment: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAssignment(Request $request, $groupId, $assignmentId)
    {
        Log::info("DEBUG DELETE: Starting delete for assignment {$assignmentId} in group {$groupId}");

        try {
            //find assignmetn again
            $assignment = Assignment::findOrFail($assignmentId);
            Log::info("DEBUG DELETE: Assignment found: " . $assignment->assignment_name);

            if ($assignment->group_id != $groupId) {
                Log::error("DEBUG DELETE: Group mismatch");
                return response()->json([
                    'message' => 'Assignment does not belong to this group !!!'
                ], 404);
            }

            $user = Auth::user();
            Log::info("DEBUG DELETE: User ID: " . $user->id);

            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                Log::error("DEBUG DELETE: User not a member");
                return response()->json([
                    'message' => 'You are not a member of this group'
                ], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;
            Log::info("DEBUG DELETE: User role: {$userRole}, Is creator: " . ($isCreator ? 'yes' : 'no'));

            //only creator can delete ass
            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                Log::error("DEBUG DELETE: Permission denied");
                return response()->json([
                    'message' => 'You do not have permission to delete this assignment'
                ], 403);
            }

            //cannot delete if there are submissions
            $submissionCount = 0;
            try {
                $submissionCount = $assignment->submissions()->count();
                Log::info("DEBUG DELETE: Submission count: " . $submissionCount);
            } catch (\Exception $e) {
                Log::warning("DEBUG DELETE: Error counting submissions (might not exist yet): " . $e->getMessage());
                // Continue with deletion if submission table doesn't exist yet
            }

            if ($submissionCount > 0) {
                Log::error("DEBUG DELETE: Cannot delete - has submissions");
                return response()->json([
                    'message' => 'Cannot delete assignment with existing submissions. Consider hiding it instead.',
                    'submission_count' => $submissionCount
                ], 400);
            }

            $assignmentName = $assignment->assignment_name;
            Log::info("DEBUG DELETE: Attempting to delete assignment: " . $assignmentName);

            $assignment->delete();

            Log::info("DEBUG DELETE: Assignment deleted successfully");

            return back()->with('success', "Assignment '{$assignmentName}' has been deleted successfully!");
        } catch (\Exception $e) {
            Log::error('DEBUG DELETE: Delete assignment error: ' . $e->getMessage());
            Log::error('DEBUG DELETE: Stack trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Failed to delete assignment: ' . $e->getMessage());
        }
    }

    // ==================== STUDENT ASSIGNMENT SUBMISSION METHODS ====================

    public function getMySubmission(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::with(['quizQuestions.options'])->findOrFail($assignmentId);

            // Check if user is member of group
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            // Get or create draft submission
            $submission = AssignmentSubmission::with(['quizResponses.selectedOption'])
                ->where('assignment_id', $assignmentId)
                ->where('student_id', $user->id)
                ->first();

            if (!$submission) {
                // Create a new draft submission
                $submission = AssignmentSubmission::create([
                    'assignment_id' => $assignmentId,
                    'student_id' => $user->id,
                    'status' => 'draft'
                ]);
            }

            // Check if can submit: not closed, not already submitted, and (before due date OR late submissions allowed)
            $isPastDue = method_exists($assignment, 'isPastDuePacific')
                ? $assignment->isPastDuePacific()
                : (now() > $assignment->date_due);
            $canSubmit = !$assignment->is_closed
                && ($submission->status === 'draft' || is_null($submission->date_submitted))
                && (!$isPastDue || $assignment->allow_late_submissions);

            // Format assignment dates to Pacific time for frontend
            $formattedAssignment = $assignment->toArray();
            if ($assignment->date_assigned) {
                $formattedAssignment['date_assigned'] = $assignment->date_assigned->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s');
            }
            if ($assignment->date_due) {
                $formattedAssignment['date_due'] = $assignment->date_due->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s');
            }
            if ($assignment->close_date) {
                $formattedAssignment['close_date'] = $assignment->close_date->setTimezone('America/Los_Angeles')->format('Y-m-d\TH:i:s');
            }

            return response()->json([
                'assignment' => $formattedAssignment,
                'submission' => $submission,
                'can_submit' => $canSubmit
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get my submission error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to load submission'], 500);
        }
    }

    public function saveDraft(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check membership
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            // Get or create submission
            $submission = AssignmentSubmission::firstOrCreate(
                [
                    'assignment_id' => $assignmentId,
                    'student_id' => $user->id
                ],
                ['status' => 'draft']
            );

            // Update based on submission type
            if ($assignment->submission_type === 'text') {
                $submission->submission_text = $request->input('submission_text');
            } elseif ($assignment->submission_type === 'external_link') {
                $submission->external_link = $request->input('external_link');
            } elseif ($assignment->submission_type === 'quiz') {
                // Save quiz responses as draft
                $responses = $request->input('quiz_responses', []);
                foreach ($responses as $questionId => $response) {
                    StudentQuizResponse::updateOrCreate(
                        [
                            'submission_id' => $submission->id,
                            'question_id' => $questionId
                        ],
                        [
                            'selected_option_id' => $response['selected_option_id'] ?? null,
                            'text_response' => $response['text_response'] ?? null
                        ]
                    );
                }
            }

            $submission->save();

            return response()->json([
                'message' => 'Draft saved successfully',
                'submission' => $submission
            ], 200);
        } catch (\Exception $e) {
            Log::error('Save draft error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to save draft'], 500);
        }
    }

    public function submitAssignment(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::with('quizQuestions.options')->findOrFail($assignmentId);

            // Check membership
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return back()->with('error', 'You are not a member of this group');
            }

            // Check if assignment is closed
            if ($assignment->is_closed) {
                return back()->with('error', 'This assignment is closed and no longer accepting submissions');
            }

            // Check if late and late submissions not allowed (compare in Pacific time if helper exists)
            $isPastDue = method_exists($assignment, 'isPastDuePacific')
                ? $assignment->isPastDuePacific()
                : (now() > $assignment->date_due);

            // Detailed debugging
            Log::info("DEBUG SUBMIT: Assignment '{$assignment->assignment_name}' (ID: {$assignment->id})");
            Log::info("DEBUG SUBMIT: Server timezone: " . date_default_timezone_get());
            Log::info("DEBUG SUBMIT: Current time (now()): " . now()->toDateTimeString() . " [TZ: " . now()->timezone->getName() . "]");
            Log::info("DEBUG SUBMIT: Current time (Carbon UTC): " . \Carbon\Carbon::now('UTC')->toDateTimeString());
            Log::info("DEBUG SUBMIT: Current time (Carbon Pacific): " . \Carbon\Carbon::now('America/Los_Angeles')->toDateTimeString());
            Log::info("DEBUG SUBMIT: Due date: " . $assignment->date_due->toDateTimeString() . " [TZ: " . $assignment->date_due->timezone->getName() . "]");
            Log::info("DEBUG SUBMIT: Due date in Pacific: " . $assignment->date_due->copy()->setTimezone('America/Los_Angeles')->toDateTimeString());
            Log::info("DEBUG SUBMIT: isPastDue: " . ($isPastDue ? 'YES' : 'NO'));
            Log::info("DEBUG SUBMIT: allow_late_submissions value: " . var_export($assignment->allow_late_submissions, true));
            Log::info("DEBUG SUBMIT: allow_late_submissions type: " . gettype($assignment->allow_late_submissions));
            Log::info("DEBUG SUBMIT: Raw DB value: " . var_export($assignment->getAttributes()['allow_late_submissions'] ?? 'NOT SET', true));

            // Explicitly cast to boolean to ensure proper comparison
            $allowLateSubmissions = (bool) $assignment->allow_late_submissions;
            Log::info("DEBUG SUBMIT: Casted allow_late_submissions: " . ($allowLateSubmissions ? 'TRUE' : 'FALSE'));

            if ($isPastDue && !$allowLateSubmissions) {
                Log::info("DEBUG SUBMIT: BLOCKING submission - past due and late submissions not allowed");
                return back()->with('error', 'This assignment no longer accepts submissions after the due date.');
            }

            Log::info("DEBUG SUBMIT: ALLOWING submission");

            // Get or create submission
            $submission = AssignmentSubmission::firstOrCreate(
                [
                    'assignment_id' => $assignmentId,
                    'student_id' => $user->id
                ],
                ['status' => 'draft']
            );

            // Check if resubmitting
            if ($submission->status === 'submitted') {
                $submission->attempt_number += 1;
            }

            // Update submission based on type
            if ($assignment->submission_type === 'text') {
                $validated = $request->validate([
                    'submission_text' => 'required|string'
                ]);
                $submission->submission_text = $validated['submission_text'];
            } elseif ($assignment->submission_type === 'external_link') {
                $validated = $request->validate([
                    'external_link' => 'required|url'
                ]);
                $submission->external_link = $validated['external_link'];
            } elseif ($assignment->submission_type === 'file') {
                $validated = $request->validate([
                    'file' => 'required|file|max:10240' // 10MB max
                ]);

                // Store file
                $path = $request->file('file')->store('assignment_submissions', 'public');
                $submission->file_path = $path;
            } elseif ($assignment->assignment_type === 'quiz' || $assignment->assignment_type === 'exam') {
                // Handle quiz/exam submission
                $responses = json_decode($request->input('quiz_responses', '{}'), true);
                $totalPoints = 0;
                $earnedPoints = 0;

                foreach ($assignment->quizQuestions as $question) {
                    $totalPoints += $question->points;

                    if (isset($responses[$question->id])) {
                        $responseData = $responses[$question->id];

                        $quizResponse = StudentQuizResponse::updateOrCreate(
                            [
                                'submission_id' => $submission->id,
                                'question_id' => $question->id
                            ],
                            [
                                'selected_option_id' => $responseData['selected_option_id'] ?? null,
                                'text_response' => $responseData['text_response'] ?? null
                            ]
                        );

                        // Auto-grade MC and TF questions
                        if (in_array($question->question_type, ['multiple_choice', 'true_false'])) {
                            $selectedOption = QuizQuestionOption::find($responseData['selected_option_id'] ?? null);
                            if ($selectedOption && $selectedOption->is_correct) {
                                $quizResponse->is_correct = true;
                                $quizResponse->points_earned = $question->points;
                                $earnedPoints += $question->points;
                            } else {
                                $quizResponse->is_correct = false;
                                $quizResponse->points_earned = 0;
                            }
                            $quizResponse->save();
                        }
                    }
                }

                // Set grade for auto-gradable quizzes
                if ($totalPoints > 0) {
                    $submission->grade = ($earnedPoints / $totalPoints) * $assignment->max_points;
                }
            }

            // Check if late submission (validation already done above, this is just for tracking)
            $isLate = $isPastDue;

            // Calculate late penalty if applicable
            $latePenalty = 0;
            if ($isLate && $assignment->late_penalty_percentage > 0 && $submission->grade) {
                $latePenalty = ($submission->grade * $assignment->late_penalty_percentage) / 100;
                $submission->late_penalty_applied = $latePenalty;
                $submission->grade -= $latePenalty;
            }

            // Mark as submitted
            $submission->status = 'submitted';
            $submission->date_submitted = now();
            $submission->is_late = $isLate;
            $submission->save();

            $message = 'Assignment submitted successfully!';
            if ($isLate && $latePenalty > 0) {
                $message .= ' Note: A late penalty of ' . number_format($latePenalty, 2) . ' points was applied.';
            } elseif ($isLate) {
                $message .= ' Note: This submission was submitted after the due date.';
            }

            return back()->with('success', $message);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Submit assignment error: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit assignment: ' . $e->getMessage());
        }
    }

    // ==================== TEACHER GRADING - VIEW SUBMISSIONS ====================

    public function getAssignmentSubmissions(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if user is owner/moderator/creator
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;

            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                return response()->json(['message' => 'You do not have permission to view submissions'], 403);
            }

            // Get all group members
            $groupMembers = DB::table('group_members')
                ->join('users', 'group_members.user_id', '=', 'users.id')
                ->where('group_members.group_id', $groupId)
                ->where('group_members.role', 'member') // Only get students
                ->select('users.id', 'users.name', 'users.email')
                ->get();

            // Get all submissions for this assignment
            $submissions = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->with('student')
                ->get()
                ->keyBy('student_id');

            // Build submissions array with all students
            $submissionsArray = [];
            foreach ($groupMembers as $member) {
                $submission = $submissions->get($member->id);

                $submissionsArray[] = [
                    'student_id' => $member->id,
                    'student_name' => $member->name,
                    'student_email' => $member->email,
                    'status' => $submission ? $submission->status : 'not_submitted',
                    'grade' => $submission ? $submission->grade : null,
                    'submitted_at' => $submission && $submission->date_submitted ?
                        $submission->date_submitted->format('M j, Y g:i A') : null,
                    'is_late' => $submission ? $submission->is_late : false,
                ];
            }

            // Calculate stats
            $stats = [
                'total' => count($groupMembers),
                'submitted' => count(array_filter($submissionsArray, fn($s) => $s['status'] === 'submitted' || $s['status'] === 'graded')),
                'graded' => count(array_filter($submissionsArray, fn($s) => $s['status'] === 'graded')),
                'not_submitted' => count(array_filter($submissionsArray, fn($s) => $s['status'] === 'not_submitted')),
            ];

            return response()->json([
                'success' => true,
                'submissions' => $submissionsArray,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Get assignment submissions error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch submissions'], 500);
        }
    }

    // Get single student submission for grading
    public function getStudentSubmission(Request $request, $groupId, $assignmentId, $studentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);
            $student = User::findOrFail($studentId);

            // Check if user is owner/moderator/creator
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership || !in_array($membership->role, ['owner', 'moderator'])) {
                if ($assignment->creator_id !== $user->id) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }

            // Get or create submission
            $submission = AssignmentSubmission::firstOrCreate(
                [
                    'assignment_id' => $assignmentId,
                    'student_id' => $studentId
                ],
                [
                    'status' => 'not_submitted'
                ]
            );

            // Load quiz responses if it's a quiz
            if ($assignment->assignment_type === 'quiz' || $assignment->assignment_type === 'exam') {
                $submission->load('quizResponses');
                Log::info('Quiz responses loaded', [
                    'submission_id' => $submission->id,
                    'responses_count' => $submission->quizResponses->count()
                ]);
            }

            return response()->json([
                'success' => true,
                'submission' => $submission,
                'assignment' => $assignment->load('quizQuestions.options'),
                'student' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Get student submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch submission: ' . $e->getMessage()
            ], 500);
        }
    }

    // Grade student submission
    public function gradeSubmission(Request $request, $groupId, $assignmentId, $studentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if user is owner/moderator/creator
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership || !in_array($membership->role, ['owner', 'moderator'])) {
                if ($assignment->creator_id !== $user->id) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }

            // Validate input
            $validated = $request->validate([
                'grade' => 'required|numeric|min:0|max:' . $assignment->max_points,
                'teacher_feedback' => 'nullable|string'
            ]);

            // Find submission
            $submission = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('student_id', $studentId)
                ->firstOrFail();

            // Update submission
            $submission->grade = $validated['grade'];
            $submission->teacher_feedback = $validated['teacher_feedback'] ?? null;
            $submission->status = 'graded';
            $submission->graded_at = now();
            $submission->save();

            return response()->json([
                'success' => true,
                'message' => 'Grade saved successfully',
                'submission' => $submission
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Grade submission error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save grade: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== TEACHER QUIZ MANAGEMENT METHODS ====================

    public function getQuizQuestions(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if user is owner/moderator/creator
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            $questions = QuizQuestion::where('assignment_id', $assignmentId)
                ->with('options')
                ->orderBy('order')
                ->get();

            return response()->json([
                'success' => true,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            Log::error('Get quiz questions error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch quiz questions'], 500);
        }
    }

    public function saveQuizQuestions(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if user is owner/moderator/creator
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;

            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                return response()->json(['message' => 'You do not have permission to edit this quiz'], 403);
            }

            $validated = $request->validate([
                'questions' => 'required|array',
                'questions.*.id' => 'nullable|integer',
                'questions.*.question_text' => 'required|string',
                'questions.*.question_type' => 'required|in:multiple_choice,true_false,short_answer,essay',
                'questions.*.points' => 'required|numeric|min:0',
                'questions.*.options' => 'nullable|array',
                'questions.*.options.*.option_text' => 'required|string',
                'questions.*.options.*.is_correct' => 'required|boolean',
            ]);

            // Delete all existing questions and options for this assignment
            QuizQuestion::where('assignment_id', $assignmentId)->delete();

            // Create new questions
            foreach ($validated['questions'] as $index => $questionData) {
                $question = QuizQuestion::create([
                    'assignment_id' => $assignmentId,
                    'question_text' => $questionData['question_text'],
                    'question_type' => $questionData['question_type'],
                    'points' => $questionData['points'],
                    'order' => $index + 1
                ]);

                // Add options for MC/TF questions
                if (isset($questionData['options']) && !empty($questionData['options'])) {
                    foreach ($questionData['options'] as $optIndex => $optionData) {
                        QuizQuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optionData['option_text'],
                            'is_correct' => $optionData['is_correct'],
                            'order' => $optIndex + 1
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Quiz questions saved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Save quiz questions error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to save quiz questions', 'error' => $e->getMessage()], 500);
        }
    }

    public function addQuizQuestion(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check if user is owner/moderator/creator
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;

            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                return response()->json(['message' => 'You do not have permission to edit this quiz'], 403);
            }

            $validated = $request->validate([
                'question_text' => 'required|string',
                'question_type' => 'required|in:multiple_choice,true_false,short_answer,essay',
                'points' => 'required|integer|min:1',
                'order' => 'required|integer|min:0',
                'correct_answer' => 'nullable|string',
                'options' => 'required_if:question_type,multiple_choice,true_false|array',
                'options.*.option_text' => 'required|string',
                'options.*.is_correct' => 'required|boolean',
                'options.*.order' => 'required|integer|min:0'
            ]);

            $question = QuizQuestion::create([
                'assignment_id' => $assignmentId,
                'question_text' => $validated['question_text'],
                'question_type' => $validated['question_type'],
                'points' => $validated['points'],
                'order' => $validated['order'],
                'correct_answer' => $validated['correct_answer'] ?? null
            ]);

            // Add options for MC/TF questions
            if (isset($validated['options'])) {
                foreach ($validated['options'] as $optionData) {
                    QuizQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'is_correct' => $optionData['is_correct'],
                        'order' => $optionData['order']
                    ]);
                }
            }

            return response()->json([
                'message' => 'Question added successfully',
                'question' => $question->load('options')
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Add quiz question error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to add question'], 500);
        }
    }

    public function updateQuizQuestion(Request $request, $groupId, $assignmentId, $questionId)
    {
        try {
            $user = Auth::user();
            $question = QuizQuestion::with('options')->findOrFail($questionId);
            $assignment = Assignment::findOrFail($assignmentId);

            // Check permissions
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;

            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                return response()->json(['message' => 'You do not have permission to edit this quiz'], 403);
            }

            $validated = $request->validate([
                'question_text' => 'sometimes|required|string',
                'question_type' => 'sometimes|required|in:multiple_choice,true_false,short_answer,essay',
                'points' => 'sometimes|required|integer|min:1',
                'order' => 'sometimes|required|integer|min:0',
                'correct_answer' => 'nullable|string',
                'options' => 'sometimes|array',
                'options.*.id' => 'nullable|exists:quiz_question_options,id',
                'options.*.option_text' => 'required|string',
                'options.*.is_correct' => 'required|boolean',
                'options.*.order' => 'required|integer|min:0'
            ]);

            $question->update($validated);

            // Update options if provided
            if (isset($validated['options'])) {
                // Delete old options not in the new list
                $newOptionIds = collect($validated['options'])->pluck('id')->filter();
                $question->options()->whereNotIn('id', $newOptionIds)->delete();

                foreach ($validated['options'] as $optionData) {
                    if (isset($optionData['id'])) {
                        QuizQuestionOption::where('id', $optionData['id'])->update([
                            'option_text' => $optionData['option_text'],
                            'is_correct' => $optionData['is_correct'],
                            'order' => $optionData['order']
                        ]);
                    } else {
                        QuizQuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => $optionData['option_text'],
                            'is_correct' => $optionData['is_correct'],
                            'order' => $optionData['order']
                        ]);
                    }
                }
            }

            return response()->json([
                'message' => 'Question updated successfully',
                'question' => $question->load('options')
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Update quiz question error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update question'], 500);
        }
    }

    public function deleteQuizQuestion(Request $request, $groupId, $assignmentId, $questionId)
    {
        try {
            $user = Auth::user();
            $question = QuizQuestion::findOrFail($questionId);
            $assignment = Assignment::findOrFail($assignmentId);

            // Check permissions
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership) {
                return response()->json(['message' => 'You are not a member of this group'], 403);
            }

            $userRole = $membership->role;
            $isCreator = $assignment->created_by == $user->id;

            if (!$isCreator && $userRole !== 'owner' && $userRole !== 'moderator') {
                return response()->json(['message' => 'You do not have permission to edit this quiz'], 403);
            }

            $question->delete();

            return response()->json([
                'message' => 'Question deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Delete quiz question error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete question'], 500);
        }
    }

    // ==================== RUBRIC SYSTEM ====================

    public function getRubrics(Request $request, $groupId, $assignmentId)
    {
        try {
            $assignment = Assignment::with('rubrics')->findOrFail($assignmentId);

            return response()->json([
                'rubrics' => $assignment->rubrics,
                'total_points' => $assignment->getRubricTotalPoints()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get rubrics error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch rubrics'], 500);
        }
    }

    public function saveRubrics(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check permissions
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership || !in_array($membership->role, ['owner', 'moderator']) && $assignment->created_by != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'rubrics' => 'required|array',
                'rubrics.*.id' => 'nullable|exists:rubrics,id',
                'rubrics.*.criteria_name' => 'required|string|max:255',
                'rubrics.*.description' => 'nullable|string',
                'rubrics.*.max_points' => 'required|numeric|min:0',
                'rubrics.*.order' => 'required|integer|min:0'
            ]);

            // Delete rubrics not in the new list
            $rubricIds = collect($validated['rubrics'])->pluck('id')->filter();
            $assignment->rubrics()->whereNotIn('id', $rubricIds)->delete();

            // Create or update rubrics
            foreach ($validated['rubrics'] as $rubricData) {
                if (isset($rubricData['id'])) {
                    Rubric::where('id', $rubricData['id'])->update([
                        'criteria_name' => $rubricData['criteria_name'],
                        'description' => $rubricData['description'] ?? null,
                        'max_points' => $rubricData['max_points'],
                        'order' => $rubricData['order']
                    ]);
                } else {
                    Rubric::create([
                        'assignment_id' => $assignmentId,
                        'criteria_name' => $rubricData['criteria_name'],
                        'description' => $rubricData['description'] ?? null,
                        'max_points' => $rubricData['max_points'],
                        'order' => $rubricData['order']
                    ]);
                }
            }

            return response()->json([
                'message' => 'Rubrics saved successfully',
                'rubrics' => $assignment->fresh()->rubrics
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Save rubrics error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to save rubrics'], 500);
        }
    }

    public function gradeWithRubric(Request $request, $groupId, $assignmentId, $studentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::with('rubrics')->findOrFail($assignmentId);
            $submission = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('student_id', $studentId)
                ->firstOrFail();

            // Check permissions
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership || !in_array($membership->role, ['owner', 'moderator']) && $assignment->created_by != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            $validated = $request->validate([
                'rubric_scores' => 'required|array',
                'rubric_scores.*.rubric_id' => 'required|exists:rubrics,id',
                'rubric_scores.*.points_earned' => 'required|numeric|min:0',
                'rubric_scores.*.feedback' => 'nullable|string',
                'overall_feedback' => 'nullable|string'
            ]);

            // Delete old rubric scores for this submission
            $submission->rubricScores()->delete();

            // Save new rubric scores
            $totalPoints = 0;
            foreach ($validated['rubric_scores'] as $scoreData) {
                RubricScore::create([
                    'rubric_id' => $scoreData['rubric_id'],
                    'submission_id' => $submission->id,
                    'points_earned' => $scoreData['points_earned'],
                    'feedback' => $scoreData['feedback'] ?? null
                ]);
                $totalPoints += $scoreData['points_earned'];
            }

            // Update submission grade and status
            $submission->update([
                'grade' => $totalPoints,
                'teacher_feedback' => $validated['overall_feedback'] ?? $submission->teacher_feedback,
                'status' => 'graded',
                'graded_at' => now()
            ]);

            return response()->json([
                'message' => 'Graded successfully with rubric',
                'submission' => $submission->load('rubricScores.rubric')
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Grade with rubric error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to grade submission'], 500);
        }
    }

    // ==================== RESUBMISSION SYSTEM ====================

    public function getAllSubmissionAttempts(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Get all submission attempts for this user
            $attempts = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('student_id', $user->id)
                ->orderBy('attempt_number', 'desc')
                ->get();

            $canResubmit = false;
            if ($assignment->allow_resubmission) {
                $latestAttempt = $attempts->first();
                $attemptCount = $attempts->count();
                $maxAttempts = $assignment->max_resubmissions ?? PHP_INT_MAX;

                // Can resubmit if: latest is graded, not past due (or late allowed), and under max attempts
                $canResubmit = $latestAttempt
                    && $latestAttempt->status === 'graded'
                    && $attemptCount < $maxAttempts
                    && (!$assignment->isPastDuePacific() || $assignment->allow_late_submissions);
            }

            return response()->json([
                'attempts' => $attempts,
                'can_resubmit' => $canResubmit,
                'max_attempts' => $assignment->max_resubmissions,
                'current_attempt_number' => $attempts->count()
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get submission attempts error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch attempts'], 500);
        }
    }

    // ==================== ANALYTICS DASHBOARD ====================

    public function getAssignmentAnalytics(Request $request, $groupId, $assignmentId)
    {
        try {
            $user = Auth::user();
            $assignment = Assignment::findOrFail($assignmentId);

            // Check permissions (only teachers)
            $membership = DB::table('group_members')
                ->where('user_id', $user->id)
                ->where('group_id', $groupId)
                ->first();

            if (!$membership || !in_array($membership->role, ['owner', 'moderator']) && $assignment->created_by != $user->id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // Get all graded submissions
            $submissions = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('status', 'graded')
                ->whereNotNull('grade')
                ->get();

            if ($submissions->isEmpty()) {
                return response()->json([
                    'message' => 'No graded submissions yet',
                    'stats' => null
                ], 200);
            }

            $grades = $submissions->pluck('grade')->toArray();
            sort($grades);

            // Calculate statistics
            $count = count($grades);
            $sum = array_sum($grades);
            $average = $sum / $count;

            // Median
            $middle = floor($count / 2);
            $median = $count % 2 === 0
                ? ($grades[$middle - 1] + $grades[$middle]) / 2
                : $grades[$middle];

            // Standard deviation
            $variance = array_sum(array_map(function ($grade) use ($average) {
                return pow($grade - $average, 2);
            }, $grades)) / $count;
            $stdDev = sqrt($variance);

            // Grade distribution (A, B, C, D, F based on percentage)
            $maxPoints = $assignment->max_points;
            $distribution = [
                'A' => 0, // 90-100%
                'B' => 0, // 80-89%
                'C' => 0, // 70-79%
                'D' => 0, // 60-69%
                'F' => 0  // <60%
            ];

            foreach ($grades as $grade) {
                $percentage = ($grade / $maxPoints) * 100;
                if ($percentage >= 90) $distribution['A']++;
                elseif ($percentage >= 80) $distribution['B']++;
                elseif ($percentage >= 70) $distribution['C']++;
                elseif ($percentage >= 60) $distribution['D']++;
                else $distribution['F']++;
            }

            return response()->json([
                'stats' => [
                    'total_submissions' => $count,
                    'average' => round($average, 2),
                    'median' => round($median, 2),
                    'std_dev' => round($stdDev, 2),
                    'min' => min($grades),
                    'max' => max($grades),
                    'distribution' => $distribution,
                    'max_points' => $maxPoints
                ],
                'submissions' => $submissions->map(function ($sub) {
                    return [
                        'student_name' => $sub->student->name,
                        'grade' => $sub->grade,
                        'is_late' => $sub->is_late,
                        'submitted_at' => $sub->date_submitted
                    ];
                })
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get analytics error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch analytics'], 500);
        }
    }

    // ==================== SUBMISSION COMMENTS/FEEDBACK ====================

    public function addSubmissionComment(Request $request, $groupId, $assignmentId, $studentId)
    {
        try {
            $user = Auth::user();
            $submission = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('student_id', $studentId)
                ->firstOrFail();

            $validated = $request->validate([
                'comment_text' => 'required|string',
                'is_private' => 'nullable|boolean'
            ]);

            $comment = SubmissionComment::create([
                'submission_id' => $submission->id,
                'user_id' => $user->id,
                'comment_text' => $validated['comment_text'],
                'is_private' => $validated['is_private'] ?? false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment->load('user')
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Add comment error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to add comment'], 500);
        }
    }

    public function getSubmissionComments(Request $request, $groupId, $assignmentId, $studentId)
    {
        try {
            $submission = AssignmentSubmission::where('assignment_id', $assignmentId)
                ->where('student_id', $studentId)
                ->firstOrFail();

            $comments = $submission->comments()->with('user')->get();

            return response()->json([
                'comments' => $comments
            ], 200);
        } catch (\Exception $e) {
            Log::error('Get comments error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch comments'], 500);
        }
    }
}
