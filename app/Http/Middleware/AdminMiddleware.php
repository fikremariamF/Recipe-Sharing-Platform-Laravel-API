<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('AdminMiddleware', ['user' => auth()->user()]);
        if (auth()->check() && auth()->user()->hasRole('Admin')) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized or You do not have the neccessary permissions'], 403);
    }
}
