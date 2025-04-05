@extends('layouts.sidebar-header')

@section('content')
    <div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
        <x-notify::notify />
    </div>

    <div x-data="{ open: false }" class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 animate-fade-in">
        @notifyJs
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <h2 class="text-lg sm:text-xl font-bold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>   
                        Time Management Records
                    </h2>

                    <button @click="open = true" class="px-4 py-2 bg-blue-600 text-white rounded-md">Add Record</button>
                </div>
            </div>

            <div class="overflow-x-auto">
                @foreach ($monthlyRecords as $month => $records)
                    <!-- Monthly Header -->
                    <div class="p-4 sm:p-6 bg-gray-50 mt-4">
                        <h3 class="text-md font-bold">{{ $month }}</h3>
                    </div>

                    <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm text-gray-700 mb-6">
                        <thead class="bg-gray-50 text-gray-700 font-semibold">
                            <tr>
                                <th class="py-2 px-4 border-b">Date</th>
                                <th class="py-2 px-4 border-b">Check In</th>
                                <th class="py-2 px-4 border-b">Break Out</th>
                                <th class="py-2 px-4 border-b">Break In</th>
                                <th class="py-2 px-4 border-b">Check Out</th>
                                <th class="py-2 px-4 border-b">Total</th>
                                <th class="py-2 px-4 border-b">Late/Absences</th>
                                <th class="py-2 px-4 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($records as $record)
                                <tr>
                                    <td class="px-4 text-center py-3">{{ \Carbon\Carbon::parse($record->date)->format('F d, Y') }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->check_in ? \Carbon\Carbon::createFromFormat('H:i:s', $record->check_in)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->break_out ? \Carbon\Carbon::createFromFormat('H:i:s', $record->break_out)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->break_in ? \Carbon\Carbon::createFromFormat('H:i:s', $record->break_in)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->check_out ? \Carbon\Carbon::createFromFormat('H:i:s', $record->check_out)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->total_hours }} hours</td>
                                    <td class="px-4 text-center py-3">{{ $record->total_late_absences }}</td>
                                    <td>
                                        <div class="flex justify-center gap-4 items-center">
                                            <button class="text-blue-600 font-semibold">View | Edit</button>
                                            <form action="{{ route('time.management.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 mt-3 font-semibold">Delete</button>
                                            </form>
                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>                    
                    </table>
                @endforeach
            </div>
        </div>

        <!-- MODAL -->
        <div x-show="open" x-data="timeTracking()" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-semibold">Add Time Record</h3>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <form action="{{ route('time.management.store') }}" method="POST" class="mt-4" x-data="timeCalculator()">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" class="mt-1 block w-full border rounded-md p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check In</label>
                            <input type="time" name="check_in" x-model="checkIn" @change="calculateTime()" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Break Out</label>
                            <input type="time" name="break_out" x-model="breakOut" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Break In</label>
                            <input type="time" name="break_in" x-model="breakIn" @change="calculateTime()" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check Out</label>
                            <input type="time" name="check_out" x-model="checkOut" @change="calculateTime()" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Total Hours</label>
                            <input type="text" name="total_hours" x-model="totalHours" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" readonly>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Late/Absences</label>
                            <input type="text" name="total_late_absences" x-model="lateMinutes" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" readonly>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                        <button type="button" @click="open = false" class="px-4 py-2 border rounded-md text-gray-700">Cancel</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
@endsection

<script>
   function timeCalculator() {
    return {
        checkIn: null, // Stores the check-in time
        breakOut: null, // Stores break-out time
        breakIn: null, // Stores break-in time
        checkOut: null, // Stores check-out time
        totalHours: '', // Stores total worked hours
        lateMinutes: '', // Stores late minutes
        
        // Main function to calculate time
        calculateTime() {
    const currentDay = new Date().getDay(); // Get the current day (0 = Sunday, 1 = Monday, etc.)

    // Default expected check-in and check-out times
    let expectedCheckIn = new Date(`1970-01-01T09:00:00`); // 9:00 AM by default (for Tuesday to Friday)
    let expectedCheckOut = new Date(`1970-01-01T18:00:00`); // 6:00 PM by default (for Tuesday to Friday)

    // Adjust for Monday (8:00 AM check-in and 5:00 PM check-out)
    if (currentDay === 1) {
        expectedCheckIn = new Date(`1970-01-01T08:00:00`); // 8:00 AM on Monday
        expectedCheckOut = new Date(`1970-01-01T17:00:00`); // 5:00 PM on Monday
    }

    let totalMinutesWorked = 0; // Total minutes worked during the day
    let lateMinutesTotal = 0; // Total late minutes

    // Convert input times to Date objects for easier calculation
    const checkInTime = this.checkIn ? new Date(`1970-01-01T${this.checkIn}:00`) : null;
    const breakOutTime = this.breakOut ? new Date(`1970-01-01T${this.breakOut}:00`) : null;
    const breakInTime = this.breakIn ? new Date(`1970-01-01T${this.breakIn}:00`) : null;
    const checkOutTime = this.checkOut ? new Date(`1970-01-01T${this.checkOut}:00`) : null;

    // Calculate total minutes worked if check-in and check-out times are provided
    if (checkInTime && checkOutTime) {
        totalMinutesWorked = (checkOutTime - checkInTime) / 60000; // Convert milliseconds to minutes

        // Subtract break time (if break-out and break-in times are provided)
        if (breakOutTime && breakInTime) {
            totalMinutesWorked -= (breakInTime - breakOutTime) / 60000; // Subtract break duration in minutes
        }
    } 
    else if (checkInTime && !checkOutTime) {
        // If the check-out time is missing, assume half-day work (240 minutes)
        totalMinutesWorked = 240;
        if (breakOutTime) totalMinutesWorked += 30; // Add break-out time if provided
        if (breakInTime) totalMinutesWorked += 30; // Add break-in time if provided
    }

    // Calculate late minutes if the employee checks in later than the expected check-in time
    if (checkInTime && checkInTime > expectedCheckIn) {
        lateMinutesTotal += (checkInTime - expectedCheckIn) / 60000; // Convert milliseconds to minutes
    }

    // Calculate late minutes for break-in (if the break-in is later than the expected check-out time)
    if (breakInTime && breakInTime > expectedCheckOut) {
        lateMinutesTotal += (breakInTime - expectedCheckOut) / 60000; // Convert milliseconds to minutes
    }

    // Store the total hours worked (rounded down to nearest whole number)
    this.totalHours = Math.floor(totalMinutesWorked / 60); // Convert minutes to hours

    // Store the total late minutes (rounded down to nearest whole number)
    this.lateMinutes = Math.floor(lateMinutesTotal); // Convert late minutes to whole number
}

    };
}


    </script>