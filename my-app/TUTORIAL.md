# Notes App Tutorial

A complete guide to building a Laravel Notes application with user/admin authentication, Tailwind CSS styling, and Flux UI components.

## Table of Contents

1. [Project Setup](#project-setup)
2. [Database Design](#database-design)
3. [Authentication & Roles](#authentication--roles)
4. [Models & Relationships](#models--relationships)
5. [Controllers](#controllers)
6. [Views with Tailwind CSS](#views-with-tailwind-css)
7. [Routes](#routes)
8. [Testing the App](#testing-the-app)

---

## Project Setup

### Step 1: Create a New Laravel Project

```bash
composer create-project laravel/laravel notes-app
cd notes-app
```

### Step 2: Install Laravel Fortify for Authentication

```bash
composer require laravel/fortify
php artisan fortify:install
```

### Step 3: Install Flux UI (Free Edition)

```bash
composer require livewire/flux
```

### Step 4: Install Tailwind CSS

Tailwind CSS comes pre-configured with Laravel 12. Ensure your `resources/css/app.css` includes:

```css
@import "tailwindcss";
```

### Step 5: Run Initial Migrations

```bash
php artisan migrate
```

---

## Database Design

### Step 1: Add Role Column to Users Table

Create a migration to add the role column:

```bash
php artisan make:migration add_role_to_users_table
```

Update the migration:

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['user', 'admin'])->default('user')->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
```

### Step 2: Create Notes Table

```bash
php artisan make:migration create_notes_table
```

Update the migration:

```php
public function up(): void
{
    Schema::create('notes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('content');
        $table->boolean('is_public')->default(false);
        $table->timestamps();
    });
}
```

### Step 3: Run the Migrations

```bash
php artisan migrate
```

---

## Authentication & Roles

### Update the User Model

Add to `app/Models/User.php`:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

protected $fillable = [
    'name',
    'email',
    'role',  // Add this
    'password',
];

/**
 * Check if user is admin
 */
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

/**
 * Get the notes for the user.
 */
public function notes(): HasMany
{
    return $this->hasMany(Note::class);
}
```

---

## Models & Relationships

### Create the Note Model

```bash
php artisan make:model Note
```

Update `app/Models/Note.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_public',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### Create Factories

Create `database/factories/NoteFactory.php`:

```bash
php artisan make:factory NoteFactory
```

```php
public function definition(): array
{
    return [
        'user_id' => User::factory(),
        'title' => fake()->sentence(4),
        'content' => fake()->paragraphs(3, true),
        'is_public' => fake()->boolean(20),
    ];
}
```

---

## Controllers

### Create the NotesController

```bash
php artisan make:controller NotesController
```

Full controller code is in `app/Http/Controllers/NotesController.php`.

Key features:
- **Index**: Admins see all notes, users see only their own
- **Store**: Creates note with current user ID
- **Show**: Public notes viewable by anyone, private notes only by owner or admin
- **Edit/Update/Destroy**: Only owner or admin can modify

---

## Views with Tailwind CSS

### Create View Directory

```bash
mkdir -p resources/views/notes
```

### Index View (`resources/views/notes/index.blade.php`)

Key components:
- Uses Flux UI components (`flux:button`, `flux:badge`, `flux:icon`)
- Tailwind CSS for styling (grid, cards, hover effects)
- Shows public/private badges
- Admin sees note owner name
- Responsive grid layout

### Create View (`resources/views/notes/create.blade.php`)

Key components:
- `flux:input` for title
- `flux:textarea` for content
- `flux:switch` for public/private toggle
- Form validation with `flux:error`

### Edit View (`resources/views/notes/edit.blade.php`)

Similar to create view but pre-filled with note data.

### Show View (`resources/views/notes/show.blade.php`)

Displays full note content with owner information.

### Update Sidebar

Add to `resources/views/layouts/app/sidebar.blade.php`:

```html
<flux:sidebar.item icon="document-text" :href="route('notes.index')" :current="request()->routeIs('notes.*')" wire:navigate>
    {{ __('My Notes') }}
</flux:sidebar.item>
```

---

## Routes

Update `routes/web.php`:

```php
use App\Http\Controllers\NotesController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::resource('notes', NotesController::class);
});
```

---

## Testing the App

### Step 1: Seed the Database

Update `database/seeders/DatabaseSeeder.php`:

```php
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

    // Create notes for both users
    Note::factory(5)->create(['user_id' => $admin->id]);
    Note::factory(5)->create(['user_id' => $user->id]);
}
```

Run the seeder:

```bash
php artisan db:seed
```

### Step 2: Start the Development Server

```bash
composer run dev
```

### Step 3: Test Credentials

**Admin User:**
- Email: admin@example.com
- Password: password

**Regular User:**
- Email: user@example.com
- Password: password

### Step 4: Test Features

1. **Login as admin** - Should see all notes from all users
2. **Login as user** - Should see only own notes
3. **Create note** - Should appear in your list
4. **Edit note** - Only your own or if admin
5. **Delete note** - Only your own or if admin
6. **Public notes** - Visible to others if marked public

---

## Key Features Summary

### User Roles
- **User**: Can CRUD only their own notes
- **Admin**: Can CRUD all notes and see all users' notes

### Note Visibility
- **Private**: Only owner and admins can view
- **Public**: Anyone can view (if they have the link)

### UI Components Used
- Flux UI buttons, badges, forms, icons
- Tailwind CSS grid, flexbox, spacing, colors
- Responsive design with mobile support
- Dark mode support

---

## Next Steps

Potential enhancements:
1. Add rich text editor for note content
2. Add note categories/tags
3. Add note search functionality
4. Add note sharing via email
5. Add note export (PDF, Markdown)
6. Add note templates

---

## File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── NotesController.php
├── Models/
│   ├── Note.php
│   └── User.php
database/
├── factories/
│   ├── NoteFactory.php
│   └── UserFactory.php
├── migrations/
│   ├── 2026_02_08_202418_add_role_to_users_table.php
│   ├── 2026_02_08_202418_create_notes_table.php
│   └── ...
└── seeders/
    └── DatabaseSeeder.php
resources/
└── views/
    ├── layouts/
    │   └── app/
    │       └── sidebar.blade.php
    └── notes/
        ├── index.blade.php
        ├── create.blade.php
        ├── edit.blade.php
        └── show.blade.php
routes/
└── web.php
```

---

Happy coding! 🚀
