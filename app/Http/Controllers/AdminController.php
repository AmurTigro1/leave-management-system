<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function requests()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        $ctoApplications = OvertimeRequest::where('status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        return view('admin.requests', compact('leaveApplications', 'ctoApplications'));
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
    
        return view('admin.on_leave', compact('teamLeaves', 'birthdays', 'month'));
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
