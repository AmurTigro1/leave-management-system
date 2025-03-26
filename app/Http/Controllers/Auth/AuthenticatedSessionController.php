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
    public function landing(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to(Auth::user()->redirectToDashboard());
        }

        return view('employee.landing');
    }
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to(Auth::user()->redirectToDashboard());
        }
    
        return view('main_resources.logins.lms_cto_login');
    }
    
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        session()->save();
        $request->session()->regenerate();
        $user = Auth::user();

        notify()->success('Login Successful! Welcome Back.');
        return redirect($user->redirectToDashboard());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget('system');
    
        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        notify()->success('Logout Successful!');
        return redirect('/login');
    }    
}
