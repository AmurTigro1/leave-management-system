@extends('main_resources.index')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <!-- Back Button -->
    <a href="{{ route('employee.leave_request') }}" class="text-blue-600 hover:underline text-sm flex items-center mb-4">
        &larr; Back to Leave Requests
    </a>

    <h2 class="text-2xl font-bold text-gray-800">Leave Request Details</h2>

 <!-- Leave Information -->
 <div class="mt-4 p-4 bg-gray-50 rounded-lg">
    <p class="text-lg"><span class="font-semibold">Leave Type:</span> {{ $leave->leave_type }}</p>
    
    <!-- Leave Details -->
    <div class="mt-2">
        <span class="font-semibold">Leave Details:</span>
        @php $details = json_decode($leave->leave_details, true); @endphp
        @if($details)
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($details as $key => $value)
                    <span class="bg-blue-500 text-white px-3 py-1 text-sm rounded">{{ ucfirst($key) }}: {{ ucfirst($value) }}</span>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">N/A</p>
        @endif
    </div>

    <!-- Reason -->
    <p class="mt-2"><span class="font-semibold">Reason:</span> {{ $leave->reason ?? 'No reason provided.' }}</p>

    <!-- Dates (Formatted) -->
    <p class="mt-2"><span class="font-semibold">Start Date:</span> {{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}</p>
    <p class="mt-2"><span class="font-semibold">End Date:</span> {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</p>

    <!-- Status Badge -->
    <p class="mt-4">
        <span class="font-semibold">Status:</span>
        <span class="px-3 py-1 rounded text-white text-sm 
            {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
            {{ $leave->status }}
        </span>
    </p>
</div>
</div>
@endsection
