<x-layouts::app :title="__('My Notes')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('My Notes') }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    @if(auth()->user()->isAdmin())
                        {{ __('Managing all notes as admin') }}
                    @else
                        {{ __('Manage your personal notes') }}
                    @endif
                </p>
            </div>
            <div class="flex gap-2">
                @if(auth()->user()->isAdmin())
                    <flux:button href="{{ route('notes.pending-reviews') }}" variant="outline" icon="clipboard-document-list">
                        {{ __('Pending Reviews') }}
                    </flux:button>
                @endif
                <flux:button href="{{ route('notes.create') }}" variant="primary" icon="plus">
                    {{ __('New Note') }}
                </flux:button>
            </div>
        </div>

        @if(session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($notes as $note)
                <div class="relative overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <flux:badge variant="solid" :color="$note->getStatusBadgeColor()" size="sm">
                                {{ $note->getStatusBadgeText() }}
                            </flux:badge>
                        </div>
                        <div class="flex gap-1">
                            @if($note->isDraft() && $note->user_id === auth()->id())
                                <form method="POST" action="{{ route('notes.submit-for-review', $note) }}" class="inline">
                                    @csrf
                                    <flux:button type="submit" variant="ghost" size="sm" icon="paper-airplane" title="{{ __('Submit for Review') }}" />
                                </form>
                            @endif
                            <flux:button href="{{ route('notes.edit', $note) }}" variant="ghost" size="sm" icon="pencil-square" />
                            <form method="POST" action="{{ route('notes.destroy', $note) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this note?')" />
                            </form>
                        </div>
                    </div>

                    <a href="{{ route('notes.show', $note) }}" class="block">
                        <h3 class="mb-2 text-lg font-semibold text-zinc-900 hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
                            {{ $note->title }}
                        </h3>
                        <p class="line-clamp-3 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ Str::limit(strip_tags($note->content), 150) }}
                        </p>
                    </a>

                    <div class="mt-4 flex items-center justify-between text-xs text-zinc-500">
                        <span>{{ $note->created_at->diffForHumans() }}</span>
                        @if(auth()->user()->isAdmin())
                            <span class="font-medium">{{ $note->user->name }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">
                    <div class="mb-4 rounded-full bg-zinc-100 p-4 dark:bg-zinc-800">
                        <flux:icon name="document-text" class="size-8 text-zinc-500" />
                    </div>
                    <h3 class="mb-1 text-lg font-medium text-zinc-900 dark:text-white">{{ __('No notes yet') }}</h3>
                    <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Get started by creating your first note.') }}</p>
                    <flux:button href="{{ route('notes.create') }}" variant="primary">{{ __('Create Note') }}</flux:button>
                </div>
            @endforelse
        </div>

        @if($notes->hasPages())
            <div class="mt-4">
                {{ $notes->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
