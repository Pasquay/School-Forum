<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultPostSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::where('email', 'admin@usc.edu.ph')->first();

        $home = Group::where('name', 'Home')->first();
        $announcements = Group::where('name', 'Announcements')->first();
        $faculty = Group::where('name', 'Faculty Hub')->first();

        Post::firstOrCreate(
            [
                'title' => 'Welcome to CaroLinks!',
                'group_id' => $home->id,
            ],
            [
                'content' => 'The home page for anything and everything USC related. Feel free to introduce yourself, ask questions, or share your experiences!',
                'user_id' => $owner->id,
            ]
        );

        Post::firstOrCreate(
            [
                'title' => 'First Announcement',
                'group_id' => $announcements->id,
            ],
            [
                'content' => 'Stay tuned for official updates, news, and important information from the university administration.',
                'user_id' => $owner->id,
            ]
        );

        Post::firstOrCreate(
            [
                'title' => 'Faculty Welcome',
                'group_id' => $faculty->id,
            ],
            [
                'content' => 'Welcome faculty members! Use this space to collaborate, share resources, and discuss academic matters privately.',
                'user_id' => $owner->id,
            ]
        );
    }
}
