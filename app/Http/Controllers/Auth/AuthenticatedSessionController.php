<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
    
        $user = Auth::user();
        $system = $request->input('system', 'cto'); // Default to CTO if not provided
    
        // Store system in session
        session(['system' => $system]);
    
        if ($user->role === 'supervisor') {
            return redirect(route('supervisor.dashboard'));
        }
    
        if ($user->role === 'employee') {
            // Redirect based on system
            return redirect(route($system === 'lms' ? 'lms.dashboard' : 'cto.dashboard'));
        }
    
        if ($user->role === 'hr') {
            return redirect(route('hr.dashboard'));
        }
    
        return redirect()->intended(route('employee.dashboard'));
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Forget the stored system session
        $request->session()->forget('system');
    
        // Logout the user
        Auth::guard('web')->logout();
    
        // Invalidate and regenerate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }    

    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();
        
        if ($user->role !== 'employee') {
            return redirect('/');
        }

        $system = session('system');

        if (($request->routeIs('lms.dashboard') && $system !== 'lms') ||
            ($request->routeIs('cto.dashboard') && $system !== 'cto')) {
            return redirect('/');
        }

        return $next($request);
    }
}
