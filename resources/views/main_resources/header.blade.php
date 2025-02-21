<header class="py-4">
    <div class="container mx-auto flex justify-between items-center text-center mb-2">
        <!-- Logo -->
        <a href="/" class="text-blue-600 font-bold text-2xl">DILG</a>

        <!-- Navigation Links -->
        <nav id="menu" class="flex items-center space-x-6">
            @if (Auth::check())
            <a href="{{ route('employee.dashboard') }}" 
            class="hidden md:block text-gray-600 font-semibold py-2 rounded-lg hover:text-blue-500">
             Make a Request
         </a>
            <a href="{{ route('employee.leave_request') }}" 
               class="hidden md:block text-gray-600 font-semibold py-2 rounded-lg hover:text-blue-500">
                My Requests
            </a>
            @endif
            @if (Auth::check())
            <!-- Dropdown Menu -->
            <div class="relative">
                <button id="dropdown-btn" class="flex items-center text-gray-600 font-semibold px-2 py-1 rounded-lg hover:bg-gray-100 focus:outline-none">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-2">
                        @if (auth()->user()->profile_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                        @else
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
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
                        <!-- Mobile View: List Your Boarding House -->
                        <li class="block md:hidden">
                            <a href="" class="block px-4 py-2 hover:bg-gray-100 hover:text-blue-500">
                                My requests
                            </a>
                        </li>
                        <li>
                        <li>
                            <a href="{{ route('employee.profile')}}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Profile</a>
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
            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 font-semibold text-lg">
                    {{ __('Login') }}
                </a>
                <a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-600 font-semibold text-lg">
                    {{ __('Register') }}
                </a>
            </div>
            @endif
        </nav>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const dropdownBtn = document.getElementById("dropdown-btn");
        const dropdownMenu = document.getElementById("dropdown-menu");

        dropdownBtn.addEventListener("click", (e) => {
            e.stopPropagation(); // Prevent event propagation to document
            dropdownMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", () => {
            if (!dropdownMenu.classList.contains("hidden")) {
                dropdownMenu.classList.add("hidden");
            }
        });
    });
</script>
