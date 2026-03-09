<div class="w-full max-w-xl">
    <div class="flex items-center justify-between mb-6">
        <flux:heading>{{ $note->title }}</flux:heading>

            <flux:badge>Favorited {{ $note->favorited_at ?? 'Not' }}</flux:badge>

        <div class="flex items-center gap-2">
            <flux:button wire:click="toggleFavorite" variant="ghost">
                @if ($note->isFavorited())
                    <flux:icon.star solid class="w-5 h-5" color="yellow" />
                @else
                    <flux:icon.star class="w-5 h-5" />
                @endif
            </flux:button>
            <flux:button href="{{ route('notes.edit', $note->id) }}" wire:navigate variant="ghost">
                Edit
            </flux:button>
            <flux:button href="{{ route('notes.index') }}" wire:navigate>Back</flux:button>
        </div>
    </div>

    <div class="p-4 border rounded-lg">
        <flux:text>{{ $note->content }}</flux:text>
    </div>

    <flux:separator class="my-6" />

    <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
        @csrf
        @method('DELETE')
        <flux:button type="submit" variant="danger">Delete Note</flux:button>
    </form>
</div>
