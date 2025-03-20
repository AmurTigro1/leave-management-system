@extends('layouts.sidebar-header')

@section('content')
<div class="bg-white shadow-xl rounded-lg p-8 space-y-8 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('employee.leave_request') }}" class="inline-flex items-center text-blue-600 font-medium hover:underline transition duration-300">
        &larr; Back to Leave Requests
    </a>

    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-800">Leave Request Details</h2>

    <!-- PDF Download Button -->
    <div class="text-right">
        <a href="{{ route('leave.viewPdf', $leave->id) }}" target="_blank" 
            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
            View & Download PDF
        </a>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800">
        <div>
            <p class="font-semibold text-gray-900">Leave Type</p>
            <p class="text-gray-700">{{ $leave->leave_type }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Leave Details</p>
            @php $details = json_decode($leave->leave_details, true); @endphp
            @if($details)
                <ul class="list-disc list-inside text-gray-700">
                    @foreach($details as $key => $value)
                        <li><span class="font-medium">{{ ucfirst($key) }}:</span> {{ ucfirst($value) }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">N/A</p>
            @endif
        </div>

        <div>
            <p class="font-semibold text-gray-900">Reason</p>
            <p class="text-gray-700">{{ $leave->reason ?? 'No reason provided.' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Start Date</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">End Date</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Total Days</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Status</p>
            <span class="px-3 py-1 text-white text-sm font-semibold rounded 
                {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($leave->status) }}
            </span>
        </div>

        @if($leave->status === 'rejected')
        <div>
            <p class="font-semibold text-red-700">Disapproval Reason</p>
            <p class="text-red-600">{{ $leave->disapproval_reason }}</p>
        </div>
        @endif

        <div>
            <p class="font-semibold text-gray-900">Approved Days with Pay</p>
            <p class="text-gray-700">{{ $leave->approved_days_with_pay }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Approved Days without Pay</p>
            <p class="text-gray-700">{{ $leave->approved_days_without_pay }}</p>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 p-6 rounded-lg shadow-md">
        <p class="text-gray-700">If you have any questions or need further assistance regarding your leave request, please contact the HR department.</p>
        <a href="#" class="text-blue-600 font-semibold hover:underline">Contact HR</a>
    </div>
</div>
@endsection

<!-- Custom CSS -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .hover-shadow:hover {
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
    }
</style>