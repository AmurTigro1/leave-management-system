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
                    ->get(['inclusive_date_start', 'inclusive_date_end']) // Get both dates
                    ->map(function ($request) {
                        return [
                            'start' => $request->inclusive_date_start,
                            'end' => $request->inclusive_date_end,
                        ];
                    });
        $holidays = Holiday::select('date')->get();

        // $totalOvertime = $this->calculateOvertime($appliedDates, $holidays);
        
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
        $request->validate([
            'position' => 'required|string',
            'office_division' => 'required|string',
            'inclusive_date_start' => 'required|date',
            'inclusive_date_end' => 'required|date|after_or_equal:inclusive_date_start',
            'working_hours_applied' => 'required|integer|min:4|max:10',
            'overtime_rate' => 'required|in:1.0,1.5,2.0',
            'is_driver' => 'nullable|boolean',
            'distance_km' => 'nullable|required_if:is_driver,1|integer|min:0|max:50',
            'is_weekend' => 'nullable|boolean',
            'is_holiday' => 'nullable|boolean',
        ]);
    
        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'position' => $request->position,
            'office_division' => $request->office_division,
            'working_hours_applied' => $request->working_hours_applied,
            'inclusive_date_start' => $request->inclusive_date_start,
            'inclusive_date_end' => $request->inclusive_date_end,
            'overtime_rate' => $request->overtime_rate,
            'is_driver' => $request->has('is_driver'),
            'distance_km' => $request->is_driver ? $request->distance_km : null,
            'is_weekend' => $request->has('is_weekend'),
            'is_holiday' => $request->has('is_holiday'),
        ]);
    
        notify()->success('Overtime request submitted successfully!');
        return redirect()->back();
    }

    // function calculateOvertime($selectedDates) {
    //     $totalOvertime = 0;
    //     $weekendRate = 1.5;
    //     $holidayRate = 2.0;
    //     $minimumWeekendHours = 4;
    
    //     foreach ($selectedDates as $date) {
    //         $dayOfWeek = $date; // 1 (Monday) to 7 (Sunday)
    //         $isHoliday = in_array($date, $holidays); // Check if the date is a holiday
    
    //         if ($isHoliday) {
    //             $totalOvertime += $minimumWeekendHours * $holidayRate;
    //         } elseif ($dayOfWeek >= 6) { // Saturday (6) or Sunday (7)
    //             $totalOvertime += $minimumWeekendHours * $weekendRate;
    //         } else {
    //             // Regular weekday overtime calculation
    //             $totalOvertime += $regularOvertimeHours; // Adjust based on your logic
    //         }
    //     }
    
    //     return $totalOvertime;
    // }

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

    public function editOvertime($id) {
        $overtime = OvertimeRequest::findOrFail($id);
        return view('CTO.edit', compact('id', 'overtime'));
    }

    public function updateOvertime(Request $request, $id)
    {
        // Validate the form input
        $request->validate([
            'position' => 'nullable|string|max:255',
            'office_division' => 'required|string',
            'inclusive_date_start' => 'required|date',
            'inclusive_date_end' => 'required|date|after_or_equal:inclusive_date_start',
            'working_hours_applied' => 'required|integer|min:1',
        ]);
    
        $overtime = OvertimeRequest::findOrFail($id);
    
        // Update overtime details
        $overtime->update([
            'position' => $request->position,
            'office_division' => $request->office_division,
            'inclusive_date_start' => $request->inclusive_date_start,
            'inclusive_date_end' => $request->inclusive_date_end,
            'working_hours_applied' => $request->working_hours_applied,
        ]);
    
        return redirect()->back()->with('success', 'Overtime request updated successfully.');
    }

    public function deleteOvertime($id) {
        OvertimeRequest::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Overtime request deleted successfully.');
    }
}
