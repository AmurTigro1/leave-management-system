@extends('main_resources.index')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Leave Management Dashboard</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Leaves -->
        <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">Total Leave Requests</h3>
            <p class="text-3xl font-bold">{{ $totalLeaves }}</p>
        </div>

        <!-- Approved Leaves -->
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">Approved Leaves</h3>
            <p class="text-3xl font-bold">{{ $approvedLeaves }}</p>
        </div>

        <!-- Pending Leaves -->
        <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">Pending Leaves</h3>
            <p class="text-3xl font-bold">{{ $pendingLeaves }}</p>
        </div>

        <!-- Rejected Leaves -->
        <div class="bg-red-500 text-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold">Rejected Leaves</h3>
            <p class="text-3xl font-bold">{{ $rejectedLeaves }}</p>
        </div>
    </div>
    <div class="mt-6 bg-white p-6 rounded-lg flex justify-center">
        <div class="w-64 h-64">
            <canvas id="leaveChart"></canvas>
        </div>
    </div>
    

</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById("leaveChart").getContext("2d");
        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Approved", "Pending", "Rejected"],
                datasets: [{
                    data: [{{ $approvedLeaves }}, {{ $pendingLeaves }}, {{ $rejectedLeaves }}],
                    backgroundColor: ["#10B981", "#FACC15", "#EF4444"]
                }]
            }
        });
    });
</script>