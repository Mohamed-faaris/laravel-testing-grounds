<div class="w-full">
    <div class="flex items-center justify-between mb-6">
        <flux:heading>Notes</flux:heading>
        <flux:button href="{{ route('notes.create') }}" wire:navigate variant="primary">
            New Note
        </flux:button>
    </div>

    @if ($notes->isEmpty())
        <flux:text>No notes yet. Create your first note!</flux:text>
    @else
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($notes as $note)
                <a href="{{ route('notes.show', $note->id) }}" wire:navigate
                   class="block p-4 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="flex items-start justify-between">
                        <flux:heading level="3">{{ $note->title }}</flux:heading>
                        @if ($note->favorited_at)
                            <flux:icon.star solid class="w-5 h-5 text-yellow-500" />
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
