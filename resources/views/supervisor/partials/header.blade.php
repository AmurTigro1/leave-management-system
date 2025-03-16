<header class="py-4 bg-white shadow-md w-full">
    <div class="container mx-auto flex justify-between items-center text-center">
        <!-- Logo -->
        <p class="px-4 text-xl text-blue-600 font-semibold">Supervisor Dashboard</p>

             <!-- Navigation Links -->
             <nav id="menu" class="flex items-center space-x-6">
                <div class="relative">
                    <button id="notificationBtn" class="relative">
                        <span class="text-gray-600">🔔</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-64 bg-white shadow-md rounded-lg overflow-hidden">
                        <ul>
                            @foreach(auth()->user()->unreadNotifications as $notification)
                                <li class="p-3 border-b hover:bg-gray-100 text-sm">
                                    {{ $notification->data['message'] }}
                                    <small class="block text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <script>
                document.getElementById("notificationBtn").addEventListener("click", function() {
                    document.getElementById("notificationsDropdown").classList.toggle("hidden");
                });
                </script>
                
            @if (Auth::check())
            <!-- Dropdown Menu -->
            <div class="relative">
                <button id="dropdown-btn" class="flex items-center text-gray-600 font-semibold px-2 py-1 rounded-lg hover:bg-gray-100 focus:outline-none">
                    <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-2">
                        @if (auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}" class="w-full h-full object-cover">
                        @else
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                            <a href="/supervisor-profile" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Profile</a>
                        </li>
                        <li>
                            <button id="openModal" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">
                                Logout
                            </button>
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

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden px-4 max-sm:m-10">
    <div class="bg-white rounded-lg shadow-lg p-6 w-[90%] max-w-[450px]">
        <div class="flex justify-center">
            <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[70px] w-[70px] sm:h-[80px] sm:w-[80px] mb-4">
        </div>
        <p class="text-center text-lg sm:text-xl"><strong>Ready to Leave?</strong></p>
        <p class="text-gray-500 text-center mb-4 text-sm sm:text-md mt-2">
            Select "Logout" below if you are ready to end your current session.
        </p>

        <!-- Buttons -->
        <div class="mt-4 flex sm:flex-row justify-center gap-2">
            <button id="closeModal" class="px-4 bg-gray-300 rounded hover:bg-gray-400 w-full sm:w-auto">
                Cancel
            </button>
            <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 w-full sm:w-auto">
                    Logout 
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const dropdownBtn = document.getElementById("dropdown-btn");
        const dropdownMenu = document.getElementById("dropdown-menu");
        const modal = document.getElementById("logoutModal");
        const openModalBtn = document.getElementById("openModal");
        const closeModalBtn = document.getElementById("closeModal");

        // Dropdown menu toggle
        dropdownBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", () => {
            if (!dropdownMenu.classList.contains("hidden")) {
                dropdownMenu.classList.add("hidden");
            }
        });

        // Open modal
        openModalBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });

        // Close modal
        closeModalBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Close modal when clicking outside
        window.addEventListener("click", (e) => {
            if (e.target === modal) modal.classList.add("hidden");
        });
    });
</script>
