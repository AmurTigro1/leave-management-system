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

    public function index() {
        $employees = User::paginate(10);
        $totalUsers = User::count();
        $approvedLeaves = Leave::where('supervisor_status', 'approved')->count();
        $pendingLeaves = Leave::where('status', 'waiting_for_supervisor')->count();
        $rejectedLeaves = Leave::where('supervisor_status', 'rejected')->count();
        $leaveStats = [
            'Pending' => $pendingLeaves,
            'Approved' => $approvedLeaves,
            'Rejected' => $rejectedLeaves,
        ];
        return view('supervisor.dashboard', compact('totalUsers', 'approvedLeaves', 'pendingLeaves', 'rejectedLeaves', 'leaveStats', 'employees'));
    }
    
    public function requests()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('status', 'waiting_for_supervisor')->get();
    
        return view('supervisor.requests', compact('leaveApplications'));
    }
    

    // public function requests()
    // {
    //     $leaves = Leave::with('user')->latest()->get();
    //     return view('supervisor.show_request', compact('leaves'));
    // }


    public function approve(Request $request, Leave $leave)
    {
        // Ensure leave exists
        if (!$leave) {
            return redirect()->back()->with('error', 'Leave application not found.');
        }
    
        // Ensure HR has approved it first before Supervisor approval
        if ($leave->hr_status !== 'approved') {
            return redirect()->back()->with('error', 'HR approval is required before supervisor approval.');
        }
    
        $user = $leave->user;
    
        // Calculate the total leave days requested
        $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
    
        // Deduct leave from the correct balance
        if ($leave->leave_type === 'Vacation Leave') {
            if ($user->vacation_leave_balance >= $daysRequested) {
                $user->decrement('vacation_leave_balance', $daysRequested);
            } else {
                return back()->with('error', 'Insufficient vacation leave balance.');
            }
        } elseif ($leave->leave_type === 'Sick Leave') {
            if ($user->sick_leave_balance >= $daysRequested) {
                $user->decrement('sick_leave_balance', $daysRequested);
            } else {
                return back()->with('error', 'Insufficient sick leave balance.');
            }
        } else {
            return back()->with('error', 'Invalid leave type.');
        }
    
        // Update leave status
        $leave->update([
            'supervisor_status' => 'approved',
            'status' => 'approved', // Officially approved
            'supervisor_id' => Auth::id(),
        ]);
    
        // Notify the Employee
        $user->notify(new LeaveStatusNotification($leave, "Your leave request has been approved by your Supervisor."));
    
        // Notify HR
        $hrUsers = User::where('role', 'HR')->get();
        Notification::send($hrUsers, new LeaveStatusNotification($leave, "The leave request of {$user->first_name} has been approved by the Supervisor."));
    
        return back()->with('success', 'Leave approved successfully.');
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
