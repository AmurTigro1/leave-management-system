<header class="py-4">
    <div class="container mx-auto flex justify-between items-center text-center mb-2">
        <div class="text-blue-600 font-bold text-2xl">DILG CTO & LMS</div>
        
        <!-- Navigation Links -->
        <nav id="menu" class="flex items-center space-x-6">

            @if (Auth::check())
                <!-- Dropdown Menu -->
                <div class="relative">
                    <button id="dropdown-btn" class="flex items-center text-gray-600 font-semibold px-2 py-1 rounded-lg hover:bg-gray-100 focus:outline-none">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-2">
                            @if (auth()->user()->profile_image)
                                <img src="{{ asset('storage/profile_images/' . auth()->user()->profile_image) }}" alt="Profile Photo" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('img/default-avatar.png')}}" alt="default avatar" class="w-full h-full object-cover">
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
                                @if (Auth::user()->role === 'employee')
                                    <a href="{{ route('lms_cto.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @elseif (Auth::user()->role === 'hr')
                                    <a href="{{ route('hr.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @elseif (Auth::user()->role === 'supervisor')
                                    <a href="{{ route('supervisor.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @elseif (Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                                @endif
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
                    <a href="{{ route('login') }}" 
                        class="block px-4 py-2 rounded-lg bg-blue-600 text-white font-bold">
                        Login
                    </a>
                </div>
            @endif
        </nav>
    </div>
</header>