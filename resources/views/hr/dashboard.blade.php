@extends('layouts.hr.sidebar-header')

@section('content')
<div class="max-w-7xl mx-auto p-6 space-y-6">
    <div class="container mx-auto px-4 py-6">
        <!-- Page Title -->
        <h2 class="text-3xl font-bold text-gray-800 mb-6">HR Dashboard</h2>
    
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

    <!-- Employee Leave Balances -->
    <div class="p-6 bg-white shadow-md rounded-lg">
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Employee Leave Balances</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 text-sm text-gray-700">
                <thead class="bg-gray-50 text-gray-700 font-semibold">
                    <tr>
                        <th class="py-3 px-4 border-b">Employee</th>
                        <th class="py-3 px-4 border-b">Profile</th>
                        <th class="py-3 px-4 border-b">Vacation Leave</th>
                        <th class="py-3 px-4 border-b">Sick Leave</th>
                        <th class="py-3 px-4 border-b">Leave Balance</th>
                        <th class="py-3 px-4 border-b">Cocs</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach ($employees as $employee)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="py-3 px-4 border-b">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td class="py-3 px-4 border-b">
                                <div class="flex justify-center items-center">
                                    @if ($employee->profile_image)
                                        <img src="{{ asset('storage/profile_images/' . $employee->profile_image) }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <span class="text-sm text-gray-500">No Image</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 border-b">{{ $employee->vacation_leave_balance }} days</td>
                            <td class="py-3 px-4 border-b">{{ $employee->sick_leave_balance }} days</td>
                            <td class="py-3 px-4 border-b">{{ $employee->leave_balance }} days</td>
                            <td class="py-3 px-4 border-b">{{ $employee->overtime_balance }} days</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-between items-center">
            <p class="text-gray-600 text-sm">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} employees
            </p>
            <div class="space-x-2">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
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
@endsection
