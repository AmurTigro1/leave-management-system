@php
    $pendingLeaveCount = App\Models\Leave::where('status', 'pending')
        ->where('admin_status', 'approved')
        ->count();
    $rejectedLeaveCount = App\Models\Leave::where('leave_type', 'Mandatory Leave')
        ->where('supervisor_status', 'rejected')
        ->where('hr_status', '!=', 'rejected')
        ->count();
    $pendingOvertimeCount = App\Models\OvertimeRequest::where('status', 'pending')
        ->where('admin_status', 'Ready for Review')
        ->count();

    $pendingRequestsCount = $pendingLeaveCount + $pendingOvertimeCount + $rejectedLeaveCount;
@endphp

<div x-data="{ sidebarOpen: window.innerWidth > 1024 }" 
    x-init="window.addEventListener('resize', () => sidebarOpen = window.innerWidth > 1024)" 
    class="h-screen">

    <button @click="sidebarOpen = true" 
        x-show="!sidebarOpen"
        class="lg:hidden fixed top-4 left-4 z-40 p-2 rounded-md text-gray-800 bg-white shadow-md">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <div x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 text-white shadow-lg transition-transform duration-300 ease-in-out 
        lg:translate-x-0 lg:static lg:inset-0 ">
        <aside class="w-64 bg-gray-50 h-screen shadow-md fixed">
            <!-- Header Section -->
            <div class="flex items-center justify-between px-4 py-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-700">
                        <img src="/img/dilg-main.png" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-black text-md font-semibold">LMS & CTO SYSTEM</h1>
                        <p class="text-black text-sm">DILG BOHOL</p>
                    </div>
                </div>

                <!-- Close Button (Only in Mobile) -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 space-y-2 m-4 text-gray-600">
                <!-- Dashboard Link -->
                <a href="{{ route('hr.dashboard') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.dashboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Leaderboard Link -->
                <a href="{{ route('hr.leaderboard') }}" class="hover:bg-gray-200 flex items-center p-2 rounded-md {{ request()->routeIs('hr.leaderboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    <span>Leaderboard</span>
                </a>

                <!-- Make a Request Dropdown -->
                <div class="relative">
                    <input type="checkbox" id="dropdown-toggle1" class="peer hidden">
                    
                    <label for="dropdown-toggle1" class="flex items-center p-2 space-x-2 rounded-md w-full text-gray-500 hover:bg-gray-200 focus:bg-white cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <span>Make a Request</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </label>
                
                    <div class="absolute left-0 top-full hidden peer-checked:flex flex-col w-48 mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-20">
                        <a href="{{ route('hr.make_leave_request') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Leave Request
                        </a>
                        <a href="{{ route('hr.make_cto_request') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            CTO Request
                        </a>
                    </div>
                </div>

                <!-- List of Requests -->
                <a href="{{ route('hr.requests') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.requests', 'hr.leave_details') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                    <span>List of Requests</span>
                    @if($pendingRequestsCount > 0)
                        <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">
                            {{ $pendingRequestsCount }}
                        </span>
                    @endif
                </a>

                <!-- Employees -->
                <a href="{{ route('hr.on_leave') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.on_leave') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span>Employees</span>
                </a>

                <!-- Users -->
                <a href="{{ route('hr.users') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.users') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <span>Users</span>
                </a>

                <!-- COC Logs -->
                <a href="{{ route('coc_logs.hr') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('coc_logs*') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    </svg>
                    <span>COC Logs</span>
                </a>

                <!-- Holidays -->
                <a href="{{ route('hr.holidays.index') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.holidays.index') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                    </svg>
                    <span>Holidays</span>
                </a>

                <!-- Calendar-->
                <a href="{{ route('hr.holiday.calendar') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.holiday.calendar') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Calendar</span>
                </a>
            </nav>
        </aside>
    </div>

    <!-- Main Content -->
    {{-- <div :class="sidebarOpen ? 'ml-64' : 'ml-0'" class="flex-1 transition-all duration-300 ease-in-out">
        <button 
            @click="sidebarOpen = true" 
            class="absolute left-4 inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 m-5"
            x-show="!sidebarOpen"
            x-transition>
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div> --}}
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownToggle1 = document.getElementById('dropdown-toggle1');
        const dropdownToggle2 = document.getElementById('dropdown-toggle2');

        // Close dropdown 2 when dropdown 1 is opened
        if (dropdownToggle1) {
            dropdownToggle1.addEventListener('change', function () {
                if (this.checked && dropdownToggle2) {
                    dropdownToggle2.checked = false;
                }
            });
        }

        // Close dropdown 1 when dropdown 2 is opened
        if (dropdownToggle2) {
            dropdownToggle2.addEventListener('change', function () {
                if (this.checked && dropdownToggle1) {
                    dropdownToggle1.checked = false;
                }
            });
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (event) {
            if (dropdownToggle1) {
                const isClickInsideDropdown1 = event.target.closest('.relative') === document.querySelector('#dropdown-toggle1')?.closest('.relative');
                if (!isClickInsideDropdown1) {
                    dropdownToggle1.checked = false;
                }
            }

            if (dropdownToggle2) {
                const isClickInsideDropdown2 = event.target.closest('.relative') === document.querySelector('#dropdown-toggle2')?.closest('.relative');
                if (!isClickInsideDropdown2) {
                    dropdownToggle2.checked = false;
                }
            }
        });
    });
</script>