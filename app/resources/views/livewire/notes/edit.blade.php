<div class="w-full max-w-xl">
    <flux:heading>Edit Note</flux:heading>
    <flux:subheading>Update your note</flux:subheading>

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
            <flux:button type="submit" variant="primary">Save Changes</flux:button>
            <flux:button href="{{ route('notes.show', $note->id) }}" wire:navigate>Cancel</flux:button>
        </div>
    </form>
</div>
