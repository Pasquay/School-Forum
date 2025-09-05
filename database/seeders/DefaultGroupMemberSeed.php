<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultGroupMemberSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'admin@usc.edu.ph')->first();
        $groupIds = [1,2,3,4];
        $defaultGroups = Group::whereIn('id', $groupIds)->get();
        foreach($defaultGroups as $group){
            if($group->owner_id !== $owner->id){
                $group->owner_id = $owner->id;
                $group->save();
            }
            $group->members()->syncWithoutDetaching([
                $owner->id = ['role' => 'owner']
            ]);
        }
    }
}
