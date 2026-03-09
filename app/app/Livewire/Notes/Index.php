<?php

namespace App\Livewire\Notes;

use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Notes')]
class Index extends Component
{
    public Collection $notes;

    public function mount(): void
    {
        $this->notes = auth()->user()->notes()
            ->select('id', 'title', 'favorited_at')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.notes.index');
    }
}
