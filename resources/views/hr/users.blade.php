@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
        <x-notify::notify />
    </div>
    @notifyJs

    <!-- Help Icon -->
<div class="relative">
    <button id="helpBtn" class="text-blue-600 text-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
          </svg>          
    </button>
</div>

<!-- Modal -->
<div id="helpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-[10000] hidden">
    <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
        <div class="flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Help Guide</h3>
            <button id="closeModalBtn" class="text-gray-600 hover:text-gray-800 focus:outline-none">
               Close
            </button>
        </div>
        <div class="mt-4">
            <p><strong>How to use:</strong></p>
            <ul class="list-disc pl-5 text-sm">
                <li>Click on <span class="text-blue-600 font-semibold">'Create New'</span> to add a new user.</li>
                <li>Use the search bar to filter users by name, email, or position.</li>
                <li>Export the user list as a PDF by clicking the <span class="text-red-600 font-semibold">'Export to PDF'</span> button.</li>
            </ul>
        </div>
        <div class="mt-4">
            <p><strong>HR and Supervisor Concern:</strong></p>
            <ul class="list-disc pl-5 text-sm">
                <li>The <span class="text-green-700 font-semibold">HR</span> cannot delete itself and the Supervisor if there's no other user using their role since it needs at least 1 record to uphold the role.
                </li>
                <li>The <span class="text-green-700 font-semibold">HR</span> cannot assign itself to any other role if there's no other user using the role of <span class="text-green-700 font-semibold">HR</span> - only when the <span class="text-green-700 font-semibold">HR</span> assigned a new user to the role of <span class="text-green-700 font-semibold">HR</span> can they or the new user assign the current <span class="text-green-700 font-semibold">HR</span> to any other role.
                </li>
                <li>The <span class="text-green-700 font-semibold">HR</span> can only assign 2 users for supervisor and hr roles since the role involves important matter of handling requests.</li>
            </ul>
        </div>
    </div>
</div>

<!-- JavaScript for Modal Behavior -->
<script>
    // Get modal and buttons
    const helpBtn = document.getElementById('helpBtn');
    const helpModal = document.getElementById('helpModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    // Open the modal when the help icon is clicked
    helpBtn.addEventListener('click', function() {
        helpModal.classList.remove('hidden');
    });

    // Close the modal when the close button is clicked
    closeModalBtn.addEventListener('click', function() {
        helpModal.classList.add('hidden');
    });

    // Optionally close the modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === helpModal) {
            helpModal.classList.add('hidden');
        }
    });
</script>

    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-lg mb-4">
        <strong>Note:</strong> 
        {{ session('error') }}

        @if(session('role') == 'hr')
            @php
                // Fetch the current HR users from the database, ordered by the most recent first
                $currentHrUsers = App\Models\User::where('role', 'hr')->orderBy('updated_at', 'desc')->get();
            @endphp
            @if($currentHrUsers->count() > 0)
                <div class="mt-4">
                    <p class="font-semibold text-lg text-gray-800">Current HR Users:</p>
                    <div class="space-y-4 mt-4">
                        @foreach($currentHrUsers as $index => $hr)
                            <div class="flex items-center space-x-4 p-4 bg-white shadow rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="{{ 
                                        $hr->profile_image && file_exists(storage_path('app/public/profile_images/' . $hr->profile_image)) 
                                            ? asset('storage/profile_images/' . $hr->profile_image) 
                                            : ($hr->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $hr->profile_pictures)) 
                                                ? asset('storage/profile_pictures/' . $hr->profile_image) 
                                                : asset('img/default-avatar.png')) 
                                    }}" 
                                    class="h-12 w-12 rounded-full object-cover" alt="{{ $hr->name }}">
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $hr->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $hr->email }}</p>
                                </div>

                                @if($index === 0 )
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-blue-500 rounded-full">Latest Added</span>
                                @elseif($index === $currentHrUsers->count() - 1)
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">Current (Displayed in the PDF File)</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @elseif(session('role') == 'supervisor')
            @php
                // Fetch the current Supervisor users from the database, ordered by the most recent first
                $currentSupervisorUsers = App\Models\User::where('role', 'supervisor')->orderBy('updated_at', 'desc')->get();
            @endphp
            @if($currentSupervisorUsers->count() > 0)
                <div class="mt-4">
                    <p class="font-semibold text-lg text-gray-800">Current Supervisors:</p>
                    <div class="space-y-4 mt-4">
                        @foreach($currentSupervisorUsers as $index => $supervisor)
                            <div class="flex items-center space-x-4 p-4 bg-white shadow rounded-lg">
                                <div class="flex-shrink-0">
                                    <img src="{{ 
                                        $supervisor->profile_image && file_exists(storage_path('app/public/profile_images/' . $supervisor->profile_image)) 
                                            ? asset('storage/profile_images/' . $supervisor->profile_image) 
                                            : ($supervisor->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $supervisor->profile_pictures)) 
                                                ? asset('storage/profile_pictures/' . $supervisor->profile_image) 
                                                : asset('img/default-avatar.png')) 
                                    }}" 
                                    class="h-12 w-12 rounded-full object-cover" alt="{{ $supervisor->name }}">
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800">{{ $supervisor->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $supervisor->email }}</p>
                                </div>

                                @if($index === 0)
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-blue-500 rounded-full">Latest Added</span>
                                @elseif($index === $currentSupervisorUsers->count() - 1)
                                    <span class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
@endif



<div class="container mx-auto px-4 py-6 animate-fade-in">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col space-y-4 md:space-y-0 md:flex-row md:justify-between md:items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2 text-blue-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                    <h2 class="text-xl font-bold text-gray-800">Users Account Management</h2>
                </div>
                
                <!-- Button Group -->
                <div class="flex items-center space-x-2">
                    @include('hr.modals.user-create')
                    <!-- Create New Button -->
                    <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Create New
                    </button>
                    <a href="{{ route('hr.users', array_merge(request()->query(), ['export' => 'pdf'])) }}" 
                        class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                         </svg>
                         Export to PDF
                     </a>
                </div>
            </div>

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
            }" class="mt-6">
                <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                    <div class="w-full md:w-48">
                        <select x-model="orderBy" @change="fetchResults()" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        <button 
                            @click="search = ''; fetchResults()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200 text-sm font-medium">
                            Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="employee-results" class="overflow-x-auto">
            @include('hr.partials.user-list', ['users' => $users])
        </div> 
    </div>
    <div class="py-2 px-4 rounded-lg shadow-lg">
        @php
            // Fetch the current HR users from the database, ordered by the most recent first
            $currentHrUsers = App\Models\User::where('role', 'hr')->orderBy('updated_at', 'desc')->get();
        @endphp

        @if($currentHrUsers->count() == 2)
            <h1 class="px-4 mt-4 text-gray-500">The PDF File requires only 1 <span class="text-green-600 font-semibold">HR</span> user hence one must be assigned to other roles available (Employee, Admin)</h1>
        @endif
        @if($currentHrUsers->count() > 0)
            <div class="mt-4 px-4">
                <p class="font-semibold text-lg text-gray-800">Current HR Users:</p>
                <div class="space-y-4 mt-4">
                    @foreach($currentHrUsers as $index => $hr)
                        <div class="flex items-center space-x-4 p-4 bg-white shadow rounded-lg">
                            <div class="flex-shrink-0">
                                <img src="{{ 
                                    $hr->profile_image && file_exists(storage_path('app/public/profile_images/' . $hr->profile_image)) 
                                        ? asset('storage/profile_images/' . $hr->profile_image) 
                                        : ($hr->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $hr->profile_pictures)) 
                                            ? asset('storage/profile_pictures/' . $hr->profile_image) 
                                            : asset('img/default-avatar.png')) 
                                }}" 
                                class="h-12 w-12 rounded-full object-cover" alt="{{ $hr->name }}">
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $hr->name }}</p>
                                <p class="text-sm text-blue-600">{{ $hr->email }}</p>
                            </div>

                            @if($index === 0 )
                                <span class="px-3 py-1 text-xs font-bold text-white bg-blue-500 rounded-full">Latest Added</span>
                            @elseif($index === $currentHrUsers->count() - 1)
                                <span class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    <div class="py-2 px-4 rounded-lg shadow-lg"> 
        @php
            // Fetch the current Supervisor users from the database, ordered by the most recent first
            $currentSupervisorUsers = App\Models\User::where('role', 'supervisor')->orderBy('updated_at', 'desc')->get();
        @endphp

        @if($currentSupervisorUsers->count() == 2)
            <h1 class="px-4 mt-4 text-gray-500">The PDF File requires only 1 <span class="text-green-600 font-semibold">Supervisor</span> user hence one must be assigned to other roles available (Employee, Admin)</h1>
        @endif

        @if($currentSupervisorUsers->count() > 0)
            <div class="mt-4 px-4">
                <p class="font-semibold text-lg text-gray-800">Current Supervisors:</p>
                <div class="space-y-4 mt-4">
                    @foreach($currentSupervisorUsers as $index => $supervisor)
                        <div class="flex items-center space-x-4 p-4 bg-white shadow rounded-lg">
                            <div class="flex-shrink-0">
                                <img src="{{ 
                                    $supervisor->profile_image && file_exists(storage_path('app/public/profile_images/' . $supervisor->profile_image)) 
                                        ? asset('storage/profile_images/' . $supervisor->profile_image) 
                                        : ($supervisor->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $supervisor->profile_pictures)) 
                                            ? asset('storage/profile_pictures/' . $supervisor->profile_image) 
                                            : asset('img/default-avatar.png')) 
                                }}" 
                                class="h-12 w-12 rounded-full object-cover" alt="{{ $supervisor->name }}">
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800">{{ $supervisor->name }}</p>
                                <p class="text-sm text-blue-600">{{ $supervisor->email }}</p>
                            </div>

                            @if($index === 0)
                                <span class="px-3 py-1 text-xs font-bold text-white bg-blue-500 rounded-full">Latest Added</span>
                            @elseif($index === $currentSupervisorUsers->count() - 1)
                                <span class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full">Current</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
<script>
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

    @media (max-width: 640px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        th, td {
            min-width: 100px;
            padding: 0.5rem;
        }
        
        #userModal {
            padding: 1rem;
        }
        
        #userModal > div {
            width: 95%;
            max-height: 90vh;
            overflow-y: auto;
        }
    }
</style>
@endsection
@notifyCss