<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        // Create regular user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        // Create notes for admin
        Note::factory(5)->create([
            'user_id' => $admin->id,
        ]);

        // Create notes for user
        Note::factory(5)->create([
            'user_id' => $user->id,
        ]);

        // Create additional random users with notes
        User::factory(3)
            ->has(Note::factory(3))
            ->create();
    }
}
