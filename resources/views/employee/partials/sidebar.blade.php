{{-- <div x-data="{ sidebarOpen: false }">
    <!-- Burger Button (Hidden when Sidebar is Open) -->
    <button @click="sidebarOpen = !sidebarOpen" 
        x-show="!sidebarOpen"
        class="lg:hidden fixed top-4 left-4 z-40 p-2 rounded-md text-gray-800">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>

    <!-- Sidebar -->
    <div x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-30 w-64 bg-gray-800 text-white shadow-lg transition-transform duration-300 ease-in-out 
        lg:translate-x-0 lg:static lg:inset-0">
        <aside class="w-64 bg-gray-800 h-screen shadow-md fixed">
            <!-- Header Section -->
            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-700">
                <!-- User Info -->
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-700">
                        <img src="/img/dilg-main.png" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-200 truncate" style="max-width: 8rem;">DILG - Bohol</p>
                        <p class="text-sm text-gray-300">Province</p>
                    </div>
                </div>

                <!-- Close Button (Only in Mobile) -->
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 space-y-2">
                <a href="{{ route('lms.dashboard') }}" class="m-3 flex items-center px-4 py-3 rounded-lg transition-all duration-200 
                        hover:bg-blue-600 {{ request()->routeIs('lms.dashboard') ? 'bg-blue-600 shadow-lg' : 'text-gray-300' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('employee.leaderboard')}}" 
                    class="m-3 flex items-center text-white px-4 py-3 text-sm rounded-lg transition-all duration-200 
                        hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
                        {{ request()->routeIs('employee.leaderboard') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Leaderboard
                </a>

                <a href="{{ route('employee.make_request') }}" class="m-3 flex items-center px-4 py-3 rounded-lg transition-all duration-200 
                        hover:bg-blue-600 {{ request()->routeIs('employee.make_request') ? 'bg-blue-600 shadow-lg' : 'text-gray-300' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Make a Request
                </a>

                <a href="{{ route('employee.leave_request') }}" class="m-3 flex items-center px-4 py-3 rounded-lg transition-all duration-200 
                        hover:bg-blue-600 {{ request()->routeIs('employee.leave_request') ? 'bg-blue-600 shadow-lg' : 'text-gray-300' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    My Request
                </a> 
                <a href="{{ route('holiday.calendar') }}" 
                    class="m-3 flex items-center text-white px-4 py-3 text-sm rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
                        {{ request()->routeIs('holiday.calendar') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Holidays
                </a>     
            </nav>
        </aside>
    </div>
</div>
<style>
    [x-cloak] { display: none !important; }
</style> --}}
<!-- Sidebar Component -->
<div x-data="{ isSidebarOpen: true }" class="min-h-screen flex">
    <!-- Sidebar -->
    <div 
        :class="isSidebarOpen ? 'w-64' : 'w-0 opacity-0'" 
        class="bg-white border-r border-gray-300 rounded-lg transition-all duration-300 ease-in-out h-screen flex flex-col fixed overflow-hidden"
    >
        <div class="relative p-4 flex items-center justify-between bg-gray-50 border border-b-gray-200">
            <div class="flex items-center space-x-3" x-show="isSidebarOpen" x-transition>
                <img src="/img/dilg-main.png" alt="DILG LOGO" class="w-12 h-12">
                <h1 class="text-md font-semibold ">DILG-BOHOL PROVINCE</h1>
            </div>
            <button 
                @click="isSidebarOpen = false" 
                class="text-gray-500 hover:text-gray-700 focus:outline-none"
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

<div x-show="isSidebarOpen" x-transition class="flex-1 p-4 space-y-2 text-gray-600 bg-gray-50">
    <!-- Dashboard Link -->
    <a href="{{ route('lms.dashboard') }}" class="flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('lms.dashboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        <span>Dashboard</span>
    </a>

    <!-- Leaderboard Link -->
    <a href="{{ route('employee.leaderboard') }}" class="flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('employee.leaderboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>
        <span>Leaderboard</span>
    </a>

    <!-- Make a Request Link -->
    <a href="{{ route('employee.make_request') }}" class="flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('employee.make_request') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
        </svg>
        <span>Make a Request</span>
    </a>

    <!-- My Request Link -->
    <a href="{{ route('employee.leave_request') }}" class="flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('employee.leave_request') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
        </svg>
        <span>My Request</span>
    </a>

    <!-- Holidays Link -->
    <a href="{{ route('holiday.calendar') }}" class="flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('holiday.calendar') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
        </svg>
        <span>Holidays</span>
    </a>
</div>
    </div>

    <!-- Main Content -->
    <div :class="isSidebarOpen ? 'ml-64' : 'ml-0'" class="flex-1 transition-all duration-300 ease-in-out">

            <!-- Sidebar Toggle Button -->
            <button 
                @click="isSidebarOpen = true" 
                class="absolute left-4 inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 m-5"
                x-show="!isSidebarOpen"
                x-transition
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
    
    </div>
</div>

