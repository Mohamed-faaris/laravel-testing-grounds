<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotesController extends Controller
{
    /**
     * Display a listing of the user's notes.
     */
    public function index(): View
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $notes = Note::with('user')->latest()->paginate(10);
        } else {
            $notes = $user->notes()->latest()->paginate(10);
        }

        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new note.
     */
    public function create(): View
    {
        return view('notes.create');
    }

    /**
     * Store a newly created note in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'draft'; // All new notes start as drafts

        Note::create($validated);

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified note.
     */
    public function show(Note $note): View
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $note->user_id !== $user->id && ! $note->isPublished()) {
            abort(403);
        }

        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note.
     */
    public function edit(Note $note): View
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $note->user_id !== $user->id) {
            abort(403);
        }

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified note in storage.
     */
    public function update(Request $request, Note $note): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isAdmin() && $note->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
    }

    /**
     * Submit a note for publication review.
     */
    public function submitForReview(Note $note): RedirectResponse
    {
        $user = Auth::user();

        if ($note->user_id !== $user->id) {
            abort(403);
        }

        if ($note->submitForReview()) {
            return redirect()->route('notes.index')->with('success', 'Note submitted for review.');
        }

        return redirect()->route('notes.index')->with('error', 'Could not submit note for review.');
    }

    /**
     * Display pending reviews for admin approval.
     */
    public function pendingReviews(): View
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $pendingNotes = Note::with('user')
            ->where('status', 'pending_review')
            ->latest()
            ->paginate(10);

        return view('notes.pending-reviews', compact('pendingNotes'));
    }

    /**
     * Approve a note for publication.
     */
    public function approve(Note $note): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        if ($note->approve()) {
            return redirect()->route('notes.pending-reviews')->with('success', 'Note approved and published.');
        }

        return redirect()->route('notes.pending-reviews')->with('error', 'Could not approve note.');
    }

    /**
     * Reject a note.
     */
    public function reject(Note $note): RedirectResponse
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        if ($note->reject()) {
            return redirect()->route('notes.pending-reviews')->with('success', 'Note rejected.');
        }

        return redirect()->route('notes.pending-reviews')->with('error', 'Could not reject note.');
    }
}
