<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('View Note')]
class Show extends Component
{
    public Note $note;

    public function mount(int $id): void
    {
        $this->note = auth()->user()->notes()->findOrFail($id);
    }

    public function toggleFavorite(): void
    {
        if ($this->note->isFavorited()) {
            $this->note->unfavorite();
        } else {
            $this->note->favorite();
        }
    }

    public function render()
    {
        return view('livewire.notes.show');
    }
}
