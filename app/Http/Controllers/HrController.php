<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\HRSupervisor;
use App\Models\Leave;
use App\Models\OvertimeRequest;
use App\Models\YearlyHoliday;
use App\Services\YearlyHolidayService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EmailUpdateRequest;
use App\Services\HolidayService;
use App\Notifications\LeaveStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\Holiday;

class HrController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query();
    
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
        }
    
        $employees = $query->paginate(10)->withQueryString();
    
        // If it's an AJAX request, return only the partial view
        if ($request->ajax()) {
            return view('hr.partials.employee-list', compact('employees'))->render();
        }
    
    
        // Get pending leave requests
        $pendingLeaves = Leave::where('status', 'pending')->get();
    
        // Statistics Data
        $totalEmployees = User::count();
        $totalPendingLeaves = Leave::where('status', 'pending')->count();
        $totalApprovedLeaves = Leave::where('status', 'approved')->count();
        $totalRejectedLeaves = Leave::where('status', 'rejected')->count();
        $totalApprovedOvertime = OvertimeRequest::where('status', 'approved')->count();
        $totalPendingOvertime = OvertimeRequest::where('status', 'pending')->count();
        $totalRejectedOvertime = OvertimeRequest::where('status', 'rejected')->count();
    
        // Data for Chart.js
        $leaveStats = [
            'Pending' => $totalPendingLeaves,
            'Approved' => $totalApprovedLeaves,
            'Rejected' => $totalRejectedLeaves,
        ];
    
        $cocStats = [
            'Pending' => $totalPendingOvertime,
            'Approved' => $totalApprovedOvertime,
            'Rejected' => $totalRejectedOvertime,
        ];
    
        return view('hr.dashboard', compact('employees', 'pendingLeaves', 'totalEmployees', 'leaveStats', 'cocStats', 'search'));
    }
    
    
        public function onLeave(Request $request) {
        $month = $request->query('month', now()->month);
        $today = now()->toDateString(); 
    
        // Fetch employees whose birthday falls in the selected month
        $birthdays = User::whereMonth('birthday', $month)->get();
    
        // Get employees who are on approved leave this month (but only if their leave has not yet ended)
        $teamLeaves = Leave::whereMonth('start_date', $month)
                            ->where('status', 'approved')
                            ->where('end_date', '>=', $today) // Ensures leave is still ongoing
                            ->with('user') // Ensures the user object is available
                            ->get();
        $overtimeRequests = OvertimeRequest::where('status', 'approved')
        ->whereMonth('inclusive_date_start', $month)
        ->whereYear('inclusive_date_start', now()->year)
        ->get();
    
        return view('hr.on_leave', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
    }

    public function requests()
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('admin_status', 'approved')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        $ctoApplications = OvertimeRequest::where('admin_status', 'approved')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        return view('hr.requests', compact('leaveApplications', 'ctoApplications'));
    }
    
    
    public function showLeaveCertification($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);
        $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;

        return view('hr.leave_certification', compact('leave', 'daysRequested'));
    }

    public function showleave($id) {
        $leave = Leave::findOrFail($id); 
        $official = HRSupervisor::find($id);

        return view('hr.leave_details', compact('leave','official'));
    }

    public function showcto($id) {
        $cto = OvertimeRequest::findOrFail($id); 

        return view('hr.cto_details', compact('cto'));
    }

    // HR officer reviews applications
    public function review(Request $request, $leaveId) 
    {
        $leave = Leave::findOrFail($leaveId);
        $user = $leave->user;
    
        // Validate the request
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'disapproval_reason' => 'nullable|string',
        ]);
    
        // Convert status to lowercase
        $hr_status = strtolower($request->status);
    
        // Prepare the update data
        $updateData = [
            'hr_status' => $hr_status,
            'status' => $hr_status === 'rejected' ? 'rejected' : $leave->status,
            'disapproval_reason' => $request->disapproval_reason,
            'hr_officer_id' => auth()->id(),
        ];
    
        // If approved, apply leave deductions
        if ($hr_status === 'approved') {
    
            // Handle balance deductions
            $leaveTypeForDeduction = $leave->leave_type === 'Mandatory Leave' ? 'Vacation Leave' : $leave->leave_type;
    
            switch ($leaveTypeForDeduction) {
                case 'Sick Leave':
                    if ($user->sick_leave_balance >= $leave->days_applied) {
                        $user->sick_leave_balance -= $leave->days_applied;
                    } else {
                        $remainingDays = $leave->days_applied - $user->sick_leave_balance;
                        $user->sick_leave_balance = 0;
                        $user->vacation_leave_balance -= $remainingDays;
                    }
                    break;
    
                case 'Vacation Leave':
                    if ($user->vacation_leave_balance >= $leave->days_applied) {
                        $user->vacation_leave_balance -= $leave->days_applied;
                    } else {
                        $remainingDays = $leave->days_applied - $user->vacation_leave_balance;
                        $user->vacation_leave_balance = 0;
                        $user->sick_leave_balance -= $remainingDays;
                    }
                    break;
    
                case 'Maternity Leave':
                    $user->maternity_leave -= $leave->days_applied;
                    break;
    
                case 'Paternity Leave':
                    $user->paternity_leave -= $leave->days_applied;
                    break;
    
                case 'Solo Parent Leave':
                    $user->solo_parent_leave -= $leave->days_applied;
                    break;
    
                case 'Study Leave':
                    $user->study_leave -= $leave->days_applied;
                    break;
    
                case '10-Day VAWC Leave':
                    $user->vawc_leave -= $leave->days_applied;
                    break;
    
                case 'Rehabilitation Privilege':
                    $user->rehabilitation_leave -= $leave->days_applied;
                    break;
    
                case 'Special Leave Benefits for Women Leave':
                    $user->special_leave_benefit -= $leave->days_applied;
                    break;
    
                case 'Special Emergency Leave':
                    $user->special_emergency_leave -= $leave->days_applied;
                    break;
    
                default:
                    // No deduction for other leave types
                    break;
            }
    
            // Update supervisor fields
            $updateData['supervisor_status'] = 'approved';
            $updateData['supervisor_id'] = auth()->id();
            $updateData['status'] = 'approved';
        }
    
        // Save changes
        $leave->update($updateData);
        $user->save();
    
        notify()->success('Leave application reviewed successfully!');
        return redirect()->route('hr.requests');
    }   

    

        public function generateLeaveReport($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);
        $user = $leave->user;

        $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
        $vacationBalance = $user->vacation_leave_balance - ($leave->leave_type == 'Vacation Leave' ? $daysRequested : 0);
        $sickBalance = $user->sick_leave_balance - ($leave->leave_type == 'Sick Leave' ? $daysRequested : 0);

        $data = [
            'user' => $user,
            'leave' => $leave,
            'vacationBalance' => $vacationBalance,
            'sickBalance' => $sickBalance,
            'daysRequested' => $daysRequested,
            'date' => now()->format('F d, Y'),
        ];

        // Load PDF view
        $pdf = Pdf::loadView('hr.leave_report', $data);
        
        // Return PDF for download
        return $pdf->download('leave_certificate.pdf');
    }

    public function showOvertime($id) {
        $overtimeRequests = OvertimeRequest::findOrFail($id); 

        return view('hr.CTO.show_overtime_request', compact('overtimeRequests'));
    }

    public function profile() {
        $user = Auth::user();
    
        return view('hr.profile.index', [
            'user' => $user,
        ]);
    }
    
    public function profile_edit(Request $request): View
    {
        return view('hr.profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }
    public function password_edit(Request $request): View
    {
        return view('hr.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        notify()->success('Profile Updated Successfully!');

        return Redirect::route('hr.profile.partials.update-profile-information-form')->with('status', 'profile-updated');
    }


    public function updateEmail(EmailUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        notify()->success('Email Updated Successfully!');

        return Redirect::route('hr.profile.partials.update-profile-information-form')->with('status', 'email-updated');
    }

    public function leaderboard()
    {
        $employees = User::with(['leaves' => function ($query) {
            $query->where('status', 'approved')
                  ->whereMonth('start_date', now()->month) // Ensure it's within the month
                  ->whereYear('start_date', now()->year);
        }])->get();
    
        // Calculate total absences correctly
        $employees->each(function ($employee) {
            $employee->total_absences = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        });
        $employees = $employees->sortBy('total_absences')->take(5);
        return view('hr.leaderboard', compact('employees'));
    }

    public function calendar() {
        $holidays = Holiday::orderBy('date')->get()->map(function ($holiday) {
            $holiday->day = Carbon::parse($holiday->date)->format('d'); // Example: 01
            $holiday->month = Carbon::parse($holiday->date)->format('M'); // Example: Jan
            $holiday->day_name = Carbon::parse($holiday->date)->format('D'); // Example: Mon
            return $holiday;
        });
        return view('hr.holiday-calendar', compact('holidays'));
    }

    public function holiday()
    {
        $holidays = YearlyHoliday::orderBy('date')->get();
        return view('hr.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new holiday.
     */
    public function create()
    {
        return view('hr.holidays.create');
    }

    /**
     * Store a newly created holiday in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:regular,special,national',
            'repeats_annually' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        YearlyHoliday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'repeats_annually' => $request->boolean('repeats_annually'),
        ]);

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    /**
     * Show the form for editing the specified holiday.
     */
    public function edit(YearlyHoliday $holiday)
    {
        return view('hr.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified holiday in storage.
     */
    public function update(Request $request, YearlyHoliday $holiday)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:regular,special,national',
            'repeats_annually' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'repeats_annually' => $request->boolean('repeats_annually'),
        ]);

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified holiday from storage.
     */
    public function destroy(YearlyHoliday $holiday)
    {
        $holiday->delete();

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
    public function approve($id, Request $request)
    {
        $request = OvertimeRequest::findOrFail($id);

        if ($request->admin_status !== 'Ready for Review') {
            notify()->error('Cannot approve. Admin review is required first.');
            return redirect()->back();
        }

        if ($request->hr_status === 'pending') {
            $request->update(['hr_status' => 'approved']);
            notify()->success('CTO approved by HR.');
        } else {
            notify()->error('This request has already been processed by HR.');
        }

        return redirect()->back();
    }

    public function reject($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);
        $overtime->update(['hr_status' => 'rejected']);

        notify()->success('Overtime request rejected successfully.');
        return redirect()->back();
    }

    public function cocLogs()
    {
        $logs = CompensatoryTimeLog::orderBy('created_at', 'desc')->paginate(10);
        return view('hr.CTO.coclog', compact('logs'));
    }
}
