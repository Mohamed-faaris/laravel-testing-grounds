<?php

use App\Http\Controllers\NoteController;
use App\Livewire\Notes\Create as NotesCreate;
use App\Livewire\Notes\Edit as NotesEdit;
use App\Livewire\Notes\Index as NotesIndex;
use App\Livewire\Notes\Show as NotesShow;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::livewire('notes', NotesIndex::class)->name('notes.index');
    Route::livewire('notes/create', NotesCreate::class)->name('notes.create');
    Route::livewire('notes/{id}', NotesShow::class)->name('notes.show');
    Route::livewire('notes/{id}/edit', NotesEdit::class)->name('notes.edit');
    Route::delete('notes/{id}', [NoteController::class, 'destroy'])->name('notes.destroy');
});

require __DIR__.'/settings.php';
