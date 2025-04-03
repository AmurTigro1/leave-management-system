@extends('layouts.hr.sidebar-header')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('hr.my_requests') }}" class="p-2 rounded-full bg-white shadow-sm hover:bg-gray-50 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Leave Request Details</h1>
                    <p class="text-sm text-gray-500">Request ID: #{{ $leave->id }}</p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('hr.leave.viewPdf', $leave->id) }}" target="_blank" 
                    class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-xs hover:bg-gray-50 transition duration-200 text-sm font-medium text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6 space-y-6">
        <!-- Status Badge -->
        <div class="flex justify-between items-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                {{ $leave->status == 'approved' ? 'bg-green-100 text-green-800' : 
                   ($leave->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                {{ ucfirst($leave->status) }}
            </span>
            <span class="text-sm text-gray-500">
                Submitted on {{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}
            </span>
        </div>

        <!-- Key Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Leave Type Card -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Leave Type</p>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $leave->leave_type }}</p>
            </div>
            
            <!-- Date Range Card -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Date Range</p>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} - 
                    {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} days total
                </p>
            </div>
            
            <!-- Days Breakdown Card -->
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Days Breakdown</p>
                <div class="mt-1 flex space-x-4">
                    <div>
                        <p class="text-sm text-gray-500">With Pay</p>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $leave->approved_days_with_pay ?: '0' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Without Pay</p>
                        <p class="text-lg font-medium text-gray-900">
                            {{ $leave->approved_days_without_pay ?: '0' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="space-y-4">
            <!-- Leave Details Section -->
            <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-xs">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Leave Details</h3>
                @php $details = json_decode($leave->leave_details, true); @endphp
                @if($details)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($details as $key => $value)
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $key)) }}</p>
                                <p class="mt-1 text-gray-900">{{ ucfirst($value) }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No additional details provided</p>
                @endif
            </div>

            <!-- Reason Section -->
            <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-xs">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Reason</h3>
                <p class="text-gray-700">{{ $leave->reason ?? 'No reason provided.' }}</p>
            </div>

            @if($leave->status === 'rejected')
                <!-- Disapproval Reason Section -->
                <div class="bg-red-50 p-5 rounded-lg border border-red-100">
                    <h3 class="text-lg font-semibold text-red-800 mb-3">Disapproval Reason</h3>
                    <p class="text-red-700">{{ $leave->disapproval_reason ?: 'No disapproval reason provided.' }}</p>
                </div>
            @endif

            <!-- Additional Information Section -->
            <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-xs">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Additional Information</h3>
                <p class="text-gray-700">{{ $leave->others ?: 'No additional information provided.' }}</p>
            </div>
        </div>

        <!-- HR Contact Card -->
        <div class="bg-indigo-50 p-5 rounded-lg border border-indigo-100">
            <div class="flex items-start">
                <div class="flex-shrink-0 p-2 bg-indigo-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-indigo-800">Need help with your leave request?</h3>
                    <p class="mt-1 text-indigo-700">Contact our HR department for any questions or assistance regarding your leave.</p>
                    {{-- <a href="#" class="mt-2 inline-flex items-center text-indigo-600 font-medium hover:underline transition duration-200 text-sm">
                        Contact HR
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a> --}}
                </div>
            </div>
        </div>
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