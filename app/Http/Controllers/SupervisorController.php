<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Http\Requests\EmailUpdateRequest;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
use App\Models\YearlyHoliday;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HRSupervisor;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveStatusNotification;

class SupervisorController extends Controller

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
    
        if ($request->ajax()) {
            // Return only the employee list as a partial view for AJAX requests
            return view('supervisor.partials.employee-list', compact('employees'))->render();
        }
    
        $totalUsers = User::count();
        $approvedLeaves = Leave::where('supervisor_status', 'approved')->count();
        $pendingLeaves = Leave::where('status', 'waiting_for_supervisor')->count();
        $rejectedLeaves = Leave::where('supervisor_status', 'rejected')->count();
        $approvedCto = OvertimeRequest::where('supervisor_status', 'approved')->count();
        $pendingCto = OvertimeRequest::where('supervisor_status', 'pending')->count();
        $rejectedCto = OvertimeRequest::where('supervisor_status', 'rejected')->count();
    
        $leaveStats = [
            'Pending' => $pendingLeaves,
            'Approved' => $approvedLeaves,
            'Rejected' => $rejectedLeaves,
        ];

        $cocStats = [
            'Pending' => $pendingCto,
            'Approved' => $approvedCto,
            'Rejected' => $rejectedCto,
        ];
    
        return view('supervisor.dashboard', compact('totalUsers', 'approvedLeaves', 'pendingLeaves', 'rejectedLeaves', 'leaveStats', 'cocStats' , 'employees', 'search'));
    }
    
    
    public function requests()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval with pagination
        $leaveApplications = Leave::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'leave_page'); // Use a custom page name
    
        // Get CTO applications waiting for supervisor approval with pagination
        $ctoApplications = OvertimeRequest::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'cto_page'); // Use a different page name to avoid conflict
    
        return view('supervisor.requests', compact('leaveApplications', 'ctoApplications'));
    }
    

//Supervisor Approve
// public function approve(Request $request, $leave) {
//     // Find the leave request
//     $leaveRequest = Leave::findOrFail($leave);

//     // Check if the request is already approved or rejected
//     if ($leaveRequest->supervisor_status !== 'pending') {
//         return redirect()->back()->withErrors(['status' => 'This leave request has already been processed.']);
//     }

//     // Get the user associated with the leave request
//     $user = $leaveRequest->user;

//     // Deduct leave credits based on the leave type
//     switch ($leaveRequest->leave_type) {
//         case 'Vacation Leave':
//         case 'Sick Leave':
//             // Deduct from Vacation Leave first, then Sick Leave
//             if ($user->vacation_leave_balance >= $leaveRequest->days_applied) {
//                 $user->vacation_leave_balance -= $leaveRequest->days_applied;
//             } else {
//                 $remainingDays = $leaveRequest->days_applied - $user->vacation_leave_balance;
//                 $user->vacation_leave_balance = 0;
//                 $user->sick_leave_balance -= $remainingDays;
//             }
//             break;
//         case 'Maternity Leave':
//             $user->maternity_leave -= $leaveRequest->days_applied;
//             break;
//         case 'Paternity Leave':
//             $user->paternity_leave -= $leaveRequest->days_applied;
//             break;
//         case 'Solo Parent Leave':
//             $user->solo_parent_leave -= $leaveRequest->days_applied;
//             break;
//         case 'Study Leave':
//             $user->study_leave -= $leaveRequest->days_applied;
//             break;
//         case 'VAWC Leave':
//             $user->vawc_leave -= $leaveRequest->days_applied;
//             break;
//         case 'Rehabilitation Leave':
//             $user->rehabilitation_leave -= $leaveRequest->days_applied;
//             break;
//         case 'Special Leave Benefit':
//             $user->special_leave_benefit -= $leaveRequest->days_applied;
//             break;
//         case 'Special Emergency Leave':
//             $user->special_emergency_leave -= $leaveRequest->days_applied;
//             break;
//         default:
//             return redirect()->back()->withErrors(['leave_type' => 'Invalid leave type.']);
//     }

//     // Update the supervisor status to approved
//     $leaveRequest->supervisor_status = 'approved';

//     // If HR status is also approved, update the overall status
//     if ($leaveRequest->hr_status === 'approved') {
//         $leaveRequest->status = 'approved';
//     }

//     // Save the updated leave request and user balances
//     $leaveRequest->save();
//     $user->save();

//     notify()->success('Leave request approved successfully!');
//     return redirect()->back()->with('success', 'Leave request successfully approved');
// }


    // Supervisor rejects the request
    public function reject(Request $request, Leave $leave)
{
    if (Auth::user()->role !== 'supervisor') {
        abort(403, 'Unauthorized access.');
    }

    if ($leave->hr_status !== 'approved') {
        return redirect()->back()->with('error', 'Cannot reject: HR approval is required first.');
    }

    $request->validate([
        'disapproval_reason' => 'nullable|string',
    ]);

    $leave->fill([
        'supervisor_status' => 'rejected',
        'disapproval_reason' => $request->disapproval_reason,
        'status' => 'Rejected',
        'supervisor_id' => Auth::id(),
    ]);

    if ($leave->save()) {
        return redirect()->back()->with('success', 'Leave application rejected by Supervisor.');
    }

    return redirect()->back()->with('error', 'Failed to reject leave application.');
}


    public function profile() {
        $user = Auth::user();
    
        return view('supervisor.profile.index', [
            'user' => $user,
        ]);
    }
    
    public function profile_edit(Request $request): View
    {
        return view('supervisor.profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }
    public function password_edit(Request $request): View
    {
        return view('supervisor.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        notify()->success('Profile Updated Successfully!');

        return Redirect::route('supervisor.profile.partials.update-profile-information-form')->with('status', 'profile-updated');
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

        return Redirect::route('supervisor.profile.partials.update-profile-information-form')->with('status', 'email-updated');
    }

    public function onLeave(Request $request) {
        $month = $request->query('month', now()->month);
        $today = now()->toDateString(); 
    
        $birthdays = User::whereMonth('birthday', $month)
        ->orderByRaw('DAY(birthday) ASC')
        ->get();
    
        $teamLeaves = Leave::whereMonth('start_date', $month)
                            ->where('status', 'approved')
                            ->where('end_date', '>=', $today)
                            ->with('user')
                            ->get();
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT); 
        $year = now()->year;
        
        $overtimeRequests = OvertimeRequest::where('status', 'approved')
            ->where('inclusive_dates', 'LIKE', "%-{$monthPadded}-%")
            ->where('inclusive_dates', 'LIKE', "{$year}-%")
            ->get();
        return view('supervisor.on_leave', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
    }

    public function leaderboard()
    {
        $employees = User::with(['leaves' => function ($query) {
            $query->where('status', 'approved')
                  ->whereMonth('start_date', now()->month) // Ensure it's within the month
                  ->whereYear('start_date', now()->year);
        }])->get();
    
        $employees->each(function ($employee) {
            $employee->total_absences = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        });
        $employees = $employees->sortBy('total_absences')->take(10)->values();
        return view('supervisor.leaderboard', compact('employees'));
    }

    public function holiday(Request $request)
    {
        $selectedYear = (int) $request->input('year', date('Y'));  // Cast year to integer
    
        // Get holidays for the selected year
        $holidays = YearlyHoliday::whereYear('date', $selectedYear)
            ->orWhere('repeats_annually', true)
            ->orderBy('date')
            ->get()
            ->map(function ($holiday) use ($selectedYear) {
                // Ensure repeating holidays use the selected year
                if ($holiday->repeats_annually) {
                    $date = Carbon::parse($holiday->date);
    
                    // Force cast to integer to avoid the Carbon::setUnit() error
                    $holiday->date = Carbon::create((int) $selectedYear, (int) $date->month, (int) $date->day)->format('Y-m-d');
                }
                return $holiday;
            });
    
        // Group holidays by month
        $groupedHolidays = $holidays->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('F Y');
        });
    
        // Prepare data for calendar view
        $calendarData = $this->prepareCalendarData($holidays, $selectedYear);
    
        return view('supervisor.holiday-calendar', compact(
            'groupedHolidays',
            'calendarData',
            'selectedYear',
        ));
    }

    protected function prepareCalendarData($holidays, $year)
{
    $months = [];

    for ($month = 1; $month <= 12; $month++) {
        // Cast year and month to integers to avoid Carbon errors
        $year = (int) $year;
        $month = (int) $month;

        $date = Carbon::create($year, $month, 1);
        $daysInMonth = $date->daysInMonth;

        $monthData = [
            'name' => $date->format('F'),
            'year' => $year,
            'days' => []
        ];

        // Filter holidays for this month
        $monthHolidays = $holidays->filter(function ($holiday) use ($month) {
            return (int) Carbon::parse($holiday->date)->month === $month;
        });

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($year, $month, $day);

            $dayHolidays = $monthHolidays->filter(function ($holiday) use ($day) {
                return (int) Carbon::parse($holiday->date)->day === $day;
            });

            $monthData['days'][$day] = [
                'date' => $currentDate,
                'holidays' => $dayHolidays,
                'isWeekend' => $currentDate->isWeekend()
            ];
        }

        $months[$month] = $monthData;
    }

    return $months;
}

    public function viewPdf($id)
    {
        $leave = Leave::findOrFail($id);
        $officials = HRSupervisor::all();

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.leave_details', compact('leave', 'supervisor', 'hr', 'officials'));
        
        return $pdf->stream( $leave->user->last_name . ', '. $leave->user->first_name . '- Leave Request' . '.pdf');
    }

    public function ctoviewPdf($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.overtime_details', compact('overtime', 'supervisor', 'hr'));
        
        return $pdf->stream( $overtime->user->last_name . ', '. $overtime->user->first_name . '- CTO Request' . '.pdf');

    }

    public function markAsRead()
    {
        $user = auth()->user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true, 'message' => 'Notifications marked as read.']);
    }

    public function delete($id)
    {
        $notification = auth()->user()->notifications()->find($id);
    
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Notification not found']);
    }
    

    public function deleteAll()
    {
        $user = Auth::user();

        if ($user) {
            $user->notifications()->delete();
            return response()->json(['success' => true, 'message' => 'All notifications deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No notifications found.']);
    }

    public function showSupervisorModal()
    {
        $employees = User::with(['leaves' => function ($query) {
            $query->where('status', 'approved')
                  ->whereMonth('start_date', now()->month) 
                  ->whereYear('start_date', now()->year);
        }])
        ->orderBy('last_name', 'asc')  
        ->get();
        $employees->each(function ($employee) {
            $employee->total_absences = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        });
        $employees = $employees
        ->sortBy(['total_absences', 'last_name'])
        ->values();

        return view('supervisor.partials.supervisor-modal', compact('employees'));
    }
}
