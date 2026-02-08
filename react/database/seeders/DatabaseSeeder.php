<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create regular user
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create regular user
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);

        // Create sample notes for both users
        Note::create([
            'user_id' => $admin->id,
            'title' => 'Welcome to the Notes App',
            'content' => 'This is a sample note created by the admin user. Admins can view and manage all notes.',
        ]);

        Note::create([
            'user_id' => $user->id,
            'title' => 'My First Note',
            'content' => 'This is a sample note created by a regular user. Users can only see their own notes.',
        ]);

        Note::create([
            'user_id' => $user->id,
            'title' => 'Shopping List',
            'content' => "- Milk\n- Eggs\n- Bread\n- Coffee",
        ]);

        Note::create([
            'user_id' => $admin->id,
            'title' => 'Project Ideas',
            'content' => "1. Build a notes app\n2. Add authentication\n3. Implement role-based access\n4. Style with Tailwind CSS",
        ]);
    }
}
