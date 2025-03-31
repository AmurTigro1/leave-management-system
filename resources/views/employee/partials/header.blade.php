@include('employee.partials.modal.logout')
<header class="py-3 bg-gray-50 shadow-md w-full">
    <div class="container mx-auto max-w-7xl px-4 flex sm:flex-row justify-between space-y-3 sm:space-y-0">
        <!-- Spacer for larger screens -->
        <div class="mr-52"></div>

        <!-- Navigation Links -->
        <nav class="flex items-center space-x-6">
            <div class="flex flex-row text-gray-600 space-x-2">
                <div class="sm:flex hidden text-gray-600 space-x-4">
                    <div class="flex items-center">
                        <span class="font-medium text-sm">Sick Leave:</span>
                        <span class="font-semibold text-gray-800 text-sm bg-gray-50 px-2 py-1 rounded-md ml-2">
                            {{ Auth::user()->sick_leave_balance }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-medium text-sm">Vacation Leave:</span>
                        <span class="font-semibold text-gray-800 text-sm bg-gray-50 px-2 py-1 rounded-md ml-2">
                            {{ Auth::user()->vacation_leave_balance }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-medium text-sm">Total COCs:</span>
                        <span class="font-semibold text-gray-800 text-sm bg-gray-50 px-2 py-1 rounded-md ml-2">
                            {{ Auth::user()->overtime_balance }}
                        </span>
                    </div>
                </div>
            
                <div class="relative">
                    <!-- Bell Icon Button -->
                    <button id="notification-button" class="p-2 rounded-full bg-gray-100 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V10a6 6 0 10-12 0v4c0 .728-.195 1.414-.595 2L4 17h5m6 0a3 3 0 01-6 0"/>
                        </svg>
                
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white px-2 py-0.5 text-xs rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                
                    <!-- Notification Dropdown -->
                    <div id="notification-container" class="absolute right-0 bg-white shadow-lg rounded-xl border border-gray-200 p-4 mt-2 w-64 hidden z-10">
                        <h3 class="text-gray-700 font-semibold mb-2">Notifications</h3>
                
                        @forelse(auth()->user()->notifications as $notification)
                            <div class="notification-item p-2 rounded mb-2 bg-gray-200 flex justify-between items-center" data-id="{{ $notification->id }}">
                                <span class="text-xs font-bold">{{ $notification->data['message'] ?? 'New Notification' }}</span>
                                <button class="delete-notification text-red-500 text-xs px-1" data-id="{{ $notification->id }}">
                                    ✖
                                </button>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No new notifications.</p>
                            <hr>
                        @endforelse
                
                        <div class="mt-3 flex gap-2 justify-between">
                            <button id="mark-all-as-read" class="text-gray-500 text-xs">
                                Mark all as read
                            </button>
                            <button id="delete-all-notifications" class="text-gray-500 text-xs">
                                Delete All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::check())
            <!-- Dropdown Menu -->
            <div class="relative">
                <button id="dropdown-btn" class="flex items-center justify-between w-full bg-white px-4 rounded-lg shadow-md border border-gray-300 transition-all duration-200 ease-in-out">
                    <div class="flex items-center justify-between bg-white p-2 rounded-lg">
                        
                        <!-- Profile Section -->    
                        <div class="flex items-center space-x-4">
                            <!-- Profile Image -->
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-gray-200 hover:border-blue-300 transition-all duration-300">
                                @if (auth()->user()->profile_image)
                                    <img src="{{ asset('storage/profile_images/' . auth()->user()->profile_image) }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                    </svg>
                                @endif
                            </div>
                    
                            <!-- Username -->
                            <span class="text-gray-700 font-semibold text-sm hover:text-blue-600 transition-colors duration-300">
                                {{ Auth::user()->first_name }}
                            </span>
                        </div>
                    </div>
                    <!-- Dropdown Icon -->
                    <svg class="w-4 h-4 text-gray-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.292 7.292a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="dropdown-menu" class="absolute hidden bg-white shadow-lg rounded-lg mt-2 w-44 right-0 z-50 border border-gray-200">
                    <ul class="py-2 text-gray-700 text-sm">
                        {{-- <li class="block md:hidden">
                            <a href="" class="block px-4 py-2 hover:bg-blue-100 hover:text-blue-600 transition">My Requests</a>
                        </li> --}}
                        <li>
                            <a href="/lms-profile" class="block px-4 py-2 hover:bg-blue-100 hover:text-blue-600 transition">Profile</a>
                        </li>
                        <li>
                            <button id="openModal" class="block w-full text-left px-4 py-2 hover:bg-red-100 hover:text-red-600 transition">
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const notificationButton = document.getElementById("notification-button");
        const notificationContainer = document.getElementById("notification-container");
        const markAsReadButton = document.getElementById("mark-all-as-read");
        const deleteAllButton = document.getElementById("delete-all-notifications");
        const notificationBadge = document.getElementById("notification-badge");

        notificationButton.addEventListener("click", function () {
            notificationContainer.classList.toggle("hidden");
        });

    markAsReadButton.addEventListener("click", function () {
        fetch("{{ route('notifications.markAsRead') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json",
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll(".notification-item").forEach(item => {
                    item.classList.remove("bg-gray-200");
                    item.classList.add("bg-white");
                });

                if (notificationBadge) {
                    notificationBadge.style.display = "none";
                }
            }
        })
        .catch(error => console.error("Error:", error));
    });

    document.querySelectorAll(".delete-notification").forEach(button => {
        button.addEventListener("click", function () {
            let notificationId = this.getAttribute("data-id");

            fetch(`/notifications/delete/${notificationId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`[data-id="${notificationId}"]`).remove();
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });

        deleteAllButton.addEventListener("click", function () {
            fetch("{{ route('notifications.deleteAll') }}", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelectorAll(".notification-item").forEach(item => {
                        item.remove();
                    });

                    if (notificationBadge) {
                        notificationBadge.style.display = "none";
                    }
                }
            })
            .catch(error => console.error("Error:", error));
        });

        document.addEventListener("click", function (event) {
            if (!notificationButton.contains(event.target) && !notificationContainer.contains(event.target)) {
                notificationContainer.classList.add("hidden");
            }
        });
    });
</script>
