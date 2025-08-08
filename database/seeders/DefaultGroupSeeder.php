<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class DefaultGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default "General" group if it doesn't exist
        if (!Group::where('id', 1)->exists()) {
            Group::create([
                'id' => 1,
                'name' => 'Home',
                'description' => 'Social Media\'s central hub',
                'owner_id' => 9,
                'member_count' => 1,
                'is_private' => false,
            ]);
        }
    }
}
