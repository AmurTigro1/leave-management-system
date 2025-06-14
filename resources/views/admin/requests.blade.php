@extends('layouts.admin.sidebar-header')

@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>
<div class="hidden lg:block w-full bg-white rounded animate-fade-in">
    <div class="flex flex-col lg:flex-row justify-between items-start gap-4 p-4">
        <!-- Leave Applications Section -->
        <div class="shadow-lg rounded-lg p-4 w-full lg:w-1/2">
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-4 md:mb-6 flex items-center gap-2">
                <i class="lucide lucide-file-text"></i> Review Leave Applications
            </h2>

            @if ($leaveApplications->isEmpty())
                <p class="text-gray-600 p-4">No leave applications available.</p>
            @else
                <div class="space-y-4">
                    @foreach ($leaveApplications as $leave)
                    <div class="bg-white shadow-md rounded-lg p-4 md:p-6 transition-all hover:shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-700">Status:</p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                                </svg>
                                <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">{{ $leave->status}}</span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-end gap-4">
                            <div class="flex-shrink-0">
                                @if ($leave->user->profile_image)
                                @php
                                    $profileImage = null;

                                    if ($leave->user->profile_image) {
                                        $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                                        $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;

                                        if (file_exists(public_path($imagePath1))) {
                                            $profileImage = asset($imagePath1);
                                        } elseif (file_exists(public_path($imagePath2))) {
                                            $profileImage = asset($imagePath2);
                                        }
                                    }
                                @endphp

                                <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0" 
                                    alt="{{ $leave->user->name }}">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" 
                                        alt="" 
                                        class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                @endif
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1 uppercase">
                                    {{ $leave->user->first_name }} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name }}
                                </h3>
                                <p class="text-gray-600 text-xs sm:text-sm mb-1">Leave Type: {{ $leave->leave_type }}</p>
                                <p class="text-gray-600 text-xs sm:text-sm">Duration: <span class="font-semibold">{{ $leave->days_applied }} days</span></p>
                            </div>
                             <a href="{{ route('admin.leave_details', ['id' => $leave->id]) }}" class="text-blue-600 text-sm sm:text-base">View Request</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            <div class="mt-6">
                {{ $leaveApplications->links() }}
            </div>
        @endif
    </div>


        <!-- CTO Applications Section -->
        <div class="shadow-lg rounded-lg p-4 w-full lg:w-1/2">
            <h2 class="text-xl md:text-2xl font-bold text-gray-700 mb-4 md:mb-6 flex items-center gap-2">
                <i class="lucide lucide-file-text"></i> Review CTO Applications
            </h2>

            @if ($ctoApplications->isEmpty())
                <p class="text-gray-600 p-4">No CTO applications available.</p>
            @else
                <div class="space-y-4">
                    @foreach ($ctoApplications as $cto)
                    <div class="bg-white shadow-md rounded-lg p-4 md:p-6 transition-all hover:shadow-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2">
                                <p class="font-bold text-gray-700">Status:</p>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                                </svg>
                                <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">{{ $cto->status}}</span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-end gap-4">
                            <div class="flex-shrink-0">
                                @if ($cto->user->profile_image)
                                    @php
                                        $profileImage = null;

                                        if ($cto->user->profile_image) {
                                            $imagePath1 = 'storage/profile_images/' . $cto->user->profile_image;
                                            $imagePath2 = 'storage/profile_pictures/' . $cto->user->profile_image;

                                            if (file_exists(public_path($imagePath1))) {
                                                $profileImage = asset($imagePath1);
                                            } elseif (file_exists(public_path($imagePath2))) {
                                                $profileImage = asset($imagePath2);
                                            }
                                        }
                                    @endphp

                                    <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0" 
                                        alt="{{ $cto->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                    @endif
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1 uppercase">
                                    {{ $cto->user->first_name }} {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name }}
                                </h3>
                                <p class="text-gray-600 text-xs sm:text-sm mb-1">Working Hours: {{ $cto->working_hours_applied }} hours</p>
                                <p class="text-gray-600 text-xs sm:text-sm">
                                    Duration: {{ count(explode(', ', $cto->inclusive_dates)) }} {{ Str::plural('day', count(explode(', ', $cto->inclusive_dates))) }}
                                </p>
                            </div>
                            <a href="{{ route('admin.cto_details', ['id' => $cto->id]) }}" class="text-blue-600 text-sm sm:text-base">View Request</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $leaveApplications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Mobile View (Only visible on small devices) -->
<div class="block lg:hidden w-full bg-white rounded animate-fade-in p-4 space-y-6">
    <!-- Mobile Leave Applications -->
    <div>
        <h2 class="text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Leave Applications
        </h2>

        @if ($leaveApplications->isEmpty())
            <p class="text-gray-600">No leave applications available.</p>
        @else
            <div class="space-y-4">
                @foreach ($leaveApplications as $leave)
                    <div class="bg-white shadow rounded p-3">
                        <div class="flex items-center gap-3 mb-2">
                            @if ($leave->user->profile_image)
                                @php
                                    $profileImage = null;

                                    if ($leave->user->profile_image) {
                                        $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                                        $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;

                                        if (file_exists(public_path($imagePath1))) {
                                            $profileImage = asset($imagePath1);
                                        } elseif (file_exists(public_path($imagePath2))) {
                                            $profileImage = asset($imagePath2);
                                        }
                                    }
                                @endphp

                                <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                    class="w-10 h-10 rounded-full object-cover" 
                                    alt="{{ $leave->user->name }}">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" 
                                        alt="" 
                                        class="w-10 h-10 rounded-full object-cover">
                                @endif
                            <div>
                                <h3 class="text-sm font-semibold uppercase">{{ $leave->user->first_name }} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name }}</h3>
                                <p class="text-xs text-gray-500">Leave Type: {{ $leave->leave_type }}</p>
                                <p class="text-xs text-gray-500">Duration: {{ $leave->days_applied }} days</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="bg-yellow-500 text-white py-1 px-2 rounded-full text-xs">{{ $leave->status }}</span>
                            <a href="{{ route('admin.leave_details', ['id' => $leave->id]) }}" class="text-blue-600">View</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Mobile CTO Applications -->
    <div>
        <h2 class="text-lg font-bold text-gray-700 mb-3 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> CTO Applications
        </h2>

        @if ($ctoApplications->isEmpty())
            <p class="text-gray-600">No CTO applications available.</p>
        @else
            <div class="space-y-4">
                @foreach ($ctoApplications as $cto)
                    <div class="bg-white shadow rounded p-3">
                        <div class="flex items-center gap-3 mb-2">
                            @if ($cto->user->profile_image)
                                @php
                                    $profileImage = null;

                                    if ($cto->user->profile_image) {
                                        $imagePath1 = 'storage/profile_images/' . $cto->user->profile_image;
                                        $imagePath2 = 'storage/profile_pictures/' . $cto->user->profile_image;

                                        if (file_exists(public_path($imagePath1))) {
                                            $profileImage = asset($imagePath1);
                                        } elseif (file_exists(public_path($imagePath2))) {
                                            $profileImage = asset($imagePath2);
                                        }
                                    }
                                @endphp

                                <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                    class="w-10 h-10 rounded-full object-cover" 
                                    alt="{{ $cto->user->name }}">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" 
                                        alt="" 
                                        class="w-10 h-10 rounded-full object-cover">
                                @endif
                            <div>
                                <h3 class="text-sm font-semibold uppercase">{{ $cto->user->first_name }} {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name }}</h3>
                                <p class="text-xs text-gray-500">Hours: {{ $cto->working_hours_applied }}</p>
                                <p class="text-xs text-gray-500">Duration: {{ count(explode(', ', $cto->inclusive_dates)) }} {{ Str::plural('day', count(explode(', ', $cto->inclusive_dates))) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="bg-yellow-500 text-white py-1 px-2 rounded-full text-xs">{{ $cto->status }}</span>
                            <a href="{{ route('admin.cto_details', ['id' => $cto->id]) }}" class="text-blue-600">View</a>
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

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .flex-col {
            flex-direction: column;
        }
        .w-full {
            width: 100%;
        }
        .gap-4 {
            gap: 1rem;
        }
    }

    @media (max-width: 640px) {
        .text-xl {
            font-size: 1.25rem;
        }
        .p-4 {
            padding: 1rem;
        }
        .w-16, .h-16 {
            width: 4rem;
            height: 4rem;
        }
    }
</style>
@endsection
@notifyCss