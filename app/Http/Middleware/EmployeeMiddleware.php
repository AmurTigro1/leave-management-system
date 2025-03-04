<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current route name
        $routeName = $request->route()->getName();

        // Determine the system based on the route
        $currentSystem = str_contains($routeName, 'lms') ? 'lms' : (str_contains($routeName, 'cto') ? 'cto' : null);

        if (!$currentSystem) {
            return $next($request); // Allow other routes
        }

        // Get the stored system session
        $storedSystem = session('system');

        // If the user is already logged into a system and tries to access another, redirect them
        if ($storedSystem && $storedSystem !== $currentSystem) {
            return redirect()->route($storedSystem . '.dashboard')->with('error', 'You must log out before accessing another system.');
        }

        // Store the system in the session if not already set
        session(['system' => $currentSystem]);

        return $next($request);
    }
}
