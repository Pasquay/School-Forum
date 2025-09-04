<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultUserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@usc.edu.ph'],
            [
                'name' => 'admin',
                'password' => bcrypt('admin'),
                'role' => 'staff',
            ]
        );
    }
}
