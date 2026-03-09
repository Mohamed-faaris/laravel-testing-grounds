<?php

namespace App\Livewire\Notes;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Note')]
class Create extends Component
{
    public string $title = '';

    public string $content = '';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        auth()->user()->notes()->create($validated);

        $this->redirectRoute('notes.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.notes.create');
    }
}
