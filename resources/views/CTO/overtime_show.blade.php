@extends('layouts.sidebar-header')

@section('content')
<div class="bg-white shadow-md sm:shadow-xl rounded-lg p-4 sm:p-6 md:p-8 space-y-6 sm:space-y-8 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('cto.overtime_list') }}" class="inline-flex items-center text-blue-600 font-medium hover:underline transition duration-300 text-sm sm:text-base">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Overtime Requests
    </a>

    <!-- Title and PDF Button -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Overtime Request Details</h2>
        
        <!-- PDF Download Button -->
        <a href="{{ route('overtime.viewPdf', $overtime->id) }}" target="_blank" 
            class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg shadow-md hover:bg-blue-700 transition text-sm sm:text-base text-center">
            View & Download PDF
        </a>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 text-gray-800">
        <!-- Date Filed -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Date Filed</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('M d, Y') }}</p>
        </div>

        <!-- Position -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Position</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $overtime->user->position ?? 'No position provided.' }}</p>
        </div>

        <!-- Inclusive Dates -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg md:col-span-2">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Inclusive Dates</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{  \Carbon\Carbon::parse($overtime->inclusive_dates)->format('M j Y,') }}</p>
        </div>

        <!-- Status Indicators -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg md:col-span-2">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Admin Status -->
                <div>
                    <p class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Admin Status</p>
                    <span class="inline-block px-3 py-1 text-xs sm:text-sm font-semibold rounded-full
                        {{ $overtime->admin_status == 'approved' ? 'bg-green-100 text-green-700' : 
                           ($overtime->admin_status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($overtime->admin_status) }}
                    </span>
                </div>
                
                <!-- HR Status -->
                <div>
                    <p class="font-semibold text-gray-900 text-sm sm:text-base mb-2">HR Status</p>
                    <span class="inline-block px-3 py-1 text-xs sm:text-sm font-semibold rounded-full
                        {{ $overtime->hr_status == 'approved' ? 'bg-green-100 text-green-700' : 
                           ($overtime->hr_status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($overtime->hr_status) }}
                    </span>
                </div>
                
                <!-- Supervisor Status -->
                <div>
                    <p class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Supervisor Status</p>
                    <span class="inline-block px-3 py-1 text-xs sm:text-sm font-semibold rounded-full
                        {{ $overtime->status == 'approved' ? 'bg-green-100 text-green-700' : 
                           ($overtime->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst($overtime->status) }}
                    </span>
                </div>
            </div>
        </div>

        @if($overtime->status === 'rejected')
        <!-- Disapproval Reason -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg md:col-span-2">
            <p class="font-semibold text-red-700 text-sm sm:text-base">Disapproval Reason</p>
            <p class="text-red-600 text-sm sm:text-base mt-1">{{ $overtime->disapproval_reason }}</p>
        </div>
        @endif

        <!-- Approved Days -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Approved Days</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $overtime->approved_days ?: 'Currently None' }}</p>
        </div>

        <!-- Earned Hours -->
        <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
            <p class="font-semibold text-gray-900 text-sm sm:text-base">Earned Hours</p>
            <p class="text-gray-700 text-sm sm:text-base mt-1">{{ $overtime->earned_hours }}</p>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 p-4 sm:p-6 rounded-lg shadow-sm sm:shadow-md">
        <p class="text-gray-700 text-sm sm:text-base">If you have any questions or need further assistance regarding your overtime request, please contact the HR department.</p>
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