<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = $request->user();
        $userType = strtoupper((string) ($user->utype ?? ''));

        if ($userType !== 'ADM') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
