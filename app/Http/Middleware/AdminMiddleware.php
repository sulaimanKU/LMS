<?php

namespace App\Http\Middleware;

use Closure;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
 public function handle(Request $request, Closure $next, $role): Response
{
    if (!Auth::check()) {
        return redirect()->route('login.view');
    }

    /** @var \App\Models\User $user */
    $user = Auth::user();

    if (!$user->hasRole($role)) {
        return redirect()->route('dashboard')->with('error', "Access Denied: You do not have $role privileges.");
    }

    return $next($request);
}
}
