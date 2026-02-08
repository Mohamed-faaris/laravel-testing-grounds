<?php

use App\Models\Note;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(['role' => 'user']);
    $this->admin = User::factory()->create(['role' => 'admin']);
});

it('allows authenticated users to view notes index', function () {
    $note = Note::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user)
        ->get(route('notes.index'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('notes/index')
            ->has('notes.data', 1)
        );
});

it('allows admins to view all notes', function () {
    $userNote = Note::factory()->create(['user_id' => $this->user->id]);
    $adminNote = Note::factory()->create(['user_id' => $this->admin->id]);

    $this->actingAs($this->admin)
        ->get(route('notes.index'))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('notes/index')
            ->has('notes.data', 2)
        );
});

it('prevents users from viewing notes index when not authenticated', function () {
    $this->get(route('notes.index'))
        ->assertRedirect(route('login'));
});

it('allows users to create notes', function () {
    $this->actingAs($this->user)
        ->post(route('notes.store'), [
            'title' => 'Test Note',
            'content' => 'This is a test note content.',
        ])
        ->assertRedirect(route('notes.index'));

    $this->assertDatabaseHas('notes', [
        'title' => 'Test Note',
        'content' => 'This is a test note content.',
        'user_id' => $this->user->id,
    ]);
});

it('validates note creation', function () {
    $this->actingAs($this->user)
        ->post(route('notes.store'), [
            'title' => '',
            'content' => '',
        ])
        ->assertSessionHasErrors(['title', 'content']);
});

it('allows users to view their own notes', function () {
    $note = Note::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user)
        ->get(route('notes.show', $note))
        ->assertSuccessful()
        ->assertInertia(fn ($page) => $page
            ->component('notes/show')
            ->has('note')
        );
});

it('prevents users from viewing other users notes', function () {
    $otherUser = User::factory()->create();
    $note = Note::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($this->user)
        ->get(route('notes.show', $note))
        ->assertForbidden();
});

it('allows admins to view any note', function () {
    $note = Note::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->admin)
        ->get(route('notes.show', $note))
        ->assertSuccessful();
});

it('allows users to update their own notes', function () {
    $note = Note::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user)
        ->put(route('notes.update', $note), [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ])
        ->assertRedirect(route('notes.index'));

    $this->assertDatabaseHas('notes', [
        'id' => $note->id,
        'title' => 'Updated Title',
        'content' => 'Updated content.',
    ]);
});

it('prevents users from updating other users notes', function () {
    $otherUser = User::factory()->create();
    $note = Note::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($this->user)
        ->put(route('notes.update', $note), [
            'title' => 'Updated Title',
            'content' => 'Updated content.',
        ])
        ->assertForbidden();
});

it('allows users to delete their own notes', function () {
    $note = Note::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->user)
        ->delete(route('notes.destroy', $note))
        ->assertRedirect(route('notes.index'));

    $this->assertDatabaseMissing('notes', ['id' => $note->id]);
});

it('prevents users from deleting other users notes', function () {
    $otherUser = User::factory()->create();
    $note = Note::factory()->create(['user_id' => $otherUser->id]);

    $this->actingAs($this->user)
        ->delete(route('notes.destroy', $note))
        ->assertForbidden();
});

it('allows admins to delete any note', function () {
    $note = Note::factory()->create(['user_id' => $this->user->id]);

    $this->actingAs($this->admin)
        ->delete(route('notes.destroy', $note))
        ->assertRedirect(route('notes.index'));

    $this->assertDatabaseMissing('notes', ['id' => $note->id]);
});
