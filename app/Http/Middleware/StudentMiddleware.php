<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next ,$student): Response
    {
         if (!Auth::check()) {
        return redirect()->route('login.view');
    }

    /** @var \App\Models\Users $user */
    $user = Auth::user();
    if(!$user->hasRole($student)){
         return redirect()->route('login.view')->with('error', 'Unauthorized access.');
    }
  return $next($request);

    }
}
