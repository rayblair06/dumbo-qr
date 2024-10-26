<?php

it('sends 200 response on root endpoint', function () {
    $response = get('/');

    $statusCode = $response->getStatusCode();

    expect($statusCode)->toBe(200);
});

it('generates svg qr code as default', function () {
    $response = post('/', [
        'json' => [
            'data' => 'hello',
        ],
    ]);

    $statusCode = $response->getStatusCode();
    $headers = $response->getHeaders();

    expect($statusCode)
        ->toBeInt()
        ->toBe(200);

    expect($headers)
        ->toBeArray()
        ->toMatchArray([
            'Content-Type' => ['image/svg+xml'],
        ]);
});

it('generates png qr code', function () {
    $response = post('/', [
        'json' => [
            'data' => 'hello',
            'format' => 'png',
        ],
    ]);

    $statusCode = $response->getStatusCode();
    $headers = $response->getHeaders();

    expect($statusCode)
        ->toBeInt()
        ->toBe(200);

    expect($headers)
        ->toBeArray()
        ->toMatchArray([
            'Content-Type' => ['image/png'],
        ]);
});

it('throws validation error on empty body', function () {
    $response = post('/', [
        'http_errors' => false, // Don't throw exceptions
    ]);

    $statusCode = $response->getStatusCode();

    expect($statusCode)->toBe(400);
});
