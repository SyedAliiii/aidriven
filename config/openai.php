<?php

return [
    'api_key' => env('OPENAI_API_KEY'),
    'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
    'model' => env('OPENAI_MODEL', 'gpt-4.1-mini'),
    'timeout' => (int) env('OPENAI_TIMEOUT', 60),
    'verify_ssl' => filter_var(env('OPENAI_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
    'ca_bundle' => env('OPENAI_CA_BUNDLE'),
];

