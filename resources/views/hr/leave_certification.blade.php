@extends('main_resources.index')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-6 shadow-md rounded-lg">
    <h2 class="text-xl font-semibold mb-4 text-center">Certification of Leave Credits</h2>

    <div class="mb-4">
        <p><strong>Employee Name:</strong> {{ $leave->user->name }}</p>
        <p><strong>Leave Type:</strong> {{ $leave->leave_type }}</p>
        <p><strong>Leave Period:</strong> {{ $leave->start_date }} to {{ $leave->end_date }}</p>
    </div>

    <div class="border rounded-lg p-4">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2"> </th>
                    <th class="border p-2">Vacation Leave</th>
                    <th class="border p-2">Sick Leave</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border p-2 font-semibold">Total Earned</td>
                    <td class="border p-2 text-center">{{ $leave->user->vacation_leave_balance + $daysRequested }}</td>
                    <td class="border p-2 text-center">{{ $leave->user->sick_leave_balance + $daysRequested }}</td>
                </tr>
                <tr>
                    <td class="border p-2 font-semibold">Less this application</td>
                    <td class="border p-2 text-center">
                        {{ $leave->leave_type == 'Vacation Leave' ? $daysRequested : 0 }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ $leave->leave_type == 'Sick Leave' ? $daysRequested : 0 }}
                    </td>
                </tr>
                <tr>
                    <td class="border p-2 font-semibold">Balance</td>
                    <td class="border p-2 text-center">{{ $leave->user->vacation_leave_balance }}</td>
                    <td class="border p-2 text-center">{{ $leave->user->sick_leave_balance }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <p class="text-lg font-bold text-center">MYLOVE C. FLOOD</p>
        <p class="text-center">HRMO</p>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('hr.leave_certification', ['leaveId' => $leave->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            View Certification
        </a>
        
    </div>
</div>
@endsection
