<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;

class GroupController extends Controller
{
    public function showGroups(){
        $user = User::findOrFail(Auth::id());
        
        $createdGroups = $user->groups()
                              ->wherePivot('role', 'owner')
                              ->latest()
                              ->get();

        $moderatedGroups = $user->groups()
                                ->wherePivot('role', 'moderator')
                                ->latest()
                                ->get();

        $joinedGroups = $user->groups()
                             ->wherePivot('role', 'member')
                             ->latest()
                             ->get();

        return view('groups', compact(
            'createdGroups',
            'moderatedGroups',
            'joinedGroups',
        ));
    }

    public function showCreateGroup(){
        return view('create-group');
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
