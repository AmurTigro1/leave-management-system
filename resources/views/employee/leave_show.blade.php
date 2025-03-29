@extends('layouts.sidebar-header')

@section('content')
<div class="bg-white shadow-md sm:shadow-xl rounded-lg p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('employee.leave_request') }}" class="inline-flex items-center text-blue-600 font-medium hover:underline transition duration-300 text-sm sm:text-base">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Leave Requests
    </a>

    <!-- Title and PDF Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Leave Request Details</h2>
        
        <!-- PDF Download Button -->
        <a href="{{ route('leave.viewPdf', $leave->id) }}" target="_blank" 
            class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg shadow-md hover:bg-blue-700 transition text-sm sm:text-base text-center">
            View & Download PDF
        </a>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-gray-800">
        <!-- Leave Type -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Leave Type</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $leave->leave_type }}</p>
        </div>

        <!-- Leave Details -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Leave Details</p>
            @php $details = json_decode($leave->leave_details, true); @endphp
            @if($details)
                <ul class="list-disc list-inside text-gray-700 text-sm sm:text-base mt-1 space-y-1">
                    @foreach($details as $key => $value)
                        <li><span class="font-medium">{{ ucfirst($key) }}:</span> {{ ucfirst($value) }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-sm sm:text-base mt-1">N/A</p>
            @endif
        </div>

        <!-- Reason -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Reason</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $leave->reason ?? 'No reason provided.' }}</p>
        </div>

        <!-- Start Date -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Start Date</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</p>
        </div>

        <!-- End Date -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">End Date</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</p>
        </div>

        <!-- Total Days -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Total Days</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</p>
        </div>

        <!-- Status -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Status</p>
            <span class="inline-block mt-1 px-3 py-1 text-white text-xs sm:text-sm font-semibold rounded 
                {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($leave->status) }}
            </span>
        </div>

        @if($leave->status === 'rejected')
        <!-- Disapproval Reason -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-red-700 text-sm sm:text-base">Disapproval Reason</p>
            <p class="text-red-600 text-sm sm:text-base mt-1">{{ $leave->disapproval_reason }}</p>
        </div>
        @endif

        <!-- Approved Days with Pay -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Approved Days with Pay</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $leave->approved_days_with_pay }}</p>
        </div>

        <!-- Approved Days without Pay -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Approved Days without Pay</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $leave->approved_days_without_pay }}</p>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 p-4 sm:p-6 rounded-lg shadow-sm sm:shadow-md">
        <p class="text-gray-700 text-sm sm:text-base">If you have any questions or need further assistance regarding your leave request, please contact the HR department.</p>
        <a href="#" class="inline-block mt-2 text-blue-600 font-semibold hover:underline text-sm sm:text-base">Contact HR</a>
    </div>
</div>
@endsection

<!-- Custom CSS -->
<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .hover-shadow:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>