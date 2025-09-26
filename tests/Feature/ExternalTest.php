<?php

use Laravel\Passport\Client;
use Laravel\Passport\Passport;

use function Pest\Laravel\getJson;

it('calls /api/external with external.read scope', function () {
    $client = Client::factory()->create();

    Passport::actingAsClient($client, ['external.read']);

    getJson('/api/external')
        ->assertOk()
        ->assertJsonPath('status', 'ok');
});
