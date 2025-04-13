@extends('layouts.hr.sidebar-header')

@section('content')
    <!-- Notification -->
    <div class="fixed top-2 right-2 z-[9999] sm:top-4 sm:right-4 md:top-6 md:right-6">
        <x-notify::notify />
    </div>
    @notifyJs

    <!-- Help Icon -->
    <div class="relative">
        <button id="helpBtn" class="text-blue-600 hover:text-blue-800 fixed bottom-4 right-4 bg-white p-3 rounded-full shadow-lg z-50 sm:static sm:shadow-none sm:p-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>          
        </button>
    </div>

    <!-- Help Modal -->
    <div id="helpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-[10000] hidden p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-4 sm:p-6">
            <div class="flex justify-between items-center">
                <h3 class="text-lg sm:text-xl font-semibold text-gray-800">Help Guide</h3>
                <button id="closeModalBtn" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                   Close
                </button>
            </div>
            <div class="mt-4 space-y-4">
                <div>
                    <p class="font-semibold text-gray-700">How to use:</p>
                    <ul class="list-disc pl-5 text-sm space-y-1 mt-2">
                        <li>Click on <span class="text-blue-600 font-semibold">'Create New'</span> to add a new user.</li>
                        <li>Use the search bar to filter users by name, email, or position.</li>
                        <li>Export the user list as a PDF by clicking the <span class="text-red-600 font-semibold">'Export to PDF'</span> button.</li>
                    </ul>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">HR and Supervisor Concern:</p>
                    <ul class="list-disc pl-5 text-sm space-y-1 mt-2">
                        <li>The <span class="text-green-700 font-semibold">HR</span> cannot delete itself and the Supervisor if there's no other user using their role since it needs at least 1 record to uphold the role.</li>
                        <li>The <span class="text-green-700 font-semibold">HR</span> cannot assign itself to any other role if there's no other user using the role of <span class="text-green-700 font-semibold">HR</span> - only when the <span class="text-green-700 font-semibold">HR</span> assigned a new user to the role of <span class="text-green-700 font-semibold">HR</span> can they or the new user assign the current <span class="text-green-700 font-semibold">HR</span> to any other role.</li>
                        <li>The <span class="text-green-700 font-semibold">HR</span> can only assign 2 users for supervisor and hr roles since the role involves important matter of handling requests.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-4 mx-4 sm:mx-0">
        <strong>Note:</strong> 
        {{ session('error') }}

        @if(session('role') == 'hr')
            @php
                $currentHrUsers = App\Models\User::where('role', 'hr')->orderBy('updated_at', 'desc')->get();
            @endphp
            @if($currentHrUsers->count() > 0)
                <div class="mt-4">
                    <p class="font-semibold text-gray-800">Current HR Users:</p>
                    <div class="space-y-3 mt-3">
                        @foreach($currentHrUsers as $index => $hr)
                            <div class="flex items-center space-x-3 p-3 bg-white shadow rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="{{ 
                                        $hr->profile_image && file_exists(storage_path('app/public/profile_images/' . $hr->profile_image)) 
                                            ? asset('storage/profile_images/' . $hr->profile_image) 
                                            : ($hr->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $hr->profile_pictures)) 
                                                ? asset('storage/profile_pictures/' . $hr->profile_image) 
                                                : asset('img/default-avatar.png')) 
                                    }}" 
                                    class="h-10 w-10 rounded-full object-cover" alt="{{ $hr->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $hr->name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $hr->email }}</p>
                                </div>

                                @if($index === 0 )
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-blue-500 rounded-full">Latest</span>
                                @elseif($index === $currentHrUsers->count() - 1)
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @elseif(session('role') == 'supervisor')
            @php
                $currentSupervisorUsers = App\Models\User::where('role', 'supervisor')->orderBy('updated_at', 'desc')->get();
            @endphp
            @if($currentSupervisorUsers->count() > 0)
                <div class="mt-4">
                    <p class="font-semibold text-gray-800">Current Supervisors:</p>
                    <div class="space-y-3 mt-3">
                        @foreach($currentSupervisorUsers as $index => $supervisor)
                            <div class="flex items-center space-x-3 p-3 bg-white shadow rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="{{ 
                                        $supervisor->profile_image && file_exists(storage_path('app/public/profile_images/' . $supervisor->profile_image)) 
                                            ? asset('storage/profile_images/' . $supervisor->profile_image) 
                                            : ($supervisor->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $supervisor->profile_pictures)) 
                                                ? asset('storage/profile_pictures/' . $supervisor->profile_image) 
                                                : asset('img/default-avatar.png')) 
                                    }}" 
                                    class="h-10 w-10 rounded-full object-cover" alt="{{ $supervisor->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $supervisor->name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $supervisor->email }}</p>
                                </div>

                                @if($index === 0)
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-blue-500 rounded-full">Latest</span>
                                @elseif($index === $currentSupervisorUsers->count() - 1)
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <div class="container mx-auto px-2 sm:px-4 py-4 sm:py-6 animate-fade-in">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header Section -->
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:justify-between md:items-center">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6 mr-2 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800">Users Account Management</h2>
                    </div>
                    
                    <!-- Button Group -->
                    <div class="flex items-center space-x-2">
                        @include('hr.modals.user-create')
                        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 sm:px-4 rounded-lg flex items-center transition-colors duration-200 text-sm sm:text-base">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                            </svg>
                            Create New
                        </button>
                        <a href="{{ route('hr.users', array_merge(request()->query(), ['export' => 'pdf'])) }}" 
                            class="flex items-center px-3 sm:px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 text-xs sm:text-sm">
                             <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                             </svg>
                             Export PDF
                         </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div x-data="{
                    search: '{{ request('search') }}',
                    orderBy: '{{ request('order_by') }}',
                    fetchResults() {
                        const url = new URL('{{ route('hr.users') }}');
                        if (this.search) url.searchParams.append('search', this.search);
                        if (this.orderBy) url.searchParams.append('order_by', this.orderBy);
                        
                        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(response => response.text())
                            .then(html => document.getElementById('employee-results').innerHTML = html);
                    }
                }" class="mt-4 sm:mt-6">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:space-y-0 sm:space-x-3">
                        <div class="w-full sm:w-40">
                            <select x-model="orderBy" @change="fetchResults()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Order By</option>
                                <option value="created_at">Created At</option>
                                <option value="last_name">Last Name</option>
                            </select>
                        </div>
                
                        <div class="flex-1 flex space-x-2">
                            <input 
                                type="text" 
                                x-model="search"
                                @input.debounce.500ms="fetchResults()"
                                placeholder="Search by name, email, or position"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                            <button 
                                @click="search = ''; fetchResults()"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-2 rounded-lg transition-colors duration-200 text-xs sm:text-sm font-medium">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Table -->
            <div id="employee-results" class="overflow-x-auto">
                @include('hr.partials.user-list', ['users' => $users])
            </div> 
        </div>

        <!-- HR Users Section -->
        <div class="bg-white rounded-lg shadow-md p-4 mt-4">
            @php
                $currentHrUsers = App\Models\User::where('role', 'hr')->orderBy('updated_at', 'desc')->get();
            @endphp

            @if($currentHrUsers->count() == 2)
                <p class="text-gray-500 text-sm sm:text-base mb-3">The PDF File requires only 1 <span class="text-green-600 font-semibold">HR</span> user hence one must be assigned to other roles available (Employee, Admin)</p>
            @endif
            @if($currentHrUsers->count() > 0)
                <div>
                    <p class="font-semibold text-gray-800">Current HR Users:</p>
                    <div class="space-y-3 mt-3">
                        @foreach($currentHrUsers as $index => $hr)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="{{ 
                                        $hr->profile_image && file_exists(storage_path('app/public/profile_images/' . $hr->profile_image)) 
                                            ? asset('storage/profile_images/' . $hr->profile_image) 
                                            : ($hr->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $hr->profile_pictures)) 
                                                ? asset('storage/profile_pictures/' . $hr->profile_image) 
                                                : asset('img/default-avatar.png')) 
                                    }}" 
                                    class="h-10 w-10 rounded-full object-cover" alt="{{ $hr->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $hr->name }}</p>
                                    <p class="text-xs sm:text-sm text-blue-600 truncate">{{ $hr->email }}</p>
                                </div>

                                @if($index === 0 )
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-blue-500 rounded-full">Latest</span>
                                @elseif($index === $currentHrUsers->count() - 1)
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Supervisor Users Section -->
        <div class="bg-white rounded-lg shadow-md p-4 mt-4"> 
            @php
                $currentSupervisorUsers = App\Models\User::where('role', 'supervisor')->orderBy('updated_at', 'desc')->get();
            @endphp

            @if($currentSupervisorUsers->count() == 2)
                <p class="text-gray-500 text-sm sm:text-base mb-3">The PDF File requires only 1 <span class="text-green-600 font-semibold">Supervisor</span> user hence one must be assigned to other roles available (Employee, Admin)</p>
            @endif

            @if($currentSupervisorUsers->count() > 0)
                <div>
                    <p class="font-semibold text-gray-800">Current Supervisors:</p>
                    <div class="space-y-3 mt-3">
                        @foreach($currentSupervisorUsers as $index => $supervisor)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="{{ 
                                        $supervisor->profile_image && file_exists(storage_path('app/public/profile_images/' . $supervisor->profile_image)) 
                                            ? asset('storage/profile_images/' . $supervisor->profile_image) 
                                            : ($supervisor->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $supervisor->profile_pictures)) 
                                                ? asset('storage/profile_pictures/' . $supervisor->profile_image) 
                                                : asset('img/default-avatar.png')) 
                                    }}" 
                                    class="h-10 w-10 rounded-full object-cover" alt="{{ $supervisor->name }}">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $supervisor->name }}</p>
                                    <p class="text-xs sm:text-sm text-blue-600 truncate">{{ $supervisor->email }}</p>
                                </div>

                                @if($index === 0)
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-blue-500 rounded-full">Latest</span>
                                @elseif($index === $currentSupervisorUsers->count() - 1)
                                    <span class="px-2 py-0.5 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Help Modal Behavior
        const helpBtn = document.getElementById('helpBtn');
        const helpModal = document.getElementById('helpModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        helpBtn.addEventListener('click', function() {
            helpModal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', function() {
            helpModal.classList.add('hidden');
        });

        window.addEventListener('click', function(event) {
            if (event.target === helpModal) {
                helpModal.classList.add('hidden');
            }
        });

        // Fetch results function
        function fetchResults(searchTerm) {
            fetch(`{{ route('hr.users') }}?search=${searchTerm}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('employee-results').innerHTML = html;
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection
@notifyCss