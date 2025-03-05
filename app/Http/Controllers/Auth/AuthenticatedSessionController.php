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
    
        switch ($user->role) {
            case 'employee':
                // Store system in session for employees
                $system = $request->input('system', 'cto'); // Default to CTO
                session(['system' => $system]);
    
                return redirect(route($system === 'lms' ? 'lms.dashboard' : 'cto.dashboard'));
    
            case 'supervisor':
                return redirect(route('supervisor.dashboard'));
    
            case 'hr':
                return redirect(route('hr.dashboard'));
    
            default:
                return redirect()->intended(route('employee.dashboard'));
        }
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

    // public function handle(Request $request, Closure $next)
    // {
    //     if (!Auth::check()) {
    //         return redirect('/');
    //     }

    //     $user = Auth::user();
        
    //     if ($user->role !== 'employee') {
    //         return redirect('/');
    //     }

    //     $system = session('system');

    //     if (($request->routeIs('lms.dashboard') && $system !== 'lms') ||
    //         ($request->routeIs('cto.dashboard') && $system !== 'cto')) {
    //         return redirect('/');
    //     }

    //     return $next($request);
    // }
}
