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
}
