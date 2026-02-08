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

        if ($user->role === 'admin') {
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

        Note::create($validated);

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function show(Note $note): Response
    {
        $this->authorize('view', $note);

        return Inertia::render('notes/show', [
            'note' => $note->load('user'),
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
}
