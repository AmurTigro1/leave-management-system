@extends('layouts.sidebar-header')
@section('content')
<div class="w-full p-3 rounded-xl shadow-md">
        <!-- Back Button with Animation -->
        {{-- <div class="bg-[url('/public/img/office-image.jpg')] bg-cover bg-center p-[100px] rounded">
            <h1 class="text-center text-white font-bold text-[30px] bg-black/50 p-4 rounded">Leave Management System & Compensatory Time Off</h1>
        </div>       --}}
        <div class="bg-[url('/public/img/office-image.jpg')] bg-cover bg-center bg-no-repeat min-h-[400px] md:min-h-[450px] w-full rounded-lg overflow-hidden">
        </div> 
    <!-- Profile Image & Upload -->
    <div class="relative w-32 h-32 ml-6 mt-[-100px]">
        <!-- Image Wrapper (Group for Hover) -->
        <div class="relative group w-full h-full">
            <!-- Profile Image -->
            <img id="profile-preview"
                src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}"
                class="w-full h-full rounded-full object-cover border-4 border-gray-300 shadow-md cursor-pointer">

            <!-- Hover Overlay (Only on Image Hover) -->
            <label for="profile_image" 
                class="absolute inset-0 bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 flex justify-center items-center transition-opacity duration-300 cursor-pointer pointer-events-none">
                <span class="text-white text-sm font-medium pointer-events-auto">Change Image</span>
            </label>
        </div>

        <!-- Hidden File Input & Update Button -->
        <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="relative mt-1">
            @csrf
            <input type="file" name="profile_image" id="profile_image" class="hidden">
            
            <!-- Update Button (Moved Down & Right) -->
            <button id="update-button" type="submit" 
                class="absolute top-[-65px] -right-20 bg-green-500 text-white px-4 py-1 text-sm rounded-md hidden hover:bg-green-600 transition z-20 pointer-events-auto">
                Update
            </button>
        </form>
    </div>

    
    <!-- User Info -->
    <div class="ml-[25px] py-2">
        <div class="mt-3 space-y-3">
            <div class="flex justify-between items-center">
                <p class="text-lg font-semibold">{{ $user->first_name }} {{ strtoupper(substr($user->middle_name, 0, 1)) }}. {{ $user->last_name }}</p>
                <div class="relative mr-[15px]">
                    <!-- Checkbox Toggle -->
                    <input type="checkbox" id="dropdown-profile" class="peer hidden">
                    
                    <!-- Dotted Icon Button -->
                    <label for="dropdown-profile" class="flex items-center space-x-2 text-gray-800 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                        </svg>
                    </label>
                
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 top-full hidden peer-checked:flex flex-col w-[180px] bg-white border border-gray-300 rounded-md shadow-lg">
                        <a href="/profile-edit" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex justify-between items-center">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                  </svg> <span class="ml-2">Email & Profile</span>
                            </div>
                        </a>
                        <a href="/password-edit" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 flex justify-between items-center">
                            <div class="flex">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg> <span class="ml-2">Password</span>
                            </div>
                        </a>
                    </div>
                </div>            
            </div>
            <div class="flex justify-start items-center">
                <img src="/img/philippines.png" alt="" class="mr-3 w-[25px] h-[25px]">
                <p class="text-gray-600 capitalize">: Tagbilaran City, Bohol, Philippines</p>
            </div>
            <div class="flex justify-start items-center">
                <p class="text-blue-600 underline">{{ $user->email}}</p>
                <span class="px-3 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                      </svg>                      
                </span> 
                <div class="flex justify-start items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    <p>{{ $user->department ?? 'Not Assigned' }}</p>
                </div>
                <span class="px-3 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m5.25 4.5 7.5 7.5-7.5 7.5m6-15 7.5 7.5-7.5 7.5" />
                      </svg>                      
                </span>
                <p class="text-gray-600 capitalize">{{ $user->role ?? 'User' }}</p>
            </div>
            <!-- Additional Details -->
            <div class="flex justify-between items-center w-[250px]">
                <div class="mt-3 mr-3">
                    <span class="font-medium border border-gray-300 py-2 px-4 rounded">Joined:</span>
                </div>
                <div class="mt-3 bg-blue-600 text-white rounded py-2 px-4">
                    <p>{{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Leave and Overtime Distribution</h3>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Leave Balance Section -->
        <div class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Leave Balance</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Vacation:</span>
                    <span>{{ $user->vacation_leave_balance }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Sick:</span>
                    <span>{{ $user->sick_leave_balance }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Maternity Leave:</span>
                    <span>{{ $user->maternity_leave }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Paternity Leave:</span>
                    <span>{{ $user->paternity_leave }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Solo Parent Leave:</span>
                    <span>{{ $user->solo_parent_leave }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Study Leave:</span>
                    <span>{{ $user->study_leave }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>VAWC Leave:</span>
                    <span>{{ $user->vawc_leave }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Rehabilitation Leave:</span>
                    <span>{{ $user->rehabilitation_leave }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Special Leave Benefit:</span>
                    <span>{{ $user->special_leave_benefit }} day(s)</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Special Emergency Leave:</span>
                    <span>{{ $user->special_emergency_leave }} day(s)</span>
                </div>
            </div>
                    <!-- Total Leave & Overtime Balance Section -->
        <div class="bg-gray-50 mt-4 rounded-lg">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Total Leave & Overtime Balance</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Leave Available:</span>
                    <span>{{ $user->leave_balance }} days</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Overtime Available:</span>
                    <span>{{ $user->overtime_balance }} days</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Graph Section -->
    <div class="mt-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center">Leave Distribution Graph</h3>
        <div class="bg-gray-50 p-6 rounded-lg">
            <canvas id="leaveGraph" class="w-full h-80 shadow-md"></canvas>
        </div>
    </div>
 </div>
</div>
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('leaveGraph').getContext('2d');
    const leaveGraph = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Vacation', 'Sick', 'Maternity', 'Paternity', 'Solo Parent', 
                'Study', 'VAWC', 'Rehabilitation', 'Special Leave Benefit', 
                'Special Emergency', 'Overtime Available', 'Leave Available'
            ],
            datasets: [{
                label: 'Days',
                data: [
                    {{ $user->vacation_leave_balance }}, 
                    {{ $user->sick_leave_balance }}, 
                    {{ $user->maternity_leave }}, 
                    {{ $user->paternity_leave }}, 
                    {{ $user->solo_parent_leave }}, 
                    {{ $user->study_leave }}, 
                    {{ $user->vawc_leave }}, 
                    {{ $user->rehabilitation_leave }}, 
                    {{ $user->special_leave_benefit }}, 
                    {{ $user->special_emergency_leave }}, 
                    {{ $user->overtime_balance }}, 
                    {{ $user->leave_balance }}
                ],
                backgroundColor: [
                    '#22c55e', '#eab308', '#3b82f6', '#a855f7', '#f97316', 
                    '#14b8a6', '#ef4444', '#8b5cf6', '#64748b', '#f43f5e', 
                    '#6b7280', '#4b5563'
                ],
                borderColor: [
                    '#16a34a', '#ca8a04', '#2563eb', '#9333ea', '#ea580c', 
                    '#0d9488', '#dc2626', '#7c3aed', '#475569', '#e11d48', 
                    '#4b5563', '#374151'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Days'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Leave Types'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true
                }
            }
        }
    });
</script>

<!-- script -->
<script>
    document.getElementById("profile_image").onchange = function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById("profile-preview").src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    document.addEventListener("DOMContentLoaded", function() {
        const fileInput = document.getElementById("profile_image");
        const updateButton = document.getElementById("update-button");
        const previewImage = document.getElementById("profile-preview");

        fileInput.addEventListener("change", function(event) {
            // Show update button when a file is selected
            updateButton.classList.remove("hidden");

            // Preview the selected image
            const reader = new FileReader();
            reader.onload = function() {
                previewImage.src = reader.result;
            };
            if (event.target.files.length > 0) {
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    });
</script>
@endsection
{{-- <div class="mt-4 p-4 bg-blue-50 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-blue-700">Leave Balance</h3>
    <div class="flex gap-4 mt-2">
        <span class="bg-green-500 text-white px-3 py-1 rounded text-sm">{{ $user->leave_balance}} days</span>
        <span class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Sick: {{ $user->leave_balance['sick'] ?? 0 }} days</span>
        <span class="bg-red-500 text-white px-3 py-1 rounded text-sm">Casual: {{ $user->leave_balance['casual'] ?? 0 }} days</span>
    </div>
</div> --}}