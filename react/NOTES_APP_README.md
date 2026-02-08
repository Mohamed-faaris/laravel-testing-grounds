# Laravel Notes App - Tutorial & Documentation

A complete notes application built with Laravel 12, Inertia.js (React), and Tailwind CSS. Features user authentication with role-based access control (User/Admin).

## Features

- **Authentication**: Laravel Fortify-powered authentication (login, register, password reset)
- **Role-based Access**: Two user roles - User and Admin
    - **Users**: Can create, read, update, and delete their own notes
    - **Admins**: Can view all notes from all users and manage them
- **CRUD Operations**: Full Create, Read, Update, Delete functionality for notes
- **Modern UI**: Built with Tailwind CSS v4 and shadcn/ui components
- **Type-Safe Routing**: Laravel Wayfinder for type-safe route imports

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── NoteController.php          # CRUD controller for notes
│   └── Policies/
│       └── NotePolicy.php              # Authorization policies
├── Models/
│   ├── Note.php                        # Note model with relationships
│   └── User.php                        # User model with role

resources/js/
├── components/ui/                      # shadcn/ui components
├── layouts/                            # App layouts
└── pages/
    ├── notes/
    │   ├── index.tsx                   # List all notes
    │   ├── create.tsx                  # Create new note
    │   ├── edit.tsx                    # Edit existing note
    │   └── show.tsx                    # View single note
    └── dashboard.tsx                   # Dashboard page

routes/
└── web.php                             # Application routes
database/
├── migrations/                         # Database migrations
└── factories/                          # Model factories
```

## Installation & Setup

### Prerequisites

- PHP 8.4+
- Node.js 18+
- SQLite (or configure your preferred database)

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 2: Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Configure your database in `.env`:

```env
DB_CONNECTION=sqlite
# DB_DATABASE=/path/to/database.sqlite
```

### Step 3: Database Setup

```bash
# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# (Optional) Seed with sample data
php artisan db:seed
```

### Step 4: Build Assets

```bash
# Build for development
npm run dev

# Or build for production
npm run build
```

### Step 5: Start the Application

```bash
# Using Laravel's built-in server
php artisan serve

# Or using Sail (Docker)
./vendor/bin/sail up
```

The application will be available at `http://localhost:8000`

## Usage

### First Time Setup

1. Register a new account at `/register`
2. By default, all new users are assigned the "user" role
3. To create an admin user, update the role directly in the database:
    ```sql
    UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
    ```

### User Features

- **Dashboard**: Overview page accessible at `/dashboard`
- **My Notes**: View all your personal notes
- **Create Note**: Add new notes with title and content
- **Edit Note**: Modify existing notes
- **Delete Note**: Remove notes permanently

### Admin Features

Admins have all user features plus:

- **View All Notes**: See notes from all users
- **Manage Any Note**: Edit or delete any user's notes
- **User Attribution**: Notes show the owner's name

## Code Guide

### Creating a Note

Notes are created through the `NoteController@store` method:

```php
public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $validated['user_id'] = Auth::id();

    Note::create($validated);

    return redirect()->route('notes.index')->with('success', 'Note created successfully.');
}
```

### Authorization

Authorization is handled through Laravel Policies:

```php
// NotePolicy.php
public function update(User $user, Note $note): bool
{
    return $user->role === 'admin' || $user->id === $note->user_id;
}
```

### Frontend with Inertia

Using Wayfinder for type-safe routes:

```tsx
import { store } from '@/routes/notes';
import { useForm } from '@inertiajs/react';

const { post } = useForm(data);

// Submit to store route
post(store().url);
```

### Database Schema

**notes table:**

- `id`: Primary key
- `user_id`: Foreign key to users table
- `title`: Note title (string)
- `content`: Note content (text)
- `created_at`/`updated_at`: Timestamps

**users table:**

- Includes all Laravel Fortify columns
- `role`: Enum ('user', 'admin') - defaults to 'user'

## Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=NoteTest
```

## Key Commands

```bash
# Generate Wayfinder routes after route changes
php artisan wayfinder:generate --with-form

# Run code formatting
./vendor/bin/pint

# Run type checking
npm run type-check

# Run linter
npm run lint
```

## Technologies Used

- **Backend**: Laravel 12, PHP 8.4
- **Frontend**: React 19, TypeScript, Inertia.js v2
- **Styling**: Tailwind CSS v4, shadcn/ui
- **Authentication**: Laravel Fortify
- **Routing**: Laravel Wayfinder (type-safe)
- **Testing**: Pest PHP 4

## Learn More

- [Laravel Documentation](https://laravel.com/docs/12.x)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)
- [shadcn/ui Documentation](https://ui.shadcn.com/)

## License

This project is open-sourced software licensed under the MIT license.
