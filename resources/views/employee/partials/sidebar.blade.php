<!-- Sidebar Component -->
<div x-data="{ 
        isSidebarOpen: window.innerWidth > 1024, 
        updateSidebar() { 
            this.isSidebarOpen = window.innerWidth > 1024;
        } 
    }" 
    x-init="updateSidebar(); window.addEventListener('resize', () => updateSidebar())"
    class="min-h-screen flex">
    <!-- Sidebar -->
    <div 
        :class="isSidebarOpen ? 'w-64' : 'w-0 opacity-0'" 
        class="bg-white border-r border-gray-300 rounded-lg transition-all duration-300 ease-in-out h-screen flex flex-col fixed overflow-hidden"
    >
        <div class="relative p-4 flex items-center justify-between bg-gray-50 border border-b-gray-200">
            <div class="flex items-center space-x-3" x-show="isSidebarOpen" x-transition>
                <img src="/img/dilg-main.png" alt="DILG LOGO" class="w-12 h-12">
                <h1 class="text-md font-semibold">DILG-BOHOL PROVINCE</h1>
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
            <a href="{{ route('lms_cto.dashboard') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('lms_cto.dashboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span>Dashboard</span>
            </a>

    <!-- Leaderboard Link -->
    <a href="{{ route('employee.leaderboard') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('employee.leaderboard') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>
        <span>Leaderboard</span>
    </a>

    <div class="relative">
        <!-- Hidden Checkbox for Toggle -->
        <input type="checkbox" id="dropdown-toggle" class="peer hidden">
        
        <!-- My Request Dropdown Button -->
        <label for="dropdown-toggle" class="flex items-center p-2 space-x-2 rounded-md w-full text-gray-500 hover:bg-gray-200 focus:bg-white cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
            </svg>
            <span>Make a Request</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </label>
    
        <!-- Dropdown Menu -->
        <div class="absolute left-0 top-full hidden peer-checked:flex flex-col w-48 mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
            <a href="{{ route('employee.make_request') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                Leave Request
            </a>
            <a href="{{ route('cto.overtime_request') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                Overtime Request
            </a>
        </div>
    </div>    

    <a href="{{ route('employee.leave_request') }}" class="hover:bg-gray-200 flex items-center p-2 space-x-2 rounded-md {{ request()->routeIs('employee.leave_request') ? 'bg-white shadow-lg' : 'text-gray-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
        </svg>          
        <span>List of Request </span>
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

