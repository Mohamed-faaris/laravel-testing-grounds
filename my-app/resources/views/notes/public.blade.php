<x-layouts::app :title="__('Public Notes')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Public Notes') }}</h1>
                <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Published notes from all users') }}</p>
            </div>
            @auth
                <div class="flex gap-2">
                    <flux:button href="{{ route('notes.index') }}" variant="outline" icon="document-text">
                        {{ __('My Notes') }}
                    </flux:button>
                    <flux:button href="{{ route('notes.create') }}" variant="primary" icon="plus">
                        {{ __('Create Note') }}
                    </flux:button>
                </div>
            @else
                <div class="flex gap-2">
                    <flux:button href="{{ route('login') }}" variant="outline">
                        {{ __('Login') }}
                    </flux:button>
                    <flux:button href="{{ route('register') }}" variant="primary">
                        {{ __('Register') }}
                    </flux:button>
                </div>
            @endauth
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($publicNotes as $note)
                <div class="relative overflow-hidden rounded-xl border border-zinc-200 bg-white p-6 shadow-sm transition-all hover:shadow-md dark:border-zinc-700 dark:bg-zinc-900">
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex items-center gap-2">
                            <flux:badge variant="solid" color="emerald" size="sm">
                                {{ __('Published') }}
                            </flux:badge>
                        </div>
                        @auth
                            @if(auth()->user()->isAdmin() || auth()->id() === $note->user_id)
                                <div class="flex gap-1">
                                    <flux:button href="{{ route('notes.edit', $note) }}" variant="ghost" size="sm" icon="pencil-square" />
                                    <form method="POST" action="{{ route('notes.destroy', $note) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-600 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this note?')" />
                                    </form>
                                </div>
                            @endif
                        @endauth
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
                        <flux:icon name="document-text" class="size-8 text-zinc-500" />
                    </div>
                    <h3 class="mb-1 text-lg font-medium text-zinc-900 dark:text-white">{{ __('No published notes yet') }}</h3>
                    <p class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Be the first to publish a note!') }}</p>
                    @auth
                        <flux:button href="{{ route('notes.create') }}" variant="primary">{{ __('Create Note') }}</flux:button>
                    @else
                        <flux:button href="{{ route('login') }}" variant="primary">{{ __('Login to Create') }}</flux:button>
                    @endauth
                </div>
            @endforelse
        </div>

        @if($publicNotes->hasPages())
            <div class="mt-4">
                {{ $publicNotes->links() }}
            </div>
        @endif
    </div>
</x-layouts::app>
