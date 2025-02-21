<?php

namespace App\Http\Controllers;
use App\Models\Holiday;
use App\Models\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('employee.dashboard', compact('leaves'));
    }

    public function store(Request $request) {
        $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'leave_details' => 'nullable|array', // Validate checkboxes as an array
            'abroad_details' => 'nullable|string', // Text input for Abroad
        ]);

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
            'reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Leave request submitted successfully.');
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
        return view('employee.profile', compact('user'));
        
    }


}
