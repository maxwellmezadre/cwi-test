<?php

use Laravel\Passport\Client;
use Laravel\Passport\Passport;
use function Pest\Laravel\getJson;

function actAsClient(array $scopes = []): void {
    $client = Client::factory()->create();

    Passport::actingAsClient($client, $scopes);
}

it('blocks when no token is provided (401)', function () {
    getJson('/api/users')->assertUnauthorized();
});

it('blocks user list without users.read scope (403)', function () {
    actAsClient(['external.read']);
    getJson('/api/users')->assertForbidden();
});

it('allows user list with users.read scope (200)', function () {
    actAsClient(['users.read']);
    getJson('/api/users')->assertOk();
});

it('blocks /api/external without external.read scope (403)', function () {
    actAsClient(['users.read']);
    getJson('/api/external')->assertForbidden();
});
