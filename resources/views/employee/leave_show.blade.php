@extends('layouts.sidebar-header')

@section('content')
<div class=" bg-white rounded-lg ">
    <!-- Back Button with Animation -->
    <a href="{{ route('employee.leave_request') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center mb-4 transition-transform duration-300 hover:translate-x-2">
        &larr; Back to Leave Requests
    </a>

    <!-- Title with Fade-In Animation -->
    <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
        Leave Request Details
        <span class="animate-pulse w-3 h-3 bg-green-500 rounded-full"></span>
    </h2>

    <!-- Leave Information with Gradient Background and Hover Effect -->
    <div class="mt-4 p-6 bg-gray-50 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
        <!-- Leave Type with Icon -->
        <p class="text-lg flex items-center">
            <span class="font-semibold">Leave Type:</span>
            <span class="ml-2">{{ $leave->leave_type }}</span>
        </p>

        <!-- Leave Details with Tags Animation -->
        <div class="mt-4 flex">
            <span class="font-semibold">Leave Details:</span>
            @php $details = json_decode($leave->leave_details, true); @endphp
            @if($details)
                <div class="flex flex-wrap gap-2">
                    @foreach($details as $key => $value)
                        <span class="bg-blue-500 text-white px-3 py-1 text-sm rounded-full transform transition-all duration-300 hover:scale-110 ml-2">{{ ucfirst($key) }}: {{ ucfirst($value) }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">N/A</p>
            @endif
        </div>

        <!-- Reason with Fade-In Animation -->
        <p class="mt-4 animate-fade-in">
            <span class="font-semibold">Reason:</span>
            <span class="ml-2">{{ $leave->reason ?? 'No reason provided.' }}</span>
        </p>

        <!-- Dates with Icon and Hover Effect -->
        <div class="mt-4">
            <p class="flex items-center">
                <span class="font-semibold">Start Date:</span>
                <span class="ml-2">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}</span>
                <svg class="w-5 h-5 ml-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </p>
            <p class="mt-2 flex items-center">
                <span class="font-semibold">End Date:</span>
                <span class="ml-2">{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</span>
                <svg class="w-5 h-5 ml-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </p>
            <p class="mt-2 flex items-center">
                <span class="font-semibold ">Total Days:</span>
            <span class="ml-2">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</span>
        </p>
        </div>

        <!-- Status Badge with Pulse Animation -->
        <p class="mt-6">
            <span class="font-semibold">Status:</span>
            <span class="px-3 py-1 rounded-full text-white text-sm animate-pulse
                {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ $leave->status }}
            </span>
        </p>
    </div>
<!-- Leave request information and HR remarks -->
<div class=" bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1">
    <!-- Leave request details -->
    <div class=" text-gray-700">
        <!-- Disapproval Reason -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            @if($leave->status === 'rejected')
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <p class="text-sm font-semibold text-red-600">Disapproval Reason:</p>
                <p class="mt-1 text-gray-600">{{$leave->disapproval_reason}}</p>
            </div>
        @endif

        <!-- Approved Days with Pay -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-sm font-semibold text-blue-600">Approved Days with Pay:</p>
            <p class="mt-1 text-gray-600">{{$leave->approved_days_with_pay}}</p>
        </div>

        <!-- Approved Days without Pay -->
        <div class="bg-white p-4 rounded-lg shadow-sm">
            <p class="text-sm font-semibold text-blue-600">Approved Days without Pay:</p>
            <p class="mt-1 text-gray-600">{{$leave->approved_days_without_pay}}</p>
        </div>
    </div>
</div>
    <!-- Additional Information Section -->
    <div class="mt-8 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Additional Information</h3>
        <p class="text-gray-600">If you have any questions or need further assistance regarding your leave request, please contact the HR department.</p>
        <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-300">Contact HR</button>
    </div>
        <!-- Download PDF Button -->
        <div class="mt-6">
            <a href="{{ route('leave.downloadPdf', $leave->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-lg flex items-center gap-2 transition-all transform hover:scale-105">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 8a1 1 0 012 0v3h6V8a1 1 0 012 0v4a1 1 0 01-1 1H6a1 1 0 01-1-1V8zm-1 7a1 1 0 011-1h10a1 1 0 011 1v2H4v-2z" clip-rule="evenodd"/>
                </svg>
                Download PDF
            </a>
        </div>
</div>

</div>

<!-- Custom CSS for Animations -->
<style>
    .animate-fade-in {
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .animate-pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endsection