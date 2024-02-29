<?php

return [
    'ssl' => [
        'local_cert' => env('certificate'),
        'local_pk'   => env('private_key'),
        'allow_self_signed' => true,
        'verify_peer' => false
    ],
];
