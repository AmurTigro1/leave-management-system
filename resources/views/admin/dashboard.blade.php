@extends('layouts.admin.sidebar-header')

@section('content')

<!-- Notification positioning fixed for all screens -->
<div class="fixed top-0 left-0 right-0 sm:left-auto sm:right-4 sm:top-4 z-[9999]">
    <x-notify::notify class="w-full sm:w-auto" />
</div>

<!-- Main container with responsive padding -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 animate-fade-in">
    <!-- Page title -->
    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Application Verification</h2>
    
    <!-- Stats cards container -->
    <div class="container mx-auto">
        <!-- Leave & CTO Requests Summary - responsive grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            @foreach([['Leave Requests', $leaveStats], ['CTO Requests', $cocStats]] as [$title, $stats])
                <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md flex flex-col h-full">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-3 sm:mb-4">{{ $title }}</h2>
                    <div class="grid grid-cols-3 gap-3 sm:gap-4 flex-grow">
                        @foreach(['Pending' => 'yellow', 'Approved' => 'green', 'Rejected' => 'red'] as $status => $color)
                            <div class="bg-{{ $color }}-100 p-3 sm:p-4 rounded-lg shadow text-center flex flex-col justify-center min-h-[100px] sm:min-h-[120px]">
                                <h3 class="text-xs sm:text-sm font-semibold text-gray-700">{{ $status }}</h3>
                                <p class="text-xl sm:text-2xl font-bold text-{{ $color }}-600 mt-1">{{ $stats[$status] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>    

    <div class="mt-4">
        <form method="GET" action="{{ route('admin.dashboard') }}">
            <label for="year" class="text-sm font-medium text-gray-700">Select Year:</label>
            <select name="year" id="year" class="border rounded p-2 w-[30%] lg:w-[10%]">
                @foreach(range(now()->year, now()->year - 5) as $yr)
                    <option value="{{ $yr }}" {{ request('year', now()->year) == $yr ? 'selected' : '' }}>
                        {{ $yr }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="ml-2 bg-blue-500 text-white px-4 py-1 rounded">Apply</button>
        </form>
        
            <!-- Visitor Chart Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">
                Monthly Visitor Analytics for {{ $selectedYear }}
            </h2>
            
            <div class="overflow-x-auto w-full">
                <div style="min-width: 700px;"> <!-- Set a minimum width to fit all bars -->
                    <canvas id="visitorChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('visitorChart').getContext('2d');
            const visitorChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months ?? []) !!},
                    datasets: [{
                        label: 'Visitors',
                        data: {!! json_encode($visitorCounts ?? []) !!},
                        backgroundColor: 'rgba(59, 130, 246, 0.5)', // Tailwind blue-500
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Important for mobile
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // Force whole numbers
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                font: {
                                    size: 12 // Better readability on mobile
                                }
                            }
                        }
                    }
                }
            });
        </script>
    </div>


    <!-- Search bar with responsive layout -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 my-4 sm:my-5" x-data="{ search: '' }">
        <div class="flex-grow">
            <input 
                type="text" 
                x-model="search"
                @input.debounce.500ms="fetchResults(search)"
                placeholder="Search by name, email, or position"
                class="border border-gray-300 rounded-md px-3 sm:px-4 py-2 focus:ring-blue-500 focus:border-blue-500 w-full text-sm sm:text-base"
            >
        </div>
        <button 
            @click="search = ''; fetchResults('')"
            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition text-sm sm:text-base whitespace-nowrap">
            Clear Search
        </button>
    </div>
    
    <!-- Search Results - responsive container -->
    <div id="employee-results" class="overflow-x-auto">
        @include('admin.partials.employee-list', ['employees' => $employees])
    </div>
</div>

<!-- Improved responsive script -->
<script>
    function fetchResults(searchTerm) {
        fetch(`{{ route('admin.dashboard') }}?search=${encodeURIComponent(searchTerm)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            document.getElementById('employee-results').innerHTML = html;
        })
        .catch(error => {
            console.error('Fetch error:', error);
            document.getElementById('employee-results').innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">Error loading results. Please try again.</p>
                        </div>
                    </div>
                </div>
            `;
        });
    }
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Ensure tables are responsive */
    @media (max-width: 640px) {
        #employee-results table {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>

@endsection
@notifyCss