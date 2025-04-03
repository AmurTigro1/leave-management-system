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
    $records = TimeManagement::orderBy('created_at', 'desc')->get();
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

    // Expected work times
    $expectedCheckIn = Carbon::createFromTime(7, 0);
    $expectedBreakIn = Carbon::createFromTime(12, 30);

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
    if ($checkIn && $checkIn->greaterThan($expectedCheckIn)) {
        $lateMinutes += $expectedCheckIn->diffInMinutes($checkIn);
    }
    if ($breakIn && $breakIn->greaterThan($expectedBreakIn)) {
        $lateMinutes += $expectedBreakIn->diffInMinutes($breakIn);
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
        'total_late_absences' => $lateMinutes . ' min',
    ]);

    notify()->success('Time Recorded successfully!');
    return redirect()->back()->with('success', '');
}

}
