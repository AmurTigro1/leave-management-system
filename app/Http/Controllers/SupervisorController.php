<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Http\Requests\EmailUpdateRequest;
use App\Models\Leave;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use App\Models\VisitorLog;
use App\Models\CocLog;
use App\Models\YearlyHoliday;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\HRSupervisor;
use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\LeaveViolation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveStatusNotification;

class SupervisorController extends Controller

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
            return view('supervisor.partials.employee-list', compact('employees'))->render();
        }


        $pendingLeaves = Leave::where('admin_status', 'approved')->get();

        $totalEmployees = User::count();
        $totalPendingLeaves = Leave::where('admin_status', 'approved')->count();
        $totalApprovedLeaves = Leave::where('status', 'approved')->count();
        $totalRejectedLeaves = Leave::where('status', 'rejected')->count();
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

        $months = collect(range(1, 12))->map(function ($month) {
            return \Carbon\Carbon::create()->month($month)->format('F');
        });

        $visitorCounts = $months->map(function ($monthName, $index) use ($rawData) {
            return $rawData->get($index + 1, 0);
        });

        return view('supervisor.dashboard', compact('employees', 'pendingLeaves', 'totalEmployees', 'leaveStats', 'cocStats', 'search', 'months', 'visitorCounts', 'selectedYear'));
    }


    public function myUntimelyLeaveApplications(Request $request)
    {
        // Get users who have leave violations with count
        $usersWithViolations = User::whereHas('leaveViolations', function ($query) use ($request) {
            $query
            ->whereHas('leave', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'rejected']);
            })
            ->when($request->filled('from_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            });
        })
        ->withCount(['leaveViolations' => function ($query) use ($request) {
            $query
            ->whereHas('leave', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'rejected']);
            })
            ->when($request->filled('from_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            });
        }])
        ->orderBy('leave_violations_count', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('supervisor.untimely_leave_applications', compact('usersWithViolations'));
    }

    // Add this new method to fetch leave applications for a specific user via AJAX
    public function getUserLeaveApplications(Request $request, $userId)
    {
        $leaveApplications = LeaveViolation::with(['user', 'leave'])
            ->where('user_id', $userId)
            ->whereHas('leave', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'rejected']);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($violation) {

                // Format leave_details as a simple string
                $leaveDetailsText = 'N/A';
                $leaveDetails = $violation->leave->leave_details ?? null;

                if ($leaveDetails) {
                    $decoded = json_decode($leaveDetails, true);
                    if (is_array($decoded) && !empty($decoded)) {
                        // Convert to readable string like "key1: value1, key2: value2"
                        $parts = [];
                        foreach ($decoded as $key => $value) {
                            $parts[] = "$key: $value";
                        }
                        $leaveDetailsText = implode(', ', $parts);
                    }
                }

                return [
                    'type' => $violation->leave->leave_type ?? 'N/A',
                    'leave_details' => $leaveDetailsText ?? 'N/A',
                    'reason' => $violation->leave->reason ?? 'No reason provided',
                    'start_date' => $violation->leave->start_date ?? 'N/A',
                    'end_date' => $violation->leave->end_date ?? 'N/A',
                    'status' => $violation->leave->status ?? 'Pending',
                    'days_applied' => $violation->leave->days_applied ?? 0,
                    'filed_date' => $violation->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json($leaveApplications);
    }

    public function untimelySickLeave(Request $request){

          $usersWithViolations = User::whereHas('leaveViolations', function ($query) use ($request) {
            $query
            ->where('violation_type', 'sick_leave')
            ->whereHas('leave', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'rejected']);
            })
            ->when($request->filled('from_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            });
          })
          ->withCount(['leaveViolations' => function ($query) use ($request) {
            $query
            ->where('violation_type', 'sick_leave')
            ->whereHas('leave', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'rejected']);
            })
            ->when($request->filled('from_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->to_date);
            });
        }])
        ->orderBy('leave_violations_count', 'desc')
        ->paginate(10)
        ->withQueryString();

        return view('supervisor.untimely_sick_leave_applications', compact('usersWithViolations'));
    }

    public function getUserUntimelySickLeaveApplications(Request $request, $userId)
    {
        $leaveApplications = LeaveViolation::with(['user', 'leave'])
            ->where('user_id', $userId)
            ->where('violation_type', 'sick_leave')
            ->whereHas('leave', function ($q) {
                $q->whereNotIn('status', ['cancelled', 'rejected']);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($violation) {

                // Format leave_details as a simple string
                $leaveDetailsText = 'N/A';
                $leaveDetails = $violation->leave->leave_details ?? null;

                if ($leaveDetails) {
                    $decoded = json_decode($leaveDetails, true);
                    if (is_array($decoded) && !empty($decoded)) {
                        // Convert to readable string like "key1: value1, key2: value2"
                        $parts = [];
                        foreach ($decoded as $key => $value) {
                            $parts[] = "$key: $value";
                        }
                        $leaveDetailsText = implode(', ', $parts);
                    }
                }

                return [
                    'type' => $violation->leave->leave_type ?? 'N/A',
                    'leave_details' => $leaveDetailsText ?? 'N/A',
                    'reason' => $violation->leave->reason ?? 'No reason provided',
                    'start_date' => $violation->leave->start_date ?? 'N/A',
                    'end_date' => $violation->leave->end_date ?? 'N/A',
                    'status' => $violation->leave->status ?? 'Pending',
                    'days_applied' => $violation->leave->days_applied ?? 0,
                    'filed_date' => $violation->created_at->format('Y-m-d H:i:s'),
                    'leave_files' => $violation->leave->leave_files ? $violation->leave->leave_files : null,
                ];
            });

        return response()->json($leaveApplications);
    }


    public function requests()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Unauthorized access.');
        }

        $leaveApplications = Leave::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'leave_page');

        $ctoApplications = OvertimeRequest::where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(5, ['*'], 'cto_page');

        return view('supervisor.requests', compact('leaveApplications', 'ctoApplications'));
    }

    public function reject(Request $request, Leave $leave)
{
    if (Auth::user()->role !== 'supervisor') {
        abort(403, 'Unauthorized access.');
    }

    if ($leave->hr_status !== 'approved') {
        return redirect()->back()->with('error', 'Cannot reject: HR approval is required first.');
    }

    $request->validate([
        'disapproval_reason' => 'nullable|string',
    ]);

    $leave->fill([
        'supervisor_status' => 'rejected',
        'disapproval_reason' => $request->disapproval_reason,
        'status' => 'Rejected',
        'supervisor_id' => Auth::id(),
    ]);

    if ($leave->save()) {
        return redirect()->back()->with('success', 'Leave application rejected by Supervisor.');
    }

    return redirect()->back()->with('error', 'Failed to reject leave application.');
}


    public function profile() {
        $user = Auth::user();

        return view('supervisor.profile.index', [
            'user' => $user,
        ]);
    }

    public function profile_edit(Request $request): View
    {
        return view('supervisor.profile.partials.update-profile-information-form', [
            'user' => $request->user(),
        ]);
    }
    public function password_edit(Request $request): View
    {
        return view('supervisor.profile.partials.update-password-form', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        notify()->success('Profile Updated Successfully!');

        return Redirect::route('supervisor.profile.partials.update-profile-information-form')->with('status', 'profile-updated');
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

        return Redirect::route('supervisor.profile.partials.update-profile-information-form')->with('status', 'email-updated');
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
        return view('supervisor.on_leave', compact('teamLeaves', 'birthdays', 'month', 'overtimeRequests'));
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
        return view('supervisor.leaderboard', compact('employees'));
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

        return view('supervisor.holiday-calendar', compact(
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

    public function viewPdf($id)
    {
        $leave = Leave::findOrFail($id);
        $officials = HRSupervisor::all();

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();

        $pdf = PDF::loadView('pdf.leave_details', compact('leave', 'supervisor', 'hr', 'officials'));

        return $pdf->stream( $leave->user->last_name . ', '. $leave->user->first_name . '- Leave Request' . '.pdf');
    }

    public function ctoviewPdf($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        $earned = CocLog::whereNotNull('certification_coc')
        ->orderByDesc(DB::raw('GREATEST(created_at, updated_at)'))
        ->first();

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();

        $pdf = PDF::loadView('pdf.overtime_details', compact('overtime', 'supervisor', 'hr', 'earned'));

        return $pdf->stream( $overtime->user->last_name . ', '. $overtime->user->first_name . '- CTO Request' . '.pdf');

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
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found']);
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

    public function showSupervisorModal()
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

        return view('supervisor.partials.supervisor-modal', compact('employees'));
    }
}
