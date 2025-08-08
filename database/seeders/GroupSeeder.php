<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some users first if they don't exist
        if (User::count() < 5) {
            User::factory()->count(5)->create();
        }

        // Create 10 groups with random owners
        Group::factory()->count(10)->create();

        // Create some specific groups
        Group::factory()->create([
            'name' => 'Laravel Developers',
            'description' => 'A community for Laravel developers',
            'is_private' => false
        ]);

        Group::factory()->private()->create([
            'name' => 'VIP Members',
            'description' => 'Exclusive group for VIP members'
        ]);
    }
}
