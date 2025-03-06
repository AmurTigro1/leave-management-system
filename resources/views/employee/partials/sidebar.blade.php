<div x-data="{ sidebarOpen: false }">
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

<!-- Sidebar -->
<div 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg text-gray-600 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
>
<aside class="w-64 bg-gray-800 h-screen shadow-md fixed">
    <!-- Header Section -->
    <div class="flex items-center bg-gray-800 justify-between px-4 py-1 border-b border-gray-200 overflow-y-auto ">
        <!-- User Info Section -->
        <div class="flex items-center space-x-4">
            <!-- User Profile Picture -->
            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                <img src="/img/dilg-main.png" class="w-full h-full object-cover">
            </div>
            <!-- User Greeting -->
            <div class="flex-1">
                <p 
                    class="text-lg font-semibold text-gray-200 truncate" 
                    style="max-width: 8rem;" >
                    DILG - Bohol
                </p>
                <p class="text-sm text-gray-300 text-center">Province </p>
            </div>
        </div>
    </div>
        <!-- Navigation Links -->
        <nav class="mt-6 space-y-2 flex-1 overflow-y-auto px-4">
            @php
                $navLinks = [
                    ['route' => 'lms.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
                    ['route' => 'employee.leaderboard', 'label' => 'Leaderboard', 'icon' => 'leaderboard'],
                    ['route' => 'employee.make_request', 'label' => 'Make a Request', 'icon' => 'plus-circle'],
                    ['route' => 'employee.leave_request', 'label' => 'My Request', 'icon' => 'clipboard-list'],
                    ['route' => 'holiday.calendar', 'label' => 'Holidays', 'icon' => 'calendar'],
                ];
            @endphp
            
            @foreach ($navLinks as $link)
                <a href="{{ route($link['route']) }}"
                   class="flex items-center px-4 py-3 text-sm rounded-lg transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white {{ request()->routeIs($link['route']) ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <use href="#{{ $link['icon'] }}"></use>
                    </svg>
                    {{ $link['label'] }}
                </a>
            @endforeach
        <nav class="mt-6 space-y-2">
            <!-- Dashboard Link -->
            <a href="{{ session('system') === 'lms' ? route('lms.dashboard') : route('cto.dashboard') }}" 
                class="m-3 flex items-center text-white px-4 py-3 text-sm rounded-lg transition-all duration-200 
                    hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
                    {{ request()->routeIs('lms.dashboard') || request()->routeIs('cto.dashboard') || request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Dashboard
            </a>
        
        
            <!-- Make a Request Link -->
            <a href="{{ route('employee.make_request')}}" 
               class="m-3 flex items-center text-white px-4 py-3 text-sm rounded-lg transition-all duration-200 
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
                      {{ request()->routeIs('employee.make_request') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Make a Request
            </a>
        
            <!-- My Request Link -->
            <a href="{{ route('employee.leave_request')}}" 
               class="m-3 flex items-center text-white px-4 py-3 text-sm rounded-lg transition-all duration-200 
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
                      {{ request()->routeIs('employee.leave_request') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                My Request
            </a>    

            <!-- Holidays Link -->
            <a href="{{ route('holiday.calendar')}}" 
               class="m-3 flex items-center text-white px-4 py-3 text-sm rounded-lg transition-all duration-200 
                      hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
                      {{ request()->routeIs('holiday.calendar') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Holidays
            </a>  
        </nav>
    </aside>
</div>
<style>
    [x-cloak] { display: none !important; }
</style>
