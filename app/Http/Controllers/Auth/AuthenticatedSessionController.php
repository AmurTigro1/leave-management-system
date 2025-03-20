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
                emotify('success', 'Login Successful! Welcome Back.');
                return redirect(route('lms_cto.dashboard'));
    
            case 'supervisor':

                emotify('success', 'Login Successful! Welcome Back.');
                return redirect(route('supervisor.dashboard'));
    
            case 'hr':

                emotify('success', 'Login Successful! Welcome Back.');
                return redirect(route('hr.dashboard'));

            case 'admin':

                emotify('success', 'Login Successful! Welcome Back.');
                return redirect(route('admin.dashboard'));
    
            default:
                return redirect()->intended(route('lms_cto.dashboard'));
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
        
        notify()->success('Logout Successful!');
        return redirect('/lms-cto/login');
    }    
}
