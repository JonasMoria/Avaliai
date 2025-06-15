<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RedirectIfAuthenticated {
    public function handle(Request $request, Closure $next, ...$guards) {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect('/'); // Redirecionamento padrão, altere se necessário
            }
        }

        return $next($request);
    }
}
