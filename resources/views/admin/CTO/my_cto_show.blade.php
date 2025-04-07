@extends('layouts.admin.sidebar-header')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-5 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.cto_requests') }}" class="p-2 rounded-full bg-white shadow-sm hover:bg-gray-50 transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">CTO Request Details</h1>
                    <p class="text-sm text-gray-500">Request ID: #{{ $overtime->id }}</p>
                </div>
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('admin.overtime.viewPdf', $overtime->id) }}" target="_blank" 
                    class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-xs hover:bg-gray-50 transition duration-200 text-sm font-medium text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <div class="flex justify-between items-center">
            <div class="flex space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $overtime->admin_status == 'approved' ? 'bg-green-100 text-green-800' : 
                       ($overtime->admin_status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    Admin: {{ ucfirst($overtime->admin_status) }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $overtime->hr_status == 'approved' ? 'bg-green-100 text-green-800' : 
                       ($overtime->hr_status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    HR: {{ ucfirst($overtime->hr_status) }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                    {{ $overtime->status == 'approved' ? 'bg-green-100 text-green-800' : 
                       ($overtime->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    Supervisor: {{ ucfirst($overtime->status) }}
                </span>
            </div>
            <span class="text-sm text-gray-500">
                Submitted on {{ \Carbon\Carbon::parse($overtime->date_filed)->format('M d, Y') }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Position</p>
                <p class="mt-1 text-lg font-medium text-gray-900">{{ $overtime->user->position ?? 'No position provided.' }}</p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Inclusive Date</p>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($overtime->inclusive_dates)->format('M d, Y') }}
                </p>
            </div>
            
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Earned Hours</p>
                <p class="mt-1 text-lg font-medium text-gray-900">
                    {{ $overtime->earned_hours ?: '0' }}
                </p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white p-5 rounded-lg border border-gray-100 shadow-xs">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Approved Days</h3>
                <p class="text-gray-700">{{ $overtime->approved_days ?: 'Currently None' }}</p>
            </div>

            @if($overtime->status === 'rejected')
                <div class="bg-red-50 p-5 rounded-lg border border-red-100">
                    <h3 class="text-lg font-semibold text-red-800 mb-3">Disapproval Reason</h3>
                    <p class="text-red-700">{{ $overtime->disapproval_reason ?: 'No disapproval reason provided.' }}</p>
                </div>
            @endif
        </div>

        <div class="bg-indigo-50 p-5 rounded-lg border border-indigo-100">
            <div class="flex items-start">
                <div class="flex-shrink-0 p-2 bg-indigo-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-indigo-800">Need help with your overtime request?</h3>
                    <p class="mt-1 text-indigo-700">Contact our HR department for any questions or assistance regarding your overtime.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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