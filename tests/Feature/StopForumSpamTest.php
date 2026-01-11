<?php

use Illuminate\Support\Facades\Http;
use Waterhole\Models\User;

test('it blocks spam registration', function () {
    Http::fake([
        '*' => Http::response([
            'success' => 1,
            'ip' => [
                'appears' => 1,
                'frequency' => 2,
                'confidence' => 60,
            ],
            'email' => [
                'appears' => 1,
                'frequency' => 0,
                'confidence' => 0,
            ],
            'username' => [
                'appears' => 1,
                'frequency' => 0,
                'confidence' => 0,
            ],
        ]),
    ]);

    $response = $this
        ->post(route('waterhole.register.submit'), [
            'name' => 'Spammer',
            'email' => 'spam@example.com',
            'password' => 'Password123!',
        ]);

    $response->assertSessionHasErrors('spam');

    expect(User::count())->toBe(0);
});
