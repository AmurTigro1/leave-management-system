@extends('layouts.sidebar-header')
@section('content')

<!-- Leave Request List -->
<div class=" px-32 overflow-hidden">
<!-- Leave Request List -->
<h3 class="text-2xl font-bold mt-6 text-gray-800">Your Leave Requests</h3>

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
                <th class="p-3">Total Days</th>
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
                    {{-- < class="p-3">
                        <span class="px-3 py-1 rounded text-white text-sm
                        {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                        {{ $leave->status }}
                        </span>
                    </> --}}
                    <td class="p-3">
                        @if ($leave->hr_status == 'approved' && $leave->supervisor_status == 'pending')
                            <span class="px-2 py-1 text-sm font-semibold text-orange-700 bg-orange-200 rounded-lg">
                                Waiting for Supervisor Approval
                            </span>
                        @elseif ($leave->hr_status == 'approved' && $leave->supervisor_status == 'approved')
                            <span class="px-2 py-1 text-sm font-semibold text-green-700 bg-green-200 rounded-lg">
                                Approved
                            </span>
                        @elseif ($leave->hr_status == 'rejected' || $leave->supervisor_status == 'rejected')
                            <span class="px-2 py-1 text-sm font-semibold text-red-700 bg-red-200 rounded-lg">
                                Rejected
                            </span>
                        @else
                            <span class="px-2 py-1 text-sm font-semibold text-yellow-700 bg-yellow-200 rounded-lg">
                                Pending
                            </span>
                        @endif
                    </td>
                    
                    </td>
                    <td class="p-3">
                    <span class="ml-2">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</span>
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