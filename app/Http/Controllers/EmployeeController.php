<?php

namespace App\Http\Controllers;
use App\Models\Holiday;
use App\Services\YearlyHolidayService;
use App\Models\OvertimeRequest;
use App\Models\HRSupervisor;
use App\Models\Leave;
use App\Models\User;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EmailUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function indexLMS(Request $request) {
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
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT); // Format as 2 digits (01-12)
        $year = now()->year;
        
        $overtimeRequests = OvertimeRequest::where('status', 'approved')
            ->where('inclusive_dates', 'LIKE', "%-{$monthPadded}-%") // Check for month in any position
            ->where('inclusive_dates', 'LIKE', "{$year}-%") // Check for current year
            ->get();

        return view('employee.dashboard', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
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
        return view('employee.leaderboard', compact('employees'));
    }
    
    
    public function showUsersModal()
    {
        $employees = User::with(['leaves' => function ($query) {
            $query->where('status', 'approved')
                  ->whereMonth('start_date', now()->month) // Ensure it's within the month
                  ->whereYear('start_date', now()->year);
        }])
        ->orderBy('last_name', 'asc')  // Sort by last name ascending
        ->get();
        // Calculate total absences correctly
        $employees->each(function ($employee) {
            $employee->total_absences = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        });
        $employees = $employees->sortBy('total_absences');

        return view('employee.partials.users-modal', compact('employees'));
    }

    public function loginLmsCto() {
        return view('main_resources.logins.lms_cto_login');
    }

    public function makeRequest()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('employee.make_request', compact('leaves'));
    }

    public function store(Request $request, YearlyHolidayService $yearlyHolidayService)  
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

    public function cancel($id)
    {
        $leave = Leave::findOrFail($id);

        // Ensure only the owner can cancel
        if ($leave->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Only allow pending leaves to be canceled
        if ($leave->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending leave requests can be canceled.');
        }

        // Update status to canceled
        $leave->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Leave request canceled successfully.');
    }

    public function restore($id)
    {
        $leave = Leave::findOrFail($id);

        // Ensure only the owner can restore
        if ($leave->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Only allow restoring canceled requests
        if ($leave->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Only canceled requests can be restored.');
        }

        $leave->update(['status' => 'pending']);

        return redirect()->back()->with('success', 'Leave request restored successfully.');
    }
    public function showRequests() {
        $holidays = Holiday::orderBy('date')->get()->map(function ($holiday) {
            $holiday->day = Carbon::parse($holiday->date)->format('d'); // Example: 01
            $holiday->month = Carbon::parse($holiday->date)->format('M'); // Example: Jan
            $holiday->day_name = Carbon::parse($holiday->date)->format('D'); // Example: Mon
            return $holiday;
        });
        // Ensure the user is authenticated
        $user = auth()->user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your reservations.');
        }
    
        // Get the authenticated user's reservations ordered by latest first with pagination
        $leaves = $user->leaves()->orderBy('created_at', 'desc')->paginate(10); // Adjust the number as needed
    
        return view('employee.leave_request', compact('leaves', 'holidays'));
    }
    
    public function show($id) {
        $leave = Leave::findOrFail($id); // Fetch the leave request by ID
    
        return view('employee.leave_show', compact('leave'));
    }
    
    public function profile() {
        $user = Auth::user();
    
        return view('employee.profile.index', [
            'user' => $user,
        ]);
    }
    
    public function profile_edit(Request $request): View
    {
        return view('employee.profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }
    public function password_edit(Request $request): View
    {
        return view('employee.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        notify()->success('Profile Updated Successfully!');

        return Redirect::route('employee.profile.partials.update-profile-information-form')->with('status', 'profile-updated');
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

        return Redirect::route('employee.profile.partials.update-profile-information-form')->with('status', 'email-updated');
    }

    public function getLeaves(Request $request)
    {
        $month = $request->month ?? date('m'); // Default to current month
    
        $leaves = Leave::with('user:id,name,first_name,last_name,profile_image')
            ->whereMonth('start_date', $month)
            ->orderBy('start_date', 'asc')
            ->get();
    
        return response()->json($leaves->map(function ($leave) {
            return [
                "id" => $leave->id,
                "first_name" => $leave->user->first_name,
                "last_name" => $leave->user->last_name,
                "start" => \Carbon\Carbon::parse($leave->start_date)->format('F j, Y'),
                "end" => \Carbon\Carbon::parse($leave->end_date)->format('F j, Y'),
                "status" => ucfirst($leave->status), // Capitalize first letter
                "duration" => \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1,
                "profile_image" => $leave->user->profile_image ? asset('storage/profile_images/' . $leave->user->profile_image) : asset('images/default.png')
            ];
        }));
    }

    public function getOvertimes(Request $request)
    {
        $month = $request->month ?? date('m'); // Default to current month

        $overtimes = OvertimeRequest::with('user:id,name,first_name,last_name,profile_image')
            ->where('inclusive_dates', 'LIKE', '%-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-%')
            ->orderByRaw("SUBSTRING_INDEX(inclusive_dates, ',', 1) ASC") // Orders by first date in the list
            ->get();
            
        return response()->json($overtimes->map(function ($overtime) {
            // Parse the comma-separated dates
            $dates = explode(', ', $overtime->inclusive_dates);
            $firstDate = \Carbon\Carbon::parse($dates[0]);
            $lastDate = \Carbon\Carbon::parse(end($dates));
            
            // Format the date display
            $dateDisplay = count($dates) === 1
                ? $firstDate->format('F j, Y')
                : $firstDate->format('F j, Y') . ' to ' . $lastDate->format('F j, Y');
        
            return [
                "id" => $overtime->id,
                "first_name" => $overtime->user?->first_name ?? 'Unknown',
                "last_name" => $overtime->user?->last_name ?? '',
                "date" => $dateDisplay,
                "admin_status" => ucfirst($overtime->admin_status ?? 'Pending'),
                "hours" => $overtime->working_hours_applied ?? 0,
                "profile_image" => $overtime->user?->profile_image
                    ? asset('storage/profile_images/' . $overtime->user->profile_image)
                    : asset('images/default.png'),
                // Optional: include all dates if needed
                "all_dates" => array_map(fn($d) => \Carbon\Carbon::parse($d)->format('F j, Y'), $dates)
            ];
        }));
    }
    
    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::delete('public/profile_images/' . $user->profile_image);
            }

            // Store new image
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $filename = basename($imagePath);


            $user->update(['profile_image' => $filename]);
        }

        return back()->with('success', 'Profile image updated successfully!');
    }

    public function viewPdf($id)
    {
        $leave = Leave::findOrFail($id);
        $official = HRSupervisor::find($id);

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.leave_details', compact('leave', 'supervisor', 'hr', 'official'));
        
        return $pdf->stream('leave_request_' . $leave->id . '.pdf');
    }
    

    public function holiday() {
        $holidays = Holiday::orderBy('date')->get()->map(function ($holiday) {
            $holiday->day = Carbon::parse($holiday->date)->format('d'); // Example: 01
            $holiday->month = Carbon::parse($holiday->date)->format('M'); // Example: Jan
            $holiday->day_name = Carbon::parse($holiday->date)->format('D'); // Example: Mon
            return $holiday;
        });
        return view('employee.holiday-calendar', compact('holidays'));
    }

    public function editLeave($id) {
        $leave = Leave::findOrFail($id);
        return view('employee.edit', compact('id', 'leave'));
    }

    public function updateLeave(Request $request, $id)
    {
        // Validate the form input
        $request->validate([
            'leave_type' => 'required|string|max:255',
            'salary_file' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_applied' => 'required|integer|min:1',
            'commutation' => 'required|boolean',
            'reason' => 'nullable|string',
            'signature' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'abroad_details' => 'nullable|string', // Ensure it's validated
        ]);
    
        // Find the leave record
        $leave = Leave::findOrFail($id);
    
        // Initialize leave details array
        $leaveDetails = [];
    
        // **Vacation Leave / Special Privilege Leave**
        if ($request->leave_type === 'Vacation Leave' || $request->leave_type === 'Special Privilege Leave') {
            if ($request->filled('within_philippines')) {
                $leaveDetails['Within the Philippines'] = $request->within_philippines; // Store the text input
            }
            if ($request->filled('abroad_details')) {
                $leaveDetails['Abroad'] = $request->abroad_details; // Store the text input
            }
        }
    
        // **Sick Leave**
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
    
        // Update leave details
        $leave->update([
            'leave_type' => $request->leave_type,
            'salary_file' => $request->salary_file,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days_applied' => $request->days_applied,
            'commutation' => $request->commutation,
            'reason' => $request->reason,
            'signature' => $request->signature,
            'leave_details' => !empty($leaveDetails) ? json_encode($leaveDetails) : null, // Save as JSON
        ]);
    
        return redirect()->back()->with('success', 'Leave request updated successfully.');
    }

    public function deleteLeave($id) {
        Leave::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Leave request deleted successfully.');
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
        $user = Auth::user();

        if ($user) {
            $notification = $user->notifications()->find($id);
            if ($notification) {
                $notification->delete();
                return response()->json(['success' => true, 'message' => 'Notification deleted.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.']);
    }

    // Delete all notifications
    public function deleteAll()
    {
        $user = Auth::user();

        if ($user) {
            $user->notifications()->delete();
            return response()->json(['success' => true, 'message' => 'All notifications deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No notifications found.']);
    }
}
