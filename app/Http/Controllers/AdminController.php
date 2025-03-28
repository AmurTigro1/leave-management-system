<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YearlyHolidayService;
use App\Models\Leave;
use App\Models\OvertimeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EmailUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Holiday;

class AdminController extends Controller
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
            return view('admin.partials.employee-list', compact('employees'))->render();
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
    
        return view('admin.dashboard', compact('employees', 'pendingLeaves', 'totalEmployees', 'leaveStats', 'cocStats', 'search'));
    }

    public function makeLeaveRequest()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('admin.make_leave_request', compact('leaves'));
    }

    public function storeLeave(Request $request, YearlyHolidayService $yearlyHolidayService)  
    {
        $inclusiveLeaveTypes = [
            'Maternity Leave',
            'Study Leave',
            'Rehabilitation Privilege',
            'Special Leave Benefits for Women Leave'
        ];
    
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($request, $inclusiveLeaveTypes, $yearlyHolidayService) {
                    $leaveType = $request->leave_type;
                    $startDate = Carbon::parse($value);
    
                    if (!in_array($leaveType, $inclusiveLeaveTypes) && 
                        ($startDate->isWeekend() || $yearlyHolidayService->isHoliday($startDate))) {
                        $fail('The start date cannot be a weekend or holiday for this leave type.');
                    }
                }
            ],
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'days_applied' => 'required|integer|min:1',
            'commutation' => 'required|boolean',
            'leave_details' => 'nullable|array', 
            'abroad_details' => 'nullable|string', 
        ]);
    
        $user = Auth::user();
    
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        if (in_array($request->leave_type, $inclusiveLeaveTypes)) {
            $daysApplied = $startDate->diffInDays($endDate) + 1;
        } else {
            $daysApplied = 0;
            $currentDate = $startDate->copy();
            $holidays = $yearlyHolidayService->getHolidaysBetweenDates($startDate, $endDate);
    
            while ($currentDate->lte($endDate)) {
                if (!$currentDate->isWeekend() && !in_array($currentDate->format('Y-m-d'), $holidays)) {
                    $daysApplied++;
                }
                $currentDate->addDay();
            }
    
            if ($daysApplied === 0) {
                $isValidStartDate = !$startDate->isWeekend() && 
                                    !$yearlyHolidayService->isHoliday($startDate);
    
                if ($isValidStartDate) {
                    $daysApplied = 1;
                } else {
                    return redirect()->back()->withErrors([
                        'start_date' => 'Your selected dates only include weekends/holidays which are not counted for this leave type.'
                    ]);
                }
            }
        }
    
        // For Mandatory Leave, check vacation leave balance
        $leaveTypeForBalance = $request->leave_type === 'Mandatory Leave' ? 'Vacation Leave' : $request->leave_type;
        
        // Calculate available balance
        if ($leaveTypeForBalance === 'Sick Leave') {
            $availableLeaveBalance = $user->sick_leave_balance;
        } elseif ($leaveTypeForBalance === 'Vacation Leave') {
            $availableLeaveBalance = $user->vacation_leave_balance;
        } else {
            $availableLeaveBalance = match ($leaveTypeForBalance) {
                'Maternity Leave' => $user->maternity_leave,
                'Paternity Leave' => $user->paternity_leave,
                'Solo Parent Leave' => $user->solo_parent_leave,
                'Study Leave' => $user->study_leave,
                '10-Day VAWC Leave' => $user->vawc_leave,
                'Rehabilitation Privilege' => $user->rehabilitation_leave,
                'Special Leave Benefits for Women Leave' => $user->special_leave_benefit,
                'Special Emergency Leave' => $user->special_emergency_leave,
                default => 0,
            };
        }
    
        // For Sick Leave and Vacation Leave, we need to check combined balance
        if (in_array($leaveTypeForBalance, ['Sick Leave', 'Vacation Leave'])) {
            $combinedBalance = $user->sick_leave_balance + $user->vacation_leave_balance;
            if ($daysApplied > $combinedBalance) {
                return redirect()->back()->withErrors(['end_date' => 'You do not have enough combined Sick and Vacation Leave balance for this request.']);
            }
        } else {
            if ($daysApplied > $availableLeaveBalance) {
                return redirect()->back()->withErrors(['end_date' => 'You do not have enough balance for ' . $request->leave_type . '.']);
            }
        }
    
        $leaveDetails = [];
    
        if ($request->leave_type === 'Vacation Leave' || $request->leave_type === 'Special Privilege Leave') {
            if ($request->filled('within_philippines')) {
                $leaveDetails['Within the Philippines'] = $request->within_philippines;
            }
            if ($request->filled('abroad_details')) {
                $leaveDetails['Abroad'] = $request->abroad_details;
            }
        }
    
        if ($request->leave_type === 'Sick Leave') {
            if ($request->has('in_hospital')) {
                $leaveDetails['In Hospital'] = $request->input('in_hospital_details', 'Yes');
            }
            if ($request->has('out_patient')) {
                $leaveDetails['Out Patient'] = $request->input('out_patient_details', 'Yes');
            }
        }
    
        // **Study Leave**
        if ($request->leave_type === 'Study Leave') {
            if ($request->has('completion_masters')) {
                $leaveDetails[] = 'Completion of Master\'s Degree';
            }   
            if ($request->has('bar_review')) {
                $leaveDetails[] = 'BAR Review';
            }
        }
    
        // **Other Purposes**
        if ($request->leave_type === 'Other Purposes') {
            if ($request->has('monetization')) {
                $leaveDetails[] = 'Monetization of Leave Credits';
            }
            if ($request->has('terminal_leave')) {
                $leaveDetails[] = 'Terminal Leave';
            }
        }
    
        // **Others Leave Type**
        if ($request->leave_type === 'Others') {
            if ($request->filled('others_details')) {
                $leaveDetails[] = 'Other Details';
                $leaveDetails[] = $request->others_details;
            }
        }
    
        // Store leave request with a default status of "Pending"
        Leave::create([
            'user_id' => auth()->id(),
            'leave_type' => $request->leave_type,
            'leave_details' => json_encode($leaveDetails), // Store all selected details as JSON
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'salary_file' => $request->salary_file,
            'days_applied' => $daysApplied,
            'commutation' => $request->commutation,
            'date_filing' => now(),
            'reason' => $request->reason,
            'signature' => $request->signature,
            'status' => 'pending', // Default status for new requests
        ]);

    
        notify()->success('Leave request submitted successfully! It is now pending approval.');
        return redirect()->back();
    }

    public function makeCTORequest()
    {
        $overtimereq = OvertimeRequest::where('user_id', auth()->id())->get();

        $appliedDates = OvertimeRequest::where('user_id', auth()->id())
                    ->get('inclusive_dates');
        $holidays = Holiday::select('date')->get();
        
        return view('admin.make_cto_request', compact('overtimereq', 'appliedDates', 'holidays'));
    }

    public function storeCTO(Request $request)
    {
        $request->validate([
            'inclusive_dates' => 'required|string',
            'working_hours_applied' => 'required|integer|min:4',
        ]);

        // Convert the comma-separated dates string to an array
        $datesArray = explode(', ', $request->inclusive_dates);
        
        // Optionally, you might want to validate each date
        foreach ($datesArray as $date) {
            if (!strtotime($date)) {
                return back()->withErrors(['inclusive_dates' => 'Invalid date format detected']);
            }
        }

        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'working_hours_applied' => $request->working_hours_applied,
            'inclusive_dates' => $request->inclusive_dates, // Store as comma-separated string
            'admin_status' => 'pending', // Goes to admin first
            'hr_status' => 'pending', // HR reviews only after admin approval
        ]);

        notify()->success('Overtime request submitted successfully! Pending admin review.');
        return redirect()->back();
    }

    public function requests()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('admin_status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        $ctoApplications = OvertimeRequest::where('admin_status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        return view('admin.requests', compact('leaveApplications', 'ctoApplications'));
    }

    public function review(Request $request, Leave $leave)
{
    $request->validate([
        'admin_status' => 'required|in:Approved,Rejected', // Ensures correct input
        'disapproval_reason' => 'nullable|string',
    ]);

    // Convert status to lowercase for consistency
    $admin_status = strtolower($request->admin_status); // "Approved" -> "approved", "Rejected" -> "rejected"

    // Determine the new status based on admin_status
    $status = ($admin_status === 'rejected') ? 'rejected' : $leave->status;

    // Update leave record
    $leave->update([
        'admin_status' => $admin_status, // Update HR status
        'status' => $status, // Also update overall status if rejected
        'disapproval_reason' => $request->disapproval_reason,
        'admin_id' => Auth::id(),
    ]);

    notify()->success('Leave application reviewed by Admin.');
    return Redirect::route('admin.requests');
}

public function ctoreview(Request $request, OvertimeRequest $cto)
{
    $request->validate([
        'admin_status' => 'required|in:Ready for Review,Rejected', // Ensures correct input
        // 'disapproval_reason' => 'nullable|string',
    ]);

    // Convert status to lowercase for consistency
    $admin_status = strtolower($request->admin_status); // "Approved" -> "approved", "Rejected" -> "rejected"

    // Determine the new status based on admin_status
    // $status = ($admin_status === 'rejected') ? 'rejected' : $cto->status;

    // Update leave record
    $cto->update([
        'admin_status' => $admin_status, // Update HR status
        // 'status' => $status, // Also update overall status if rejected
        // 'disapproval_reason' => $request->disapproval_reason,
        'admin_id' => Auth::id(),
    ]);

    notify()->success('CTO application reviewed by Admin.');
    return Redirect::route('admin.requests');
}


    public function showleave($id) {
        $leave = Leave::findOrFail($id); 

        return view('admin.leave_details', compact('leave'));
    }

    public function showcto($id) {
        $cto = OvertimeRequest::findOrFail($id); 

        return view('admin.cto_details', compact('cto'));
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
    
        return view('admin.on_leave', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
    }

    public function profile() {
        $user = Auth::user();
    
        return view('admin.profile.index', [
            'user' => $user,
        ]);
    }
    
    public function profile_edit(Request $request): View
    {
        return view('admin.profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }
    public function password_edit(Request $request): View
    {
        return view('admin.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        notify()->success('Profile Updated Successfully!');

        return Redirect::route('admin.profile.partials.update-profile-information-form')->with('status', 'profile-updated');
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

        return Redirect::route('admin.profile.partials.update-profile-information-form')->with('status', 'email-updated');
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
        return view('admin.leaderboard', compact('employees'));
    }

    public function holiday() {
        $holidays = Holiday::orderBy('date')->get()->map(function ($holiday) {
            $holiday->day = Carbon::parse($holiday->date)->format('d'); // Example: 01
            $holiday->month = Carbon::parse($holiday->date)->format('M'); // Example: Jan
            $holiday->day_name = Carbon::parse($holiday->date)->format('D'); // Example: Mon
            return $holiday;
        });
        return view('admin.holiday-calendar', compact('holidays'));
    }

    public function approveByAdmin($id)
    {
        $request = OvertimeRequest::findOrFail($id);

        if ($request->admin_status === 'pending') {
            $request->update(['admin_status' => 'approved']);
            notify()->success('CTO approved by admin. Now pending HR review.');
        } else {
            notify()->error('This request has already been processed by the admin.');
        }

        return redirect()->back();
    }
}
