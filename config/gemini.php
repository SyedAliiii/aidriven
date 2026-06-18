<?php

return [
    'api_key' => env('GEMINI_API_KEY'),
    'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    'timeout' => (int) env('GEMINI_TIMEOUT', 60),
    'verify_ssl' => filter_var(env('GEMINI_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
    'ca_bundle' => env('GEMINI_CA_BUNDLE'),
];

