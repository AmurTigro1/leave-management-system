@section('title', 'HR Dashboard')

<header class="py-3 bg-gray-50 shadow-md w-full">
    <div class="container mx-auto max-w-7xl px-4 flex sm:flex-row justify-between space-y-3 sm:space-y-0">
        <div class="mr-52"></div>

        <nav class="flex items-center -ml-5">
            <div class="flex flex-row text-gray-600 space-x-2">
                <div class="sm:flex hidden text-gray-600 space-x-4">
                    <div class="flex items-center">
                        <span class="font-medium text-sm">Wellness Leave:</span>
                        <span class="font-semibold text-gray-800 text-sm bg-gray-50 px-2 py-1 rounded-md ml-2">
                            {{ Auth::user()->wellness_leave_balance }}
                        </span>
                    </div>
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
                    <button id="hr-notification-button" class="p-2 rounded-full bg-gray-100 relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V10a6 6 0 10-12 0v4c0 .728-.195 1.414-.595 2L4 17h5m6 0a3 3 0 01-6 0" />
                        </svg>

                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <span id="hr-notification-badge"
                                class="absolute -top-1 -right-1 bg-red-500 text-white px-2 py-0.5 text-xs rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div id="hr-notification-container"
                        class="absolute right-0 bg-white shadow-lg rounded-lg sm:rounded-xl border border-gray-200 p-3 sm:p-4 mt-2 w-64 sm:w-64 md:w-72 hidden z-50">

                        <h3 class="text-sm sm:text-base font-semibold text-gray-700 mb-2">HR Notifications</h3>

                        <div class="max-h-60 overflow-y-auto">
                            @forelse(auth()->user()->notifications as $notification)
                                <div class="notification-item p-2 rounded mb-2 bg-gray-50 hover:bg-gray-100 flex justify-between items-start sm:items-center"
                                    data-id="{{ $notification->id }}">
                                    <span class="text-xs sm:text-sm break-words">
                                        {!! Str::of($notification->data['message'] ?? 'New Notification')->replace('approved', '<span class="text-green-500 font-medium">approved</span>')->replace('rejected', '<span class="text-red-500 font-medium">rejected</span>') !!}
                                    </span>
                                    <button class="delete-notification text-red-500 hover:text-red-700 text-xs px-1"
                                        data-id="{{ $notification->id }}">
                                        ✖
                                    </button>
                                </div>
                            @empty
                                <p class="text-gray-500 text-xs sm:text-sm py-2">No new notifications.</p>
                            @endforelse
                        </div>

                        <div class="mt-3 flex gap-2 justify-between border-t border-gray-100 pt-3">
                            <button id="hr-mark-all-as-read"
                                class="text-gray-500 hover:text-gray-700 text-xs sm:text-sm">Mark all as read</button>
                            <button id="hr-delete-all-notifications"
                                class="text-gray-500 hover:text-gray-700 text-xs sm:text-sm">Delete All</button>
                        </div>
                    </div>
                </div>

            </div>

            @if (Auth::check())
                <div class="relative">
                    <button id="dropdown-btn"
                        class="flex items-center justify-between w-full px-4 rounded-lg transition-all duration-200 ease-in-out">
                        <div class="flex items-center justify-between p-2 rounded-lg">

                            <div class="flex items-center space-x-4">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border-2 border-gray-200 hover:border-blue-300 transition-all duration-300">
                                        @php
                                            $profileImage = null;

                                            if (auth()->user()->profile_image) {
                                                $imagePath1 = 'storage/profile_images/' . auth()->user()->profile_image;
                                                $imagePath2 =
                                                    'storage/profile_pictures/' . auth()->user()->profile_image;

                                                if (file_exists(public_path($imagePath1))) {
                                                    $profileImage = asset($imagePath1);
                                                } elseif (file_exists(public_path($imagePath2))) {
                                                    $profileImage = asset($imagePath2);
                                                }
                                            }
                                        @endphp

                                        @if ($profileImage)
                                            <img src="{{ $profileImage }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>

                                    <span
                                        class="hidden md:inline text-gray-700 font-semibold text-sm hover:text-blue-600 transition-colors duration-300">
                                        {{ Auth::user()->first_name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.292 7.292a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <div id="dropdown-menu"
                        class="absolute hidden bg-white shadow-lg rounded-lg mt-2 w-44 right-0 z-50 border border-gray-200">
                        <ul class="py-2 text-gray-700 text-sm">
                            <li>
                                <a href="/hr-profile"
                                    class="block px-4 py-2 hover:bg-blue-100 hover:text-blue-600 transition">Profile</a>
                            </li>
                            <li>
                                <button id="openModal"
                                    class="block w-full text-left px-4 py-2 hover:bg-red-100 hover:text-red-600 transition">
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </nav>
    </div>
</header>

<div id="logoutModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-[9999]">
    <div class="bg-white rounded-lg shadow-lg p-6 w-[90%] max-w-[450px] mx-auto">
        <div class="flex justify-center">
            <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[70px] w-[70px] sm:h-[80px] sm:w-[80px] mb-4">
        </div>
        <p class="text-center text-lg sm:text-xl"><strong>Ready to Leave?</strong></p>
        <p class="text-gray-500 text-center mb-4 text-sm sm:text-md mt-2">
            Select "Logout" below if you are ready to end your current session.
        </p>

        <div class="mt-4 flex sm:flex-row justify-center gap-2">
            <div class="w-full sm:w-auto">
                <button id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 w-full sm:w-auto">
                    Cancel
                </button>
            </div>

            <form action="{{ route('logout') }}"
                onsubmit="document.getElementById('loading-screen').classList.remove('hidden'); this.querySelector('button').disabled = true;"
                method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 w-full sm:w-auto">
                    Logout
                </button>
            </form>

            <div id="loading-screen"
                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden z-[10000]">
                <div class="flex flex-col items-center bg-white p-6 rounded-lg shadow-lg">
                    <svg class="animate-spin h-10 w-10 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-8-8v16" />
                    </svg>
                    <p class="text-gray-700">Logging out...</p>
                </div>
            </div>
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
        const hrNotificationButton = document.getElementById("hr-notification-button");
        const hrNotificationContainer = document.getElementById("hr-notification-container");
        const hrMarkAsReadButton = document.getElementById("hr-mark-all-as-read");
        const hrDeleteAllButton = document.getElementById("hr-delete-all-notifications");
        const hrNotificationBadge = document.getElementById("hr-notification-badge");

        dropdownBtn.addEventListener("click", (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle("hidden");
        });

        document.addEventListener("click", () => {
            if (!dropdownMenu.classList.contains("hidden")) {
                dropdownMenu.classList.add("hidden");
            }
        });

        openModalBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
        });
        closeModalBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        window.addEventListener("click", (e) => {
            if (e.target === modal) modal.classList.add("hidden");
        });

        hrNotificationButton.addEventListener("click", function() {
            hrNotificationContainer.classList.toggle("hidden");
        });

        hrMarkAsReadButton.addEventListener("click", function() {
            fetch("{{ route('hr.notifications.markAsRead') }}", {
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

                        if (hrNotificationBadge) {
                            hrNotificationBadge.style.display = "none";
                        }
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        document.querySelectorAll(".delete-notification").forEach(button => {
            button.addEventListener("click", function() {
                let notificationId = this.getAttribute("data-id");

                fetch(`/notifications/hr-delete/${notificationId}`, {
                        method: 'DELETE',
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`[data-id="${notificationId}"]`)
                        .remove();
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });

        hrDeleteAllButton.addEventListener("click", function() {
            fetch("{{ route('hr.notifications.deleteAll') }}", {
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

                        if (hrNotificationBadge) {
                            hrNotificationBadge.style.display = "none";
                        }
                    }
                })
                .catch(error => console.error("Error:", error));
        });

        document.addEventListener("click", function(event) {
            if (!hrNotificationButton.contains(event.target) && !hrNotificationContainer.contains(event
                    .target)) {
                hrNotificationContainer.classList.add("hidden");
            }
        });
    });
</script>
