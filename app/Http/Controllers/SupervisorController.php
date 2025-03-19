<?php

namespace App\Http\Controllers;
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
    
    
    public function requests()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('status', 'waiting_for_supervisor')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 
        return view('supervisor.requests', compact('leaveApplications'));
    }

//Supervisor Approve
public function approve(Request $request, $leave) {
    // Find the leave request
    $leaveRequest = Leave::findOrFail($leave);

    // Check if the request is already approved or rejected
    if ($leaveRequest->supervisor_status !== 'pending') {
        return redirect()->back()->withErrors(['status' => 'This leave request has already been processed.']);
    }

    // Get the user associated with the leave request
    $user = $leaveRequest->user;

    // Deduct leave credits based on the leave type
    switch ($leaveRequest->leave_type) {
        case 'Vacation Leave':
        case 'Sick Leave':
            // Deduct from Vacation Leave first, then Sick Leave
            if ($user->vacation_leave_balance >= $leaveRequest->days_applied) {
                $user->vacation_leave_balance -= $leaveRequest->days_applied;
            } else {
                $remainingDays = $leaveRequest->days_applied - $user->vacation_leave_balance;
                $user->vacation_leave_balance = 0;
                $user->sick_leave_balance -= $remainingDays;
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

    public function edit(Request $request): View
    {
        return view('supervisor.profile.edit', [
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

        return Redirect::route('supervisor.profile.edit')->with('status', 'profile-updated');
    }
}
