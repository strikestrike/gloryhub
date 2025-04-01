<?php

namespace Database\Seeders;

use App\Models\DukeLevel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users with complete field set
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'player',
            'password' => Hash::make('password'),
            'alliance' => 'north',
            'alliance_locked' => false,
            'kingdom_id' => 1
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'super_admin',
            'password' => Hash::make('securepassword'),
            'alliance' => 'west',
            'alliance_locked' => true,
            'kingdom_id' => 2
        ]);

        // Create king user
        User::create([
            'name' => 'King User',
            'email' => 'king@example.com',
            'role' => 'king',
            'password' => Hash::make('royalpassword'),
            'alliance' => 'royal',
            'alliance_locked' => true,
            'kingdom_id' => 3
        ]);

        // Create 10 random users
        // User::factory(10)->create();
        
        
        $levels = [
            [46, 300, 200, 200, 200],
            [47, 600, 300, 300, 300],
            [48, 900, 300, 300, 300],
            [49, 1400, 400, 400, 400],
            [50, 2000, 400, 400, 400],
        ];

        foreach ($levels as $level) {
            DukeLevel::create([
                'level' => $level[0],
                'castle' => $level[1],
                'range' => $level[2],
                'stables' => $level[3],
                'barracks' => $level[4]
            ]);
        }
    }
}
