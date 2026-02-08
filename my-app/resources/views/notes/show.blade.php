<x-layouts::app :title="$note->title">
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <flux:button href="{{ route('notes.index') }}" variant="ghost" icon="arrow-left" size="sm">
                {{ __('Back to Notes') }}
            </flux:button>

            @if(auth()->user()->isAdmin() || auth()->id() === $note->user_id)
                <div class="flex gap-2">
                    <flux:button href="{{ route('notes.edit', $note) }}" variant="outline" icon="pencil">
                        {{ __('Edit') }}
                    </flux:button>
                    <form method="POST" action="{{ route('notes.destroy', $note) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <flux:button type="submit" variant="danger" icon="trash" onclick="return confirm('Are you sure you want to delete this note?')">
                            {{ __('Delete') }}
                        </flux:button>
                    </form>
                </div>
            @endif
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center gap-3">
                <flux:badge variant="solid" :color="$note->getStatusBadgeColor()">
                    {{ $note->getStatusBadgeText() }}
                </flux:badge>
                <span class="text-sm text-zinc-500">{{ $note->created_at->format('F j, Y') }}</span>
            </div>

            <h1 class="mb-6 text-3xl font-bold text-zinc-900 dark:text-white">{{ $note->title }}</h1>

            <div class="prose prose-zinc max-w-none dark:prose-invert">
                {!! nl2br(e($note->content)) !!}
            </div>

            <div class="mt-8 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                <div class="flex items-center justify-between text-sm text-zinc-500">
                    <div class="flex items-center gap-2">
                        <flux:avatar :name="$note->user->name" :initials="$note->user->initials()" size="sm" />
                        <span>{{ $note->user->name }}</span>
                    </div>
                    <div class="text-right">
                        <p>{{ __('Last updated:') }} {{ $note->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
