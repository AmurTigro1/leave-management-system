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
                                <th class="py-2 px-4 border-b">Late/Undertime</th>
                                <th class="py-2 px-4 border-b">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $totalLateAbsences = 0;
                            @endphp
                            @foreach ($records as $record)
                                <tr>
                                    <td class="px-4 text-center py-3">{{ \Carbon\Carbon::parse($record->date)->format('F d, Y') }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->check_in ? \Carbon\Carbon::createFromFormat('H:i:s', $record->check_in)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->break_out ? \Carbon\Carbon::createFromFormat('H:i:s', $record->break_out)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->break_in ? \Carbon\Carbon::createFromFormat('H:i:s', $record->break_in)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">{{ $record->check_out ? \Carbon\Carbon::createFromFormat('H:i:s', $record->check_out)->format('h:i A') : '-' }}</td>
                                    <td class="px-4 text-center py-3">
                                        {{ $record->total_hours }} 
                                        {{ $record->total_hours == 1 ? 'hour' : 'hours' }}
                                    </td>
                                    <td class="px-4 text-center py-3">
                                        {{ $record->total_late_absences }} 
                                        {{ $record->total_late_absences == 1 ? 'minute' : 'minutes' }}
                                    </td>
                                    
                                    <td>
                                        <div class="flex justify-center gap-4 items-center">
                                            {{-- <button class="text-blue-600 font-semibold" onclick="openEditModal({{ $record->id }})">Edit</button> --}}
                                            <form action="{{ route('time.management.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 mt-3 font-semibold">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                    
                                </tr>

                                @php
                                    $totalLateAbsences += $record->total_late_absences;
                                @endphp
                            @endforeach
                        </tbody>                    
                    </table>

                    <!-- Total Late/Absences for the Month -->
                    <div class="p-4 sm:p-6 bg-gray-50 mt-4">
                        <h4 class="text-md font-bold">Total Late/Undertime for {{ $month }}: {{ $totalLateAbsences }} minutes</h4>
                    </div>

                @endforeach
            </div>
        </div>

        @if(empty($records))
            <div class="text-center text-gray-500 mt-4">
                Currently no time recorded
            </div>
        @endif

        <!-- MODAL -->
        <div x-show="open" x-data="timeTracking()" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-lg font-semibold">Add Time Record</h3>
                    <button @click="open = false" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <select name="time_period" id="time-period-select" onchange="toggleForm()" class="rounded-lg w-[48%] mt-4">
                    <option value="whole_day" selected>Whole day</option>
                    <option value="morning">Morning</option>
                    <option value="afternoon">Afternoon</option>
                </select>
                
                <!-- Whole Day Form -->
                <form action="{{ route('time.management.store') }}" method="POST" class="mt-4" id="whole-day-form" x-data="timeCalculator()">
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
                            <label class="block text-sm font-medium text-gray-700">Late/Undertime</label>
                            <input type="text" name="total_late_absences" x-model="lateMinutes" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" readonly>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                        <button type="button" @click="open = false" class="px-4 py-2 border rounded-md text-gray-700">Cancel</button>
                    </div>
                </form>
                
                <!-- Morning Form -->
                <form action="{{ route('morning-time.management.store') }}" method="POST" class="mt-4" id="morning-form">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" class="mt-1 block w-full border rounded-md p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check In</label>
                            <input type="time" name="check_in" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Break Out</label>
                            <input type="time" name="break_out" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Total Hours</label>
                            <input type="number" name="total_hours" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Late/Absences</label>
                            <input type="number" name="total_late_absences" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" required>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                        <button type="button" @click="open = false" class="px-4 py-2 border rounded-md text-gray-700">Cancel</button>
                    </div>
                </form>
                
                <!-- Afternoon Form -->
                <form action="{{ route('afternoon-time.management.store') }}" method="POST" class="mt-4" id="afternoon-form">
                    @csrf
                    <!-- Hidden input for user_id -->
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" class="mt-1 block w-full border rounded-md p-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Break In</label>
                            <input type="time" name="break_in" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check Out</label>
                            <input type="time" name="check_out" class="mt-1 block w-full border rounded-md p-2">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Total Hours</label>
                            <input type="number" name="total_hours" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" required>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Late/Absences</label>
                            <input type="number" name="total_late_absences" class="mt-1 block w-full border rounded-md p-2 bg-gray-100" required>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
                        <button type="button" @click="open = false" class="px-4 py-2 border rounded-md text-gray-700">Cancel</button>
                    </div>
                </form>
                
                
                <script>
                    function toggleForm() {
                        const timePeriod = document.getElementById('time-period-select').value;
                        document.getElementById('whole-day-form').style.display = timePeriod === 'whole_day' ? 'block' : 'none';
                        document.getElementById('morning-form').style.display = timePeriod === 'morning' ? 'block' : 'none';
                        document.getElementById('afternoon-form').style.display = timePeriod === 'afternoon' ? 'block' : 'none';
                    }
                
                    // Initial call to set the default display (Whole day)
                    toggleForm();
                </script>
                
            </div>
        </div>
    </div>

     <!-- Modal Structure -->
     <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-[34%]">
            <h3 class="text-lg font-semibold mb-4 border-b pb-3">Edit Time Record</h3>
            <form id="editForm" action="" method="POST" onsubmit="calculateAndSave(event)">
                @csrf
                <input type="hidden" id="recordId" name="id">
                <div class="mb-4">
                    <label for="date" class="block">Date</label>
                    <input type="date" id="date" name="date" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <div class="mb-4 w-full">
                        <label for="check_in" class="block">Check In</label>
                        <input type="time" id="check_in" name="check_in" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4 w-full">
                        <label for="break_out" class="block">Break Out</label>
                        <input type="time" id="break_out" name="break_out" class="w-full px-4 py-2 border rounded">
                    </div>
                </div>
                <div class="flex justify-between items-center gap-4">
                    <div class="mb-4 w-full">
                        <label for="break_in" class="block">Break In</label>
                        <input type="time" id="break_in" name="break_in" class="w-full px-4 py-2 border rounded">
                    </div>
                    <div class="mb-4 w-full">
                        <label for="check_out" class="block">Check Out</label>
                        <input type="time" id="check_out" name="check_out" class="w-full px-4 py-2 border rounded" required>
                    </div>
                </div>
                <!-- Total Hours and Total Late/Absences -->
                <div class="mb-4">
                    <label for="total_hours" class="block">Total Hours</label>
                    <input type="text" id="total_hours" name="total_hours" class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
                </div>
                <div class="mb-4">
                    <label for="total_late_absences" class="block">Total Late/Absences</label>
                    <input type="text" id="total_late_absences" name="total_late_absences" class="w-full px-4 py-2 border rounded bg-gray-100" readonly>
                </div>
                <input type="hidden" name="_method" value="PUT">
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md mr-2">Save</button>
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-md text-gray-700">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    

@endsection

<script>
    function timeCalculator() {
    return {
        checkIn: null,
        breakOut: null,
        breakIn: null,
        checkOut: null,
        totalHours: '',
        lateMinutes: '',
        
        calculateTime() {
            const selectedDate = new Date(document.querySelector('input[name="date"]').value);
            const currentDay = selectedDate.getDay(); // Get day based on selected date

            // Set expected times based on the selected day
            let expectedCheckIn = new Date(selectedDate.setHours(9, 0, 0, 0)); // 09:00 AM
            let expectedCheckOut = new Date(selectedDate.setHours(18, 0, 0, 0)); // 06:00 PM

            if (currentDay === 1) { // If it's Monday (1 in JavaScript's getDay())
                expectedCheckIn = new Date(selectedDate.setHours(8, 0, 0, 0)); // 08:00 AM for Monday
                expectedCheckOut = new Date(selectedDate.setHours(17, 0, 0, 0)); // 05:00 PM for Monday
            }

            let totalMinutesWorked = 0;
            let lateMinutesTotal = 0;

            const checkInTime = this.checkIn ? new Date(`1970-01-01T${this.checkIn}:00`) : null;
            const breakOutTime = this.breakOut ? new Date(`1970-01-01T${this.breakOut}:00`) : null;
            const breakInTime = this.breakIn ? new Date(`1970-01-01T${this.breakIn}:00`) : null;
            const checkOutTime = this.checkOut ? new Date(`1970-01-01T${this.checkOut}:00`) : null;

            // Calculate worked minutes (subtract break time if applicable)
            if (checkInTime && checkOutTime) {
                totalMinutesWorked = (checkOutTime - checkInTime) / 60000;
                if (breakOutTime && breakInTime) {
                    totalMinutesWorked -= (breakInTime - breakOutTime) / 60000; // Subtract break time
                }
            } else if (checkInTime && !checkOutTime) {
                totalMinutesWorked = 240; // Default work duration (4 hours)
                if (breakOutTime) totalMinutesWorked += 30; // Break duration (30 minutes)
                if (breakInTime) totalMinutesWorked += 30; // Break duration (30 minutes)
            }

            // Calculate lateness (compare actual check-in/check-out times to expected ones)
            if (checkInTime && checkInTime > expectedCheckIn) {
                lateMinutesTotal += (checkInTime - expectedCheckIn) / 60000; // Lateness in minutes
            }

            if (breakInTime && breakInTime > expectedCheckOut) {
                lateMinutesTotal += (breakInTime - expectedCheckOut) / 60000; // Lateness in minutes
            }

            // Update the total hours and lateness in minutes
            this.totalHours = Math.floor(totalMinutesWorked / 60);
            this.lateMinutes = Math.floor(lateMinutesTotal);
        }
    };
}


    function openEditModal(recordId) {
    fetch(`/time-management/${recordId}/edit`)
        .then(response => response.json())
        .then(record => {
            // Populate the modal form with record data
            document.getElementById('recordId').value = record.id;
            document.getElementById('date').value = record.date;
            document.getElementById('check_in').value = record.check_in;
            document.getElementById('break_out').value = record.break_out;
            document.getElementById('break_in').value = record.break_in;
            document.getElementById('check_out').value = record.check_out;

            // Call the time calculator to update total hours and late/absences
            timeCalculator().calculateTime();

            // Populate the calculated fields
            document.getElementById('total_hours').value = record.total_hours;
            document.getElementById('total_late_absences').value = record.total_late_absences;

            // Set the form action URL for updating the record
            document.getElementById('editForm').action = `/time-management/${record.id}`;

            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
        })
        .catch(error => console.error('Error fetching record:', error));
}


function closeModal() {
    // Hide the modal
    document.getElementById('editModal').classList.add('hidden');
}


// Dummy function to simulate record fetching (replace with actual data fetching)
function getRecordById(recordId) {
    // Replace with actual logic to fetch record data (via AJAX or something else)
    return {
        id: recordId,
        date: '2025-04-06',
        check_in: '09:00',
        break_out: '12:00',
        break_in: '12:30',
        check_out: '18:00'
    };
}

</script>
