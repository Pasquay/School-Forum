<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;

class GroupController extends Controller
{
    public function showGroups(){
        $user = User::findOrFail(Auth::id());

        $groups = Group::orderBy('name', 'asc')
                       ->orderBy('created_at', 'desc')
                       ->get();
        
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

        return view('groups', compact(
            'groups',
            'createdGroups',
            'moderatedGroups',
            'joinedGroups',
        ));
    }

    public function showCreateGroup(){
        return view('create-group');
    }

    public function createGroup(Request $request){
        try {
            $groupData = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:50'],
                'description' => ['required', 'string', 'min:10', 'max:500'],
                // pic
                // banner
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
                // pic
                // banner
                'is_private' => isset($groupData['is_private']) && $groupData['is_private'] === '1',
                'rules' => $groupData['rules'],
                'resources' => $groupData['resources'] ?? [],
                'owner_id' => Auth::id(),
                'member_count' => 1,
            ]);

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
