<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function enabled(): bool
    {
        return (bool) config('recaptcha.enabled')
            && filled(config('recaptcha.site_key'))
            && filled(config('recaptcha.secret_key'));
    }

    public function verify(?string $token, ?string $ip = null): array
    {
        if (!$this->enabled()) {
            return ['success' => true, 'score' => 1.0, 'skipped' => true];
        }

        if (blank($token)) {
            return ['success' => false, 'score' => 0.0, 'error-codes' => ['missing-input-response']];
        }

        $response = Http::asForm()
            ->timeout((int) config('recaptcha.timeout', 10))
            ->post(config('recaptcha.verify_url'), [
                'secret' => config('recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]);

        if (!$response->ok()) {
            return ['success' => false, 'score' => 0.0, 'error-codes' => ['http-request-failed']];
        }

        return $response->json();
    }

    public function passes(?string $token, ?string $ip = null): bool
    {
        $result = $this->verify($token, $ip);

        return (bool) ($result['success'] ?? false)
            && (float) ($result['score'] ?? 0) >= (float) config('recaptcha.minimum_score', 0.5);
    }
}
