<?php

return [
    // Allow all origins for CORS
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:5173', 'http://127.0.0.1:5173'],
    'allowed_headers' => ['*'],
    'exposed_headers' => ['X-Session-ID'],
    'max_age' => 0,
    'supports_credentials' => true,
    'allowed_origins_patterns' => [],
];