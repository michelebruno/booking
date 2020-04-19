<?php

return [
    "webmaster" => [
        "email" => env("BOOKING_WEBMASTER_EMAIL", "bm.michelebruno@gmail.com")
    ],

    'paypal' => [
        'webhooks' => [
            'all' => "26R2292566757364T"
        ],
        'client' => [
            'id' => env('PAYPAL_CLIENT_ID', 'sb'),
            'secret' => env('PAYPAL_CLIENT_SECRET')
        ]
    ]
];
