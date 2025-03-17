@extends('layouts.hr.sidebar-header')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
    <!-- Back Button with Animation -->
    <a href="{{ route('hr.overtime_requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300">
        &larr; Back to Overtime Requests
    </a>
    
    <!-- Download PDF Button -->
    <div class="text-right">
        <a href="{{ route('overtime.viewPdf', $overtimeRequests->id) }}" target="_blank" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-5 py-2 rounded-lg shadow-md hover:opacity-90 transition">
            View & Download PDF
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Date Filed</p>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($overtimeRequests->date_filed)->format('F d, Y') }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Position</p>
            <p class="text-gray-900">{{ $overtimeRequests->position }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Office/Division</p>
            <p class="text-gray-900">{{ $overtimeRequests->office_division }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Working Hours Applied</p>
            <p class="text-gray-900">{{ $overtimeRequests->working_hours_applied }} hours</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Inclusive Start Date</p>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($overtimeRequests->inclusive_date_start)->format('F d, Y') }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Inclusive End Date</p>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($overtimeRequests->inclusive_date_end)->format('F d, Y') }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Approved Days</p>
            <p class="text-gray-900">{{ $overtimeRequests->approved_days ?? 'N/A' }}</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Earned Hours</p>
            <p class="text-gray-900">{{ $overtimeRequests->earned_hours }} hours</p>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Supervisor Status</p>
            <span class="px-3 py-1 text-white rounded-full {{ $overtimeRequests->supervisor_status == 'approved' ? 'bg-green-500' : ($overtimeRequests->supervisor_status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($overtimeRequests->supervisor_status) }}
            </span>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">HR Status</p>
            <span class="px-3 py-1 text-white rounded-full {{ $overtimeRequests->hr_status == 'approved' ? 'bg-green-500' : ($overtimeRequests->hr_status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($overtimeRequests->hr_status) }}
            </span>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Final Status</p>
            <span class="px-3 py-1 text-white rounded-full {{ $overtimeRequests->status == 'approved' ? 'bg-green-500' : ($overtimeRequests->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($overtimeRequests->status) }}
            </span>
        </div>
    </div>

    @if($overtimeRequests->disapproval_reason)
    <div class="bg-red-50 p-4 rounded-lg shadow">
        <p class="font-semibold text-lg text-red-600">Disapproval Reason</p>
        <p class="text-gray-900">{{ $overtimeRequests->disapproval_reason }}</p>
    </div>
    @endif
</div>
@endsection