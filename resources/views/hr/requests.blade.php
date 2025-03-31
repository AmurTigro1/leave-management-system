@extends('layouts.hr.sidebar-header')

@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>
<div class="w-full bg-white rounded animate-fade-in p-4 lg:p-6 flex flex-col lg:flex-row gap-6">

    <!-- Leave Applications Section -->
    <div class="shadow-lg rounded-lg py-4 px-4 w-full">
        <h2 class="text-xl lg:text-2xl font-bold text-gray-700 mb-4 lg:mb-6 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Review Leave Applications
        </h2>

        @if ($leaveApplications->isEmpty())
            <p class="text-gray-600">No leave applications available.</p>
        @else
            @php $leaveFound = false; @endphp
            <div class="space-y-4">
                @foreach ($leaveApplications as $leave)
                    @if (($leave->status == 'pending' && $leave->admin_status == 'approved') || 
                        ($leave->status != 'approved' || $leave->admin_status != 'approved') && 
                        $leave->hr_status != 'rejected')
                        @php $leaveFound = true; @endphp
                        <div class="bg-white shadow-md rounded-lg p-4 lg:p-6 transition-all hover:shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Status Section -->
                                <div class="flex items-center gap-2">
                                    <p class="text-sm lg:text-base font-bold text-gray-700">Status</p>
                                    <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">{{ $leave->status }}</span>
                                </div>
                                
                                <!-- View Link - Always visible but positioned differently -->
                                <a href="{{ route('hr.leave_details', ['id' => $leave->id]) }}" class="text-blue-600 text-sm lg:text-base sm:self-end">View Request</a>
                            </div>
                            
                            <!-- User Info Section -->
                            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                                <!-- Profile Image -->
                                <div class="flex-shrink-0">
                                    @if ($leave->user->profile_image)
                                        <img src="{{ asset('storage/profile_images/' . $leave->user->profile_image) }}" class="w-16 h-16 lg:w-20 lg:h-20 rounded-full object-cover">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" alt="" class="w-16 h-16 lg:w-20 lg:h-20 rounded-full object-cover">
                                    @endif
                                </div>
                                
                                <!-- User Details -->
                                <div class="flex-grow">
                                    <h3 class="text-sm lg:text-base font-semibold text-gray-900 uppercase">
                                        {{ $leave->user->first_name }} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name }}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                        <p class="text-gray-600 text-sm">Leave Type: {{ $leave->leave_type }}</p>
                                        <p class="text-gray-600 text-sm">Duration: <span class="font-semibold">{{$leave->days_applied}} days</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            @if($leave->leave_type == 'Mandatory Leave' && $leave->supervisor_status == 'rejected')
                                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                                    <p class="font-bold text-sm lg:text-base">Supervisor Status: <span class="text-red-500 capitalize">{{ $leave->supervisor_status}}</span></p>
                                    <p class="text-gray-600 text-sm">Reason: <span class="text-red-500">{{ $leave->disapproval_reason}}</span></p>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            @if (!$leaveFound)
                <p class="text-gray-600">No leave applications available.</p>
            @endif
        @endif
    </div>

    <!-- CTO Applications Section -->
    <div class="shadow-lg rounded-lg py-4 px-4 w-full">
        <h2 class="text-xl lg:text-2xl font-bold text-gray-700 mb-4 lg:mb-6 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Review CTO Applications
        </h2>

        @if ($ctoApplications->isEmpty())
            <p class="text-gray-600">No CTO applications available.</p>
        @else
            @php $ctoFound = false; @endphp
            <div class="space-y-4">
                @foreach ($ctoApplications as $cto)
                    @if (($cto->status == 'pending' && $cto->admin_status == 'Ready for Review') || 
                        ($cto->status != 'approved' || $cto->admin_status != 'Ready for Review') && 
                        $cto->hr_status != 'rejected')
                        @php $ctoFound = true; @endphp
                        <div class="bg-white shadow-md rounded-lg p-4 lg:p-6 transition-all hover:shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Status Section -->
                                <div class="flex items-center gap-2">
                                    <p class="text-sm lg:text-base font-bold text-gray-700">Status</p>
                                    <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">{{ $cto->status }}</span>
                                </div>
                                
                                <!-- View Link -->
                                <a href="{{ route('hr.cto_details', ['id' => $cto->id]) }}" class="text-blue-600 text-sm lg:text-base sm:self-end">View Request</a>
                            </div>
                            
                            <!-- User Info Section -->
                            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                                <!-- Profile Image -->
                                <div class="flex-shrink-0">
                                    @if ($cto->user->profile_image)
                                        <img src="{{ asset('storage/profile_images/' . $cto->user->profile_image) }}" class="w-16 h-16 lg:w-20 lg:h-20 rounded-full object-cover">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" alt="" class="w-16 h-16 lg:w-20 lg:h-20 rounded-full object-cover">
                                    @endif
                                </div>
                                
                                <!-- User Details -->
                                <div class="flex-grow">
                                    <h3 class="text-sm lg:text-base font-semibold text-gray-900 uppercase">
                                        {{ $cto->user->first_name }} {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name }}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                        <p class="text-gray-600 text-sm">Hours Applied: {{ $cto->working_hours_applied }} hours</p>
                                        <p class="text-gray-600 text-sm">Duration: <span class="font-semibold">{{ round(\Carbon\Carbon::parse($cto->inclusive_date_start)->diffInDays(\Carbon\Carbon::parse($cto->inclusive_date_end))) + 1 }} days</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @if (!$ctoFound)
                <p class="text-gray-600">No CTO applications available.</p>
            @endif
        @endif
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
<script>
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        console.error("AJAX Error:", jqxhr.responseText);
    });
</script>
@endsection
@notifyCss