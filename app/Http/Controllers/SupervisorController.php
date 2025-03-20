<?php

namespace App\Http\Controllers;
use App\Http\Requests\EmailUpdateRequest;
use App\Models\Leave;
use App\Models\User;
use Carbon\Carbon;
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
    
        $leaveStats = [
            'Pending' => $pendingLeaves,
            'Approved' => $approvedLeaves,
            'Rejected' => $rejectedLeaves,
        ];
    
        return view('supervisor.dashboard', compact('totalUsers', 'approvedLeaves', 'pendingLeaves', 'rejectedLeaves', 'leaveStats', 'employees', 'search'));
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
    
        return view('supervisor.on_leave', compact('teamLeaves', 'birthdays', 'month'));
    }
    
    public function requests()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('status', 'waiting_for_supervisor')
        ->orderBy('created_at', 'asc') 
        ->paginate(9); 
        return view('supervisor.requests', compact('leaveApplications'));
    }

//Supervisor Approve
public function approve(Request $request, $leave) 
{
    // Retrieve the Leave model using the ID
    $leaveRequest = Leave::findOrFail($leave);

    // Check if the request is already approved or rejected
    if ($leaveRequest->supervisor_status !== 'pending') {
        return redirect()->back()->withErrors(['status' => 'This leave request has already been processed.']);
    }

    // Get the associated user
    $user = $leaveRequest->user;

    // Deduct leave credits based on leave type
    switch ($leaveRequest->leave_type) {
        case 'Vacation Leave':
        case 'Sick Leave':
            // Combined logic for Vacation Leave and Sick Leave
            $totalBalance = $user->vacation_leave_balance + $user->sick_leave_balance;
            if ($totalBalance >= $leaveRequest->days_applied) {
                // Deduct from Vacation Leave first
                if ($user->vacation_leave_balance >= $leaveRequest->days_applied) {
                    $user->vacation_leave_balance -= $leaveRequest->days_applied;
                } else {
                    // Deduct remaining days from Sick Leave
                    $remainingDays = $leaveRequest->days_applied - $user->vacation_leave_balance;
                    $user->vacation_leave_balance = 0;
                    $user->sick_leave_balance -= $remainingDays;
                }
            } else {
                return redirect()->back()->withErrors(['status' => 'Not enough combined Vacation and Sick Leave balance.']);
            }
            break;
        case 'Mandatory Leave':
            // Mandatory Leave deducts from Vacation Leave only
            if ($user->vacation_leave_balance >= $leaveRequest->days_applied) {
                $user->vacation_leave_balance -= $leaveRequest->days_applied;
            } else {
                return redirect()->back()->withErrors(['status' => 'Not enough Vacation Leave balance for Mandatory Leave.']);
            }
            break;
        case 'Maternity Leave':
            $user->maternity_leave -= $leaveRequest->days_applied;
            break;
        case 'Paternity Leave':
            $user->paternity_leave -= $leaveRequest->days_applied;
            break;
        case 'Solo Parent Leave':
            $user->solo_parent_leave -= $leaveRequest->days_applied;
            break;
        case 'Study Leave':
            $user->study_leave -= $leaveRequest->days_applied;
            break;
        case 'VAWC Leave':
            $user->vawc_leave -= $leaveRequest->days_applied;
            break;
        case 'Rehabilitation Leave':
            $user->rehabilitation_leave -= $leaveRequest->days_applied;
            break;
        case 'Special Leave Benefit':
            $user->special_leave_benefit -= $leaveRequest->days_applied;
            break;
        case 'Special Emergency Leave':
            $user->special_emergency_leave -= $leaveRequest->days_applied;
            break;
        default:
            return redirect()->back()->withErrors(['leave_type' => 'Invalid leave type.']);
    }

    // Update the supervisor status to approved
    $leaveRequest->supervisor_status = 'approved';

    // If HR status is also approved, update the overall status
    if ($leaveRequest->hr_status === 'approved') {
        $leaveRequest->status = 'approved';
    }

    // Save the updated leave request and user balances
    $leaveRequest->save();
    $user->save();

    // ✅ Send the notification with the correct Leave model
    $user->notify(new LeaveStatusNotification($leaveRequest, "Your leave request has been approved by your Supervisor."));

    notify()->success('Leave request approved successfully!');
    return redirect()->back()->with('success', 'Leave request successfully approved');
}


    // Supervisor rejects the request
    public function reject(Request $request, Leave $leave)
    {
    if ($leave->hr_status !== 'approved') {
        return redirect()->back()->with('error', 'Cannot reject: HR approval is required first.');
    }

    $leave->update([
        'supervisor_status' => 'rejected',
        'status' => 'Rejected',
        'supervisor_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Leave application rejected by Supervisor.');
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
}
