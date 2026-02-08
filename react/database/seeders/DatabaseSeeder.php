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

        // Create regular users
        $user1 = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $user3 = User::factory()->create([
            'name' => 'Bob Johnson',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Create sample published notes (approved by admin)
        Note::create([
            'user_id' => $admin->id,
            'title' => 'Welcome to the Notes App',
            'content' => 'This is a sample note created by the admin user. Admins can view and manage all notes. This note has been published and is visible to everyone.',
            'status' => Note::STATUS_PUBLISHED,
            'published_at' => now()->subDays(7),
            'reviewed_by' => $admin->id,
            'review_notes' => 'Approved as a welcome note for the community',
        ]);

        Note::create([
            'user_id' => $user1->id,
            'title' => 'My First Note',
            'content' => 'This is a sample note created by a regular user. Users can only see their own notes. This note is currently private.',
            'status' => Note::STATUS_PRIVATE,
        ]);

        Note::create([
            'user_id' => $user1->id,
            'title' => 'Shopping List',
            'content' => "- Organic milk\n- Free-range eggs\n- Whole grain bread\n- Fair trade coffee\n- Fresh vegetables\n- Dark chocolate",
            'status' => Note::STATUS_PUBLISHED,
            'published_at' => now()->subDays(2),
            'reviewed_by' => $admin->id,
            'review_notes' => 'Nice practical note! Approved for community sharing.',
        ]);

        Note::create([
            'user_id' => $admin->id,
            'title' => 'Project Ideas',
            'content' => "Here are some project ideas for full-stack development:\n\n1. Build a notes app with authentication\n2. Add role-based access control\n3. Implement content moderation\n4. Style with Tailwind CSS\n5. Add real-time features\n6. Implement search functionality\n7. Add categories and tags\n8. Create a mobile app version",
            'status' => Note::STATUS_PUBLISHED,
            'published_at' => now()->subDays(1),
            'reviewed_by' => $admin->id,
            'review_notes' => 'Great roadmap for development! Very helpful for the community.',
        ]);

        // Create pending notes (awaiting admin review)
        Note::create([
            'user_id' => $user2->id,
            'title' => 'My Thoughts on Productivity',
            'content' => 'Productivity is not about doing more things. It\'s about doing the right things at the right time. Focus on what matters most. Eliminate distractions and prioritize your most important tasks.',
            'status' => Note::STATUS_PENDING,
        ]);

        Note::create([
            'user_id' => $user3->id,
            'title' => 'Favorite Coding Resources',
            'content' => 'Here are some of my favorite learning resources:\n\n- Laravel Documentation\n- React Documentation\n- MDN Web Docs\n- Stack Overflow\n- Laracasts\n- CSS-Tricks\n- GitHub Issues',
            'status' => Note::STATUS_PENDING,
        ]);

        // Create rejected notes (with admin feedback)
        Note::create([
            'user_id' => $user1->id,
            'title' => 'Rejected Note',
            'content' => 'This note was rejected for quality reasons.',
            'status' => Note::STATUS_REJECTED,
            'reviewed_by' => $admin->id,
            'review_notes' => 'Please add more substance and detail to make this note more valuable for the community. Consider including specific examples or actionable advice.',
        ]);

        Note::create([
            'user_id' => $user2->id,
            'title' => 'Quick Tip',
            'content' => 'Use Ctrl+K in VS Code for quick actions.',
            'status' => Note::STATUS_REJECTED,
            'reviewed_by' => $admin->id,
            'review_notes' => 'This tip is too brief and not detailed enough. Consider expanding it with more context, examples, or related tips to make it more valuable.',
        ]);

        // Create more private notes for users
        Note::create([
            'user_id' => $user1->id,
            'title' => 'Personal Goals',
            'content' => 'My goals for this month:\n- Learn TypeScript\n- Build a side project\n- Read 2 technical books\n- Exercise 3x per week',
            'status' => Note::STATUS_PRIVATE,
        ]);

        Note::create([
            'user_id' => $user3->id,
            'title' => 'Meeting Notes',
            'content' => 'Team meeting notes:\n- Discussed Q1 goals\n- Assigned new tasks\n- Next meeting: Friday 2 PM\n- Action items: Code review, testing, documentation',
            'status' => Note::STATUS_PRIVATE,
        ]);

        // Create a note that's been resubmitted after rejection
        Note::create([
            'user_id' => $user2->id,
            'title' => 'Essential VS Code Shortcuts (Revised)',
            'content' => 'Here are some essential VS Code shortcuts that every developer should know:\n\n**Navigation:**\n- Ctrl+P: Quick file search\n- Ctrl+Shift+P: Command palette\n- Ctrl+G: Go to line\n- Ctrl+Shift+O: Go to symbol\n\n**Editing:**\n- Ctrl+D: Select next occurrence\n- Alt+Shift+Up/Down: Copy line\n- Ctrl+K Ctrl+C: Comment line\n- Ctrl+K Ctrl+U: Uncomment line\n\n**Debugging:**\n- F5: Start debugging\n- F9: Toggle breakpoint\n- F10: Step over\n- F11: Step into',
            'status' => Note::STATUS_PENDING,
        ]);
    }
}
