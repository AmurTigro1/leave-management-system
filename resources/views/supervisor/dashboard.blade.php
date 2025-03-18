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

    <div class="p-6 bg-white shadow-md rounded-lg">
        <ploy class="text-2xl font-semibold text-gray-800 mb-4">Total Employees</ploy>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-700">Employee</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-700">Profile</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-700">Vacation Leave</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-700">Sick Leave</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-left text-sm font-semibold text-gray-700">Total Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr class="hover:bg-gray-100 transition-colors">
                            <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                            <td class="py-3 px-4 border-b border-gray-200">
                                @if ($employee->profile_image)
                                    <img src="{{ asset('storage/profile_images/' . $employee->profile_image) }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <span class="text-sm text-gray-500">No Image</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">{{ $employee->vacation_leave_balance }} days</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">{{ $employee->sick_leave_balance }} days</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-sm text-gray-700">{{ $employee->leave_balance }} days</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <!-- Pagination -->
        <div class="mt-4">
            <p class="text-gray-600 text-sm">
                Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} employees
            </p>
           <div class="mt-4 flex justify-end">
    @if ($employees->hasPages())
        <nav class="flex space-x-2">
            {{-- Previous Page Link --}}
            @if ($employees->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                    &larr; Prev
                </span>
            @else
                <a href="{{ $employees->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                    &larr; Prev
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                @if ($page == $employees->currentPage())
                    <span class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($employees->hasMorePages())
                <a href="{{ $employees->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                    Next &rarr;
                </a>
            @else
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                    Next &rarr;
                </span>
            @endif
        </nav>
    @endif
</div>

        </div>
    </div>

</div>
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

