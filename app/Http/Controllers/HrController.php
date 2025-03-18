<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Leave;
use App\Models\OvertimeRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrController extends Controller
{
    public function index()
    {
        $employees = User::paginate(10); // Paginate with 10 records per page
        
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
    
        return view('hr.dashboard', compact('employees', 'pendingLeaves', 'totalEmployees', 'leaveStats', 'cocStats'));
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
    
        return view('hr.on_leave', compact('teamLeaves', 'birthdays', 'month'));
    }

    public function requests()
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized access.');
        }
    
        $leaveApplications = Leave::where('status', 'pending')
                                  ->orderBy('created_at', 'asc') 
                                  ->paginate(9); 
    
        return view('hr.requests', compact('leaveApplications'));
    }
    
    
    public function showLeaveCertification($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);
        $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;

        return view('hr.leave_certification', compact('leave', 'daysRequested'));
    }

    public function show($id) {
        $leave = Leave::findOrFail($id); 

        return view('hr.leave_details', compact('leave'));
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

    public function overtimeRequests() {

        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized access.');
        }
    
        $overtimeRequests = OvertimeRequest::where('status', 'pending')
                                  ->orderBy('created_at', 'asc') 
                                  ->paginate(9); 
    
        return view('hr.CTO.overtime_requests', compact('overtimeRequests'));
    }

    public function showOvertime($id) {
        $overtimeRequests = OvertimeRequest::findOrFail($id); 

        return view('hr.CTO.show_overtime_request', compact('overtimeRequests'));
    }
}
