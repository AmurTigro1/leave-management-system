
<div 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-lg text-gray-600 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
>
<aside class="w-64 bg-white h-screen shadow-md fixed">
    <!-- Header Section -->
    <div class="flex items-center bg-white justify-between px-4 py-1 border-b border-gray-200 overflow-y-auto ">
        <!-- User Info Section -->
        <div class="flex items-center space-x-4">
            <!-- User Profile Picture -->
            <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                @if (auth()->user()->profile_image)
                    <img src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                    </svg>
                @endif
            </div>
            <!-- User Greeting -->
            <div class="flex-1">
                <p 
                    class="text-lg font-semibold text-gray-800 truncate" 
                    title="{{ Auth::user()->name }}" 
                    style="max-width: 8rem;" >
                
                    Hello, {{ Auth::user()->name }}
                </p>
                <p class="text-sm text-gray-500">Welcome back!</p>
            </div>
        </div>
        
        <!-- Close Button -->
        <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-800 lg:hidden" aria-label="Close Sidebar">
            ✕
        </button>
    </div>

<!-- Navigation Links -->
<nav class="mt-6 space-y-2">
    <!-- Dashboard Link -->
    <a href="{{ route('employee.dashboard') }}" 
       class="flex items-center px-4 py-3 text-sm rounded-lg transition-all duration-200 
              hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
              {{ request()->routeIs('employee.dashboard') || request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
    </a>

    <!-- Make a Request Link -->
    <a href="{{ route('employee.make_request')}}" 
       class="flex items-center px-4 py-3 text-sm rounded-lg transition-all duration-200 
              hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
              {{ request()->routeIs('employee.make_request')  || request()->routeIs('boarding_house.edit') || request()->routeIs('rooms.edit') || request()->routeIs('boarding_house-rooms.index') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Make a Request
    </a>

    <!-- My Request Link -->
    <a href="{{ route('employee.leave_request')}}" 
       class="flex items-center px-4 py-3 text-sm rounded-lg transition-all duration-200 
              hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
              {{ request()->routeIs('employee.leave_request') || request()->routeIs('employee.leave_show') || request()->routeIs('employee.leave_edit') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        My Request
    </a>

    <!-- Holidays Link -->
    <a href="{{ route('holiday.calendar') }}" 
       class="flex items-center px-4 py-3 text-sm rounded-lg transition-all duration-200 
              hover:bg-gradient-to-r hover:from-blue-500 hover:to-blue-600 hover:text-white 
              {{ request()->routeIs('holiday.calendar') ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg' : 'text-gray-600 ' }}">
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Holidays
    </a>
</nav>
</div>
</aside>
