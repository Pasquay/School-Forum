<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;

class AssignDefaultGroupsToExistingUsers extends Seeder
{
    public function run(): void
    {
        User::all()->each(function($user) {
            switch($user->role){
                case 'student':
                    $groupIds = [1, 2, 4];
                    break;
                case 'staff':
                    $groupIds = [1, 2, 3];
                    break;
                default:
                    $groupIds = [1, 2];
                    break;
            }
            $defaultGroups = Group::whereIn('id', $groupIds)->get();
            foreach($defaultGroups as $group){
                $group->members()->syncWithoutDetaching([$user->id]);
            }
        });
    }
}