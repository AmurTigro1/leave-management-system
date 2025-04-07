<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeManagement;
use App\Models\MonthlySummary;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimeManagementController extends Controller
{
    public function timeManagement()
{
    $records = TimeManagement::orderBy('date', 'desc')->get();
    $users = User::all();

    // Group records by month
    $monthlyRecords = $records->groupBy(function ($record) {
        return Carbon::parse($record->date)->format('F Y'); // Example: "March 2024"
    });

    // Calculate absences per month
    $monthlyAbsences = [];
    foreach ($monthlyRecords as $month => $records) {
        $absences = $records->where('check_in', null)->count(); // Count days without check-in
        $monthlyAbsences[$month] = $absences;

        // Save to database (assuming a MonthlySummary table exists)
        MonthlySummary::updateOrCreate(
            ['month' => $month],
            ['total_absences' => $absences]
        );
    }

    return view('employee.time-management', compact('monthlyRecords', 'users', 'monthlyAbsences'));
}

public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'check_in' => 'nullable|date_format:H:i',
        'break_out' => 'nullable|date_format:H:i',
        'break_in' => 'nullable|date_format:H:i',
        'check_out' => 'nullable|date_format:H:i',
    ]);

    // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
    $currentDay = Carbon::parse($request->date)->dayOfWeek;

    // Expected check-in times
    $expectedCheckIn = ($currentDay === 1) ? Carbon::createFromTime(8, 0) : Carbon::createFromTime(9, 0); // 8:00 AM on Monday, 9:00 AM for other days
    $expectedCheckOut = ($currentDay === 1) ? Carbon::createFromTime(17, 0) : Carbon::createFromTime(18, 0); // 5:00 PM on Monday, 6:00 PM for other days

    // Convert inputs to Carbon instances
    $checkIn = $request->check_in ? Carbon::parse($request->check_in) : null;
    $breakOut = $request->break_out ? Carbon::parse($request->break_out) : null;
    $breakIn = $request->break_in ? Carbon::parse($request->break_in) : null;
    $checkOut = $request->check_out ? Carbon::parse($request->check_out) : null;

    // Calculate total hours worked
    $totalMinutesWorked = 0;

    if ($checkIn && $checkOut) {
        $totalMinutesWorked = $checkIn->diffInMinutes($checkOut);

        if ($breakOut && $breakIn) {
            $totalMinutesWorked -= $breakOut->diffInMinutes($breakIn); // Deduct break time
        }
    } elseif ($checkIn && !$checkOut) {
        $totalMinutesWorked = 240; // Assume 4 hours (half-day) if only check-in is provided
        if ($breakOut) $totalMinutesWorked += 30; // Add 30 mins if break-out exists
        if ($breakIn) $totalMinutesWorked += 30; // Add 30 mins if break-in exists
    }

    // Convert minutes to hours
    $totalHours = floor($totalMinutesWorked / 60);

    // Calculate late minutes
    $lateMinutes = 0;
    
    // Late calculation only if the check-in is later than the expected time
    if ($checkIn && $checkIn->greaterThan($expectedCheckIn)) {
        $lateMinutes += $expectedCheckIn->diffInMinutes($checkIn);
    }
    
    // Late calculation for break-in time if the user is late for the break-in
    if ($breakIn && $breakIn->greaterThan($expectedCheckOut)) {
        $lateMinutes += $expectedCheckOut->diffInMinutes($breakIn);
    }

    // If no late minutes, set it to 0 explicitly
    if ($lateMinutes == 0) {
        $lateMinutes = 0;
    }

    // Save to database
    TimeManagement::create([
        'user_id' => Auth::id(),
        'date' => $request->date,
        'check_in' => $request->check_in,
        'break_out' => $request->break_out,
        'break_in' => $request->break_in,
        'check_out' => $request->check_out,
        'total_hours' => $totalHours, // Ensures it always has a value
        'total_late_absences' => $lateMinutes, // Store late minutes as integer value (not as a string)
    ]);

    notify()->success('Time Recorded successfully!');
    return redirect()->back()->with('success', '');
}

public function storeMorning(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'date' => 'required|date',
            'check_in' => 'required|date_format:H:i',
            'break_out' => 'required|date_format:H:i',
            'total_hours' => 'required|numeric',
            'total_late_absences' => 'required|numeric',
        ]);

        // Store the data
        TimeManagement::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'check_in' => $request->check_in,
            'break_out' => $request->break_out,
            'total_hours' => $request->total_hours,
            'total_late_absences' => $request->total_late_absences,
            'shift_type' => 'morning', // Additional field to differentiate between morning and afternoon
        ]);

        notify()->success('Time Recorded successfully!');
        return redirect()->back();
    }

    // Store Afternoon Time
    public function storeAfternoon(Request $request)
{
    // Validate incoming request
    $request->validate([
        'date' => 'required|date',
        'break_in' => 'required|date_format:H:i',
        'check_out' => 'required|date_format:H:i',
        'total_hours' => 'required|numeric',
        'total_late_absences' => 'required|numeric',
    ]);

    // Store the data
    TimeManagement::create([
        'user_id' => Auth::id(), // Set user_id from Auth
        'date' => $request->date,
        'break_in' => $request->break_in,
        'check_out' => $request->check_out,
        'total_hours' => $request->total_hours,
        'total_late_absences' => $request->total_late_absences,
        'shift_type' => 'afternoon', // Additional field to differentiate between morning and afternoon
    ]);

    notify()->success('Time Recorded successfully!');
    return redirect()->back();
}




public function destroy($id)
{
    $record = TimeManagement::findOrFail($id); // Find the record
    $record->delete(); // Delete the record

    notify()->success('Record deleted successfully!');
    return redirect()->back();
}

public function edit($id)
{
    $record = TimeManagement::findOrFail($id);
    return response()->json($record);
}


public function update(Request $request, $id)
{
    // Find the record
    $record = TimeManagement::findOrFail($id);

    // Validate the form data
    $request->validate([
        'date' => 'required|date',
        'check_in' => 'nullable|date_format:H:i',
        'break_out' => 'nullable|date_format:H:i',
        'break_in' => 'nullable|date_format:H:i',
        'check_out' => 'nullable|date_format:H:i',
    ]);

    // Calculate hours worked and late minutes
    $checkIn = $request->check_in ? Carbon::parse($request->check_in) : null;
    $checkOut = $request->check_out ? Carbon::parse($request->check_out) : null;

    $totalMinutesWorked = 0;
    $lateMinutes = 0;

    if ($checkIn && $checkOut) {
        $totalMinutesWorked = $checkIn->diffInMinutes($checkOut);
        // Deduct break time if break times are provided
        $breakOut = $request->break_out ? Carbon::parse($request->break_out) : null;
        $breakIn = $request->break_in ? Carbon::parse($request->break_in) : null;
        if ($breakOut && $breakIn) {
            $totalMinutesWorked -= $breakOut->diffInMinutes($breakIn);
        }
    }

    // Calculate late minutes
    $expectedCheckIn = Carbon::createFromTime(9, 0);  // Assume 9 AM check-in time
    if ($checkIn && $checkIn->greaterThan($expectedCheckIn)) {
        $lateMinutes = $expectedCheckIn->diffInMinutes($checkIn);
    }

    // Update the record
    $record->update([
        'date' => $request->date,
        'check_in' => $request->check_in,
        'break_out' => $request->break_out,
        'break_in' => $request->break_in,
        'check_out' => $request->check_out,
        'total_hours' => floor($totalMinutesWorked / 60),
        'total_late_absences' => $lateMinutes,
    ]);

    // Redirect back with a success message
    notify()->success('Time Record updated successfully!');
    return redirect()->route('time-management.index');
}


}
