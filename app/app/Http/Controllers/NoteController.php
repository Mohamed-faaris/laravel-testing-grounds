<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $notes = auth()->user()->notes()->select('id', 'title', 'favorited_at')->latest()->get();
        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        auth()->user()->notes()->create($validated);
        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $note = $this->authorizeNote($id);
        return view('notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $note = $this->authorizeNote($id);
        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $note = $this->authorizeNote($id);
        $note->update($validated);
        return redirect()->route('notes.show', $note)->with('success', 'Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $note = $this->authorizeNote($id);
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Note deleted successfully.');
    }

    public function toggleFavorite(string $id)
    {
        $note = $this->authorizeNote($id);
        if ($note->isFavorited()) {
            $note->unfavorite();
            $message = 'Note unfavorited successfully.';
        } else {
            $note->favorite();
            $message = 'Note favorited successfully.';
        }

        return redirect()->route('notes.show', $note)->with('success', $message);
    }

    public function authorizeNote($id)
    {
        $note = auth()->user()->notes()->findOrFail($id);
        return $note;
    }
}
