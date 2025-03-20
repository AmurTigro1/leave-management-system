@extends('layouts.hr.sidebar-header')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6 animate-fade-in">
    <div class="container mx-auto px-4 py-6">
    
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

    <!-- Total Employees Card -->
    <div class="bg-white p-6 rounded-lg shadow-md text-center">
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Total Employees</h3>
        <p class="text-6xl font-bold text-blue-600">{{ $totalEmployees }}</p>
    </div>

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
        @include('hr.partials.employee-list', ['employees' => $employees])
    </div>
</div>

<script>
    function fetchResults(searchTerm) {
        fetch(`{{ route('hr.dashboard') }}?search=${searchTerm}`, {
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

<!-- Chart.js Script -->
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
