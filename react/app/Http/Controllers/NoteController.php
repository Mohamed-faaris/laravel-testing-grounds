<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class NoteController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $notes = Note::with('user')->latest()->paginate(10);
        } else {
            $notes = Note::where('user_id', $user->id)->latest()->paginate(10);
        }

        return Inertia::render('notes/index', [
            'notes' => $notes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('notes/create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = Note::STATUS_PRIVATE;

        Note::create($validated);

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function show(Note $note): Response
    {
        $this->authorize('view', $note);

        return Inertia::render('notes/show', [
            'note' => $note->load(['user', 'reviewer']),
        ]);
    }

    public function edit(Note $note): Response
    {
        $this->authorize('update', $note);

        return Inertia::render('notes/edit', [
            'note' => $note,
        ]);
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $this->authorize('update', $note);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy(Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully.');
    }

    /**
     * Submit note for publication review
     */
    public function submitForReview(Note $note): RedirectResponse
    {
        $this->authorize('update', $note);

        if (! $note->canBePublished()) {
            return redirect()->back()->with('error', 'This note cannot be submitted for review.');
        }

        $note->update([
            'status' => Note::STATUS_PENDING,
        ]);

        return redirect()->route('notes.index')->with('success', 'Note submitted for review. An admin will review it shortly.');
    }

    /**
     * Admin: Show pending notes for review
     */
    public function pendingReview(): Response
    {
        $this->authorize('admin', Note::class);

        $notes = Note::with('user')
            ->pending()
            ->latest()
            ->paginate(10);

        return Inertia::render('notes/pending', [
            'notes' => $notes,
        ]);
    }

    /**
     * Admin: Approve and publish a note
     */
    public function approve(Note $note, Request $request): RedirectResponse
    {
        $this->authorize('admin', Note::class);

        $validated = $request->validate([
            'review_notes' => 'nullable|string|max:1000',
        ]);

        $note->update([
            'status' => Note::STATUS_PUBLISHED,
            'published_at' => now(),
            'reviewed_by' => Auth::id(),
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        return redirect()->route('notes.pending')->with('success', 'Note published successfully!');
    }

    /**
     * Admin: Reject a note
     */
    public function reject(Note $note, Request $request): RedirectResponse
    {
        $this->authorize('admin', Note::class);

        $validated = $request->validate([
            'review_notes' => 'required|string|max:1000',
        ]);

        $note->update([
            'status' => Note::STATUS_REJECTED,
            'reviewed_by' => Auth::id(),
            'review_notes' => $validated['review_notes'],
        ]);

        return redirect()->route('notes.pending')->with('success', 'Note rejected with feedback.');
    }

    /**
     * Show public published notes (accessible to guests)
     */
    public function publicNotes(): Response
    {
        $notes = Note::with('user')
            ->published()
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('notes/public', [
            'notes' => $notes,
        ]);
    }
}
