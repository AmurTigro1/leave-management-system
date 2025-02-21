@extends('main_resources.index')
@section('content')

<!-- Leave Request List -->
<div class=" px-32">
<!-- Leave Request List -->
<h3 class="text-2xl font-bold mt-6 mb-3 text-gray-800">Your Leave Requests</h3>
    <!-- Compact Holiday Calendar -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow mb-6">
        <h3 class="text-lg font-semibold text-gray-700 flex items-center mb-3">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" 
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 
                002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Upcoming Holidays
        </h3>

        <div class="grid grid-cols-4 gap-2">
            @foreach($holidays as $holiday)
                <div class="bg-gray-100 text-center p-3 rounded-lg shadow-sm border border-gray-300 
                    hover:bg-gray-200 transition">
                    <p class="text-sm font-bold text-gray-700">{{ $holiday->day_name }}</p>
                    <p class="text-2xl font-semibold text-blue-600">{{ $holiday->day }}</p>
                    <p class="text-xs text-gray-500">{{ $holiday->month }}</p>
                    <p class="text-xs text-gray-600 mt-1">{{ $holiday->name }}</p>
                </div>
            @endforeach
        </div>
    </div>
<!-- Showing X out of Y -->
<p class="text-gray-600 text-sm mt-2">
    Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} leave requests
</p>

<div class="mt-4 bg-white shadow-md rounded-lg overflow-hidden">
    <table class="w-full border-collapse text-gray-700">
        <thead>
            <tr class="bg-gray-200 text-gray-500 text-left">
                <th class="p-3">Type</th>
                <th class="p-3">Leave Details</th>
                <th class="p-3">Reason</th>
                <th class="p-3">Start Date</th>
                <th class="p-3">End Date</th>
                <th class="p-3">Status</th>
                <th class="p-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                    <td class="p-3 font-medium">{{ $leave->leave_type }}</td>
                    <td class="p-3">
                        @php $details = json_decode($leave->leave_details, true); @endphp
                        @if($details)
                            <div class="flex flex-wrap gap-2">
                                @foreach($details as $key => $value)
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                        {{ $key }}: {{ $value }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-500">N/A</span>
                        @endif
                    </td>
                    <td class="p-3">{{ $leave->reason ?? 'No reason provided' }}</td>
                    <td class="p-3">{{ $leave->start_date }}</td>
                    <td class="p-3">{{ $leave->end_date }}</td>
                    <td class="p-3">
                        <span class="px-3 py-1 rounded text-white text-sm
                        {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                        {{ $leave->status }}
                        </span>
                    </td>
                    <td class="p-3">
                        <a href="{{ route('employee.leave_show', ['id' => $leave->id]) }}">
                        <span class="px-3 py-1 text-xs font-semibold bg-blue-500 rounded text-white">
                            view
                        </span>
                    </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4 flex justify-between items-center">
    <p class="text-gray-600 text-sm">
        Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} leave requests
    </p>
    <div class="text-sm">
        {{ $leaves->appends(request()->query())->links() }}
    </div>
</div>
</div>
@endsection