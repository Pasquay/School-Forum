<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultGroupSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'admin@usc.edu.ph')->first();

        Group::firstOrCreate(
            ['name' => 'Home'],
            [
                'description' => 'The home page of Carolinians.',
                'owner_id' => $owner->id,
                'type' => 'social',
                'is_private' => false,
                'member_count' => 1,
            ]
        );

        Group::firstOrCreate(
            ['name' => 'Announcements'],
            [
                'description' => 'Official updates and important information for all member.',
                'owner_id' => $owner->id,
                'type' => 'academic',
                'is_private' => false,
                'member_count' => 1,
            ]
        );

        Group::firstOrCreate(
            ['name' => 'Faculty Hub'],
            [
                'description' => 'A private space for faculty to collaborate, share resources, and discuss academic matters.',
                'owner_id' => $owner->id,
                'type' => 'academic',
                'is_private' => true,
                'member_count' => 1,
            ]
        );

    }
}
