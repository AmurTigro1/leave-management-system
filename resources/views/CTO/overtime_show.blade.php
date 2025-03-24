@extends('layouts.sidebar-header')

@section('content')
<div class="bg-white shadow-xl rounded-lg p-8 space-y-8 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('cto.overtime_list') }}" class="inline-flex items-center text-blue-600 font-medium hover:underline transition duration-300">
        &larr; Back to Overtime Requests
    </a>

    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-800">Overtime Request Details</h2>

    <!-- PDF Download Button -->
    <div class="text-right">
        <a href="{{ route('overtime.viewPdf', $overtime->id) }}" target="_blank" 
            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
            View & Download PDF
        </a>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800">
        <div>
            <p class="font-semibold text-gray-900">Date Filed</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Position</p>
            <p class="text-gray-700">{{ $overtime->user->position ?? 'No position provided.' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Inclusive Date Start</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Inclusive Date End</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_end)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Total Days</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->diffInDays(\Carbon\Carbon::parse($overtime->inclusive_date_end)) + 1 }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3">
            <div class="">
                <p class="font-semibold text-gray-900 mb-2">Admin Status</p>
                <span class="
                    {{ $overtime->admin_status == 'approved' ? 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700' : ($overtime->admin_status == 'rejected' ? 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700' : 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($overtime->admin_status) }}
                </span>                
            </div>
            <div class="">
                <p class="font-semibold text-gray-900 mb-2">HR Status</p>
                <span class="
                    {{ $overtime->hr_status == 'approved' ? 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700' : ($overtime->hr_status == 'rejected' ? 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700' : 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($overtime->hr_status) }}
                </span>      
            </div>
            <div class="">
                <p class="font-semibold text-gray-900 mb-2">Supervisor Status</p>
                <span class="
                    {{ $overtime->status == 'approved' ? 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-700' : ($overtime->status == 'rejected' ? 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-700' : 'inline-block px-4 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($overtime->status) }}
                </span>      
            </div>
        </div>

        @if($overtime->status === 'rejected')
        <div>
            <p class="font-semibold text-red-700">Disapproval Reason</p>
            <p class="text-red-600">{{ $overtime->disapproval_reason }}</p>
        </div>
        @endif

        <div>
            <p class="font-semibold text-gray-900">Approved Days</p>
            <p class="text-gray-700">{{ $overtime->approved_days ?: 'Currently None' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Earned Hours</p>
            <p class="text-gray-700">{{ $overtime->earned_hours }}</p>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="bg-blue-50 p-6 rounded-lg shadow-md">
        <p class="text-gray-700">If you have any questions or need further assistance regarding your overtime request, please contact the HR department.</p>
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
