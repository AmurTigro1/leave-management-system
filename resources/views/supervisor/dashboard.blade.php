@extends('layouts.supervisor.sidebar-header')

@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="container p-6 animate-fade-in">
    <h2 class="text-2xl font-bold mb-4">Application Management</h2>

    <div class="container mx-auto px-4">
        <!-- Leave & CTO Requests Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach([['Leave Requests', $leaveStats], ['CTO Requests', $cocStats]] as [$title, $stats])
                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col h-full">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $title }}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 flex-grow">
                        @foreach(['Pending' => 'yellow', 'Approved' => 'green', 'Rejected' => 'red'] as $status => $color)
                            <div class="bg-{{ $color }}-100 p-6 rounded-lg shadow text-center flex flex-col justify-center min-h-[140px]">
                                <h3 class="text-md sm:text-xl font-semibold text-gray-700">{{ $status }}</h3>
                                <p class="text-3xl sm:text-4xl font-bold text-{{ $color }}-600">{{ $stats[$status] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>    

    <div class="bg-white p-6 rounded-lg shadow-md text-center mb-4">
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Employees</h3>
        <p class="text-6xl font-bold text-blue-600">{{ $totalUsers }}</p>
    </div>

    <!-- Leave Statistics Bar Chart -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Leave Statistics Bar Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Leave Requests Overview</h3>
            <div class="w-full h-64">
                <canvas id="leaveChart"></canvas>
            </div>
        </div>
    
        <!-- CTO / Overtime Requests Bar Chart -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">CTO / Overtime Requests Overview</h3>
            <div class="w-full h-64">
                <canvas id="ctoChart"></canvas>
            </div>
        </div>        
    </div>    

    <div class="mt-4">
        <form method="GET" action="{{ route('supervisor.dashboard') }}">
            <label for="year" class="text-sm font-medium text-gray-700">Select Year:</label>
            <select name="year" id="year" class="border rounded p-2 w-[10%]">
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
            
            <canvas id="visitorChart" height="100"></canvas>
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
                        backgroundColor: 'rgba(59, 130, 246, 0.5)', // blue-500
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0 // force whole numbers
                            }
                        }
                    }
                }
            });
        </script>
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
        @include('supervisor.partials.employee-list', ['employees' => $employees])
    </div>
</div>
</div>

<script>
    function fetchResults(searchTerm) {
        fetch(`{{ route('supervisor.dashboard') }}?search=${searchTerm}`, {
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('leaveChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    label: 'Leave Requests',
                    data: [{{ $leaveStats['Pending'] }}, {{ $leaveStats['Approved'] }}, {{ $leaveStats['Rejected'] }}],
                    backgroundColor: ['#FBBF24', '#34D399', '#EF4444'],
                    borderColor: ['#D97706', '#059669', '#B91C1C'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        const ctxCTO = document.getElementById('ctoChart').getContext('2d');
        new Chart(ctxCTO, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    label: 'CTO / Overtime Requests',
                    data: [{{ $cocStats['Pending'] }}, {{ $cocStats['Approved'] }}, {{ $cocStats['Rejected'] }}],
                    backgroundColor: ['#FBBF24', '#34D399', '#EF4444'],
                    borderColor: ['#D97706', '#059669', '#B91C1C'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>

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