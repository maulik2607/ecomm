<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
  if (!Auth::user()->is_active) {
              Auth::logout();
          return redirect()->back()->with('error','Access denied: No role assigned to your account. Please contact the administrator.');
      }
        foreach ($roles as $role) {

            if ($user && $user->hasRole($role)) {
                return $next($request);
            }
        }
  
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return back()->with('error', 'Access denied: No role assigned to your account. Please contact the administrator.');
    }
}
