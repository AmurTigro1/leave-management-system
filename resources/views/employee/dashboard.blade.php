{{-- @extends('main_resources.index') --}}
@extends('layouts.sidebar-header')

@section('content')
<!-- Calendar Section -->
{{-- <section class="max-w-6xl mx-auto p-6 bg-white rounded-xl backdrop-blur-lg relative overflow-hidden">
    <div class="absolute inset-0  opacity-30"></div>
    <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-6 relative z-10">Employee Leave Calendar</h2>
    <div id="leaveCalendar" class="p-6  rounded-xl shadow-inner relative z-10"></div>
    
    <!-- Legend -->
    <div class="mt-4 flex justify-center space-x-4 relative z-10">
        <div class="flex items-center space-x-2">
            <span class="w-4 h-4 bg-green-500 rounded-full"></span>
            <span class="text-gray-600 text-sm">Approved</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="w-4 h-4 bg-yellow-300 rounded-full"></span>
            <span class="text-gray-600 text-sm">Pending</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="w-4 h-4 bg-red-500 rounded-full"></span>
            <span class="text-gray-600 text-sm">Rejected</span>
        </div>
    </div>
</section>

<!-- Employee Leave Modal -->
<div id="leaveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <button onclick="document.getElementById('leaveModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">
            ✖
        </button>
        <div id="leaveModalContent"></div>
    </div>
</div> --}}
<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">Employee Leave Calendar</h2>
    <div class="flex justify-between items-center bg-blue-100 p-4 rounded-lg shadow-md">
        <button id="prevMonth" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">◀ Prev</button>
        <h2 id="monthTitle" class="text-lg font-semibold text-gray-800"></h2>
        <button id="nextMonth" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Next ▶</button>
    </div>
        
    
    <div id="leaveContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
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
                    leaveContainer.innerHTML = `<p class="text-gray-600">No leave requests found.</p>`;
                    return;
                }

                data.forEach(leave => {
                    leaveContainer.innerHTML += `
                        <div class="bg-white p-4 rounded-lg shadow-md flex items-center space-x-4 mb-4">
                            <img src="${leave.profile_image}" class="w-12 h-12 rounded-full border-2 border-gray-300" alt="Profile">
                            <div>
                                <p class="font-semibold text-gray-900">${leave.title}</p>
                                <p class="text-sm text-gray-600">Duration: ${leave.duration} days</p>
                                <p class="text-xs text-gray-500">From: ${leave.start} <br> To: ${leave.end}</p>
                                <span class="text-sm px-4 rounded-md ${
                                    leave.status === 'Approved' ? 'bg-green-500 text-white' :
                                    leave.status === 'Pending' ? 'bg-yellow-500 text-white' :
                                    'bg-red-500 text-white'
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
