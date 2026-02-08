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

        // Create notes for admin with different statuses
        Note::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Published Admin Note',
            'status' => 'published',
        ]);
        Note::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Draft Admin Note',
            'status' => 'draft',
        ]);

        // Create notes for user with different statuses
        Note::factory()->create([
            'user_id' => $user->id,
            'title' => 'User Draft Note',
            'status' => 'draft',
        ]);
        Note::factory()->create([
            'user_id' => $user->id,
            'title' => 'User Pending Review Note',
            'status' => 'pending_review',
        ]);
        Note::factory()->create([
            'user_id' => $user->id,
            'title' => 'User Published Note',
            'status' => 'published',
        ]);
        Note::factory()->create([
            'user_id' => $user->id,
            'title' => 'User Rejected Note',
            'status' => 'rejected',
        ]);

        // Create additional random users with notes
        User::factory(3)
            ->has(Note::factory(2))
            ->create();
    }
}
