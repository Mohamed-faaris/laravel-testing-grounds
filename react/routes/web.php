<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public notes - accessible to everyone
Route::get('public-notes', [NoteController::class, 'publicNotes'])->name('notes.public');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('notes', NoteController::class);

    // Publication routes
    Route::post('notes/{note}/submit', [NoteController::class, 'submitForReview'])->name('notes.submit');
});

// Admin routes
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('pending-notes', [NoteController::class, 'pendingReview'])->name('notes.pending');
    Route::post('notes/{note}/approve', [NoteController::class, 'approve'])->name('notes.approve');
    Route::post('notes/{note}/reject', [NoteController::class, 'reject'])->name('notes.reject');
});

require __DIR__.'/settings.php';
