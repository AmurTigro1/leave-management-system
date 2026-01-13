@php
    $pendingLeaveCount = App\Models\Leave::where('status', 'approved')->count();
    $pendingOvertimeCount = App\Models\OvertimeRequest::where('status', 'approved')->count();
    $pendingRequestsCount = $pendingLeaveCount + $pendingOvertimeCount;
@endphp

<div x-data="{ sidebarOpen: window.innerWidth > 1024 }" x-init="window.addEventListener('resize', () => sidebarOpen = window.innerWidth > 1024)" class="h-screen">

    <button @click="sidebarOpen = true" x-show="!sidebarOpen"
        class="lg:hidden fixed top-4 left-4 z-40 p-2 rounded-md text-gray-800 bg-white shadow-md">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <div x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 text-white shadow-lg transition-transform duration-300 ease-in-out
        lg:translate-x-0 lg:static lg:inset-0 ">
        <aside class="w-64 bg-gray-50 h-screen shadow-md fixed">
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

                <button @click="sidebarOpen = false" class="lg:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <nav class="mt-6 space-y-2 m-4 text-gray-600">
                <a href="{{ route('supervisor.dashboard') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('supervisor.dashboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('supervisor.leaderboard') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('supervisor.leaderboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Leaderboard
                </a>

                <a href="{{ route('supervisor.on_leave') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('supervisor.on_leave') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8zm-7 14a7 7 0 0114 0H3z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Employees</span>
                </a>

                <a href="{{ route('supervisor.requests') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('supervisor.requests') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                    </svg>
                    <span>List of Requests</span>
                    @if ($pendingRequestsCount > 0)
                        <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">
                            {{ $pendingRequestsCount }}
                        </span>
                    @endif
                </a>


                <a href="{{ route('supervisor.untimely-leave-applications') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md
                {{ request()->routeIs('employee.leave_request') ? 'bg-white shadow-lg' : 'text-gray-500' }}">

                    <!-- Clock / Late Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>

                    <span>Untimely Leave Applications</span>
                </a>


                <a href="{{ route('supervisor.my_untimely_sick_applications') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md
                {{ request()->routeIs('employee.leave_request') ? 'bg-white shadow-lg' : 'text-gray-500' }}">

                    <svg
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="size-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2" /> <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /> <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6M9 12h6" />
                    </svg>

                    <span>Untimely Sick Leave Applications</span>
                </a>


                <a href="{{ route('supervisor.holiday.calendar') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('supervisor.holiday.calendar') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Holidays</span>
                </a>
            </nav>
        </aside>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggle1 = document.getElementById('dropdown-toggle1');
        const dropdownToggle2 = document.getElementById('dropdown-toggle2');

        if (dropdownToggle1) {
            dropdownToggle1.addEventListener('change', function() {
                if (this.checked && dropdownToggle2) {
                    dropdownToggle2.checked = false;
                }
            });
        }

        if (dropdownToggle2) {
            dropdownToggle2.addEventListener('change', function() {
                if (this.checked && dropdownToggle1) {
                    dropdownToggle1.checked = false;
                }
            });
        }

        document.addEventListener('click', function(event) {
            if (dropdownToggle1) {
                const isClickInsideDropdown1 = event.target.closest('.relative') === document
                    .querySelector('#dropdown-toggle1')?.closest('.relative');
                if (!isClickInsideDropdown1) {
                    dropdownToggle1.checked = false;
                }
            }

            if (dropdownToggle2) {
                const isClickInsideDropdown2 = event.target.closest('.relative') === document
                    .querySelector('#dropdown-toggle2')?.closest('.relative');
                if (!isClickInsideDropdown2) {
                    dropdownToggle2.checked = false;
                }
            }
        });
    });
</script>
