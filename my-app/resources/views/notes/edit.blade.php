<x-layouts::app :title="__('Edit Note')">
    <div class="mx-auto max-w-2xl">
        <div class="mb-6">
            <flux:button href="{{ route('notes.index') }}" variant="ghost" icon="arrow-left" size="sm">
                {{ __('Back to Notes') }}
            </flux:button>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <h1 class="mb-6 text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Edit Note') }}</h1>

            <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <flux:input
                        label="{{ __('Title') }}"
                        name="title"
                        value="{{ old('title', $note->title) }}"
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
                    >{{ old('content', $note->content) }}</flux:textarea>
                    @error('content')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </div>

                <div>
                    <flux:switch
                        name="is_public"
                        label="{{ __('Make this note public') }}"
                        description="{{ __('Public notes can be viewed by anyone') }}"
                        :checked="$note->is_public"
                    />
                </div>

                <div class="flex items-center gap-4 pt-4">
                    <flux:button type="submit" variant="primary">
                        {{ __('Update Note') }}
                    </flux:button>
                    <flux:button href="{{ route('notes.index') }}" variant="ghost">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
