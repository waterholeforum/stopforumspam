<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Minimum Frequency / Confidence
    |--------------------------------------------------------------------------
    |
    | Only block if the combined totals across checked fields reach these
    | thresholds (if provided by the API response).
    |
    */

    'min_frequency' => 2,
    'min_confidence' => 50,

    /*
    |--------------------------------------------------------------------------
    | Checks
    |--------------------------------------------------------------------------
    |
    | Which fields should be checked against the StopForumSpam API.
    |
    */

    'checks' => [
        'ip' => true,
        'email' => true,
        'username' => true,
    ],
];
