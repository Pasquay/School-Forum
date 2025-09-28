<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\InboxMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;

class GroupController extends Controller
{
    public function showGroups(Request $request){
        $sortBy = $request->get('sort', 'members');
        $timeFrame = $request->get('time', 'all');
        $showJoined = $request->get('show_joined', '1');
        $search = $request->get('search', '');

        $user = User::findOrFail(Auth::id());

        $groups = Group::query();

        if($search){
            $groups->where('name', 'like', '%' . $search . '%')
                   ->orWhere('description', 'like', '%' . $search . '%');
        }

        switch($sortBy){
            case 'new':
                $groups->orderBy('created_at', 'desc')
                       ->orderBy('name', 'asc');
                break;
            case 'active':
                if($timeFrame !== 'all'){
                    $date = match($timeFrame){
                        'today' => now()->startOfDay(),
                        'week' => now()->startOfWeek(),
                        'month' => now()->startOfMonth(),
                        'year' => now()->startOfYear(),
                        default => null,
                    };
                    if($date){
                        $groups->withCount(['posts' => function($query) use ($date){
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

        if($showJoined === '0'){
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

        foreach($groups as $group){
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

        if($request->ajax()){
            $html = '';
            foreach($groups as $group){
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

    public function showGroupsPaginated($page, Request $request){
        $sortBy = $request->get('sort', 'members');
        $timeFrame = $request->get('time', 'all');
        $showJoined = $request->get('show_joined', '1');
        $search = $request->get('search', '');

        $user = User::findOrFail(Auth::id());

        $groups = Group::query();

        foreach($groups as $group){
            $group->requested = InboxMessage::hasPendingGroupJoinRequest($user->id, $group->id);
        }

        if($search){
            $groups->where('name', 'like', '%' . $search . '%')
                   ->orWhere('description', 'like', '%' . $search . '%');
        }

        switch($sortBy){
            case 'new':
                $groups->orderBy('created_at', 'desc')
                       ->orderBy('name', 'asc');
                break;
            case 'active':
                if($timeFrame !== 'all'){
                    $date = match($timeFrame){
                        'today' => now()->startOfDay(),
                        'week' => now()->startOfWeek(),
                        'month' => now()->startOfMonth(),
                        'year' => now()->startOfYear(),
                        default => null,
                    };
                    if($date){
                        $groups->withCount(['posts' => function($query) use ($date){
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

        if($showJoined === '0'){
            $groups->whereNotIn('id', $joinedGroupIds);
        }

        // Pagination
        $perPage = 10;
        $currentPage = (int)$page;

        $groups = $groups->paginate($perPage, ['*'], 'page', $currentPage);

        $html = '';
        foreach($groups as $group){
            $html .= view('components.group-info', ['group' => $group])->render();
        }

        // $html .= '<p class="empty">test</p>';
        return response()->json([
            'html' => $html,
            'next_page' => $groups->hasMorePages() ? $groups->currentPage()+1 : NULL
        ]);
    }

    public function showCreateGroup(){
        return view('create-group');
    }

    public function createGroup(Request $request){
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

            if($request->hasFile('photo')){
                $photoPath = $request->file('photo')->storeAs(
                    'groups/photos',
                    'group-' . $group->id . '-photo.' . $request->file('photo')->extension(),
                    'public',
                );
                $group->update(['photo' => $photoPath]);
            }

            if($request->hasFile('banner')){
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
        } catch(\Illuminate\Validation\ValidationException $error){
            return redirect()->back()->withErrors($error->errors())->withInput();
        } catch (\Exception $error){
            return redirect()->back()->with('error', $error->getMessage())->withInput();
        }
    }
 
    public function showGroup($id){
        // Return to home page if $id is home group
            if($id == 1) return app(\App\Http\Controllers\PostController::class)->getLatest(request());

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
            
            $pinned->transform(function($post){
                $post->votes = $post->getVoteCountAttribute();
                $post->userVote = $post->getUserVoteAttribute();
                $post->isPinned = 1;
                return $post;
            });

        // First 15 posts in the group
            $allPosts = Post::where('group_id', $group->id)
                        ->whereDoesntHave('pinnedInGroups', function($query) use ($group){
                            $query->where('group_id', $group->id);
                        })
                        ->latest()
                        ->withCount(['votes', 'comments'])
                        ->get();

            $allPosts->transform(function($post){
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

        return view('group', compact(
            'group',
            'membership',
            'memberList',
            'pinned',
            'posts',
        ));
    }

    // SHOW GROUP PAGINATED

    public function toggleStar($id){
        $user = User::findOrFail(Auth::id());
        $group = $user->groups()->where('group_id', $id)->first();
        
        if(!$group) return back()->with('error', 'You are not a member of that group');

        $starState = $group->pivot->is_starred;
        
        $newStarState = $starState ? 0 : 1; 

        $user->groups()->updateExistingPivot($id, ['is_starred' => $newStarState]);

        return response()->json([
            'success' => true,
            'starValue' => $newStarState,
        ]);
    }

    public function toggleMute($id){
        $user = User::findOrFail(Auth::id());
        $group = $user->groups()->where('group_id', $id)->first();

        if(!$group) return back()->with('error', 'You are not a member of that group');
        
        $muteState = $group->pivot->is_muted;

        $newMuteState = $muteState ? 0 : 1;

        $user->groups()->updateExistingPivot($id, ['is_muted' => $newMuteState]);

        return response()->json([
            'success' => true,
            'muteValue' => $newMuteState,
        ]);
    }

    public function joinGroup($id){
        $user = User::findOrFail(Auth::id());
        $membership = $user->groups()
                           ->where('groups.id', $id)
                           ->exists();
        if(!$membership){
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

            if($joinedGroups->count() > 0){
                foreach($joinedGroups as $joinedGroup){
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

    public function leaveGroup($id){
        $user = User::findOrFail(Auth::id());
        $membership = $user->groups()
                           ->where('groups.id', $id)
                           ->exists();
        if($membership){
            $user->groups()
                 ->detach($id);

            $group = Group::findOrFail($id);
            $group->update(['member_count', $group->getMemberCount()]);

            $joinedGroups = $user->groups()
                                 ->wherePivot('role', 'member')
                                 ->orderBy('is_starred', 'desc')
                                 ->orderBy('name', 'asc')
                                 ->get();

            $joinedGroupsHTML = '
                <div class="section-header">
                    <p>Groups Joined</p>
                </div>';

            if($joinedGroups->count() > 0){
                foreach($joinedGroups as $joinedGroup){
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

    public function showGroupSettings($id, Request $request){
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        if($group->isOwner($user) || $group->isModerator($user)){
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

    public function addModerators($id, Request $request){
        $group = Group::findOrFail($id);
        $user = User::findOrFail(Auth::id());

        if($group->isOwner($user) || $group->isModerator($user)){
            $data = $request->validate([
                'moderators' => 'required|array|min:1',
                'moderators.*' => 'exists:users,id',
            ]);

            foreach($data['moderators'] as $moderatorId){
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

    public function demoteModerator($groupId, $userId){
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());
        
        if($group->isOwner($user) || $group->isModerator($user)){
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

    public function requestToJoinGroup($id){
        $user = User::findOrFail(Auth::id());
        $membership = $user->groups()
                           ->where('groups.id', $id)
                           ->exists();

        $requested = InboxMessage::hasPendingGroupJoinRequest($user->id, $id);
        
        if(!$membership && !$requested){
            $group = Group::findOrFail($id);
            foreach($group->getModeratorAndOwnerIds() as $recipientId){
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
    public function update(Request $request, $id){
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

    public function invite(Request $request, $groupId){
        $group = Group::findOrFail($groupId);
        $user = User::findOrFail(Auth::id());


        if(!$group->isOwner($user) && !$group->isModerator($user)){
            return redirect()->back()->with('error', 'Unauthorized');
        } else {
            $userIds = $request->input('user_ids', []);

            foreach($userIds as $userId){
                if($group->isMember(User::findOrFail($userId))){
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

    public function promote(Request $request, $groupId, $userId){
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

    public function demote(Request $request, $groupId, $userId){
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

    public function removeMember(Request $request, $groupId, $userId){
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

    public function updatePermissions(Request $request, $id){
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

    public function transferOwnership(Request $request, $id){
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

    public function destroy($id){
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
}
