<?php

return [
    'enabled' => (bool) env('RECAPTCHA_ENABLED', false),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'minimum_score' => (float) env('RECAPTCHA_MINIMUM_SCORE', 0.5),
    'timeout' => (int) env('RECAPTCHA_TIMEOUT', 10),
    'verify_url' => env('RECAPTCHA_VERIFY_URL', 'https://www.google.com/recaptcha/api/siteverify'),
];
