<?php

/**
 * This script will take the `app.php` and attempt to run it in 'worker mode'.
 * Docs: https://github.com/dunglas/frankenphp/blob/main/docs/worker.md.
 */

// Prevent worker script termination when a client connection is interrupted
ignore_user_abort(true);

// Load our application
require __DIR__.'/app.php';

// Application handler
$handler = static function () use ($app) {
    $app->run();
};

// Reset frankenPHP worker after a number of requests
// https://github.com/dunglas/frankenphp/blob/main/docs/worker.md#restart-the-worker-after-a-certain-number-of-requests
$maxRequests = (int) ($_SERVER['MAX_REQUESTS'] ?? 0);

// Here the magic of 'worker mode' happens.
for ($nbRequests = 0; !$maxRequests || $nbRequests < $maxRequests; ++$nbRequests) {
    $keepRunning = \frankenphp_handle_request($handler);

    // Call the garbage collector to reduce the chances of it being triggered in the middle of a page generation
    gc_collect_cycles();

    if (!$keepRunning) {
        break;
    }
}
