@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
        <x-notify::notify />
    </div>
    @notifyJs

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
                @include('hr.modals.user-create')
                <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Create New
                </button>
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