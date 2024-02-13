<?php

namespace App\Http\Middleware;

use App\Models\AppToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('api')->check()) {
            $token = $request->bearerToken();
            $userToken = AppToken::where('token', $token)->first();
            if ($userToken) {
                return $next($request);
            }
            return response()->json(['message' => 'Sesi anda telah berakhir. Silahkan logout'], 401);
        }
        return response()->json(['message' => 'Sesi anda telah berakhir. Silahkan logout'], 401);
    }
}
