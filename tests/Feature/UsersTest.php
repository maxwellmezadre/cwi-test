<?php

use Laravel\Passport\Client;
use Laravel\Passport\Passport;

use function Pest\Laravel\{
    postJson,
    getJson,
    putJson,
    deleteJson
};

beforeEach(function () {
    $client = Client::factory()->create();

    Passport::actingAsClient($client, ['users.read','users.write']);
});

it('creates, lists, shows, updates and deletes a user', function () {
    $created = postJson('/api/users', [
        'name' => 'Max', 'email' => 'max@test.com', 'password' => 'secret123'
    ])->assertCreated()->json('data');

    getJson('/api/users')->assertOk()->assertJsonCount(1, 'data');

    getJson("/api/users/{$created['id']}")
        ->assertOk()
        ->assertJsonPath('data.email', 'max@test.com');

    putJson("/api/users/{$created['id']}", ['name' => 'Max Updated'])
        ->assertOk()
        ->assertJsonPath('data.name', 'Max Updated');

    deleteJson("/api/users/{$created['id']}")->assertNoContent();
});

it('returns 422 when creating with invalid payload', function () {
    postJson('/api/users', [])->assertUnprocessable();
});

it('returns 422 when creating with duplicate email', function () {
    postJson('/api/users', [
        'name' => 'A', 'email' => 'dup@cwi.com', 'password' => 'secret123'
    ])->assertCreated();

    postJson('/api/users', [
        'name' => 'B', 'email' => 'dup@cwi.com', 'password' => 'secret123'
    ])->assertUnprocessable();
});

it('returns 404 when updating a non-existing user', function () {
    putJson('/api/users/999999', ['name' => 'X'])->assertNotFound();
});

it('returns 404 when deleting a non-existing user', function () {
    deleteJson('/api/users/999999')->assertNotFound();
});
