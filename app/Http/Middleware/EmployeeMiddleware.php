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
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        // Only employees should proceed
        if ($user->role !== 'employee') {
            return redirect('/');
        }

        $system = session('system');

        // Prevent employees from accessing the wrong dashboard
        if (($request->routeIs('lms.dashboard') && $system !== 'lms') ||
            ($request->routeIs('cto.dashboard') && $system !== 'cto')) {
            return redirect('/');
        }

        return $next($request);
    }
}
