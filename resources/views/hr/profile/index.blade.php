@extends('layouts.hr.sidebar-header')
@section('content')
<div class="w-full p-3 sm:p-4 rounded-xl shadow-md animate-fade-in">
    <!-- Banner Image -->
    <div class="bg-[url('/public/img/Background.png')] bg-cover bg-center bg-no-repeat min-h-[200px] sm:min-h-[300px] md:min-h-[400px] w-full rounded-lg overflow-hidden"></div>     
          
    <!-- Profile Section -->
    <div class="flex flex-col">
        <!-- Profile Image & Upload -->
        <div class="relative w-24 h-24 sm:w-32 sm:h-32 ml-4 sm:ml-6 mt-[-50px] sm:mt-[-100px]">
            <div class="relative group w-full h-full">
                <!-- Profile Image -->
                <img id="profile-preview" src="{{ 
                    $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                        ? asset('storage/profile_images/' . $user->profile_image) 
                        : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                            ? asset('storage/profile_pictures/' . $user->profile_image) 
                            : asset('img/default-avatar.png')) 
                }}" 
                class="w-full h-full rounded-full object-cover border-4 border-gray-300 shadow-md cursor-pointer" alt="{{ $user->name }}">
                <!-- Hover Overlay -->
                <label for="profile_image" 
                    class="absolute inset-0 bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 flex justify-center items-center transition-opacity duration-300 cursor-pointer pointer-events-none">
                    <span class="text-white text-xs sm:text-sm font-medium pointer-events-auto">Change</span>
                </label>
            </div>

            <!-- Hidden File Input & Update Button -->
            <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="relative mt-1">
                @csrf
                <input type="file" name="profile_image" id="profile_image" class="hidden">
                
                <button id="update-button" type="submit" 
                    class="absolute top-[-70px] sm:top-[-80px] left-[90px] sm:left-[170px] bg-green-500 text-white px-3 py-1 text-xs sm:text-sm rounded-md hidden hover:bg-green-600 transition z-20">
                    Update
                </button>
            </form>
        </div>

        <!-- User Info -->
        <div class="ml-4 sm:ml-6 py-2 w-full sm:w-auto">
            <div class="mt-2 sm:mt-3 space-y-2 sm:space-y-3">
                <div class="flex justify-between items-center w-full">
                    <p class="text-base sm:text-lg font-semibold">{{ $user->first_name }} {{ strtoupper(substr($user->middle_name, 0, 1)) }}. {{ $user->last_name }}</p>
                    <div class="relative mr-2 sm:mr-[15px] items-end">
                        <!-- Dropdown Toggle -->
                        <input type="checkbox" id="dropdown-profile" class="peer hidden">
                        <label for="dropdown-profile" class="cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                            </svg>
                        </label>
                    
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-full hidden peer-checked:flex flex-col w-[160px] sm:w-[180px] bg-white border border-gray-300 rounded-md shadow-lg z-10">
                            <a href="{{route('hr.profile.partials.update-profile-information-form')}}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                </svg>
                                <span>Email & Profile</span>
                            </a>
                            <a href="{{route('hr.profile.partials.update-password-form')}}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 flex items-center text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                </svg>
                                <span>Password</span>
                            </a>
                        </div>
                    </div>            
                </div>
                
                <!-- Location -->
                <div class="flex items-center text-sm sm:text-base">
                    <img src="/img/philippines.png" alt="Philippines" class="mr-2 w-5 h-5">
                    <span class="text-gray-600">Tagbilaran City, Bohol, Philippines</span>
                </div>
                
                <!-- Email & Department -->
                <div class="flex flex-col sm:flex-row sm:items-center space-y-1 sm:space-y-0 sm:space-x-4 text-sm sm:text-base">
                    <div class="flex items-center">
                        <span class="text-blue-600">{{ $user->email}}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 mx-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                        </svg>
                        <span>{{ $user->department ?? 'Not Assigned' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3 mx-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                        </svg>
                        <span class="text-gray-600">{{ $user->role ?? 'User' }}</span>
                    </div>
                </div>
                
                <!-- Join Date -->
                <div class="flex sm:items-center sm:gap-4 w-full gap-4">
                    <span class="font-medium border border-gray-300 py-1 px-3 rounded py-1 px-3 text-sm sm:text-base w-fit max-w-[150px] text-center">
                        Joined:
                    </span>
                    <span class="bg-blue-600 text-white rounded py-1 px-3 text-sm sm:text-base w-fit max-w-[150px] text-center">
                        {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leave and COC Section -->
<div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mt-4">
    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4 sm:mb-6">Leave and COC Distribution</h3>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
        <!-- Leave Balance Section -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3 sm:mb-4">Leave Balance</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <!-- Vacation Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Vacation:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->vacation_leave_balance ?? 0 }} 
                        {{ Str::plural('day', $user->vacation_leave_balance ?? 0) }}
                    </span>
                </div>

                <!-- Mandatory Leave Notice -->
                @if(($user->total_annual_vacation_leave - $user->vacation_leave_balance) < 5)
                <div class="bg-yellow-100 text-yellow-800 p-2 rounded mt-2 text-sm flex items-center border-l-4 border-yellow-500">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8h.01M12 18h.01M12 20h.01"></path>
                    </svg>
                    <span>
                        <strong>Mandatory Leave Reminder:</strong> You must use at least <strong>5 vacation leave days</strong> this year.  
                        Unused days will be <strong>forfeited after December 31st</strong>.  
                        You have used <strong>{{ $user->total_annual_vacation_leave - $user->vacation_leave_balance }}</strong> day(s) so far.
                    </span>
                </div>
                @endif
       
                <!-- Sick Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Sick:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->sick_leave_balance ?? 0 }} 
                        {{ Str::plural('day', $user->sick_leave_balance ?? 0) }}
                    </span> 
                </div>

                <!-- Mandatory Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Mandatory Leave:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->mandatory_leave_balance ?? 0 }} 
                        {{ Str::plural('day', $user->mandatory_leave_balance ?? 0) }}
                    </span> 
                </div>
                
                <!-- Maternity Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Maternity:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->maternity_leave ?? 0 }} 
                        {{ Str::plural('day', $user->maternity_leave ?? 0) }}
                    </span>
                </div>
                
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Special Privilege Leave:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->special_privilege_leave ?? 0 }} 
                        {{ Str::plural('day', $user->special_privilege_leave ?? 0) }}
                    </span> 
                </div>

                <!-- Solo Parent Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Solo Parent:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->solo_parent_leave ?? 0 }} 
                        {{ Str::plural('day', $user->solo_parent_leave ?? 0) }}
                    </span>
                    
                </div>
                
                <!-- Study Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Study:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->study_leave ?? 0 }} 
                        {{ Str::plural('day', $user->study_leave ?? 0) }}
                    </span>
                    
                </div>
                
                <!-- VAWC Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">VAWC:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->vawc_leave ?? 0 }} 
                        {{ Str::plural('day', $user->vawc_leave ?? 0) }}
                    </span>
                    
                </div>
                
                <!-- Rehabilitation Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Rehabilitation:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->rehabilitation_leave ?? 0 }} 
                        {{ Str::plural('day', $user->rehabilitation_leave ?? 0) }}
                    </span>
                    
                </div>
                
                <!-- Special Leave Benefit -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Special Benefits :</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->special_leave_benefit ?? 0 }} 
                        {{ Str::plural('day', $user->special_leave_benefit ?? 0) }}
                    </span>
                    
                </div>
                
                <!-- Special Emergency Leave -->
                <div class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                    <span class="text-sm sm:text-base text-gray-600">Emergency:</span>
                    <span class="font-medium text-gray-600">
                        {{ $user->special_emergency_leave ?? 0 }} 
                        {{ Str::plural('day', $user->special_emergency_leave ?? 0) }}
                    </span>
                    
                </div>
            </div>
            
            <!-- Total Leave & COC Balance -->
            <div class="mt-4 bg-white p-4 rounded-lg shadow-sm">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3">Total Balance</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- <div class="flex justify-between items-center bg-blue-50 p-2 rounded">
                        <span class="text-sm sm:text-base text-gray-700">Leave Available:</span>
                        <span class="font-medium text-blue-600">{{ $user->leave_balance ?? 0 }} day(s)</span>
                    </div> --}}
                    <div class="flex justify-between items-center bg-blue-50 p-2 rounded">
                        <span class="text-sm sm:text-base text-gray-700">COC Available:</span>
                        <span class="font-medium text-blue-600">{{ $user->overtime_balance ?? 0 }} hour(s)</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Graph Section -->
        <div class="bg-gray-50 p-4 sm:p-6 rounded-lg">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-3 sm:mb-4">Leave Distribution</h3>
            <div class="w-full h-64 sm:h-80">
                <canvas id="leaveGraph" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Profile image upload handling
        const fileInput = document.getElementById("profile_image");
        const updateButton = document.getElementById("update-button");
        const previewImage = document.getElementById("profile-preview");

        fileInput.addEventListener("change", function(event) {
            updateButton.classList.remove("hidden");
            const reader = new FileReader();
            reader.onload = function() {
                previewImage.src = reader.result;
            };
            if (event.target.files.length > 0) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });

        // Chart initialization
        const ctx = document.getElementById('leaveGraph').getContext('2d');
        const leaveGraph = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    'Vacation', 'Sick', 'Maternity', 'Paternity', 'Solo Parent', 
                    'Study', 'VAWC', 'Rehabilitation', 'Special Benefit', 
                    'Emergency', 'COC', 'Leave'
                ],
                datasets: [{
                    label: 'Days',
                    data: [
                        {{ $user->vacation_leave_balance ?? 0 }}, 
                        {{ $user->sick_leave_balance ?? 0 }}, 
                        {{ $user->maternity_leave ?? 0 }}, 
                        {{ $user->paternity_leave ?? 0 }}, 
                        {{ $user->special_privilege_leave ?? 0 }}, 
                        {{ $user->solo_parent_leave ?? 0 }}, 
                        {{ $user->study_leave ?? 0 }}, 
                        {{ $user->vawc_leave ?? 0 }}, 
                        {{ $user->rehabilitation_leave ?? 0 }}, 
                        {{ $user->special_leave_benefit ?? 0 }}, 
                        {{ $user->special_emergency_leave ?? 0 }}, 
                        {{ $user->overtime_balance ?? 0 }}, 
                        {{ $user->leave_balance ?? 0 }}
                    ],
                    backgroundColor: [
                        '#22c55e', '#eab308', '#3b82f6', '#a855f7', '#f97316', 
                        '#14b8a6', '#ef4444', '#8b5cf6', '#64748b', '#f43f5e', 
                        '#6b7280', '#4b5563'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Days/Hours'
                        }
                    },
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 45
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + (context.dataIndex >= 10 ? ' hours' : ' days');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>