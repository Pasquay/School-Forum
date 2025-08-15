<?php

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Group;
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

        $user = User::findOrFail(Auth::id());

        $groups = Group::query();

        switch($sortBy){
            case 'new':
                $groups->orderBy('created_at', 'desc');
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
                        }])->orderBy('posts_count', 'desc');
                    }
                } else {
                    $groups->withCount('posts')
                           ->orderBy('posts_count', 'desc');
                }
                break;
            case 'members':
            default:
                $groups->orderBy('member_count', 'desc');
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
                $html .= view('components.group-info', ['group' => $group])->render();
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

        $user = User::findOrFail(Auth::id());

        $groups = Group::query();

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
                            }])->orderBy('posts_count', 'desc')
                               ->orderBy('name', 'asc'); 
                    }
                } else {
                    $groups->withCount('posts')
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
        $group = Group::findOrFail($id);

        return view('group', ['group' => $group]);
    }

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
}
