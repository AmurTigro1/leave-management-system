<header class="py-4">
    <div class="container mx-auto flex justify-between items-center text-center mb-2">
        <div class="text-blue-600 font-bold text-2xl">DILG CTO & LMS</div>
        
        <!-- Navigation Links -->
        <nav id="menu" class="flex items-center space-x-6">
            @if (Auth::check())
                @if (Auth::user()->role === 'admin')
                    <!-- Admin Links -->
                    <a href="{{ route('admin.dashboard') }}" class="hidden md:block text-gray-600 font-semibold py-2 rounded-lg hover:text-blue-500">
                        Admin Dashboard
                    </a>
                    <a href="{{ route('admin.requests') }}" class="hidden md:block text-gray-600 font-semibold py-2 rounded-lg hover:text-blue-500">
                        Manage Requests
                    </a>
                @else
                    <!-- Regular User Links -->
                    <a href="{{ route('employee.make_request') }}" class="hidden md:block text-gray-600 font-semibold py-2 rounded-lg hover:text-blue-500">
                        Make a Request
                    </a>
                    <a href="{{ route('employee.leave_request') }}" class="hidden md:block text-gray-600 font-semibold py-2 rounded-lg hover:text-blue-500">
                        My Requests
                    </a>
                @endif
            @endif

            @if (Auth::check())
                <!-- Dropdown Menu -->
                <div class="relative">
                    <button id="dropdown-btn" class="flex items-center text-gray-600 font-semibold px-2 py-1 rounded-lg hover:bg-gray-100 focus:outline-none">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-2">
                            @if (auth()->user()->profile_image)
                                <img src="{{ asset('storage/profile_images/' . auth()->user()->profile_image) }}" alt="Profile Photo" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                </svg>
                            @endif
                        </div>
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5.292 7.292a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <!-- Dropdown Content -->
                    <div id="dropdown-menu" class="absolute hidden bg-white shadow-md rounded-lg mt-2 w-40 z-50">
                        <ul class="py-2 text-gray-600">
                            <li class="block md:hidden">
                                <a href="" class="block px-4 py-2 hover:bg-gray-100 hover:text-blue-500">
                                    My requests
                                </a>
                            </li>
                            <li>
                                @if (session('system') === 'lms')
                                    <a href="{{ route('lms.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @elseif (session('system') === 'cto')
                                    <a href="{{ route('cto.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @elseif (Auth::user()->role === 'hr')
                                    <a href="{{ route('hr.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @elseif (Auth::user()->role === 'supervisor')
                                    <a href="{{ route('supervisor.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @endif
                            </li>                                                                               
                            <li>
                                <a href="{{ route('employee.profile.index') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Profile</a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <div class="relative">
                    <button id="customDropdownBtn" class="px-3 py-2 w-40 rounded-md font-semibold text-lg flex justify-between items-center">
                        Login
                        <svg id="dropdownArrow" class="w-4 h-4 ml-2 transition-transform duration-300 transform" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                    
                    <div id="customDropdownMenu" class="absolute hidden bg-white rounded-md w-40 shadow-lg z-50">
                        <ul class="py-2">
                            <li>
                                <a href="{{route('cto.login')}}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">CTO Login</a>
                            </li>
                            <li>
                                <a href="{{route('lms.login')}}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">LMS Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </nav>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Profile Dropdown
        const profileDropdownBtn = document.getElementById("dropdown-btn");
        const profileDropdownMenu = document.getElementById("dropdown-menu");

        if (profileDropdownBtn) {
            profileDropdownBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                profileDropdownMenu.classList.toggle("hidden");
            });

            document.addEventListener("click", (e) => {
                if (!profileDropdownMenu.contains(e.target) && !profileDropdownBtn.contains(e.target)) {
                    profileDropdownMenu.classList.add("hidden");
                }
            });
        }

        // Login Dropdown
        const loginDropdownBtn = document.getElementById("customDropdownBtn");
        const loginDropdownMenu = document.getElementById("customDropdownMenu");
        const loginDropdownArrow = document.getElementById("dropdownArrow");

        if (loginDropdownBtn) {
            loginDropdownBtn.addEventListener("mousedown", (e) => {
                e.stopPropagation();
                loginDropdownMenu.classList.toggle("hidden");
                loginDropdownArrow.classList.toggle("rotate-180");
            });

            document.addEventListener("click", (e) => {
                if (!loginDropdownMenu.contains(e.target) && !loginDropdownBtn.contains(e.target)) {
                    loginDropdownMenu.classList.add("hidden");
                    loginDropdownArrow.classList.remove("rotate-180");
                }
            });
        }
    });
</script>

    