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
use App\Models\Holiday;
use PDF;

class OvertimeRequestController extends Controller
{
    public function index()
    {
        $overtimereq = OvertimeRequest::where('user_id', auth()->id())->get();

        $appliedDates = OvertimeRequest::where('user_id', auth()->id())
                    ->get('inclusive_dates');
        $holidays = Holiday::select('date')->get();
        
        return view('CTO.overtime_request', compact('overtimereq', 'appliedDates', 'holidays'));
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
        $user = auth()->user();
        $overtimeBalance = $user->overtime_balance;

        // Map CTO Type to predefined working hours
        $ctoHoursMap = [
            'halfday_morning' => 4,
            'halfday_afternoon' => 4,
            'wholeday' => 8,
        ];

        // If CTO type is selected, assign corresponding hours
        if ($request->cto_type !== 'none') {
            $request->merge(['working_hours_applied' => $ctoHoursMap[$request->cto_type]]);
        }

        $request->validate([
            'inclusive_dates' => 'required|string',
            'cto_type' => 'nullable|in:none,halfday_morning,halfday_afternoon,wholeday',
            'working_hours_applied' => [
                'required_without:cto_type',
                'integer',
                'min:4',
                function ($attribute, $value, $fail) {
                    if ($value % 4 !== 0) {
                        $fail("The $attribute must be a multiple of 4.");
                    }
                },
                function ($attribute, $value, $fail) use ($overtimeBalance) {
                    if ($value > $overtimeBalance) {
                        $fail("You cannot apply more than your available COC balance.");
                    }
                }
            ],
        ]);

        // Validate inclusive dates
        $datesArray = explode(', ', $request->inclusive_dates);
        foreach ($datesArray as $date) {
            if (!strtotime($date)) {
                return back()->withErrors(['inclusive_dates' => 'Invalid date format detected']);
            }
        }

        // Store the overtime request
        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'working_hours_applied' => $request->working_hours_applied,
            'inclusive_dates' => $request->inclusive_dates,
            'admin_status' => 'pending', 
            'hr_status' => 'pending', 
        ]);

        notify()->success('Overtime request submitted successfully! Pending admin review.');
        return redirect()->back();
    }

    public function list()
    {
        $overtimereq = OvertimeRequest::where('user_id', Auth::id())->latest()->paginate(10);
        $overtime = OvertimeRequest::where('user_id', Auth::id())->first();
        return view('CTO.overtime_list', compact('overtimereq', 'overtime'));
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

    public function editOvertime($id) {
        $overtime = OvertimeRequest::findOrFail($id);
        return view('CTO.edit', compact('id', 'overtime'));
    }

    public function updateOvertime(Request $request, $id)
    {
        // Validate the form input
        $request->validate([
            'inclusive_dates' => 'required|string',
            'working_hours_applied' => 'required|integer|min:1',
        ]);
    
        $overtime = OvertimeRequest::findOrFail($id);
    
        // Update overtime details
        $overtime->update([
            'inclusive_dates' => $request->inclusive_dates,
            'working_hours_applied' => $request->working_hours_applied,
        ]);
        
        notify()->success('Overtime request updated successfully.');
        return redirect()->back();
    }

    public function deleteOvertime($id) {
        OvertimeRequest::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Overtime request deleted successfully.');
    }
}
