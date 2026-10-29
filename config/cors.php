<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    // URL de ton site Angular sur Vercel (variable FRONTEND_URL sur Render)
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:4200')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    // Mode tokens (Bearer) : pas de cookies
    'supports_credentials' => false,
];
