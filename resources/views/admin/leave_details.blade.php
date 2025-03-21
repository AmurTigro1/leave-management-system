@extends('layouts.admin.sidebar-header')

@section('content')
<div class="flex justify-between items-center gap-4 py-2 px-4">
    <div class="bg-white shadow-lg rounded-lg p-6 space-y-6 w-full">
        <h2 class="text-2xl font-bold">Leave Balances</h2>
        <div class="flex justify-between items-center">
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Vacation Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Sick Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Maternity Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Paternity Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Solo Parent Leave</div>
        </div>
        <div class="flex justify-between items-center">
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Study Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">VAWC Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Rehabilitation Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Special Leave Benefit</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Special Energency Leave</div>
        </div>
        <br>
        <div class="flex justify-between items-center">
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->vacation_leave_balance}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->sick_leave_balance}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->maternity_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->paternity_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->solo_parent_leave}} days</div>
        </div>
        <div class="flex justify-between items-center">
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->study_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->vawc_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->rehabilitation_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->special_leave_benefit}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->special_emergency_leave}} days</div>
        </div>
        <h2 class="text-2xl font-bold">Application Request</h2>
       <div class="flex justify-between items-start gap-4">
            <div class="w-full text-center">
                <p class="mb-2">The Employeee requests the application to start and end at the following dates:</p>
                <div class="p-2 bg-gray-300 text-black rounded-lg mb-2"> {{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</div>
            </div>
            <div class="w-full text-center">
                <p class="mb-2">The Application request applied for the number of days to be taken:</p>
                <div class="p-2 bg-gray-300 text-black rounded-lg">Applied days: {{ $leave->days_applied}}</div>
            </div>
       </div>
        <div class="flex justify-between items-start gap-4">
           <div class="w-full">
                <p>Commutations required:</p>
                <div class="flex justify-between items-start gap-4">
                    <div class="p-2 bg-gray-300 text-black rounded-lg mb-2 w-full text-center">
                        Yes
                    </div>
                    <div class="p-2 bg-gray-300 text-black rounded-lg mb-2 w-full text-center">
                        No
                    </div>
                </div>
           </div>
            <div class="w-full">
                <p>Type of Leave requested and details: {{ $leave->leave_type}}</p>
            </div>
        </div>
       
        <div>
            <p>Details: {{ is_string($leave->leave_details) ? implode(', ', json_decode($leave->leave_details, true)) : $leave->leave_details }}</p>
        </div>
    </div>
    <div class="bg-white shadow-lg rounded-lg p-6 space-y-6 w-full">
      
    </div>
</div>
@endsection

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