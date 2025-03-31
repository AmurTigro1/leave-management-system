<?php

namespace App\Http\Controllers;
use App\Models\Holiday;
use App\Services\YearlyHolidayService;
use App\Models\OvertimeRequest;
use App\Models\HRSupervisor;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Validation\ValidationException;
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
    
        $birthdays = User::whereMonth('birthday', $month)
        ->orderByRaw('DAY(birthday) ASC')
        ->get();
    
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
    //Original Store function:

    // public function store(Request $request, YearlyHolidayService $yearlyHolidayService)  
    // {
    //     $inclusiveLeaveTypes = [
    //         'Maternity Leave',
    //         'Study Leave',
    //         'Rehabilitation Privilege',
    //         'Special Leave Benefits for Women Leave'
    //     ];
    
    //     $request->validate([
    //         'leave_type' => 'required|string',
    //         'start_date' => [
    //             'required',
    //             'date',
    //             function ($attribute, $value, $fail) use ($request, $inclusiveLeaveTypes, $yearlyHolidayService) {
    //                 $leaveType = $request->leave_type;
    //                 $startDate = Carbon::parse($value);
    
    //                 if (!in_array($leaveType, $inclusiveLeaveTypes) && 
    //                     ($startDate->isWeekend() || $yearlyHolidayService->isHoliday($startDate))) {
    //                     $fail('The start date cannot be a weekend or holiday for this leave type.');
    //                 }
    //             }
    //         ],
    //         'end_date' => 'required|date|after_or_equal:start_date',
    //         'reason' => 'nullable|string',
    //         'signature' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    //         'days_applied' => 'required|integer|min:1',
    //         'commutation' => 'required|boolean',
    //         'leave_details' => 'nullable|array', 
    //         'abroad_details' => 'nullable|string', 
    //     ]);
    
    //     $user = Auth::user();
    
    //     $startDate = Carbon::parse($request->start_date);
    //     $endDate = Carbon::parse($request->end_date);
        
    //     if (in_array($request->leave_type, $inclusiveLeaveTypes)) {
    //         $daysApplied = $startDate->diffInDays($endDate) + 1;
    //     } else {
    //         $daysApplied = 0;
    //         $currentDate = $startDate->copy();
    //         $holidays = $yearlyHolidayService->getHolidaysBetweenDates($startDate, $endDate);
    
    //         while ($currentDate->lte($endDate)) {
    //             if (!$currentDate->isWeekend() && !in_array($currentDate->format('Y-m-d'), $holidays)) {
    //                 $daysApplied++;
    //             }
    //             $currentDate->addDay();
    //         }
    
    //         if ($daysApplied === 0) {
    //             $isValidStartDate = !$startDate->isWeekend() && 
    //                                 !$yearlyHolidayService->isHoliday($startDate);
    
    //             if ($isValidStartDate) {
    //                 $daysApplied = 1;
    //             } else {
    //                 return redirect()->back()->withErrors([
    //                     'start_date' => 'Your selected dates only include weekends/holidays which are not counted for this leave type.'
    //                 ]);
    //             }
    //         }
    //     }
    
    //     // For Mandatory Leave, check vacation leave balance
    //     $leaveTypeForBalance = $request->leave_type === 'Mandatory Leave' ? 'Vacation Leave' : $request->leave_type;
        
    //     // Calculate available balance
    //     if ($leaveTypeForBalance === 'Sick Leave') {
    //         $availableLeaveBalance = $user->sick_leave_balance;
    //     } elseif ($leaveTypeForBalance === 'Vacation Leave') {
    //         $availableLeaveBalance = $user->vacation_leave_balance;
    //     } else {
    //         $availableLeaveBalance = match ($leaveTypeForBalance) {
    //             'Maternity Leave' => $user->maternity_leave,
    //             'Paternity Leave' => $user->paternity_leave,
    //             'Solo Parent Leave' => $user->solo_parent_leave,
    //             'Study Leave' => $user->study_leave,
    //             '10-Day VAWC Leave' => $user->vawc_leave,
    //             'Rehabilitation Privilege' => $user->rehabilitation_leave,
    //             'Special Leave Benefits for Women Leave' => $user->special_leave_benefit,
    //             'Special Emergency Leave' => $user->special_emergency_leave,
    //             default => 0,
    //         };
    //     }
    
    //     // For Sick Leave and Vacation Leave, we need to check combined balance
    //     if (in_array($leaveTypeForBalance, ['Sick Leave', 'Vacation Leave'])) {
    //         $combinedBalance = $user->sick_leave_balance + $user->vacation_leave_balance;
    //         if ($daysApplied > $combinedBalance) {
    //             return redirect()->back()->withErrors(['end_date' => 'You do not have enough combined Sick and Vacation Leave balance for this request.']);
    //         }
    //     } else {
    //         if ($daysApplied > $availableLeaveBalance) {
    //             return redirect()->back()->withErrors(['end_date' => 'You do not have enough balance for ' . $request->leave_type . '.']);
    //         }
    //     }
    
    //     $leaveDetails = [];
    
    //     if ($request->leave_type === 'Vacation Leave' || $request->leave_type === 'Special Privilege Leave') {
    //         if ($request->filled('within_philippines')) {
    //             $leaveDetails['Within the Philippines'] = $request->within_philippines;
    //         }
    //         if ($request->filled('abroad_details')) {
    //             $leaveDetails['Abroad'] = $request->abroad_details;
    //         }
    //     }
    
    //     if ($request->leave_type === 'Sick Leave') {
    //         if ($request->has('in_hospital')) {
    //             $leaveDetails['In Hospital'] = $request->input('in_hospital_details', 'Yes');
    //         }
    //         if ($request->has('out_patient')) {
    //             $leaveDetails['Out Patient'] = $request->input('out_patient_details', 'Yes');
    //         }
    //     }
    
    //     // **Study Leave**
    //     if ($request->leave_type === 'Study Leave') {
    //         if ($request->has('completion_masters')) {
    //             $leaveDetails[] = 'Completion of Master\'s Degree';
    //         }   
    //         if ($request->has('bar_review')) {
    //             $leaveDetails[] = 'BAR Review';
    //         }
    //     }
    
    //     // **Other Purposes**
    //     if ($request->leave_type === 'Other Purposes') {
    //         if ($request->has('monetization')) {
    //             $leaveDetails[] = 'Monetization of Leave Credits';
    //         }
    //         if ($request->has('terminal_leave')) {
    //             $leaveDetails[] = 'Terminal Leave';
    //         }
    //     }
    
    //     // **Others Leave Type**
    //     if ($request->leave_type === 'Others') {
    //         if ($request->filled('others_details')) {
    //             $leaveDetails[] = 'Other Details';
    //             $leaveDetails[] = $request->others_details;
    //         }
    //     }
    
    //     // Store leave request with a default status of "Pending"
    //     Leave::create([
    //         'user_id' => auth()->id(),
    //         'leave_type' => $request->leave_type,
    //         'leave_details' => json_encode($leaveDetails), // Store all selected details as JSON
    //         'start_date' => $request->start_date,
    //         'end_date' => $request->end_date,
    //         'salary_file' => $request->salary_file,
    //         'days_applied' => $daysApplied,
    //         'commutation' => $request->commutation,
    //         'date_filing' => now(),
    //         'reason' => $request->reason,
    //         'signature' => $request->signature,
    //         'status' => 'pending', // Default status for new requests
    //     ]);

    
    //     notify()->success('Leave request submitted successfully! It is now pending approval.');
    //     return redirect()->back();
    // }

    //try 

    public function store(Request $request, YearlyHolidayService $yearlyHolidayService)  
{
    $leaveValidationRules = [];

    switch ($request->leave_type) {
        case 'Vacation Leave':
        case 'Special Privilege Leave':
            $leaveValidationRules = [
                'within_philippines' => 'required_without:abroad_details|string|nullable',
                'abroad_details' => 'required_without:within_philippines|string|nullable',
            ];
            break;

        case 'Sick Leave':
            $leaveValidationRules = [
                'in_hospital_details' => 'required_without:out_patient_details|string|nullable',
                'out_patient_details' => 'required_without:in_hospital_details|string|nullable',
            ];
            break;

        case 'Study Leave':
            $leaveValidationRules = [
                'completion_masters' => 'required_without:bar_review|boolean|nullable',
                'bar_review' => 'required_without:completion_masters|boolean|nullable',
            ];
            break;

        case 'Other Purposes':
            $leaveValidationRules = [
                'monetization' => 'required_without:terminal_leave|boolean|nullable',
                'terminal_leave' => 'required_without:monetization|boolean|nullable',
            ];
            break;

        case 'Others':
            $leaveValidationRules = [
                'others_details' => 'required|string|nullable'
            ];
            break;
    }

    $advanceFilingRules = [
        'Vacation Leave' => 5,
        'Special Privilege Leave' => 7,
        'Solo Parent Leave' => 5,
        'Special Leave Benefits for Women Leave' => 5,
        'Sick Leave' => 0, 
        'Maternity Leave' => 0, 
        'Paternity Leave' => 0, 
        'Mandatory Leave' => 5,
    ];

    $inclusiveLeaveTypes = [
        'Maternity Leave',
        'Study Leave',
        'Rehabilitation Privilege',
        'Special Leave Benefits for Women Leave'
    ];

    $request->validate(array_merge([
        'leave_type' => 'required|string',
        'start_date' => [
            'required',
            'date',
            function ($attribute, $value, $fail) use (
                $request, 
                $advanceFilingRules, 
                $yearlyHolidayService
            ) {
                $leaveType = $request->leave_type;
                $startDate = Carbon::parse($value);
                $today = Carbon::now();
                $advanceDaysRequired = $advanceFilingRules[$leaveType] ?? 0;

                // Check advance filing rules
                if ($advanceDaysRequired > 0) {
                    $holidays = $yearlyHolidayService->getHolidaysBetweenDates(
                        $today, 
                        $today->copy()->addDays($advanceDaysRequired * 3) // Buffer for holidays
                    );
                    $minStartDate = $today->copy()->addBusinessDays($advanceDaysRequired, $holidays);
                
                    if ($startDate->lt($minStartDate)) {
                        $fail("You must request {$leaveType} at least {$advanceDaysRequired} business days in advance (excluding weekends/holidays).");
                    }
                }

                if (!in_array($leaveType, ['Sick Leave', 'Maternity Leave', 'Paternity Leave']) && 
                    ($startDate->isWeekend() || $yearlyHolidayService->isHoliday($startDate))) {
                 $fail("You must request {$leaveType} at least {$advanceDaysRequired} business days in advance (excluding weekends/holidays).");
                }
            }
        ],
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'nullable|string',
        'signature' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'leave_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Multiple files
        'days_applied' => 'required|integer|min:1',
        'commutation' => 'required|boolean',
        'leave_details' => 'nullable|array', 
        'abroad_details' => 'nullable|string',
    ], $leaveValidationRules));

    $user = Auth::user();
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    // Define required documents with conditions
    $requiredDocs = [
        'Sick Leave' => 'Medical Certificate (if filed in advance or > 5 days)',
        'Maternity Leave' => 'Proof of Pregnancy (Ultrasound, Doctor’s Certificate)',
        'Paternity Leave' => 'Proof of Child Delivery (Birth Certificate, Medical Certificate, Marriage Contract)'
    ];

    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);
    $today = Carbon::now();

    // Calculate the difference in days between filing and leave start
    $daysUntilLeave = $today->diffInDays($startDate, false);
    $daysRequested = $startDate->diffInDays($endDate) + 1;

    // Document upload conditions
    $requiresDocs = false;

    if ($request->leave_type === 'Sick Leave') {
        // Sick Leave: Only require docs if filed in advance OR exceeds 5 days
        if ($daysUntilLeave > 0 || $daysRequested > 5) {
            $requiresDocs = true;
        }
    } elseif (in_array($request->leave_type, ['Maternity Leave', 'Paternity Leave'])) {
        // Always require docs for Maternity and Paternity Leave
        $requiresDocs = true;
    }

    // Validate document upload only when necessary
    if ($requiresDocs && !$request->hasFile('leave_files')) {
        return redirect()->back()->withErrors([
            'leave_files' => "For {$request->leave_type}, please upload the required documents: " . $requiredDocs[$request->leave_type]
        ]);
    }

    // Calculate days applied
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
            $isValidStartDate = !$startDate->isWeekend() && !$yearlyHolidayService->isHoliday($startDate);

            if ($isValidStartDate) {
                $daysApplied = 1;
            } else {
                return redirect()->back()->withErrors([
                    'start_date' => 'Your selected dates only include weekends/holidays which are not counted for this leave type.'
                ]);
            }
        }
    }

    // Handle available leave balance
    $leaveTypeForBalance = $request->leave_type === 'Mandatory Leave' ? 'Vacation Leave' : $request->leave_type;

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

    // Check combined balance for Sick + Vacation Leave
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

    // Handle file uploads
    $leaveFiles = [];
    if ($request->hasFile('leave_files')) {
        foreach ($request->file('leave_files') as $file) {
            $path = $file->store('leave_files', 'public');
            $leaveFiles[] = $path;
        }
    }

    // Prepare leave details
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

    // Store leave request
    Leave::create([
        'user_id' => auth()->id(),
        'leave_type' => $request->leave_type,
        'leave_details' => json_encode($leaveDetails),
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'salary_file' => $request->salary_file,
        'days_applied' => $daysApplied,
        'commutation' => $request->commutation,
        'date_filing' => now(),
        'reason' => $request->reason,
        'signature' => $request->signature,
        'leave_files' => json_encode($leaveFiles),
        'status' => 'pending',
    ]);

    notify()->success('Leave request submitted successfully! It is now pending approval.');
    return redirect()->back();
}


public function cancel($id)
{
    $leave = Leave::findOrFail($id);
    $user = Auth::user();

    // ✅ Only restore balance if both status and hr_status are approved
    if ($leave->status === 'approved' && $leave->hr_status === 'approved') {
        $this->restoreLeaveBalance($user, $leave);
    }

    $leave->status = 'cancelled';
    $leave->save();

    return redirect()->back()->with('success', 'Leave request has been cancelled and balance restored.');
}


// Restore Leave
public function restore($id)
{
    $leave = Leave::findOrFail($id);
    $user = Auth::user();

    // ✅ Only deduct balance if the leave was previously approved by HR
    if ($leave->status === 'cancelled' && $leave->hr_status === 'approved') {
        $this->deductLeaveBalance($user, $leave);
        $leave->status = 'approved';
    } else {
        $leave->status = 'pending';
    }

    $leave->save();

    return redirect()->back()->with('success', 'Leave request has been restored and balance deducted.');
}



// Restore Leave Balance
private function restoreLeaveBalance($user, $leave)
{
    $days = $leave->days_applied;

    switch ($leave->leave_type) {
        // ✅ Restore to Vacation Leave for both Vacation and Mandatory Leave
        case 'Vacation Leave':
        case 'Mandatory Leave':  // 🔥 Treat Mandatory Leave as Vacation Leave
            $user->vacation_leave_balance += $days;
            break;

        case 'Sick Leave':
            $user->sick_leave_balance += $days;
            break;

        case 'Maternity Leave':
            $user->maternity_leave += $days;
            break;

        case 'Paternity Leave':
            $user->paternity_leave += $days;
            break;

        case 'Solo Parent Leave':
            $user->solo_parent_leave += $days;
            break;

        case 'Study Leave':
            $user->study_leave += $days;
            break;

        case 'VAWC Leave':
            $user->vawc_leave += $days;
            break;

        case 'Rehabilitation Leave':
            $user->rehabilitation_leave += $days;
            break;

        case 'Special Leave Benefit':
            $user->special_leave_benefit += $days;
            break;

        case 'Special Emergency Leave':
            $user->special_emergency_leave += $days;
            break;
    }

    $user->save();
}


// Deduct Leave Balance
private function deductLeaveBalance($user, $leave)
{
    $days = $leave->days_applied;

    switch ($leave->leave_type) {
        // ✅ Vacation Leave + Mandatory Leave (share the same balance)
        case 'Vacation Leave':
        case 'Mandatory Leave':
            if ($user->vacation_leave_balance >= $days) {
                // ✅ Deduct from vacation leave if sufficient
                $user->vacation_leave_balance -= $days;
            } elseif (($user->vacation_leave_balance + $user->sick_leave_balance) >= $days) {
                // ✅ Combine Vacation + Sick Leave if insufficient
                $combinedBalance = $user->vacation_leave_balance + $user->sick_leave_balance;

                if ($combinedBalance >= $days) {
                    $remainingDays = $days;

                    // Deduct from vacation first
                    if ($user->vacation_leave_balance > 0) {
                        $deductFromVacation = min($remainingDays, $user->vacation_leave_balance);
                        $user->vacation_leave_balance -= $deductFromVacation;
                        $remainingDays -= $deductFromVacation;
                    }

                    // Deduct remaining from sick leave
                    if ($remainingDays > 0) {
                        $user->sick_leave_balance -= $remainingDays;
                    }
                }
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient combined Sick and Vacation Leave balance.']);
            }
            break;

        // ✅ Sick Leave (combines with Vacation Leave if insufficient)
        case 'Sick Leave':
            if ($user->sick_leave_balance >= $days) {
                // ✅ Deduct from sick leave if sufficient
                $user->sick_leave_balance -= $days;
            } elseif (($user->sick_leave_balance + $user->vacation_leave_balance) >= $days) {
                // ✅ Combine Sick + Vacation Leave if insufficient
                $combinedBalance = $user->sick_leave_balance + $user->vacation_leave_balance;

                if ($combinedBalance >= $days) {
                    $remainingDays = $days;

                    // Deduct from sick leave first
                    if ($user->sick_leave_balance > 0) {
                        $deductFromSick = min($remainingDays, $user->sick_leave_balance);
                        $user->sick_leave_balance -= $deductFromSick;
                        $remainingDays -= $deductFromSick;
                    }

                    // Deduct remaining from vacation leave
                    if ($remainingDays > 0) {
                        $user->vacation_leave_balance -= $remainingDays;
                    }
                }
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient combined Sick and Vacation Leave balance.']);
            }
            break;

        // ✅ Maternity Leave
        case 'Maternity Leave':
            if ($user->maternity_leave >= $days) {
                $user->maternity_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Maternity Leave balance.']);
            }
            break;

        // ✅ Paternity Leave
        case 'Paternity Leave':
            if ($user->paternity_leave >= $days) {
                $user->paternity_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Paternity Leave balance.']);
            }
            break;

        // ✅ Solo Parent Leave
        case 'Solo Parent Leave':
            if ($user->solo_parent_leave >= $days) {
                $user->solo_parent_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Solo Parent Leave balance.']);
            }
            break;

        // ✅ Study Leave
        case 'Study Leave':
            if ($user->study_leave >= $days) {
                $user->study_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Study Leave balance.']);
            }
            break;

        // ✅ VAWC Leave
        case 'VAWC Leave':
            if ($user->vawc_leave >= $days) {
                $user->vawc_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient VAWC Leave balance.']);
            }
            break;

        // ✅ Rehabilitation Leave
        case 'Rehabilitation Leave':
            if ($user->rehabilitation_leave >= $days) {
                $user->rehabilitation_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Rehabilitation Leave balance.']);
            }
            break;

        // ✅ Special Leave Benefit
        case 'Special Leave Benefit':
            if ($user->special_leave_benefit >= $days) {
                $user->special_leave_benefit -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Special Leave Benefit balance.']);
            }
            break;

        // ✅ Special Emergency Leave
        case 'Special Emergency Leave':
            if ($user->special_emergency_leave >= $days) {
                $user->special_emergency_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Special Emergency Leave balance.']);
            }
            break;
    }

    $user->save();
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

    public function calendar() {
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
