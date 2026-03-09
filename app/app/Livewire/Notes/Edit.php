<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit Note')]
class Edit extends Component
{
    public Note $note;

    public string $title = '';

    public string $content = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    public function mount(int $id): void
    {
        $this->note = auth()->user()->notes()->findOrFail($id);
        $this->title = $this->note->title;
        $this->content = $this->note->content;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $this->note->update($validated);

        $this->redirectRoute('notes.show', $this->note->id, navigate: true);
    }

    public function render()
    {
        return view('livewire.notes.edit');
    }
}
