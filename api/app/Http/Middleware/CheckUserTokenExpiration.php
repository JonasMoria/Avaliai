<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserTokenExpiration {
    public function handle(Request $request, Closure $next): Response {
        $token = $request->user()?->currentAccessToken();

        if ($token && $token->expires_at && now()->greaterThan($token->expires_at)) {
            $token->delete();
            return response()->json([
                'status' => 401,
                'message' => 'Acesso Expirado. Por favor, Faça login novamente.'
            ], 401);
        }
        
        return $next($request);
    }
}
