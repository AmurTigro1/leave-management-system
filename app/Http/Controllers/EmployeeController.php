<?php

namespace App\Http\Controllers;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\User;
use PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function indexLMS(Request $request) {
        $month = $request->query('month', now()->month);
        $today = now()->toDateString(); // Get today's date
    
        // Fetch employees whose birthday falls in the selected month
        $birthdays = User::whereMonth('birthday', $month)->get();
    
        // Get employees who are on approved leave this month (but only if their leave has not yet ended)
        $teamLeaves = Leave::whereMonth('start_date', $month)
                            ->where('status', 'approved')
                            ->where('end_date', '>=', $today) // Ensures leave is still ongoing
                            ->with('user') // Ensures the user object is available
                            ->get();
    
        return view('employee.dashboard', compact('teamLeaves', 'birthdays', 'month'));
    }
    
    
    
    
    public function leaderboard()
    {
        $employees = User::with(['leaves' => function ($query) {
            $query->where('status', 'approved')
                  ->whereDate('start_date', '>=', now()->subDays(30)) // Only consider last 30 days
                  ->whereDate('end_date', '<=', now()); // Ensure leave has ended
        }])
        ->get();
    
        // Calculate total absences for each employee
        foreach ($employees as $employee) {
            $employee->absent_days = $employee->leaves->sum(function ($leave) {
                return \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1;
            });
        }
    
        // Sort employees by least absences and take top 5
        $employees = $employees->sortBy('absent_days')->take(5);
    
        return view('employee.leaderboard', compact('employees'));
    }
    
    
    
    public function showUsersModal()
    {
        $users = User::all();
        return view('employee.partials.users-modal', compact('users'));
    }

    public function loginLmsCto() {
        return view('main_resources.logins.lms_cto_login');
    }

    public function makeRequest()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('employee.make_request', compact('leaves'));
    }

    public function store(Request $request) {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'department' => 'required|string',
            'reason' => 'nullable|string',
            'days_applied' => 'required|integer|min:1',
            'commutation' => 'required|boolean',
            'position' => 'required|string',
            'leave_details' => 'nullable|array', 
            'abroad_details' => 'nullable|string', 
        ]);

            $user = Auth::user();

            // Calculate number of days applied
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $daysApplied = $startDate->diffInDays($endDate) + 1; // Include start date

            // **Determine which leave balance to check**
            $availableLeaveBalance = match ($request->leave_type) {
                'Vacation Leave' => $user->vacation_leave_balance,
                'Sick Leave' => $user->sick_leave_balance,
                default => $user->leave_balance, // General leave balance
            };

            // **Check if there are enough leave credits**
            if ($daysApplied > $availableLeaveBalance) {
                return redirect()->back()->withErrors(['end_date' => 'You do not have enough balance for ' . $request->leave_type . '.']);
            }
        // Initialize an empty array to store selected leave details
            $leaveDetails = [];

            // **Vacation Leave / Special Privilege Leave**
            if ($request->leave_type === 'Vacation Leave' || $request->leave_type === 'Special Privilege Leave') {
                if ($request->filled('within_philippines')) {
                    $leaveDetails['Within the Philippines'] = $request->within_philippines; // Text input

                }
                if ($request->filled('abroad_details')) {
                    $leaveDetails['Abroad'] = $request->abroad_details; // Text input
                }
            }


        // **Sick Leave**
        if ($request->leave_type === 'Sick Leave') {
            if ($request->has('in_hospital')) {
                $leaveDetails['In Hospital'] = $request->input('in_hospital_details', 'Yes'); // Get input value or default 'Yes'
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
            'leave_details' => json_encode($leaveDetails), // Store all selected details as JSON
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'department' => $request->department,
            'position' => $request->position,
            'salary_file' => $request->salary_file,
            'days_applied' => $request->days_applied,
            'commutation' => $request->commutation,
            'date_filing' => now(),
            'reason' => $request->reason,
        ]);
        $user->save();

        notify()->success('Leave request successful!');
        return redirect()->back();
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
        $leaves = Leave::where('user_id', $user->id)->get();
    
        return view('employee.profile.index', [
            'user' => $user,
            'leaves' => $leaves,
            'vacationBalance' => $user->vacation_leave_balance,
            'sickBalance' => $user->sick_leave_balance,
        ]);
    }
    

    public function edit(Request $request): View
    {
        return view('employee.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('employee.profile.edit')->with('status', 'profile-updated');
    }
    

    // public function getLeaves()
    // {
    //     $leaves = Leave::with('user')->get();
    
    //     return response()->json($leaves->map(function ($leave) {
    //         $color = match ($leave->status) {
    //             'approved' => '#28a745', // Green
    //             'pending' => '#ffc107',  // Yellow
    //             'rejected' => '#dc3545', // Red
    //             default => '#6c757d',    // Gray for unknown
    //         };
    
    
    //         return [
    //             'title' => $leave->user->name,
    //             'start' => $leave->start_date,
    //             'end' => $leave->end_date,
    //             'status' => $leave->status,
    //             'totalDays' => $leave->end_date,
    //             'backgroundColor' => $color,
    //             'borderColor' => $color,
    //             'extendedProps' => [
    //             "profile_image" => $leave->user->profile_image ? asset('storage/profile_images/' . $leave->user->profile_image) : asset('images/default.png')
    //             ]
    //         ];
    //     }));
    // }
    // public function getLeaves()
    // {
    //     $leaves = Leave::with('user:id,name,profile_image')->get();
    
    //     $formattedLeaves = $leaves->map(function ($leave) {
    //         return [
    //             "id" => $leave->id,
    //             "title" => $leave->user->name,
    //             "start" => $leave->start_date,
    //             "end" => $leave->end_date,
    //             "status" => $leave->status,
    //             "profile_image" => $leave->user->profile_image ? asset('storage/profile_images/' . $leave->user->profile_image) : asset('images/default.png')
    //         ];
    //     });
    
    //     return response()->json($formattedLeaves);
    // }
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

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.leave_details', compact('leave', 'supervisor', 'hr'));
        
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
            'department' => 'required|string|max:255',
            'salary_file' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'position' => 'nullable|string|max:255',
            'days_applied' => 'required|integer|min:1',
            'commutation' => 'required|boolean',
            'reason' => 'nullable|string',
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
            'department' => $request->department,
            'salary_file' => $request->salary_file,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'position' => $request->position,
            'days_applied' => $request->days_applied,
            'commutation' => $request->commutation,
            'reason' => $request->reason,
            'leave_details' => !empty($leaveDetails) ? json_encode($leaveDetails) : null, // Save as JSON
        ]);
    
        return redirect()->back()->with('success', 'Leave request updated successfully.');
    }

    public function deleteLeave($id) {
        Leave::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Leave request deleted successfully.');
    }
    
}
