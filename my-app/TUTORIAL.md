# Notes App Tutorial - Publication Workflow

A complete guide to building a Laravel Notes application with user/admin authentication, publication workflow, and Tailwind CSS styling.

## Table of Contents

1. [Project Setup](#project-setup)
2. [Database Design](#database-design)
3. [Publication Workflow](#publication-workflow)
4. [Authentication & Roles](#authentication--roles)
5. [Models & Relationships](#models--relationships)
6. [Controllers](#controllers)
7. [Views with Tailwind CSS](#views-with-tailwind-css)
8. [Routes](#routes)
9. [Testing the App](#testing-the-app)

---

## Publication Workflow

### Overview

The notes app now includes a publication approval system where:

- **Users** create notes as drafts and can submit them for review
- **Admins** review submitted notes and approve/reject them
- Only **approved (published) notes** are visible to the public

### Note Statuses

- **`draft`**: Private note, only visible to the author and admins
- **`pending_review`**: Submitted for admin approval
- **`published`**: Approved and publicly visible
- **`rejected`**: Rejected by admin, not public

### Step 1: Add Status Column to Notes Table

Create a migration to add the status column:

```bash
php artisan make:migration add_status_to_notes_table
```

Update the migration:

```php
public function up(): void
{
    Schema::table('notes', function (Blueprint $table) {
        $table->enum('status', ['draft', 'pending_review', 'published', 'rejected'])->default('draft')->after('content');
    });

    // Migrate existing is_public data to status
    DB::statement("UPDATE notes SET status = 'published' WHERE is_public = 1");
    DB::statement("UPDATE notes SET status = 'draft' WHERE is_public = 0");

    Schema::table('notes', function (Blueprint $table) {
        $table->dropColumn('is_public');
    });
}
```

### Step 2: Update Note Model

Add status methods to `app/Models/Note.php`:

```php
protected $fillable = [
    'user_id',
    'title',
    'content',
    'status',
];

protected $casts = [
    'status' => 'string',
];

// Status checkers
public function isPublished(): bool { return $this->status === 'published'; }
public function isPendingReview(): bool { return $this->status === 'pending_review'; }
public function isDraft(): bool { return $this->status === 'draft'; }
public function isRejected(): bool { return $this->status === 'rejected'; }

// Workflow methods
public function submitForReview(): bool {
    if ($this->isDraft()) {
        return $this->update(['status' => 'pending_review']);
    }
    return false;
}

public function approve(): bool {
    if ($this->isPendingReview()) {
        return $this->update(['status' => 'published']);
    }
    return false;
}

public function reject(): bool {
    if ($this->isPendingReview()) {
        return $this->update(['status' => 'rejected']);
    }
    return false;
}

// UI helpers
public function getStatusBadgeColor(): string {
    return match ($this->status) {
        'draft' => 'zinc',
        'pending_review' => 'amber',
        'published' => 'emerald',
        'rejected' => 'red',
        default => 'zinc',
    };
}

public function getStatusBadgeText(): string {
    return match ($this->status) {
        'draft' => 'Draft',
        'pending_review' => 'Pending Review',
        'published' => 'Published',
        'rejected' => 'Rejected',
        default => 'Unknown',
    };
}
```

### Step 3: Update Controller

Add workflow methods to `NotesController`:

```php
// Submit note for review
public function submitForReview(Note $note): RedirectResponse
{
    if ($note->user_id !== Auth::id()) {
        abort(403);
    }

    if ($note->submitForReview()) {
        return redirect()->route('notes.index')->with('success', 'Note submitted for review.');
    }

    return redirect()->route('notes.index')->with('error', 'Could not submit note for review.');
}

// Admin: View pending reviews
public function pendingReviews(): View
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    $pendingNotes = Note::with('user')
        ->where('status', 'pending_review')
        ->latest()
        ->paginate(10);

    return view('notes.pending-reviews', compact('pendingNotes'));
}

// Admin: Approve note
public function approve(Note $note): RedirectResponse
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    if ($note->approve()) {
        return redirect()->route('notes.pending-reviews')->with('success', 'Note approved and published.');
    }

    return redirect()->route('notes.pending-reviews')->with('error', 'Could not approve note.');
}

// Admin: Reject note
public function reject(Note $note): RedirectResponse
{
    if (!Auth::user()->isAdmin()) {
        abort(403);
    }

    if ($note->reject()) {
        return redirect()->route('notes.pending-reviews')->with('success', 'Note rejected.');
    }

    return redirect()->route('notes.pending-reviews')->with('error', 'Could not reject note.');
}
```

### Step 4: Update Routes

Add workflow routes in `routes/web.php`:

```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('notes', NotesController::class);

    // Publication workflow routes
    Route::post('notes/{note}/submit-for-review', [NotesController::class, 'submitForReview'])->name('notes.submit-for-review');
    Route::get('notes-admin/pending-reviews', [NotesController::class, 'pendingReviews'])->name('notes.pending-reviews');
    Route::post('notes/{note}/approve', [NotesController::class, 'approve'])->name('notes.approve');
    Route::post('notes/{note}/reject', [NotesController::class, 'reject'])->name('notes.reject');
});
```

---

## Testing the Publication Workflow

### Test Accounts

**Admin User:**
- Email: `admin@example.com`
- Password: `password`

**Regular User:**
- Email: `user@example.com`
- Password: `password`

### Test Scenarios

1. **As a regular user:**
   - Create a note (starts as draft)
   - Submit for review (becomes pending_review)
   - Try to view a published note (should work)
   - Try to view another user's draft (should fail)

2. **As an admin:**
   - View all notes in "My Notes"
   - Click "Pending Reviews" in sidebar
   - Approve or reject pending notes
   - See approved notes become published

### Sample Data

The seeder creates notes with different statuses for testing:

- Admin: 1 published, 1 draft
- User: 1 draft, 1 pending_review, 1 published, 1 rejected

---

## File Structure

```
app/
├── Http/Controllers/
│   └── NotesController.php (updated with workflow methods)
├── Models/
│   ├── Note.php (updated with status methods)
│   └── User.php
database/
├── migrations/
│   ├── 2026_02_08_204132_add_status_to_notes_table.php
│   └── ...
├── factories/
│   ├── NoteFactory.php (updated for status)
│   └── UserFactory.php
resources/
├── views/
│   └── notes/
│       ├── index.blade.php (updated with status badges)
│       ├── create.blade.php (removed public toggle)
│       ├── edit.blade.php (shows status, submit button)
│       ├── show.blade.php (updated status badge)
│       └── pending-reviews.blade.php (NEW - admin review page)
routes/
└── web.php (added workflow routes)
```

---

## Key Features Summary

### User Roles & Permissions
- **Users**: CRUD own notes, submit drafts for review
- **Admins**: All user permissions + approve/reject reviews + view all notes

### Publication States
- **Draft**: Private, editable
- **Pending Review**: Submitted, awaiting approval
- **Published**: Publicly visible
- **Rejected**: Not published, can be resubmitted

### UI Components
- Status badges with color coding
- Submit for review buttons on drafts
- Admin approval/rejection interface
- Sidebar navigation for pending reviews

---

Happy coding! 🚀 The publication workflow adds a professional layer of content moderation to your notes app.
