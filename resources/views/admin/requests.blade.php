@extends('layouts.admin.sidebar-header')

@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>
<div class="w-full bg-white rounded animate-fade-in flex justify-between items-start">

    <!-- Leave Applications Section -->
    <div class="shadow-lg rounded mr-4 py-2 px-4 w-full">
        <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Review Leave Applications
        </h2>

        @if ($leaveApplications->isEmpty())
            <p class="text-gray-600">No leave applications available.</p>
        @else
            <div>
                @foreach ($leaveApplications as $leave)
                <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl mb-2">
                   <div class="flex justify-between items-end">
                        <div class="flex justify-start items-center">
                            <div class="flex justify-start items-center mr-3">
                                @if ($leave->user->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $leave->user->profile_image) }}" class="w-[100px] h-[100px] rounded-full object-cover">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" alt="" class="w-[50px] h-[100px] rounded-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-start mb-2 mt-2 text-gray-900 uppercase">{{ $leave->user->first_name }} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name }}</h3>
                                <p class="text-gray-600 text-sm">Leave Type: {{ $leave->leave_type }}</p>
                                <p class="text-gray-600 text-sm">Duration: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} days</span></p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.leave_details', ['id' => $leave->id]) }}"  class="text-blue-600">View Request</a>
                        </div>
                   </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- CTO Applications Section -->
    <div class="shadow-lg rounded mr-4 py-2 px-4 w-full">
        <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Review CTO Applications
        </h2>

        @if ($ctoApplications->isEmpty())
            <p class="text-gray-600">No CTO applications available.</p>
        @else
            <div>
                @foreach ($ctoApplications as $cto)
                <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl mb-2">
                   <div class="flex justify-between items-end">
                        <div class="flex justify-start items-center">
                            <div class="flex justify-start items-center mr-3">
                                @if ($cto->user->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $cto->user->profile_image) }}" class="w-[100px] h-[100px] rounded-full object-cover">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" alt="" class="w-[50px] h-[100px] rounded-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-start mb-2 mt-2 text-gray-900 uppercase">{{ $cto->user->first_name }} {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name }}</h3>
                                <p class="text-gray-600 text-sm">Working Hours Applied: {{ $cto->working_hours_applied }} hours</p>
                                <p class="text-gray-600 text-sm">Duration: <span class="font-semibold">{{ \Carbon\Carbon::parse($cto->inclusive_date_start)->diffInDays(\Carbon\Carbon::parse($cto->inclusive_date_end)) + 1 }} days</span></p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.cto_details', ['id' => $cto->id]) }}"  class="text-blue-600">View Request</a>
                        </div>
                   </div>
                </div>
                @endforeach
            </div>
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
@endsection
@notifyCss