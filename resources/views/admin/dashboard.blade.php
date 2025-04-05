@extends('layouts.admin.sidebar-header')

@section('content')

<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="max-w-7xl mx-auto  space-y-6 animate-fade-in">
    <h2 class="text-2xl font-bold">Application Verification</h2>
    <div class="container mx-auto px-4">
        <!-- Leave & CTO Requests Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach([['Leave Requests', $leaveStats], ['CTO Requests', $cocStats]] as [$title, $stats])
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col h-full">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">{{ $title }}</h2>
                    <div class="grid grid-cols-3 gap-4 flex-grow">
                        @foreach(['Pending' => 'yellow', 'Approved' => 'green', 'Rejected' => 'red'] as $status => $color)
                            <div class="bg-{{ $color }}-100 p-4 md:p-6 rounded-lg shadow text-center flex flex-col justify-center min-h-[120px] md:min-h-[140px]">
                                <h3 class="text-sm md:text-lg font-semibold text-gray-700">{{ $status }}</h3>
                                <p class="text-2xl md:text-3xl font-bold text-{{ $color }}-600 mt-1">{{ $stats[$status] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>    

    <div class="flex items-center space-x-2 my-5" x-data="{ search: '' }">
        <input 
            type="text" 
            x-model="search"
            @input.debounce.500ms="fetchResults(search)"
            placeholder="Search by name, email, or position"
            class="border border-gray-300 rounded-md px-4 py-2 focus:ring-blue-500 focus:border-blue-500 w-full"
        >
        <button 
            @click="search = ''; fetchResults('')"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
            Clear
        </button>
    </div>
    <!-- Search Results -->
    <div id="employee-results">
        @include('admin.partials.employee-list', ['employees' => $employees])
    </div>
</div>

<script>
    function fetchResults(searchTerm) {
        fetch(`{{ route('admin.dashboard') }}?search=${searchTerm}`, {
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
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

@endsection
@notifyCss