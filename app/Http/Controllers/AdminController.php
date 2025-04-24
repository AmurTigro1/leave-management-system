<?php

namespace App\Http\Controllers;

use App\Models\HRSupervisor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Services\YearlyHolidayService;
use App\Models\CocLog;
use App\Models\Leave;
use App\Models\YearlyHoliday;
use App\Models\OvertimeRequest;
use App\Models\VisitorLog;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\EmailUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
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
    
        if ($request->ajax()) {
            return view('admin.partials.employee-list', compact('employees'))->render();
        }
    
    
        $pendingLeaves = Leave::where('status', 'pending')->get();
    
        $totalEmployees = User::count();
        $totalPendingLeaves = Leave::where('admin_status', 'pending')->count();
        $totalApprovedLeaves = Leave::where('admin_status', 'approved')->count();
        $totalRejectedLeaves = Leave::where('admin_status', 'rejected')->count();
        $totalApprovedOvertime = OvertimeRequest::where('status', 'approved')->count();
        $totalPendingOvertime = OvertimeRequest::where('status', 'pending')->count();
        $totalRejectedOvertime = OvertimeRequest::where('status', 'rejected')->count();
    
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

        $selectedYear = $request->input('year', now()->year);

        $rawData = VisitorLog::selectRaw('COUNT(*) as count, MONTH(visited_at) as month')
        ->whereYear('visited_at', $selectedYear)
        ->groupBy('month')
        ->pluck('count', 'month');

        // Create full 12 months so that empty months still show as 0
        $months = collect(range(1, 12))->map(function ($month) {
            return \Carbon\Carbon::create()->month($month)->format('F');
        });

        $visitorCounts = $months->map(function ($monthName, $index) use ($rawData) {
            return $rawData->get($index + 1, 0); // +1 because Carbon months start at 1
        });
    
        return view('admin.dashboard', compact('employees', 'pendingLeaves', 'totalEmployees', 'leaveStats', 'cocStats', 'search', 'months', 'visitorCounts', 'selectedYear'));
    }

    public function makeLeaveRequest()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        $gender = Auth::user()->gender;
        return view('admin.make_leave_request', compact('leaves', 'gender'));
    }

    public function storeLeave(Request $request, YearlyHolidayService $yearlyHolidayService)  
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
        'Mandatory Leave' => 0,
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
            function ($attribute, $value, $fail) use ($request, $advanceFilingRules) {
                $leaveType = $request->leave_type;
                $startDate = Carbon::parse($value);
                $today = Carbon::now();
                $advanceDaysRequired = $advanceFilingRules[$leaveType] ?? 0;
    
                if ($advanceDaysRequired > 0) {
                    $minStartDate = $today->copy()->addDays($advanceDaysRequired);
                    
                    if ($startDate->lt($minStartDate)) {
                        $fail("You must request {$leaveType} at least {$advanceDaysRequired} days in advance.");
                    }
                }
            }
        ],
        'end_date' => 'required|date|after_or_equal:start_date',
        'reason' => 'nullable|string',
        'leave_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Multiple files
        'days_applied' => 'required|integer|min:1',
        'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'commutation' => 'required|boolean',
        'leave_details' => 'nullable|array', 
        'abroad_details' => 'nullable|string',
    ], $leaveValidationRules));

    $user = Auth::user();
    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    $requiredDocs = [
        'Maternity Leave' => 'Proof of Pregnancy (Ultrasound, Doctor’s Certificate)',
        'Paternity Leave' => 'Proof of Child Delivery (Birth Certificate, Medical Certificate, Marriage Contract)'
    ];
    
    $requiresDocs = in_array($request->leave_type, ['Maternity Leave', 'Paternity Leave']);
    
    if ($requiresDocs && !$request->hasFile('leave_files')) {
        return redirect()->back()->withErrors([
            'leave_files' => "For {$request->leave_type}, please upload the required documents: " . $requiredDocs[$request->leave_type]
        ]);
    }
    
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

    $leaveTypeForBalance = $request->leave_type === 'Mandatory Leave' ? 'Vacation Leave' : $request->leave_type;

    if ($leaveTypeForBalance === 'Sick Leave') {
        $availableLeaveBalance = $user->sick_leave_balance;
    } elseif ($leaveTypeForBalance === 'Vacation Leave') {
        $availableLeaveBalance = $user->vacation_leave_balance;
    } else {
        $availableLeaveBalance = match ($leaveTypeForBalance) {
            'Special Privilege Leave' => $user->special_privilege_leave,
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

    $leaveFiles = [];
    if ($request->hasFile('leave_files')) {
        foreach ($request->file('leave_files') as $file) {
            $path = $file->store('leave_files', 'public');
            $leaveFiles[] = $path;
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

    if ($request->leave_type === 'Study Leave') {
        if ($request->has('completion_masters')) {
            $leaveDetails[] = 'Completion of Master\'s Degree';
        }   
        if ($request->has('bar_review')) {
            $leaveDetails[] = 'BAR Review';
        }
    }

    if ($request->leave_type === 'Other Purposes') {
        if ($request->has('monetization')) {
            $leaveDetails[] = 'Monetization of Leave Credits';
        }
        if ($request->has('terminal_leave')) {
            $leaveDetails[] = 'Terminal Leave';
        }
    }

    if ($request->leave_type === 'Others') {
        if ($request->filled('others_details')) {
            $leaveDetails[] = 'Other Details';
            $leaveDetails[] = $request->others_details;
        }
    }

    $signaturePath = null;
    if ($request->hasFile('signature')) {
        $signatureFile = $request->file('signature');
        $filename = time() . '_' . $signatureFile->getClientOriginalName();
        $signatureFile->move(public_path('signatures'), $filename);
        $signaturePath = 'signatures/' . $filename;
    }

    Leave::create([
        'user_id' => auth()->id(),
        'leave_type' => $request->leave_type,
        'leave_details' => json_encode($leaveDetails),
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'vacation_balance_before' => auth()->user()->vacation_leave_balance,
        'sick_balance_before' => auth()->user()->sick_leave_balance,     
        'salary_file' => $request->salary_file,
        'days_applied' => $daysApplied,
        'commutation' => $request->commutation,
        'date_filing' => now(),
        'reason' => $request->reason,
        'signature' => $signaturePath,
        'leave_files' => json_encode($leaveFiles),
        'status' => 'pending',
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
        
        return view('admin.make_cto_request', compact('overtimereq', 'appliedDates', 'holidays'));
    }

    public function storeCTO(Request $request)
    {
        $user = auth()->user();
        $overtimeBalance = $user->overtime_balance;

        $ctoHoursMap = [
            'halfday_morning' => 4,
            'halfday_afternoon' => 4,
            'wholeday' => 8,
        ];

        if ($request->cto_type !== 'none') {
            $request->merge(['working_hours_applied' => $ctoHoursMap[$request->cto_type]]);
        }

        $request->validate([
            'inclusive_dates' => 'required|string',
            'cto_type' => 'nullable|in:none,halfday_morning,halfday_afternoon,wholeday',
            'signature' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'working_hours_applied' => [
                'required_without:cto_type',
                'integer',
                'min:4',
                'multiple_of:4',
                function ($attribute, $value, $fail) {
                    if ($value % 4 !== 0) {
                        $fail("The $attribute must be a multiple of 4.");
                    }
                },
                function ($attribute, $value, $fail) use ($overtimeBalance) {
                    if ($value > $overtimeBalance) {
                        $fail("You cannot apply more than your available COC balance.");
                    }
                }
            ],
        ]);

        $datesArray = explode(', ', $request->inclusive_dates);
        foreach ($datesArray as $date) {
            if (!strtotime($date)) {
                return back()->withErrors(['inclusive_dates' => 'Invalid date format detected']);
            }
        }

        $signaturePath = null;
        if ($request->hasFile('signature')) {
            $signatureFile = $request->file('signature');
            $filename = time() . '_' . $signatureFile->getClientOriginalName();
            $signatureFile->move(public_path('signatures'), $filename);
            $signaturePath = 'signatures/' . $filename;
        }

        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'working_hours_applied' => $request->working_hours_applied,
            'signature' => $signaturePath,
            'inclusive_dates' => $request->inclusive_dates,
            'admin_status' => 'pending', 
            'hr_status' => 'pending', 
        ]);
        
        $user->decrement('overtime_balance', $request->working_hours_applied);

        notify()->success('Overtime request submitted successfully! Pending admin review.');
        return redirect()->back();
    }

    public function requests()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
    
        $leaveApplications = Leave::where('admin_status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        $ctoApplications = OvertimeRequest::where('admin_status', 'pending')
        ->orderBy('created_at', 'desc') 
        ->paginate(9); 

        return view('admin.requests', compact('leaveApplications', 'ctoApplications'));
    }

    public function myRequests() {
        $user = auth()->user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your reservations.');
        }
    
        $leaves = $user->leaves()->orderBy('created_at', 'desc')->paginate(10); 
    
        return view('admin.my_requests', compact('leaves',));
    }

    public function show($id) {
        $leave = Leave::findOrFail($id); 
    
        return view('admin.leave_show', compact('leave'));
    }

    public function viewPdf($id)
    {
        $leave = Leave::findOrFail($id);
        $officials = HRSupervisor::all();

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.leave_details', compact('leave', 'supervisor', 'hr', 'officials'));
        
        return $pdf->stream('leave_request_' . $leave->id . '.pdf');
    }

    public function viewCtoPdf($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);
        $earned = CocLog::findOrFail($id);

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.overtime_details', compact('overtime', 'supervisor', 'hr', 'earned'));
        
        return $pdf->stream('overtime_request_' . $overtime->id . '.pdf');
    }

    public function cancel($id)
{
    $leave = Leave::findOrFail($id);
    $user = Auth::user();

    if ($leave->status === 'approved' && $leave->hr_status === 'approved') {
        $this->restoreLeaveBalance($user, $leave);
    }

    $leave->status = 'cancelled';
    $leave->save();

    return redirect()->back()->with('success', 'Leave request has been cancelled and balance restored.');
}


public function restore($id)
{
    $leave = Leave::findOrFail($id);
    $user = Auth::user();

    if ($leave->status === 'cancelled' && $leave->hr_status === 'approved') {
        $this->deductLeaveBalance($user, $leave);
        $leave->status = 'approved';
    } else {
        $leave->status = 'pending';
    }

    $leave->save();

    return redirect()->back()->with('success', 'Leave request has been restored and balance deducted.');
}

private function restoreLeaveBalance($user, $leave)
{
    $days = $leave->days_applied;

    switch ($leave->leave_type) {
        case 'Vacation Leave':
            $user->vacation_leave_balance += $days;
            break;

        case 'Mandatory Leave': 
            $user->vacation_leave_balance += $days;
            $user->mandatory_leave_balance += $days;
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

private function deductLeaveBalance($user, $leave)
{
    $days = $leave->days_applied;

    switch ($leave->leave_type) {
        case 'Vacation Leave':
            
            if ($user->vacation_leave_balance >= $days) {
                $user->vacation_leave_balance -= $days;
            } elseif (($user->vacation_leave_balance + $user->sick_leave_balance) >= $days) {
                $remainingDays = $days;

                if ($user->vacation_leave_balance > 0) {
                    $deductFromVacation = min($remainingDays, $user->vacation_leave_balance);
                    $user->vacation_leave_balance -= $deductFromVacation;
                    $remainingDays -= $deductFromVacation;
                }

                if ($remainingDays > 0) {
                    $user->sick_leave_balance -= $remainingDays;
                }
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient combined Sick and Vacation Leave balance.']);
            }
            break;

        case 'Mandatory Leave':
         
            if ($user->vacation_leave_balance >= $days) {
                $user->vacation_leave_balance -= $days;
                $user->mandatory_leave_balance -= $days;
            } elseif (($user->vacation_leave_balance + $user->sick_leave_balance) >= $days) {
                $remainingDays = $days;

                if ($user->vacation_leave_balance > 0) {
                    $deductFromVacation = min($remainingDays, $user->vacation_leave_balance);
                    $user->vacation_leave_balance -= $deductFromVacation;
                    $remainingDays -= $deductFromVacation;
                }

                if ($remainingDays > 0) {
                    $user->sick_leave_balance -= $remainingDays;
                }

              
                $user->mandatory_leave_balance -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient combined Sick and Vacation Leave balance.']);
            }
            break;

        case 'Sick Leave':
            if ($user->sick_leave_balance >= $days) {
                $user->sick_leave_balance -= $days;
            } elseif (($user->sick_leave_balance + $user->vacation_leave_balance) >= $days) {
                $combinedBalance = $user->sick_leave_balance + $user->vacation_leave_balance;

                if ($combinedBalance >= $days) {
                    $remainingDays = $days;

                    if ($user->sick_leave_balance > 0) {
                        $deductFromSick = min($remainingDays, $user->sick_leave_balance);
                        $user->sick_leave_balance -= $deductFromSick;
                        $remainingDays -= $deductFromSick;
                    }

                    if ($remainingDays > 0) {
                        $user->vacation_leave_balance -= $remainingDays;
                    }
                }
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient combined Sick and Vacation Leave balance.']);
            }
            break;

        case 'Maternity Leave':
            if ($user->maternity_leave >= $days) {
                $user->maternity_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Maternity Leave balance.']);
            }
            break;

        case 'Paternity Leave':
            if ($user->paternity_leave >= $days) {
                $user->paternity_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Paternity Leave balance.']);
            }
            break;

        case 'Solo Parent Leave':
            if ($user->solo_parent_leave >= $days) {
                $user->solo_parent_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Solo Parent Leave balance.']);
            }
            break;

        case 'Study Leave':
            if ($user->study_leave >= $days) {
                $user->study_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Study Leave balance.']);
            }
            break;

        case 'VAWC Leave':
            if ($user->vawc_leave >= $days) {
                $user->vawc_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient VAWC Leave balance.']);
            }
            break;

        case 'Rehabilitation Leave':
            if ($user->rehabilitation_leave >= $days) {
                $user->rehabilitation_leave -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Rehabilitation Leave balance.']);
            }
            break;

        case 'Special Leave Benefit':
            if ($user->special_leave_benefit >= $days) {
                $user->special_leave_benefit -= $days;
            } else {
                throw ValidationException::withMessages(['error' => 'Insufficient Special Leave Benefit balance.']);
            }
            break;

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

public function editLeave($id) {
    $leave = Leave::findOrFail($id);
    return view('admin.edit', compact('id', 'leave'));
}

public function updateLeave(Request $request, $id, YearlyHolidayService $yearlyHolidayService)
{
$leave = Leave::findOrFail($id);

$today = Carbon::now();

$startDate = Carbon::parse($request->start_date);
$endDate = Carbon::parse($request->end_date);
$days_applied = $startDate->diffInDays($endDate) + 1;

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
    'Mandatory Leave' => 0,
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
        function ($attribute, $value, $fail) use ($request, $advanceFilingRules) {
            $leaveType = $request->leave_type;
            $startDate = Carbon::parse($value);
            $today = Carbon::now();
            $advanceDaysRequired = $advanceFilingRules[$leaveType] ?? 0;

            if ($advanceDaysRequired > 0) {
                $minStartDate = $today->copy()->addDays($advanceDaysRequired);

                if ($startDate->lt($minStartDate)) {
                    $fail("You must request {$leaveType} at least {$advanceDaysRequired} days in advance.");
                }
            }
        }
    ],
    'end_date' => 'required|date|after_or_equal:start_date',
    'reason' => 'nullable|string',
    'leave_files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Multiple files
    'days_applied' => 'required|integer|min:1',
    'commutation' => 'required|boolean',
    'leave_details' => 'nullable|array',
    'abroad_details' => 'nullable|string',
], $leaveValidationRules));

$user = Auth::user();

$leaveFiles = [];
if ($request->hasFile('leave_files')) {
    foreach ($request->file('leave_files') as $file) {
        $path = $file->store('leave_files', 'public');
        $leaveFiles[] = $path;
    }
}

$leaveDetails = [];

$leave->update([
    'leave_type' => $request->leave_type,
    'leave_details' => json_encode($leaveDetails),
    'start_date' => $request->start_date,
    'end_date' => $request->end_date,
    'salary_file' => $request->salary_file,
    'days_applied' => $days_applied,
    'commutation' => $request->commutation,
    'reason' => $request->reason,
    'signature' => $request->signature,
    'leave_files' => json_encode($leaveFiles),
    'status' => 'pending',
]);

notify()->success('Leave request updated successfully!');
return redirect()->back()->with('success', 'Leave request updated successfully.');
}

public function deleteLeave($id) {
    Leave::findOrFail($id)->delete();
    return redirect()->back()->with('success', 'Leave request deleted successfully.');
}

    public function review(Request $request, Leave $leave)
{
    $request->validate([
        'admin_status' => 'required|in:Approved,Rejected',
        'disapproval_reason' => 'nullable|string',
    ]);

    $admin_status = strtolower($request->admin_status);

    $status = ($admin_status === 'rejected') ? 'rejected' : $leave->status;

    $leave->update([
        'admin_status' => $admin_status,
        'status' => $status,
        'disapproval_reason' => $request->disapproval_reason,
        'admin_id' => Auth::id(),
    ]);

    notify()->success('Leave application reviewed by Admin.');
    return Redirect::route('admin.requests');
}

public function ctoreview(Request $request, OvertimeRequest $cto)
{
    $request->validate([
        'admin_status' => 'required|in:Ready for Review,Rejected',
    ]);

    $admin_status = strtolower($request->admin_status); 

    $cto->update([
        'admin_status' => $admin_status, // Update HR status
        // 'status' => $status, // Also update overall status if rejected
        // 'disapproval_reason' => $request->disapproval_reason,
        'admin_id' => Auth::id(),
    ]);

    notify()->success('CTO application reviewed by Admin.');
    return Redirect::route('admin.requests');
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
    
        $birthdays = User::whereMonth('birthday', $month)
        ->orderByRaw('DAY(birthday) ASC')
        ->get();
    
        $teamLeaves = Leave::whereMonth('start_date', $month)
                            ->where('status', 'approved')
                            ->where('end_date', '>=', $today)
                            ->with('user')
                            ->get();
        $monthPadded = str_pad($month, 2, '0', STR_PAD_LEFT); 
        $year = now()->year;
        
        $overtimeRequests = OvertimeRequest::where('status', 'approved')
            ->where('inclusive_dates', 'LIKE', "%-{$monthPadded}-%")
            ->where('inclusive_dates', 'LIKE', "{$year}-%")
            ->get();
        return view('admin.on_leave', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
    }

    public function profile() {
        $user = Auth::user();
        $gender = $user->gender;
        $currentYear = date('Y');
        

        $usedMandatoryLeaveDays = Leave::where('user_id', $user->id)
            ->where('leave_type', 'Mandatory Leave')
            ->whereYear('start_date', $currentYear)
            ->where('status', 'approved') 
            ->sum('days_applied');
    
        $mandatoryBalance = $user->mandatory_leave_balance;
        $mandatoryLeaveDays = 5;
        $remainingLeaveDays = $mandatoryLeaveDays - $usedMandatoryLeaveDays;
    
        return view('admin.profile.index', compact('user', 'usedMandatoryLeaveDays', 'remainingLeaveDays', 'gender', 'mandatoryBalance'));
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
                  ->whereMonth('start_date', now()->month)
                  ->whereYear('start_date', now()->year);
        }])->get();
    
        $employees->each(function ($employee) {
            $employee->total_absences = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        });
        $employees = $employees->sortBy('total_absences')->take(10)->values();
        return view('admin.leaderboard', compact('employees'));
    }

    public function holiday(Request $request)
    {
        $selectedYear = (int) $request->input('year', date('Y')); 
    
        $holidays = YearlyHoliday::whereYear('date', $selectedYear)
            ->orWhere('repeats_annually', true)
            ->orderBy('date')
            ->get()
            ->map(function ($holiday) use ($selectedYear) {
                if ($holiday->repeats_annually) {
                    $date = Carbon::parse($holiday->date);
    
                    $holiday->date = Carbon::create((int) $selectedYear, (int) $date->month, (int) $date->day)->format('Y-m-d');
                }
                return $holiday;
            });
    
        $groupedHolidays = $holidays->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('F Y');
        });
    
        $calendarData = $this->prepareCalendarData($holidays, $selectedYear);
    
        return view('admin.holiday-calendar', compact(
            'groupedHolidays',
            'calendarData',
            'selectedYear',
        ));
    }

    protected function prepareCalendarData($holidays, $year)
{
    $months = [];

    for ($month = 1; $month <= 12; $month++) {
        $year = (int) $year;
        $month = (int) $month;

        $date = Carbon::create($year, $month, 1);
        $daysInMonth = $date->daysInMonth;

        $monthData = [
            'name' => $date->format('F'),
            'year' => $year,
            'days' => []
        ];

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

    public function showAdminsModal()
    {
        $employees = User::with(['leaves' => function ($query) {
            $query->where('status', 'approved')
                  ->whereMonth('start_date', now()->month) 
                  ->whereYear('start_date', now()->year);
        }])
        ->orderBy('last_name', 'asc')  
        ->get();
        $employees->each(function ($employee) {
            $employee->total_absences = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)
                        ->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        });
        $employees = $employees
        ->sortBy(['total_absences', 'last_name']) 
        ->values();

        return view('admin.partials.admins-modal', compact('employees'));
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

    public function deleteAll()
    {
        $user = Auth::user();

        if ($user) {
            $user->notifications()->delete();
            return response()->json(['success' => true, 'message' => 'All notifications deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No notifications found.']);
    }

    public function ctoRequests()
    {
        $overtimereq = OvertimeRequest::where('user_id', Auth::id())->latest()->paginate(10);
        $overtime = OvertimeRequest::where('user_id', Auth::id())->first();
        return view('admin.CTO.my_cto_list', compact('overtimereq', 'overtime'));
    }

    public function myCtoRequests($id) {
        $overtime = OvertimeRequest::findOrFail($id);
    
        return view('admin.CTO.my_cto_show', compact('overtime'));
    }

    public function updateCTO(Request $request, $id)
    {
        $request->validate([
            'inclusive_dates' => 'required|string',
            'working_hours_applied' => 'required|integer|min:0|multiple_of:4',
        ]);

        $overtime = OvertimeRequest::findOrFail($id);
        $user = Auth::user();

        $oldHours = $overtime->working_hours_applied;
        $newHours = $request->working_hours_applied;
        $diff = $newHours - $oldHours;

        if ($diff > 0 && $diff > $user->overtime_balance) {
            notify()->error('You do not have enough available COC balance to increase the hours.');
            return redirect()->back()->withInput();
        }

        if ($diff !== 0) {
            if ($diff > 0) {
                $user->decrement('overtime_balance', $diff);
            } else {
                $user->increment('overtime_balance', abs($diff));
            }
        }

        $overtime->update([
            'inclusive_dates' => $request->inclusive_dates,
            'working_hours_applied' => $newHours,
        ]);

        notify()->success('Overtime request updated successfully.');
        return redirect()->back();
    }

    public function deleteCTO($id)
    {
        OvertimeRequest::findOrFail($id)->delete();
        
        notify()->success('CTO request deleted successfully.');
        return redirect()->back();
    } 

    public function cancelCTO($id)
    {
        $CTO = OvertimeRequest::findOrFail($id);
        
        if ($CTO->status == 'cancelled') {
            notify()->warning('CTO request is already cancelled.');
            return redirect()->back();
        }

        $user = Auth::user();

        $user->increment('overtime_balance', $CTO->working_hours_applied);

        $CTO->status = 'cancelled';
        $CTO->save();

        notify()->success('CTO request has been cancelled and balance restored.');

        return redirect()->back();
    }

    public function restoreCTO($id)
    {
        $CTO = OvertimeRequest::findOrFail($id);
        $user = Auth::user();

        if ($CTO->status !== 'cancelled') {
            notify()->warning('This CTO request is not cancelled and cannot be restored.');
            return redirect()->back();
        }

        $user->decrement('overtime_balance', $CTO->working_hours_applied);

        $CTO->status = 'pending'; 
        $CTO->save();

        notify()->success('CTO request has been restored and balance deducted.');

        return redirect()->back();
    }
}
