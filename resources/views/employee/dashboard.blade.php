{{-- @extends('main_resources.index') --}}
@extends('layouts.sidebar-header')

@section('content')
    <!-- Top-Right Header -->
    <div class="fixed top-4 right-4 z-[9999]">
        <x-notify::notify />
    </div>
</section>

<div class="w-full px-4 py-8 space-y-8 animate-fade-in">
<div class="w-full px-4">
    <h3 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-gray-900 drop-shadow-lg animate-bounce">🎉 Happy Birthday! 🎉</h3>
    @if ($birthdays->isEmpty())
    <p class="text-gray-500 text-center text-sm md:text-base italic">
        No team members have birthdays this month.
    </p>
    
    @else
    <!-- Carousel Container -->
    <div x-data="{ currentIndex: 0, totalSlides: {{ ceil($birthdays->count() / 4) }} }" class="relative w-full max-w-screen-lg mx-auto overflow-hidden mt-6">
        <div class="flex transition-transform duration-700" :style="'transform: translateX(-' + (currentIndex * 100) + '%)'">
            @foreach ($birthdays->chunk(4) as $chunk)
            <div class="w-full flex flex-wrap justify-center gap-4 shrink-0">
                @foreach ($chunk as $employee)
                    <div class="w-full sm:w-[200px] bg-white shadow-lg rounded-xl p-4 flex flex-col items-center border border-gray-200 transition-transform duration-500 hover:-translate-y-2">
                        <div class="w-16 sm:w-20 h-16 sm:h-20 rounded-full overflow-hidden bg-gray-300 shadow-md ring-4 ring-blue-400 animate-pulse">
                            @if ($employee->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $employee->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('img/default-avatar.png')}}" alt="default avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="mt-3 text-center">
                            <p class="text-sm sm:text-md font-semibold">{{ $employee->first_name }} {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. {{$employee->last_name}}</p>
                            <p class="text-xs text-gray-600">🎂 {{ \Carbon\Carbon::parse($employee->birthday)->format('F d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @endforeach
        </div>

        <!-- Pagination Dots -->
        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <template x-for="(dot, index) in totalSlides">
                <div @click="currentIndex = index" class="w-3 h-3 rounded-full cursor-pointer transition-all" :class="index === currentIndex ? 'bg-blue-500 scale-110' : 'bg-gray-300'"></div>
            </template>
        </div>

        <!-- Navigation Controls -->
        <button @click="currentIndex = (currentIndex - 1 + totalSlides) % totalSlides" class="absolute border border-gray-500 left-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white shadow-md hover:scale-110">
            &larr;
        </button>
        <button @click="currentIndex = (currentIndex + 1) % totalSlides" class="absolute border border-gray-500 right-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white shadow-md hover:scale-110">
            &rarr;
        </button>
    </div>
</div>
@endif

    <!-- Leave & Overtime Requests Section -->
    <div class="p-6 bg-gray-100 rounded-xl shadow-lg border border-gray-300">
        <div class="flex justify-between items-center mb-4">
            <button id="prevMonth" class="text-gray-700 px-4 py-2 rounded-lg bg-white shadow hover:bg-gray-200">&larr;</button>
            <h2 id="monthTitle" class="text-lg font-semibold text-gray-700"></h2>
            <button id="nextMonth" class="text-gray-700 px-4 py-2 rounded-lg bg-white shadow hover:bg-gray-200">&rarr;</button>
        </div>
        <div id="leaveContainer" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"></div>
    </div>

    <!-- Employees on Leave -->
    <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-300">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Team Members on Leave</h2>
        @if($teamLeaves->isEmpty())
            <p class="text-gray-600">No team members are on leave this month.</p>
        @else
            <ul class="space-y-4">
                @foreach($teamLeaves as $leave)
                    <li class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200">
                            @if($leave->user && $leave->user->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $leave->user->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('img/default-avatar.png')}}" alt="default avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-md font-semibold text-gray-800">{{ $leave->user->first_name }}</p>
                            <p class="text-sm text-gray-600">On leave from {{ date('M d', strtotime($leave->start_date)) }} to {{ date('M d', strtotime($leave->end_date)) }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Employees with Overtime Requests -->
    <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-300 mt-8">
        <h2 class="text-xl font-semibold text-gray-700 mb-3">Team Members on Compensatory Leave</h2>
        @if($overtimeRequests->isEmpty())
            <p class="text-gray-600">No team members have overtime requests this month.</p>
        @else
            <ul class="space-y-4">
                @foreach($overtimeRequests as $overtime)
                    <li class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow">
                        <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200">
                            @if($overtime->user && $overtime->user->profile_image)
                                <img src="{{ asset('storage/profile_images/' . $overtime->user->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('img/default-avatar.png')}}" alt="default avatar" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <p class="text-md font-semibold text-gray-800">{{ $overtime->user->first_name }} {{ $overtime->user->last_name }}</p>
                            <p class="text-sm text-gray-600">CTO starting from {{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-600">Used COCs: {{ $overtime->hours }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let currentMonth = new Date().getMonth() + 1; // Default to current month

    function loadRequests() {
        let leaveUrl = `/api/leaves?month=${currentMonth}`;
        let overtimeUrl = `/api/overtimes?month=${currentMonth}`;

        Promise.all([fetch(leaveUrl).then(res => res.json()), fetch(overtimeUrl).then(res => res.json())])
            .then(([leaves, overtimes]) => {
                let leaveContainer = document.getElementById("leaveContainer");
                let monthTitle = document.getElementById("monthTitle");

                let monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];
                monthTitle.textContent = `Requests for ${monthNames[currentMonth - 1]}`;

                leaveContainer.innerHTML = ""; // Clear previous data

                if (leaves.length === 0 && overtimes.length === 0) {
                    leaveContainer.innerHTML = `<p class="text-gray-600 text-center col-span-full">No requests found.</p>`;
                    return;
                }

                // Display leave requests
                leaves.forEach(leave => {
                    leaveContainer.innerHTML += `
                    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6 mb-4 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <!-- Profile Image -->
                    <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-blue-100 bg-gray-100 flex-shrink-0">
                        <img src="${leave.profile_image}" 
                            class="w-full h-full object-cover" 
                            alt="Profile"
                            onerror="this.onerror=null; this.src='/img/default-avatar.png';">
                    </div>

                    <!-- Leave Details -->
                    <div class="flex-1">
                        <!-- Employee Name -->
                        <p class="font-bold text-gray-900 text-lg">${leave.first_name} ${leave.last_name}</p>

                        <!-- Leave Duration -->
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Leave Duration:</span>
                            <span class="text-sm font-semibold text-green-600">${leave.duration} day(s)</span>
                        </div>

                        <!-- Leave Dates -->
                        <div class="mt-2 text-sm text-gray-600">
                            <p>From: <span class="font-medium text-gray-700">${leave.start}</span></p>
                            <p>To: <span class="font-medium text-gray-700">${leave.end}</span></p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-3">
                            <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full ${
                                leave.status === 'Approved' ? 'bg-green-100 text-green-700' :
                                leave.status === 'Pending' ? 'bg-yellow-100 text-yellow-700' :
                                'bg-red-100 text-red-700'
                            }">
                                ${leave.status}
                            </span>
                        </div>
                    </div>
                </div>`;
                });

                // Display overtime requests
                overtimes.forEach(overtime => {
                    leaveContainer.innerHTML += `
                    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center space-x-6 mb-4 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <!-- Profile Image -->
                    <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-blue-100 bg-gray-100 flex-shrink-0">
                        <img src="${overtime.profile_image}" 
                            class="w-full h-full object-cover" 
                            alt="Profile"
                            onerror="this.onerror=null; this.src='/img/default-avatar.png';">
                    </div>

                    <!-- Overtime Details -->
                    <div class="flex-1">
                        <!-- Employee Name -->
                        <p class="font-bold text-gray-900 text-lg">${overtime.first_name} ${overtime.last_name}</p>

                        <!-- Overtime Hours -->
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="text-sm text-gray-600">Used COCs:</span>
                            <span class="text-sm font-semibold text-blue-600">${overtime.hours} hour(s)</span>
                        </div>

                        <!-- Overtime Date -->
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Date: <span class="font-medium text-gray-700">${overtime.date}</span></p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-3">
                            <span class="inline-block px-4 py-1 text-sm font-semibold rounded-full ${
                                overtime.status === 'Approved' ? 'bg-green-100 text-green-700' :
                                overtime.status === 'Pending' ? 'bg-yellow-100 text-yellow-700' :
                                'bg-red-100 text-red-700'
                            }">
                                ${overtime.status}
                            </span>
                        </div>
                    </div>
                </div>`;
                });
            })
            .catch(error => console.error("Error loading requests:", error));
    }

    document.getElementById("prevMonth").addEventListener("click", function () {
        currentMonth = currentMonth === 1 ? 12 : currentMonth - 1;
        loadRequests();
    });

    document.getElementById("nextMonth").addEventListener("click", function () {
        currentMonth = currentMonth === 12 ? 1 : currentMonth + 1;
        loadRequests();
    });

    loadRequests(); // Initial load
});
</script>
@endsection

<style>
    @keyframes glow {
        0% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
        100% { opacity: 0.4; transform: scale(1); }
    }

    .animate-glow {
        animation: glow 2s infinite;
    }

             @keyframes float {
                 0% { transform: translateY(0); }
                 50% { transform: translateY(-8px); }
                 100% { transform: translateY(0); }
             }
         
             @keyframes confetti {
                 0% { transform: translateY(0) rotate(0deg); opacity: 1; }
                 100% { transform: translateY(50px) rotate(360deg); opacity: 0; }
             }
         
             .animate-float {
                 animation: float 3s ease-in-out infinite;
             }
         
             .animate-confetti {
                 animation: confetti 1.5s linear infinite;
             } 

             .animate-fade-in {
                animation: fadeIn 0.8s ease-in-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
</style>

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