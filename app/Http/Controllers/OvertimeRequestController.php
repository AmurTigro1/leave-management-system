<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use PDF;

class OvertimeRequestController extends Controller
{
    public function index()
    {
        $overtimereq = OvertimeRequest::where('user_id', auth()->id())->get();

        return view('CTO.overtime_request', compact('overtimereq'));
    }

    public function dashboard()
    {
        $user_id = Auth::id();

        // Get user overtime requests
        $overtimes = OvertimeRequest::where('user_id', $user_id)->latest()->get();

        // Calculate total applied & earned hours
        $totalAppliedHours = OvertimeRequest::where('user_id', $user_id)->sum('working_hours_applied');
        $totalEarnedHours = OvertimeRequest::where('user_id', $user_id)->sum('earned_hours');

        // Count pending requests
        $pendingRequests = OvertimeRequest::where('user_id', $user_id)->where('earned_hours', 0)->count();

        return view('CTO.dashboard', compact('overtimes', 'totalAppliedHours', 'totalEarnedHours', 'pendingRequests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'position' => 'required|string',
            'office_division' => 'required|string',
            'inclusive_date_start' => 'required|date',
            'inclusive_date_end' => 'required|date|after_or_equal:inclusive_date_start',
            'working_hours_applied' => 'required|integer|min:1',
        ]);

        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'position' => $request->position,
            'office_division' => $request->office_division,
            'working_hours_applied' => $request->working_hours_applied,
            'inclusive_date_start' => $request->inclusive_date_start,
            'inclusive_date_end' => $request->inclusive_date_end,
        ]);

        notify()->success('Overtime request submitted successfully!');
        return redirect()->back();
    }

    public function list()
    {
        $overtimereq = OvertimeRequest::where('user_id', Auth::id())->latest()->paginate(10);
        return view('CTO.overtime_list', compact('overtimereq'));
    }

    public function show($id) {
        $overtime = OvertimeRequest::findOrFail($id); // Fetch the leave request by ID
    
        return view('CTO.overtime_show', compact('overtime'));
    }

    public function viewPdf($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.overtime_details', compact('overtime', 'supervisor', 'hr'));
        
        return $pdf->stream('overtime_request_' . $overtime->id . '.pdf');
    }

    public function profile() {
        $user = Auth::user();
    
        return view('CTO.profile.index', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request): View
    {
        return view('CTO.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('cto.profile.edit')->with('status', 'profile-updated');
    }
}
