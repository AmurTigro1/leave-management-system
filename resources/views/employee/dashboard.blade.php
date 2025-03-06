{{-- @extends('main_resources.index') --}}
@extends('layouts.sidebar-header')

@section('content')
    <!-- Top-Right Header -->
    <div class="absolute top-4 right-4 flex items-center space-x-3 z-50">
        <div class="fixed top-0 right-0 z-50">
            <x-notify::notify />
        </div>
        @notifyJs
    </div>

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-lg">
        <!-- Header -->
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Employee Leave Calendar</h2>

        <!-- Month Navigation -->
        <div class="flex justify-between items-center bg-gradient-to-r from-blue-500 to-blue-600 p-4 rounded-xl shadow-md">
            <button id="prevMonth" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-all duration-300 transform hover:-translate-x-1">◀ Prev</button>
            <h2 id="monthTitle" class="text-xl font-semibold text-white"></h2>
            <button id="nextMonth" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-blue-50 transition-all duration-300 transform hover:translate-x-1">Next ▶</button>
        </div>

        <!-- Leave Cards Container -->
        <div id="leaveContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            <!-- Leaves will be dynamically inserted here -->
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let currentMonth = new Date().getMonth() + 1; // Default to current month

            function loadLeaves() {
                let url = `/api/leaves?month=${currentMonth}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let leaveContainer = document.getElementById("leaveContainer");
                        let monthTitle = document.getElementById("monthTitle");

                        // Convert month number to name
                        let monthNames = [
                            "January", "February", "March", "April", "May", "June", 
                            "July", "August", "September", "October", "November", "December"
                        ];
                        monthTitle.textContent = `Leave Requests for ${monthNames[currentMonth - 1]}`;

                        leaveContainer.innerHTML = ""; // Clear previous data

                        if (data.length === 0) {
                            leaveContainer.innerHTML = `<p class="text-gray-600 text-center col-span-full">No leave requests found.</p>`;
                            return;
                        }

                        data.forEach(leave => {
                            leaveContainer.innerHTML += `
                                <div class="bg-white p-5 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="flex items-center space-x-4">
                                        <img src="${leave.profile_image}" class="w-12 h-12 rounded-full border-2 border-blue-100" alt="Profile">
                                        <div>
                                            <p class="font-semibold text-gray-900">${leave.title}</p>
                                            <p class="text-sm text-gray-600">Duration: ${leave.duration} days</p>
                                            <p class="text-xs text-gray-500 mt-1">From: ${leave.start} <br> To: ${leave.end}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <span class="text-sm px-3 py-1 rounded-full ${
                                            leave.status === 'Approved' ? 'bg-green-100 text-green-700' :
                                            leave.status === 'Pending' ? 'bg-yellow-100 text-yellow-700' :
                                            'bg-red-100 text-red-700'
                                        }">${leave.status}</span>
                                    </div>
                                </div>
                            `;
                        });
                    })
                    .catch(error => console.error("Error loading leave data:", error));
            }

            document.getElementById("prevMonth").addEventListener("click", function () {
                currentMonth = currentMonth === 1 ? 12 : currentMonth - 1;
                loadLeaves();
            });

            document.getElementById("nextMonth").addEventListener("click", function () {
                currentMonth = currentMonth === 12 ? 1 : currentMonth + 1;
                loadLeaves();
            });

            loadLeaves(); // Initial load
        });
    </script>
@endsection

<style scoped>
    .fc-event {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .fc-event:hover {
        transform: scale(1.07);
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    }

    #leaveModal.show {
        display: flex;
    }
    #leaveModal .show {
        opacity: 1;
        transform: scale(1);
    }
</style>

<script>
    function closeModal() {
        const modal = document.getElementById('leaveModal');
        modal.classList.remove('show');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@notifyCss