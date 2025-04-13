@extends('layouts.sidebar-header')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6 lg:py-8 max-w-7xl">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4 sm:mb-6 lg:mb-8 gap-2 sm:gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Holiday Calendar</h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">View and manage your organization's holidays</p>
        </div>
    </div>

    <!-- Stats Cards - Responsive Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
        <!-- Total Holidays Card -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Total Holidays</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $groupedHolidays->flatten()->count() }}</p>
                </div>
                <div class="p-2 sm:p-3 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 sm:h-8 w-6 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Regular Holiday Card -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Regular Holiday</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $groupedHolidays->flatten()->where('type', 'regular')->count() }}</p>
                </div>
                <div class="p-2 sm:p-3 rounded-lg text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 sm:h-8 w-6 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7V4a1 1 0 00-1-1H9a1 1 0 00-1 1v3m4 4v8m-7-4h14" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Special Non-Working Day Card -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">Special Non-Working Day</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $groupedHolidays->flatten()->where('type', 'special')->count() }}</p>
                </div>
                <div class="p-2 sm:p-3 rounded-lg bg-amber-50 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 sm:h-8 w-6 sm:w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18v-6m0 0l-2 2m2-2l2 2" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12l-3-3m3 3l3-3m-3 3l-3 3m3-3l3 3" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- National Holiday Card -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-gray-500">National Holiday</p>
                    <p class="mt-1 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $groupedHolidays->flatten()->where('type', 'national')->count() }}</p>
                </div>
                <div class="p-2 sm:p-3 rounded-lg text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 sm:h-8 w-6 sm:w-8" viewBox="0 0 512 512" fill="none">
                        <path fill="#0038A8" d="M0 0h512v256H0z" />
                        <path fill="#CE1126" d="M0 256h512v256H0z" />
                        <path fill="#FFF" d="M0 0l256 256L0 512V0z" />
                        <circle cx="112" cy="256" r="50" fill="#FCD116" />
                        <path fill="#FCD116" d="M112 194l15 45h47l-38 28 15 45-38-28-38 28 15-45-38-28h47z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Overview Section -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden mb-6 sm:mb-8 border border-gray-100">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-semibold text-white">Calendar Overview</h2>
                <span class="inline-flex items-center px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-blue-700 text-white">
                    {{ $selectedYear }}
                </span>
            </div>
        </div>

        <div class="p-4 sm:p-6 relative overflow-visible">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($calendarData as $month => $monthData)
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow relative">
                        <div class="bg-gray-50 px-3 sm:px-4 py-2 sm:py-3 border-b border-gray-200">
                            <h3 class="text-base sm:text-lg font-medium text-center text-gray-700">
                                {{ $monthData['name'] }} {{ $monthData['year'] }}
                            </h3>
                        </div>

                        <div class="p-2 sm:p-3">
                            <div class="grid grid-cols-7 gap-1 mb-1">
                                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                    <div class="text-xs font-medium text-center py-1 
                                        {{ $day === 'Sun' ? 'text-red-500' : 'text-gray-500' }}">
                                        {{ substr($day, 0, 1) }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="grid grid-cols-7 gap-1 relative overflow-visible">
                                @php
                                    $firstDay = \Carbon\Carbon::create($monthData['year'], $month, 1);
                                    $startDay = $firstDay->dayOfWeek;
                                    $daysInMonth = $firstDay->daysInMonth;
                                @endphp

                                @for($i = 0; $i < $startDay; $i++)
                                    <div class="h-6 sm:h-8"></div>
                                @endfor

                                @for($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $dayData = $monthData['days'][$day];
                                        $hasHoliday = $dayData['holidays']->isNotEmpty();
                                        $isSunday = \Carbon\Carbon::create($monthData['year'], $month, $day)->isSunday();
                                    @endphp

                                    <div 
                                        class="relative group h-8 sm:h-10 flex items-center justify-center text-xs sm:text-sm font-medium
                                            {{ $isSunday ? 'text-red-500' : 'text-gray-700' }}
                                            {{ $hasHoliday ? 'bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg' : '' }}"
                                        x-data="{ showTooltip: false, tooltipPosition: '' }"
                                        @mouseenter="
                                            showTooltip = true;
                                            let rect = $el.getBoundingClientRect();
                                            tooltipPosition = (rect.right > window.innerWidth - 150) ? 'left' : 
                                                            (rect.left < 150) ? 'right' : 'center';
                                        "
                                        @mouseleave="showTooltip = false"
                                    >
                                        {{ $day }}

                                        @if($hasHoliday)
                                            <span class="absolute bottom-0.5 right-0.5 w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-blue-500"></span>

                                            @php
                                                $uniqueHolidays = $dayData['holidays']->pluck('name')->unique();
                                            @endphp
                                            
                                            <div x-show="showTooltip" 
                                                class="absolute z-10 bottom-8 sm:bottom-10 left-1/2 transform -translate-x-1/2 w-40 sm:w-48 bg-black text-white text-xs rounded-md p-2 shadow-md"
                                                style="display: none;"
                                            >
                                                <strong class="block">{{ \Carbon\Carbon::create($monthData['year'], $month, $day)->format('F j, Y') }}</strong>
                                                @foreach($uniqueHolidays as $holiday)
                                                    <div>{{ $holiday }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Holiday List Section -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden border border-gray-100">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-4 sm:px-6 py-3 sm:py-4">
            <h2 class="text-lg sm:text-xl font-semibold text-white">Holiday List</h2>
        </div>
        <div class="p-4 sm:p-6">
            @if($groupedHolidays->isEmpty())
                <div class="text-center py-6 sm:py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 sm:h-12 w-10 sm:w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-base sm:text-lg font-medium text-gray-900">No holidays found</h3>
                    <p class="mt-1 text-sm text-gray-500">Add holidays to see them displayed here.</p>
                </div>
            @else
                <div class="space-y-4 sm:space-y-6 lg:space-y-8">
                    @foreach($groupedHolidays as $month => $holidays)
                        <div>
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2 sm:mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 sm:h-5 w-4 sm:w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                {{ $month }}
                            </h3>
                            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Holiday Name</th>
                                            <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recurrence</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($holidays as $holiday)
                                            @php
                                                $isSunday = \Carbon\Carbon::parse($holiday->date)->isSunday();
                                            @endphp
                                            <tr class="{{ $isSunday ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                                <td class="px-3 sm:px-6 py-3 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="text-sm font-medium {{ $isSunday ? 'text-red-600' : 'text-gray-900' }}">
                                                            {{ \Carbon\Carbon::parse($holiday->date)->format('jS F, Y') }}
                                                        </div>
                                                        @if($isSunday)
                                                            <span class="ml-1 sm:ml-2 px-1 sm:px-2 inline-flex text-xs leading-4 sm:leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Sun
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $holiday->name }}</div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-4 sm:leading-5 font-semibold rounded-full 
                                                        {{ $holiday->type == 'regular' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ ucfirst($holiday->type) }}
                                                    </span>
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                                    @if($holiday->repeats_annually)
                                                        <span class="inline-flex items-center text-green-600">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 sm:h-4 w-3 sm:w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Annual
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center text-gray-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 sm:h-4 w-3 sm:w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            One-time
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection