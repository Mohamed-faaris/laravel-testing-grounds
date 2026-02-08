<x-layouts::app :title="__('Create Note')">
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <flux:button href="{{ route('notes.index') }}" variant="ghost" icon="arrow-left" size="sm">
                {{ __('Back to Notes') }}
            </flux:button>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Create New Note') }}</h1>

            <form method="POST" action="{{ route('notes.store') }}" class="space-y-6">
                @csrf

                <div>
                    <flux:input
                        label="{{ __('Title') }}"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="{{ __('Enter note title') }}"
                        required
                    />
                    @error('title')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                <div>
                    <flux:textarea
                        label="{{ __('Content') }}"
                        name="content"
                        rows="10"
                        placeholder="{{ __('Write your note here...') }}"
                        required
                    >{{ old('content') }}</flux:textarea>
                    @error('content')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-950">
                    <div class="flex">
                        <flux:icon name="information-circle" class="size-5 text-blue-400" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">{{ __('Publication Process') }}</h3>
                            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                <p>{{ __('Notes are created as drafts. To make them public, submit them for admin review after creation.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <flux:button type="submit" variant="primary">
                        {{ __('Create Note') }}
                    </flux:button>
                    <flux:button href="{{ route('notes.index') }}" variant="ghost">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
