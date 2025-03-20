@extends('layouts.supervisor.sidebar-header')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Leave Management Dashboard</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md text-center">
            <h3 class="text-xl font-semibold text-gray-700">Total Employees</h3>
            <p class="text-4xl font-bold text-blue-600">{{ $totalUsers}}</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg shadow-md text-center">
            <h3 class="text-xl font-semibold text-gray-700">Pending Leaves</h3>
            <p class="text-4xl font-bold text-yellow-600">{{ $pendingLeaves}}</p>
        </div>
        <div class="bg-green-100 p-6 rounded-lg shadow-md text-center">
            <h3 class="text-xl font-semibold text-gray-700">Approved Leaves</h3>
            <p class="text-4xl font-bold text-green-600">{{ $approvedLeaves}}</p>
        </div>
        <div class="bg-red-100 p-6 rounded-lg shadow-md text-center">
            <h3 class="text-xl font-semibold text-gray-700">Rejected Leaves</h3>
            <p class="text-4xl font-bold text-red-600">{{ $rejectedLeaves}}</p>
        </div>
    </div>
    <!-- Leave Statistics Bar Chart -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Leave Requests Overview</h3>
        <div class="w-full h-64">
            <canvas id="leaveChart"></canvas>
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

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('leaveChart').getContext('2d');
    const leaveChart = new Chart(ctx, {
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
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection

