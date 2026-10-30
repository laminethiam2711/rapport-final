<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    // FRONTEND_URL = adresse du site Vercel (plusieurs possibles, séparées par des virgules)
    'allowed_origins' => array_map(
        fn ($url) => rtrim(trim($url), '/'),
        explode(',', env('FRONTEND_URL', 'https://rapport-final-2anr.vercel.app'))
    ),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // Authentification par jeton Bearer (pas de cookies)
    'supports_credentials' => false,
];
