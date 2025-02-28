<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class LeaveApplicationController extends Controller
{
    public function hrDashboard()
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized access.');
        }
    
        // Fetch pending leave applications, ordered by the oldest request first
        $leaveApplications = Leave::where('status', 'pending')
                                  ->orderBy('created_at', 'asc') // Oldest first
                                  ->get();
    
        return view('leave.review', compact('leaveApplications'));
    }
    

    
public function showLeaveCertification($leaveId)
{
    $leave = Leave::findOrFail($leaveId);
    $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;

    return view('hr.leave_certification', compact('leave', 'daysRequested'));
}

    public function supervisorDashboard()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('status', 'waiting_for_supervisor')->get();
    
        return view('leave.approve', compact('leaveApplications'));
    }
    
// HR officer reviews applications
public function review(Request $request, Leave $leave)
{
    $request->validate([
        'status' => 'required|in:Approved,Rejected', // This ensures correct input
        'disapproval_reason' => 'nullable|string',
        'approved_days_with_pay' => 'nullable|integer',
        'approved_days_without_pay' => 'nullable|integer',
    ]);

    // Convert status to lowercase for consistency
    $hr_status = strtolower($request->status); // "Approved" -> "approved", "Rejected" -> "rejected"


    // If HR approves, status moves to supervisor review, otherwise, it's rejected.
    $leave->update([
        'hr_status' => $hr_status, // Update HR status
        'status' => $hr_status === 'approved' ? 'waiting_for_supervisor' : 'rejected',
        'disapproval_reason' => $request->disapproval_reason,
        'approved_days_with_pay' => $request->approved_days_with_pay,
        'approved_days_without_pay' => $request->approved_days_without_pay,
        'hr_officer_id' => Auth::id(),
    ]);

    return redirect()->back()->with('success', 'Leave application reviewed by HR.');
}


    // Supervisor gives final approval
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


    // List applications based on user role
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'HR') {
            $leaveApplications = Leave::where('status', 'Pending')->get();
        } elseif ($user->role === 'Supervisor') {
            $leaveApplications = Leave::where('status', 'Approved')->whereNull('supervisor_id')->get();
        } else {
            $leaveApplications = Leave::where('user_id', $user->id)->get();
        }

        return view('leave.index', compact('leaveApplications'));
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
}
