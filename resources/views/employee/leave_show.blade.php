@extends('layouts.sidebar-header')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6 space-y-6">
    <!-- Banner -->


    <!-- Back Button with Animation -->
    <a href="{{ route('employee.leave_request') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300">
        &larr; Back to Leave Requests
    </a>
    {{-- <div class="relative w-full h-40 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold shadow-md">
        Leave Request Details
    </div> --}}
        <!-- Download PDF Button -->
        <div class="text-right">
            <a href="{{ route('leave.viewPdf', $leave->id) }}" target="_blank" class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-5 py-2 rounded-lg shadow-md hover:opacity-90 transition">
                View & Download PDF
            </a>
        </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Leave Type</p>
            <p class="text-gray-900">{{ $leave->leave_type }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Leave Details</p>
            @php $details = json_decode($leave->leave_details, true); @endphp
            @if($details)
                <ul class="list-disc list-inside text-gray-800">
                    @foreach($details as $key => $value)
                        <li><span class="font-medium">{{ ucfirst($key) }}:</span> {{ ucfirst($value) }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">N/A</p>
            @endif
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Reason</p>
            <p class="text-gray-900">{{ $leave->reason ?? 'No reason provided.' }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Start Date</p>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">End Date</p>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Total Days</p>
            <p class="text-gray-900">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Status</p>
            <span class="px-3 py-1 text-white rounded-full {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                {{ ucfirst($leave->status) }}
            </span>
        </div>
        
        @if($leave->status === 'rejected')
            <div class="bg-red-100 p-4 rounded-lg shadow">
                <p class="font-semibold text-lg text-red-700">Disapproval Reason</p>
                <p class="text-red-600">{{ $leave->disapproval_reason }}</p>
            </div>
        @endif
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Approved Days with Pay</p>
            <p class="text-gray-900">{{ $leave->approved_days_with_pay }}</p>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg shadow">
            <p class="font-semibold text-lg">Approved Days without Pay</p>
            <p class="text-gray-900">{{ $leave->approved_days_without_pay }}</p>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="bg-blue-50 p-6 rounded-lg shadow-md">
        <p class="text-gray-700">If you have any questions or need further assistance regarding your leave request, please contact the HR department.</p>
        <a href="#" class="text-blue-600 font-semibold hover:underline">Contact HR</a>
    </div>
</div>
@endsection


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