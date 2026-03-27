<?php

namespace App\Http\Middleware;

use App\Services\RecaptchaService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRecaptcha
{
    public function __construct(private readonly RecaptchaService $recaptchaService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe() || !$this->recaptchaService->enabled()) {
            return $next($request);
        }

        if ($request->routeIs('up')) {
            return $next($request);
        }

        $token = $request->input('g-recaptcha-response');

        if (!$this->recaptchaService->passes($token, $request->ip())) {
            return back()
                ->withInput($request->except(['password', 'password_confirmation', 'current-password']))
                ->withErrors(['recaptcha' => 'La verification reCAPTCHA a echoue. Merci de reessayer.']);
        }

        return $next($request);
    }
}
