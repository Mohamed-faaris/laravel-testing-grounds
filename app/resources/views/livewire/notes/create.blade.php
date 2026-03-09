<div class="w-full max-w-xl">
    <flux:heading>Create Note</flux:heading>
    <flux:subheading>Create a new note</flux:subheading>

    <form wire:submit="save" class="mt-6 space-y-6">
        <flux:input
            wire:model="title"
            label="Title"
            placeholder="Note title"
        />

        <flux:textarea
            wire:model="content"
            label="Content"
            placeholder="Note content"
            rows="5"
        />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:button href="{{ route('notes.index') }}" wire:navigate>Cancel</flux:button>
        </div>
    </form>
</div>
