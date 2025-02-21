<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index() {
        $totalLeaves = Leave::count();
        $approvedLeaves = Leave::where('status', 'approved')->count();
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $rejectedLeaves = Leave::where('status', 'rejected')->count();
    
        return view('admin.dashboard', compact('totalLeaves', 'approvedLeaves', 'pendingLeaves', 'rejectedLeaves'));
    }
    public function requests()
    {
        $leaves = Leave::with('user')->latest()->get();
        return view('admin.show_request', compact('leaves'));
    }

    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate(['status' => 'required|string|in:Approved,Rejected']);

    //     $leave = Leave::findOrFail($id);
    //     $leave->status = $request->status;
    //     $leave->save();

    //     return back()->with('success', "Leave request has been {$request->status}!");
    // }
    public function approve(Leave $leave)
{
    $user = $leave->user;
    $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;

    if ($user->leave_balance >= $daysRequested) {
        $user->decrement('leave_balance', $daysRequested);
        $leave->update(['status' => 'approved']);
        return back()->with('success', 'Leave approved successfully.');
    } else {
        return back()->with('error', 'Insufficient leave balance.');
    }
}

}
