@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="rounded-lg shadow-xl rounded m-4 p-8 space-y-8 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('hr.overtime_requests') }}" class="inline-flex items-center text-blue-600 font-medium hover:underline transition duration-300">
        &larr; Back to Overtime Requests
    </a>

    <!-- Title -->
    <h2 class="text-2xl font-bold text-gray-800">Overtime Request Details</h2>

    <!-- PDF Download Button -->
    <div class="text-right">
        <a href="{{ route('overtime.viewPdf', $overtimeRequests->id) }}" target="_blank" 
            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
            View & Download PDF
        </a>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-800">
        <div>
            <p class="font-semibold text-gray-900">Date Filed</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtimeRequests->date_filed)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Position</p>
            <p class="text-gray-700">{{ $overtimeRequests->position ?? 'No position provided.' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Office/Division</p>
            <p class="text-gray-700">{{ $overtimeRequests->office_division }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Working Hours Applied</p>
            <p class="text-gray-700">{{ $overtimeRequests->working_hours_applied }} hours</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Inclusive Start Date</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtimeRequests->inclusive_date_start)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Inclusive End Date</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtimeRequests->inclusive_date_end)->format('F d, Y') }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Total Days</p>
            <p class="text-gray-700">{{ \Carbon\Carbon::parse($overtimeRequests->inclusive_date_start)->diffInDays(\Carbon\Carbon::parse($overtimeRequests->inclusive_date_end)) + 1 }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Approved Days</p>
            <p class="text-gray-700">{{ $overtimeRequests->approved_days ?? 'Currently None' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Earned Hours</p>
            <p class="text-gray-700">{{ $overtimeRequests->earned_hours }} hours</p>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Supervisor Status</p>
            <span class="px-3 py-1 text-white text-sm font-semibold rounded 
                {{ $overtimeRequests->supervisor_status == 'approved' ? 'bg-green-500' : ($overtimeRequests->supervisor_status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($overtimeRequests->supervisor_status) }}
            </span>
        </div>

        <div>
            <p class="font-semibold text-gray-900">HR Status</p>
            <span class="px-3 py-1 text-white text-sm font-semibold rounded 
                {{ $overtimeRequests->hr_status == 'approved' ? 'bg-green-500' : ($overtimeRequests->hr_status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($overtimeRequests->hr_status) }}
            </span>
        </div>

        <div>
            <p class="font-semibold text-gray-900">Final Status</p>
            <span class="px-3 py-1 text-white text-sm font-semibold rounded 
                {{ $overtimeRequests->status == 'approved' ? 'bg-green-500' : ($overtimeRequests->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($overtimeRequests->status) }}
            </span>
        </div>
    </div>

    @if($overtimeRequests->disapproval_reason)
    <div>
        <p class="font-semibold text-red-700">Disapproval Reason</p>
        <p class="text-red-600">{{ $overtimeRequests->disapproval_reason }}</p>
    </div>
    @endif
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