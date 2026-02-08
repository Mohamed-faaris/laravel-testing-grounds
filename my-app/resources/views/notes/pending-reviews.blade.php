<x-layouts::app :title="__('Pending Reviews')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Pending Reviews') }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Review and approve notes for publication') }}</p>
            </div>
            <flux:button href="{{ route('notes.index') }}" variant="ghost" icon="arrow-left">
                {{ __('Back to Notes') }}
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if(session('error'))
            <flux:callout variant="danger" icon="exclamation-triangle">
                {{ session('error') }}
            </flux:callout>
        @endif

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($pendingNotes as $note)
                <div class="relative overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <flux:badge variant="solid" :color="$note->getStatusBadgeColor()" size="sm">
                                {{ $note->getStatusBadgeText() }}
                            </flux:badge>
                        </div>
                        <div class="flex gap-1">
                            <form method="POST" action="{{ route('notes.approve', $note) }}" class="inline">
                                @csrf
                                <flux:button type="submit" variant="ghost" size="sm" icon="check" class="text-green-600 hover:text-green-700" title="{{ __('Approve') }}" />
                            </form>
                            <form method="POST" action="{{ route('notes.reject', $note) }}" class="inline">
                                @csrf
                                <flux:button type="submit" variant="ghost" size="sm" icon="x-mark" class="text-red-600 hover:text-red-700" title="{{ __('Reject') }}" />
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
                        <span class="font-medium">{{ $note->user->name }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center rounded-xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">
                    <div class="mb-4 rounded-full bg-zinc-100 p-4 dark:bg-zinc-800">
                        <flux:icon name="clipboard-document-list" class="size-8 text-zinc-500" />
                    </div>
                    <h3 class="mb-1 text-lg font-medium text-zinc-900 dark:text-white">{{ __('No pending reviews') }}</h3>
                    <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">{{ __('All notes have been reviewed.') }}</p>
                    <flux:button href="{{ route('notes.index') }}" variant="primary">{{ __('Back to Notes') }}</flux:button>
                </div>
            @endforelse
        </div>

        @if($pendingNotes->hasPages())
            <div class="mt-4">
                {{ $pendingNotes->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
