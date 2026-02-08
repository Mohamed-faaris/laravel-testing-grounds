<?php

use App\Http\Controllers\NotesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->name('dashboard');

    Route::resource('notes', NotesController::class);

    // Additional note routes
    Route::post('notes/{note}/submit-for-review', [NotesController::class, 'submitForReview'])->name('notes.submit-for-review');
    Route::get('notes-admin/pending-reviews', [NotesController::class, 'pendingReviews'])->name('notes.pending-reviews');
    Route::post('notes/{note}/approve', [NotesController::class, 'approve'])->name('notes.approve');
    Route::post('notes/{note}/reject', [NotesController::class, 'reject'])->name('notes.reject');
});

// Public notes (accessible without auth)
Route::get('public-notes', [NotesController::class, 'publicNotes'])->name('notes.public');

require __DIR__.'/settings.php';
