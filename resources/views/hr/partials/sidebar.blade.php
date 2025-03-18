@php
    $pendingRequestsCount = App\Models\Leave::where('status', 'pending')->count();
@endphp
<div x-data="{ sidebarOpen: window.innerWidth > 1024 }" 
    x-init="window.addEventListener('resize', () => sidebarOpen = window.innerWidth > 1024)" 
    class="h-screen">

    <!-- Burger Button (Only Appears When Sidebar is Closed) -->
    <button @click="sidebarOpen = true" 
        x-show="!sidebarOpen"
        class="lg:hidden fixed top-4 left-4 z-40 p-2 rounded-md text-gray-800 bg-white shadow-md z-[9999]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Sidebar -->
    <div x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 text-white shadow-lg transition-transform duration-300 ease-in-out 
        lg:translate-x-0 lg:static lg:inset-0">
        <aside class="w-64 bg-gray-50 h-screen shadow-md fixed">
            <!-- Header Section -->
            <div class="flex items-center justify-between px-4 py-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-700">
                        <img src="/img/dilg-main.png" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h1 class="text-black text-md font-semibold">HR</h1>
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

    <div x-show="isSidebarOpen" x-transition class="flex-1 p-4 space-y-2 text-gray-600 bg-gray-50">
        <!-- Dashboard Link -->
        <a href="{{ route('hr.dashboard') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.dashboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            <span>Dashboard</span>
        </a> 

        <a href="{{ route('hr.on_leave') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.on_leave') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a4 4 0 100 8 4 4 0 000-8zm-7 14a7 7 0 0114 0H3z" clip-rule="evenodd" />
            </svg>
            <span>Employees</span>
        </a>

        <a href="{{ route('hr.leave_requests') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('hr.leave_requests') || request()->routeIs('hr.leave_details') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
            </svg>          
            <span>List of Request</span>
            @if($pendingRequestsCount > 0)
                <span class="bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">
                    {{ $pendingRequestsCount }}
                </span>
            @endif
        </a>
        

<!-- Holidays Link -->
<a href="{{ route('holiday.calendar') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('holiday.calendar') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
    </svg>
    <span>Holidays</span>
</a>
</div>
</div>

    <!-- Main Content -->
    <div :class="sidebarOpen ? 'ml-64' : 'ml-0'" class="flex-1 transition-all duration-300 ease-in-out">
        <button 
            @click="isSidebarOpen = true" 
            class="absolute left-4 inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 m-5"
            x-show="!isSidebarOpen"
            x-transition>
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
