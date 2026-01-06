<!-- Sidebar Component -->
@php
    $pendingLeaveCount = App\Models\Leave::where('admin_status', 'pending')->count();
    $pendingOvertimeCount = App\Models\OvertimeRequest::where('admin_status', 'pending')->count();
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
                <a href="{{ route('admin.dashboard') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.leaderboard') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('admin.leaderboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                    </svg>
                    Leaderboard
                </a>

                <a href="{{ route('employee-balances.index') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('employee-balances.index') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Employee Balance</span>
                </a>

                <div class="relative">
                    <input type="checkbox" id="dropdown-toggle1" class="peer hidden">

                    <label for="dropdown-toggle1"
                        class="flex items-center p-2 space-x-2 rounded-md w-full text-gray-500 hover:bg-gray-200 focus:bg-white cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>Make a Request</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </label>

                    <div
                        class="absolute left-0 top-full hidden peer-checked:flex flex-col w-48 mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-20">
                        <a href="{{ route('admin.make_leave_request') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Leave Request
                        </a>
                        <a href="{{ route('admin.make_cto_request') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            CTO Request
                        </a>
                    </div>
                </div>

                <div class="relative">
                    <input type="checkbox" id="dropdown-toggle2" class="peer hidden">

                    <label for="dropdown-toggle2"
                        class="flex items-center p-2 space-x-2 rounded-md w-full text-gray-500 hover:bg-gray-200 focus:bg-white cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span>List of Requests</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        @if ($pendingRequestsCount > 0)
                            <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">
                                {{ $pendingRequestsCount }}
                            </span>
                        @endif
                    </label>

                    <div
                        class="absolute left-0 top-full hidden peer-checked:flex flex-col w-48 mt-1 bg-white border border-gray-300 rounded-md shadow-lg z-10">
                        <a href="{{ route('admin.my_requests') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            My Leave Requests
                        </a>
                        <a href="{{ route('admin.cto_requests') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            My CTO Requests
                        </a>
                        <a href="{{ route('admin.my_extend_applications') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Untimely Leave Applications
                        </a>
                        <a href="{{ route('admin.requests') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            Pending Requests
                            @if ($pendingRequestsCount > 0)
                                <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">
                                    {{ $pendingRequestsCount }}
                                </span>
                            @endif
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.on_leave') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('admin.on_leave') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                            clip-rule="evenodd" />
                        <path
                            d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                    </svg>

                    <span>Employees</span>
                </a>

                <a href="{{ route('coc_logs.admin') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('coc_logs*') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>COC Logs</span>
                </a>

                <a href="{{ route('admin.holiday.calendar') }}"
                    class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('admin.holiday.calendar') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
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
