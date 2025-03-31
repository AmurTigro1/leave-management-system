<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\HRSupervisor;
use App\Models\Leave;
use App\Models\OvertimeRequest;
use App\Models\YearlyHoliday;
use App\Services\YearlyHolidayService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EmailUpdateRequest;
use App\Services\HolidayService;
use App\Notifications\LeaveStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use App\Models\Holiday;
use App\Models\CocLog;

class HrController extends Controller
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
            return view('hr.partials.employee-list', compact('employees'))->render();
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
    
        return view('hr.dashboard', compact('employees', 'pendingLeaves', 'totalEmployees', 'leaveStats', 'cocStats', 'search'));
    }
    
    
    public function onLeave(Request $request) {
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
        return view('hr.on_leave', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
    }

    public function makeLeaveRequest()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('hr.make_leave_request', compact('leaves'));
    }

    public function storeLeave(Request $request, YearlyHolidayService $yearlyHolidayService)  
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

    public function makeCTORequest()
    {
        $overtimereq = OvertimeRequest::where('user_id', auth()->id())->get();

        $appliedDates = OvertimeRequest::where('user_id', auth()->id())
                    ->get('inclusive_dates');
        $holidays = Holiday::select('date')->get();
        
        return view('hr.make_cto_request', compact('overtimereq', 'appliedDates', 'holidays'));
    }

    public function storeCTO(Request $request)
    {
        $request->validate([
            'inclusive_dates' => 'required|string',
            'working_hours_applied' => 'required|integer|min:4',
        ]);

        // Convert the comma-separated dates string to an array
        $datesArray = explode(', ', $request->inclusive_dates);
        
        // Optionally, you might want to validate each date
        foreach ($datesArray as $date) {
            if (!strtotime($date)) {
                return back()->withErrors(['inclusive_dates' => 'Invalid date format detected']);
            }
        }

        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'working_hours_applied' => $request->working_hours_applied,
            'inclusive_dates' => $request->inclusive_dates, // Store as comma-separated string
            'admin_status' => 'pending', // Goes to admin first
            'hr_status' => 'pending', // HR reviews only after admin approval
        ]);

        notify()->success('Overtime request submitted successfully! Pending admin review.');
        return redirect()->back();
    }

    public function requests()
    {
        if (Auth::user()->role !== 'hr') {
            abort(403, 'Unauthorized access.');
        }
    
        // Get leave applications waiting for supervisor approval
        $leaveApplications = Leave::where('admin_status', 'approved')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        $ctoApplications = OvertimeRequest::where('admin_status', 'Ready for Review')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        return view('hr.requests', compact('leaveApplications', 'ctoApplications'));
    }
    
    
    public function showLeaveCertification($leaveId)
    {
        $leave = Leave::findOrFail($leaveId);
        $daysRequested = Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;

        return view('hr.leave_certification', compact('leave', 'daysRequested'));
    }

    public function showleave($id) {
        $leave = Leave::findOrFail($id); 
        $official = HRSupervisor::find($id);

        return view('hr.leave_details', compact('leave','official'));
    }

    public function showcto($id) {
        $cto = OvertimeRequest::findOrFail($id); 

        return view('hr.cto_details', compact('cto'));
    }

    // HR officer reviews applications
    public function review(Request $request, $leaveId) 
    {
        $leave = Leave::findOrFail($leaveId);
        $user = $leave->user;
    
        // Validate the request
        $request->validate([
            'status' => 'required|in:Approved,Rejected',
            'disapproval_reason' => 'nullable|string',
        ]);
    
        // Convert status to lowercase
        $hr_status = strtolower($request->status);
    
        // Prepare the update data
        $updateData = [
            'hr_status' => $hr_status,
            'status' => $hr_status === 'rejected' ? 'rejected' : $leave->status,
            'disapproval_reason' => $request->disapproval_reason,
            'hr_officer_id' => auth()->id(),
        ];
    
        // If approved, apply leave deductions
        if ($hr_status === 'approved') {
    
            // Handle balance deductions
            $leaveTypeForDeduction = $leave->leave_type === 'Mandatory Leave' ? 'Vacation Leave' : $leave->leave_type;
    
            switch ($leaveTypeForDeduction) {
                case 'Sick Leave':
                    if ($user->sick_leave_balance >= $leave->days_applied) {
                        $user->sick_leave_balance -= $leave->days_applied;
                    } else {
                        $remainingDays = $leave->days_applied - $user->sick_leave_balance;
                        $user->sick_leave_balance = 0;
                        $user->vacation_leave_balance -= $remainingDays;
                    }
                    break;
    
                case 'Vacation Leave':
                    if ($user->vacation_leave_balance >= $leave->days_applied) {
                        $user->vacation_leave_balance -= $leave->days_applied;
                    } else {
                        $remainingDays = $leave->days_applied - $user->vacation_leave_balance;
                        $user->vacation_leave_balance = 0;
                        $user->sick_leave_balance -= $remainingDays;
                    }
                    break;
    
                case 'Maternity Leave':
                    $user->maternity_leave -= $leave->days_applied;
                    break;
    
                case 'Paternity Leave':
                    $user->paternity_leave -= $leave->days_applied;
                    break;
    
                case 'Solo Parent Leave':
                    $user->solo_parent_leave -= $leave->days_applied;
                    break;
    
                case 'Study Leave':
                    $user->study_leave -= $leave->days_applied;
                    break;
    
                case '10-Day VAWC Leave':
                    $user->vawc_leave -= $leave->days_applied;
                    break;
    
                case 'Rehabilitation Privilege':
                    $user->rehabilitation_leave -= $leave->days_applied;
                    break;
    
                case 'Special Leave Benefits for Women Leave':
                    $user->special_leave_benefit -= $leave->days_applied;
                    break;
    
                case 'Special Emergency Leave':
                    $user->special_emergency_leave -= $leave->days_applied;
                    break;
    
                default:
                    // No deduction for other leave types
                    break;
            }
    
            // Update supervisor fields
            $updateData['supervisor_status'] = 'approved';
            $updateData['supervisor_id'] = auth()->id();
            $updateData['status'] = 'approved';
        }
    
        // Save changes
        $leave->update($updateData);
        $user->save();
    
         // ✅ Send the notification with the correct Leave model
        $user->notify(new LeaveStatusNotification($leave, "Your leave request has been approved by the HR."));
        notify()->success('Leave application reviewed successfully!');
        return redirect()->route('hr.requests');
    }   

    public function ctoreview(Request $request, OvertimeRequest $cto)
    {
        $request->validate([
            'hr_status' => 'required|in:Approved,Rejected', 
            'disapproval_reason' => 'nullable|string',
        ]);

        $hr_status = strtolower($request->hr_status); 

        if ($hr_status === 'approved') {
            $supervisor_status = 'approved';
            $status = 'approved'; 

            $user = $cto->user; 
            $remainingHours = $cto->working_hours_applied; 

            if ($user && $user->overtime_balance >= $remainingHours) {

                $cocLogs = CocLog::where('user_id', $user->id)
                    ->where('is_expired', false)
                    ->orderBy('created_at', 'asc') 
                    ->get();

                foreach ($cocLogs as $cocLog) {
                    if ($remainingHours <= 0) {
                        break; 
                    }

                    if ($cocLog->coc_earned <= $remainingHours) {
                        $remainingHours -= $cocLog->coc_earned;
                        $cocLog->update(['is_expired' => true]);
                    } else {
                        
                        $cocLog->decrement('coc_earned', $remainingHours);
                        $remainingHours = 0;
                    }
                }

                $user->decrement('overtime_balance', $cto->working_hours_applied);
            } else {
                return back()->withErrors(['overtime_balance' => 'User does not have enough overtime balance.']);
            }
        } else {
            $supervisor_status = $cto->supervisor_status; 
            $status = 'rejected'; 
        }

        $cto->update([
            'hr_status' => $hr_status,
            'supervisor_status' => $supervisor_status,
            'status' => $status,
            'disapproval_reason' => $request->disapproval_reason,
            'hr_officer_id' => Auth::id(),
        ]);

        $cto->user->notify(new LeaveStatusNotification($cto, "Your overtime request has been approved by the HR."));
        notify()->success('CTO application reviewed by HR.');
        return Redirect::route('hr.requests');
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

    public function showOvertime($id) {
        $overtimeRequests = OvertimeRequest::findOrFail($id); 

        return view('hr.CTO.show_overtime_request', compact('overtimeRequests'));
    }

    public function profile() {
        $user = Auth::user();
    
        return view('hr.profile.index', [
            'user' => $user,
        ]);
    }
    
    public function profile_edit(Request $request): View
    {
        return view('hr.profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }
    public function password_edit(Request $request): View
    {
        return view('hr.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        notify()->success('Profile Updated Successfully!');

        return Redirect::route('hr.profile.partials.update-profile-information-form')->with('status', 'profile-updated');
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

        return Redirect::route('hr.profile.partials.update-profile-information-form')->with('status', 'email-updated');
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
        return view('hr.leaderboard', compact('employees'));
    }

    public function calendar(Request $request)
    {
        $selectedYear = (int) $request->input('year', date('Y'));  // Cast year to integer
    
        // Get holidays for the selected year
        $holidays = YearlyHoliday::whereYear('date', $selectedYear)
            ->orWhere('repeats_annually', true)
            ->orderBy('date')
            ->get()
            ->map(function ($holiday) use ($selectedYear) {
                // Ensure repeating holidays use the selected year
                if ($holiday->repeats_annually) {
                    $date = Carbon::parse($holiday->date);
    
                    // Force cast to integer to avoid the Carbon::setUnit() error
                    $holiday->date = Carbon::create((int) $selectedYear, (int) $date->month, (int) $date->day)->format('Y-m-d');
                }
                return $holiday;
            });
    
        // Group holidays by month
        $groupedHolidays = $holidays->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('F Y');
        });
    
        // Prepare data for calendar view
        $calendarData = $this->prepareCalendarData($holidays, $selectedYear);
    
        // Get available years for dropdown
        $availableYears = $this->getAvailableYears();
    
        return view('hr.holiday-calendar', compact(
            'groupedHolidays',
            'calendarData',
            'selectedYear',
            'availableYears'
        ));
    }
    
    protected function prepareCalendarData($holidays, $year)
{
    $months = [];

    for ($month = 1; $month <= 12; $month++) {
        // Cast year and month to integers to avoid Carbon errors
        $year = (int) $year;
        $month = (int) $month;

        $date = Carbon::create($year, $month, 1);
        $daysInMonth = $date->daysInMonth;

        $monthData = [
            'name' => $date->format('F'),
            'year' => $year,
            'days' => []
        ];

        // Filter holidays for this month
        $monthHolidays = $holidays->filter(function ($holiday) use ($month) {
            return (int) Carbon::parse($holiday->date)->month === $month;
        });

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($year, $month, $day);

            $dayHolidays = $monthHolidays->filter(function ($holiday) use ($day) {
                return (int) Carbon::parse($holiday->date)->day === $day;
            });

            $monthData['days'][$day] = [
                'date' => $currentDate,
                'holidays' => $dayHolidays,
                'isWeekend' => $currentDate->isWeekend()
            ];
        }

        $months[$month] = $monthData;
    }

    return $months;
}

    
    protected function getAvailableYears()
    {
        $minYear = YearlyHoliday::min('date') 
            ? \Carbon\Carbon::parse(YearlyHoliday::min('date'))->year 
            : date('Y');
            
        $maxYear = YearlyHoliday::max('date') 
            ? \Carbon\Carbon::parse(YearlyHoliday::max('date'))->year 
            : date('Y');
            
        $years = range($minYear, $maxYear + 1); // Include next year
        return array_combine($years, $years);
    }
    public function holiday()
    {
        $holidays = YearlyHoliday::orderBy('date')->get();
        return view('hr.holidays.index', compact('holidays'));
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

    public function ctoviewPdf($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.overtime_details', compact('overtime', 'supervisor', 'hr'));
        
        return $pdf->stream('overtime_request_' . $overtime->id . '.pdf');
    }
    /**
     * Show the form for creating a new holiday.
     */
    public function create()
    {
        return view('hr.holidays.create');
    }

    /**
     * Store a newly created holiday in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:regular,special,national',
            'repeats_annually' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        YearlyHoliday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'repeats_annually' => $request->boolean('repeats_annually'),
        ]);

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    /**
     * Show the form for editing the specified holiday.
     */
    public function edit(YearlyHoliday $holiday)
    {
        return view('hr.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified holiday in storage.
     */
    public function update(Request $request, YearlyHoliday $holiday)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:regular,special,national',
            'repeats_annually' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'repeats_annually' => $request->boolean('repeats_annually'),
        ]);

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified holiday from storage.
     */
    public function destroy(YearlyHoliday $holiday)
    {
        $holiday->delete();

        return redirect()->route('hr.holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }
    public function approve($id, Request $request)
    {
        $request = OvertimeRequest::findOrFail($id);

        if ($request->admin_status !== 'Ready for Review') {
            notify()->error('Cannot approve. Admin review is required first.');
            return redirect()->back();
        }

        if ($request->hr_status === 'pending') {
            $request->update(['hr_status' => 'approved']);
            notify()->success('CTO approved by HR.');
        } else {
            notify()->error('This request has already been processed by HR.');
        }

        return redirect()->back();
    }

    public function reject($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);
        $overtime->update(['hr_status' => 'rejected']);

        notify()->success('Overtime request rejected successfully.');
        return redirect()->back();
    }

    public function cocLogs()
    {
        $logs = CompensatoryTimeLog::orderBy('created_at', 'desc')->paginate(10);
        return view('hr.CTO.coclog', compact('logs'));
    }

    public function users()
    {
        $users = User::orderBy('last_name', 'asc')->paginate(10);
        return view('hr.users', compact('users'));
    }
    
}
