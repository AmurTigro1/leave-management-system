<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Illuminate\Support\Facades\Auth;

class OvertimeRequestController extends Controller
{
    public function index()
    {
        $overtimereq = OvertimeRequest::where('user_id', Auth::id())->latest()->get();
        return view('CTO.overtime_request', compact('overtimereq'));
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

    public function create()
    {
        return view('employee.overtime_request_form');
    }
}
