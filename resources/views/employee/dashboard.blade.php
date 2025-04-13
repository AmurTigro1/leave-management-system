@extends('layouts.sidebar-header')

@section('content')
    <div class="fixed top-4 right-4 z-[9999]">
        <x-notify::notify />
    </div>
    @notifyJs
<div class="w-full px-4 py-8 space-y-8 animate-fade-in">
<div class="w-full px-4">
    <div class="relative text-center py-12 overflow-hidden" id="birthday-header">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
        </div>
      
        <h3 class="relative text-6xl md:text-7xl font-bold bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-600 bg-clip-text text-transparent drop-shadow-lg animate-float">
          Happy Birthday!
        </h3>
        
        <p class="relative mt-4 text-xl text-indigo-300 animate-pulse">
          Let's celebrate this special day!
        </p>
        
        <button onclick="launchConfetti()" class="relative mt-6 px-6 py-3 bg-gradient-to-r from-pink-500 to-indigo-600 text-white rounded-full shadow-lg hover:scale-105 transition-all duration-300 hover:shadow-xl active:scale-95">
          🎉 Launch Confetti
        </button>
      </div>
      
      <canvas id="confetti-canvas" class="fixed top-0 left-0 w-full h-full pointer-events-none" style="z-index: 999; display: none;"></canvas>
      
    @if ($birthdays->isEmpty())
    <p class="text-gray-500 text-center text-sm md:text-base italic">
        No team members have birthdays this month.
    </p>
    
    @else
    <div x-data="{
        currentIndex: 0,
        totalSlides: 0,
        init() {
            // Ensure the DOM is fully loaded before calculating slides
            setTimeout(() => {
                this.totalSlides = document.querySelectorAll('.slide').length;
            }, 100);
        }
    }" 
    class="relative w-full max-w-screen-lg mx-auto overflow-hidden mt-8">
    
    <div class="mb-8">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-t-xl p-4 shadow-lg">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Birthdays This Month
            </h2>
        </div>
        
        <div class="bg-white/90 backdrop-blur-sm rounded-b-xl shadow-lg border border-gray-200 p-6">
            <div class="flex transition-transform duration-700" :style="'transform: translateX(-' + (currentIndex * 100) + '%)'">
                @foreach ($birthdays->chunk(4) as $chunk)
                <div class="w-full flex flex-wrap justify-center gap-4 shrink-0 slide">
                    @foreach ($chunk as $employee)
                            <div class="w-full sm:w-[220px] bg-gradient-to-br from-white/5 to-white/20 backdrop-blur-sm rounded-2xl p-6 flex flex-col items-center border border-white/10 shadow-xl transition-all duration-500 hover:-translate-y-3 hover:shadow-2xl hover:border-indigo-400/50">
                                <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden bg-gradient-to-br from-purple-500 to-pink-500 shadow-lg ring-4 ring-white/20 ring-offset-2 ring-offset-white/10 animate-float-slow">
                                    @php
                                        $profileImage = asset('img/default-avatar.png'); 

                                        if ($employee->profile_image) {
                                            $imagePath1 = 'profile_images/' . $employee->profile_image;
                                            $imagePath2 = 'profile_pictures/' . $employee->profile_image;

                                            if (Storage::disk('public')->exists($imagePath1)) {
                                                $profileImage = Storage::url($imagePath1);
                                            } elseif (Storage::disk('public')->exists($imagePath2)) {
                                                $profileImage = Storage::url($imagePath2);
                                            }
                                        }
                                        @endphp

                                        <img src="{{ $profileImage }}" alt="Profile Image" class="w-full h-full object-cover">

                            </div>
                            <div class="mt-3 text-center group-hover:text-indigo-600 transition-colors">
                                <p class="text-md font-bold text-indigo-500">{{ $employee->first_name }} {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. {{$employee->last_name}}</p>
                                <p class="text-md text-gray-600 mt-1 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($employee->birthday)->format('F d') }}
                                </p>

                            </div>
                        </div>
                    @endforeach
                </div>
                @endforeach
            </div>
            
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2 z-10">
                <template x-for="(dot, index) in totalSlides" :key="index">
                    <button 
                        @click="currentIndex = index" 
                        class="w-3 h-3 rounded-full cursor-pointer transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                        :class="index === currentIndex ? 'bg-indigo-600 scale-110' : 'bg-gray-300 hover:bg-gray-400'"
                        :aria-label="'Go to slide ' + (index + 1)">
                    </button>
                </template>
            </div>
            
            <button 
                @click="currentIndex = (currentIndex - 1 + totalSlides) % totalSlides" 
                class="absolute left-4 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white shadow-lg hover:bg-indigo-50 text-indigo-600 border border-indigo-200 hover:border-indigo-400 transition-all hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                aria-label="Previous slide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            <button 
                @click="currentIndex = (currentIndex + 1) % totalSlides" 
                class="absolute right-4 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white shadow-lg hover:bg-indigo-50 text-indigo-600 border border-indigo-200 hover:border-indigo-400 transition-all hover:scale-110 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                aria-label="Next slide">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
    
    <div class="p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-md sm:shadow-lg border border-indigo-100 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3 sm:mb-4 gap-2 sm:gap-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                Team Members on Leave
            </h2>
            
            <span class="relative inline-flex items-center gap-1 px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-indigo-100 text-indigo-800 shadow-inner hover:bg-indigo-200 transition-colors duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                </svg>
                {{ $teamLeaves->count() }} {{ Str::plural('member', $teamLeaves->count()) }}
                <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                    Team members on leave
                    <svg class="absolute text-gray-800 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                </span>
            </span>
        </div>
        
        @if($teamLeaves->isEmpty())
            <div class="bg-white/80 p-4 sm:p-6 rounded-lg border border-dashed border-gray-300 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 mx-auto text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                </svg>
                <p class="text-sm sm:text-base text-gray-600 mt-2">No scheduled leaves this month</p>
            </div>
        @else
            <ul class="grid grid-cols-1 gap-3 sm:gap-4">
                @foreach($teamLeaves as $leave)
                    <li class="flex items-start space-x-3 sm:space-x-4 bg-white p-3 sm:p-4 rounded-md sm:rounded-lg shadow-sm sm:shadow-md border border-gray-100 hover:border-indigo-200 transition-colors group">
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden bg-gradient-to-br from-indigo-100 to-indigo-200 shadow">
                                @php
                                $profileImage = null;
                            
                                if ($leave->user && $leave->user->profile_image) {
                                    $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                                    $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;
                            
                                    if (file_exists(public_path($imagePath1))) {
                                        $profileImage = asset($imagePath1);
                                    } elseif (file_exists(public_path($imagePath2))) {
                                        $profileImage = asset($imagePath2);
                                    }
                                }
                            @endphp
                            
                            <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" alt="Profile Image" class="w-full h-full object-cover">
                            
                            </div>
                            <div class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-0.5 sm:p-1 shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 sm:h-3 sm:w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-1 sm:gap-0">
                                <div class="min-w-0">
                                    <p class="text-sm sm:text-base font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors truncate">
                                        {{ $leave->user->first_name }} {{ $leave->user->last_name }}
                                    </p>
                                    <p class="text-xs sm:text-sm text-gray-600 mt-1 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="truncate">
                                            {{ date('M d Y', strtotime($leave->start_date)) }} - {{ date('M d Y', strtotime($leave->end_date)) }}
                                        </span>
                                    </p>
                                </div>
                                <span class="px-2 py-0.5 sm:px-2 sm:py-1 text-xs font-semibold bg-green-500 text-white rounded-full whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }} {{ Str::plural('day', \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1) }}
                                </span>
                            </div>
                            
                            <div class="mt-1 sm:mt-2 flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm">
                                <span class="text-gray-600">Duration:</span>
                                <span class="text-gray-700 font-medium">
                                    {{ $leave->days_applied }} {{ Str::plural('day', $leave->days_applied) }}
                                </span>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

<div class="p-4 sm:p-6 rounded-lg sm:rounded-xl shadow-md sm:shadow-lg border border-indigo-100">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3 sm:mb-4 gap-2 sm:gap-0">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Compensatory Time Off
        </h2>
        
        <span class="relative inline-flex items-center gap-1 px-2 py-1 sm:px-3 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-indigo-100 text-indigo-800 shadow-inner hover:bg-indigo-200 transition-colors duration-200 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ $overtimeRequests->count() }} {{ Str::plural('request', $overtimeRequests->count()) }}
            <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap z-50">
                Compensatory time off requests
                <svg class="absolute text-gray-800 h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
            </span>
        </span>
    </div>
    
    @if($overtimeRequests->isEmpty())
        <div class="bg-white/80 p-4 sm:p-6 rounded-lg border border-dashed border-gray-300 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12 mx-auto text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <p class="text-sm sm:text-base text-gray-600 mt-2">Smooth sailing - no CTO requests</p>
        </div>
    @else
        <ul class="grid grid-cols-1 gap-3 sm:gap-4">
            @foreach($overtimeRequests as $overtime)
                <li class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 bg-white p-3 sm:p-4 rounded-md sm:rounded-lg shadow-sm sm:shadow-md border border-gray-100 hover:border-indigo-200 transition-colors group">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full overflow-hidden bg-gradient-to-br from-indigo-100 to-indigo-200 shadow">
                            @php
                                $profileImage = null;

                                if ($overtime->user && $overtime->user->profile_image) {
                                    $imagePath1 = 'storage/profile_images/' . $overtime->user->profile_image;
                                    $imagePath2 = 'storage/profile_pictures/' . $overtime->user->profile_image;

                                    if (file_exists(public_path($imagePath1))) {
                                        $profileImage = asset($imagePath1);
                                    } elseif (file_exists(public_path($imagePath2))) {
                                        $profileImage = asset($imagePath2);
                                    }
                                }
                            @endphp

                            <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" alt="Profile Image" class="w-full h-full object-cover">

                        </div>
                        <div class="absolute -bottom-1 -right-1 bg-yellow-500 rounded-full p-0.5 sm:p-1 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 sm:h-3 sm:w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-0 w-full">
                        <div class="flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-0">
                            <p class="text-sm sm:text-base font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors truncate">
                                {{ $overtime->user->first_name }} {{ $overtime->user->last_name }}
                            </p>
                            <span class="px-2 py-0.5 sm:px-2 sm:py-1 text-xs font-semibold bg-yellow-400 text-white rounded-full whitespace-nowrap">
                                {{ $overtime->working_hours_applied }} {{ Str::plural('hour', $overtime->working_hours_applied) }}
                            </span>
                        </div>
                        
                        <div class="mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-1 text-indigo-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            @php
                                $dates = explode(',', $overtime->inclusive_dates);
                                $startDate = \Carbon\Carbon::parse(trim($dates[0]))->format('M d, Y');
                                $endDate = \Carbon\Carbon::parse(trim(end($dates)))->format('M d, Y');
                            @endphp
                            <span class="text-xs sm:text-sm text-gray-600 truncate">
                                {{ $startDate }} – {{ $endDate }}
                            </span>
                        </div>
                        
                        <div class="mt-2 sm:mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                            <div class="bg-gray-50 p-2 sm:p-3 rounded text-xs sm:text-sm w-full sm:w-auto">
                                @php
                                    $status = $overtime->status;
                                    if ($overtime->hr_status !== 'pending') {
                                        $status = $overtime->hr_status;
                                    } elseif ($overtime->admin_status !== 'pending') {
                                        $status = $overtime->admin_status;
                                    } elseif ($overtime->supervisor_status !== 'pending') {
                                        $status = $overtime->supervisor_status;
                                    }
                                @endphp
                                <span class="font-medium text-gray-700">Status:</span>
                                <span class="inline-block px-2 sm:px-3 py-1 rounded-full text-xs font-semibold
                                    @switch($status)
                                        @case('approved') bg-green-100 text-green-600 @break
                                        @case('rejected') bg-red-100 text-red-600 @break
                                        @case('cancelled') bg-gray-200 text-gray-600 @break
                                        @case('Ready for Review') bg-blue-100 text-blue-600 @break
                                        @case('Waiting for Supervisor') bg-indigo-100 text-indigo-600 @break
                                        @default bg-yellow-100 text-yellow-600
                                    @endswitch">
                                    {{ ucfirst($status) }}
                                </span>
                            </div>
                            
                            <p class="text-xs sm:text-sm text-gray-600 sm:text-right whitespace-nowrap">
                                <span class="font-medium text-gray-700">Filed:</span> 
                                {{ \Carbon\Carbon::parse($overtime->date_filed)->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
</div>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    let currentMonth = new Date().getMonth() + 1;

    function loadRequests() {
        let leaveUrl = `/api/leaves?month=${currentMonth}`;
        let overtimeUrl = `/api/overtimes?month=${currentMonth}`;

        Promise.all([fetch(leaveUrl).then(res => res.json()), fetch(overtimeUrl).then(res => res.json())])
            .then(([leaves, overtimes]) => {
                let leaveContainer = document.getElementById("leaveContainer");
                let monthTitle = document.getElementById("monthTitle");

                let monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                monthTitle.textContent = `Requests for ${monthNames[currentMonth - 1]}`;

                leaveContainer.innerHTML = "";

                if (leaves.length === 0 && overtimes.length === 0) {
                    leaveContainer.innerHTML = `<p class="text-gray-600 text-center col-span-full">No requests found.</p>`;
                    return;
                }

                leaves.forEach(leave => {
                    leaveContainer.innerHTML += `
                    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6 mb-4 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <!-- Profile Image -->
                    <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-blue-100 bg-gray-100 flex-shrink-0">
                        <img src="${leave.profile_image}" 
                            class="w-full h-full object-cover" 
                            alt="Profile"
                            onerror="this.onerror=null; this.src='/img/default-avatar.png';">
                    </div>

                    <!-- Leave Details -->
                    <div class="flex-1">
                        <!-- Employee Name -->
                        <p class="font-bold text-gray-900 text-lg">${leave.first_name} ${leave.last_name}</p>

                        <!-- Leave Duration -->
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Leave Duration:</span>
                            <span class="text-sm font-semibold text-green-600">${leave.duration} day(s)</span>
                        </div>

                        <!-- Leave Dates -->
                        <div class="mt-2 text-sm text-gray-600">
                            <p>From: <span class="font-medium text-gray-700">${leave.start}</span></p>
                            <p>To: <span class="font-medium text-gray-700">${leave.end}</span></p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-3">
                            <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full ${
                                leave.status === 'Approved' ? 'bg-green-100 text-green-700' :
                                leave.status === 'Pending' ? 'bg-yellow-100 text-yellow-700' :
                                'bg-red-100 text-red-700'
                            }">
                                ${leave.status}
                            </span>
                        </div>
                    </div>
                </div>`;
                });

                overtimes.forEach(overtime => {
                    leaveContainer.innerHTML += `
                    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6 mb-4 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <!-- Profile Image -->
                    <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-blue-100 bg-gray-100 flex-shrink-0">
                        <img src="${overtime.profile_image}" 
                            class="w-full h-full object-cover" 
                            alt="Profile"
                            onerror="this.onerror=null; this.src='/img/default-avatar.png';">
                    </div>

                    <!-- Overtime Details -->
                    <div class="flex-1">
                        <!-- Employee Name -->
                        <p class="font-bold text-gray-900 text-lg">${overtime.first_name} ${overtime.last_name}</p>

                        <!-- Overtime Hours -->
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Used COCs:</span>
                            <span class="text-sm font-semibold text-blue-600">${overtime.hours} hour(s)</span>
                        </div>

                        <!-- Overtime Date -->
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Date: <span class="font-medium text-gray-700">${overtime.date}</span></p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-3">
                            <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full ${
                                overtime.admin_status === 'Pending' ? 'bg-yellow-100 text-yellow-700' :
                                overtime.admin_status === 'Ready for Review' ? 'bg-green-100 text-green-700' :
                                'bg-red-100 text-red-700'
                            }">
                                ${overtime.admin_status}
                            </span>
                        </div>
                    </div>
                </div>`;
                });
            })
            .catch(error => console.error("Error loading requests:", error));
    }

    document.getElementById("prevMonth").addEventListener("click", function () {
        currentMonth = currentMonth === 1 ? 12 : currentMonth - 1;
        loadRequests();
    });

    document.getElementById("nextMonth").addEventListener("click", function () {
        currentMonth = currentMonth === 12 ? 1 : currentMonth + 1;
        loadRequests();
    });

    loadRequests();
});
</script>

<style>
    @keyframes glow {
        0% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
        100% { opacity: 0.4; transform: scale(1); }
    }

    .animate-glow {
        animation: glow 2s infinite;
    }

             @keyframes float {
                 0% { transform: translateY(0); }
                 50% { transform: translateY(-8px); }
                 100% { transform: translateY(0); }
             }
         
             @keyframes confetti {
                 0% { transform: translateY(0) rotate(0deg); opacity: 1; }
                 100% { transform: translateY(50px) rotate(360deg); opacity: 0; }
             }
         
             .animate-float {
                 animation: float 3s ease-in-out infinite;
             }
         
             .animate-confetti {
                 animation: confetti 1.5s linear infinite;
             } 

             .animate-fade-in {
                animation: fadeIn 0.8s ease-in-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
</style>

<style scoped>
    .fc-event {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .fc-event:hover {
        transform: scale(1.07);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    }

    #leaveModal.show {
        display: flex;
    }
    #leaveModal .show {
        opacity: 1;
        transform: scale(1);
    }
</style>

<script>
    function closeModal() {
        const modal = document.getElementById('leaveModal');
        modal.classList.remove('show');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>

<script>
    function createFireworks() {
      const container = document.querySelector('#birthday-header div.absolute');
      
      const positions = [
        { left: '10%', top: '20%' },
        { left: '30%', top: '15%' },
        { left: '70%', top: '25%' },
        { left: '90%', top: '15%' },
        { left: '20%', top: '70%' },
        { left: '40%', top: '80%' },
        { left: '60%', top: '75%' },
        { left: '80%', top: '85%' }
      ];
    
      positions.forEach((pos, index) => {
        const firework = document.createElement('div');
        firework.className = 'firework-particle';
        firework.style.left = pos.left;
        firework.style.top = pos.top;
        firework.style.animationDelay = `${index * 0.5}s`;
        container.appendChild(firework);
      });
    }
    
    function launchConfetti() {
      const canvas = document.getElementById('confetti-canvas');
      canvas.style.display = 'block';
      
      const confettiSettings = {
        particleCount: 200,
        spread: 90,
        origin: { y: 0.6 },
        colors: ['#ff0000', '#ffff00', '#00ff00', '#00ffff', '#ff00ff', '#ff8c00'],
        shapes: ['circle', 'square', 'star'],
        scalar: 1.2
      };
      
      confetti.create(canvas, {
        resize: true,
        useWorker: true
      })(confettiSettings);
      
      setTimeout(() => {
        canvas.style.display = 'none';
      }, 4000);
    }
    
    document.addEventListener('DOMContentLoaded', () => {
      createFireworks();
      setTimeout(launchConfetti, 1000);
    });
    </script>
    
    <style>
      @keyframes float {
        0%, 100% { transform: translateY(0) rotate(-1deg); }
        50% { transform: translateY(-20px) rotate(1deg); }
      }
      .animate-float {
        animation: float 3s ease-in-out infinite;
      }
      
      @keyframes pulse {
        0%, 100% { opacity: 0.8; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.05); }
      }
      .animate-pulse {
        animation: pulse 2s ease-in-out infinite;
      }
      
      @keyframes firework {
        0% { transform: translate(-50%, -50%) scale(0); opacity: 1; }
        80% { opacity: 1; }
        100% { transform: translate(-50%, -50%) scale(1.5); opacity: 0; }
      }
      .firework-particle {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        animation: firework 1.5s ease-out infinite;
        opacity: 0;
      }
      .firework-particle::before, .firework-particle::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
      }
      .firework-particle::before {
        width: 30px;
        height: 30px;
        box-shadow: 
          0 0 0 3px rgba(255,50,0,0.8),
          0 0 0 6px rgba(255,165,0,0.6),
          0 0 0 9px rgba(255,255,0,0.4);
      }
      .firework-particle::after {
        width: 15px;
        height: 15px;
        box-shadow: 
          0 0 0 2px rgba(0,200,255,0.8),
          0 0 0 4px rgba(150,0,255,0.6);
      }
      
      .firework-particle:nth-child(1)::before { box-shadow: 0 0 0 3px #ff3366, 0 0 0 6px #ff9966, 0 0 0 9px #ffcc66; }
      .firework-particle:nth-child(2)::before { box-shadow: 0 0 0 3px #66ff33, 0 0 0 6px #33ff99, 0 0 0 9px #33ffcc; }
      .firework-particle:nth-child(3)::before { box-shadow: 0 0 0 3px #3366ff, 0 0 0 6px #3399ff, 0 0 0 9px #33ccff; }
      .firework-particle:nth-child(4)::before { box-shadow: 0 0 0 3px #cc33ff, 0 0 0 6px #9966ff, 0 0 0 9px #6699ff; }
      .firework-particle:nth-child(5)::before { box-shadow: 0 0 0 3px #ff33cc, 0 0 0 6px #ff66cc, 0 0 0 9px #ff99cc; }
      .firework-particle:nth-child(6)::before { box-shadow: 0 0 0 3px #ffff33, 0 0 0 6px #ffcc33, 0 0 0 9px #ff9933; }
      .firework-particle:nth-child(7)::before { box-shadow: 0 0 0 3px #33ffff, 0 0 0 6px #66ffff, 0 0 0 9px #99ffff; }
      .firework-particle:nth-child(8)::before { box-shadow: 0 0 0 3px #ff3333, 0 0 0 6px #ff6633, 0 0 0 9px #ff9933; }
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
@endsection
@notifyCss