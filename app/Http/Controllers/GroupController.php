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
                    'title' => "You have been made a moderator for {$group->name}",
                    'body' => "You have been promoted to moderator in the group \"{$group->name}\" by {$user->name}.",
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
                'title' => "You have been made a member for {$group->name}",
                'body' => "You have been demoted to member in the group \"{$group->name}\" by {$user->name}.",
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
                    'title' => $user->name . ' requests to join ' . $group->name,
                    'body' => "{$user->name} has requested to join a group you created/moderated, \"{$group->name}\". Accept or reject this request?"
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
}
