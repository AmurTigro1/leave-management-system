@extends('layouts.admin.sidebar-header')

@section('content')

<a href="{{ route('admin.requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300">
    &larr; Back to Requests
</a>
<div class="flex justify-between items-start gap-4 px-4">
    <div class="bg-white shadow-xl rounded-lg p-6 space-y-6 w-full h-auto py-[25px]">
        <h2 class="text-2xl font-bold">Leave Balances</h2>
        <div class="flex justify-between items-center">
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Vacation Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Sick Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Maternity Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Paternity Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Solo Parent Leave</div>
        </div>
        <div class="flex justify-between items-center">
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Study Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">VAWC Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Rehabilitation Leave</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Special Leave Benefit</div>
            <div class="bg-blue-600 text-white rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">Special Energency Leave</div>
        </div>
        <br>
        <div class="flex justify-between items-center">
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->vacation_leave_balance}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->sick_leave_balance}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->maternity_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->paternity_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->solo_parent_leave}} days</div>
        </div>
        <div class="flex justify-between items-center">
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->study_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->vawc_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->rehabilitation_leave}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->special_leave_benefit}} days</div>
            <div class="bg-gray-300 text-black rounded-lg p-2 text-[10px] w-[124px] text-center mr-2">{{ $leave->user->special_emergency_leave}} days</div>
        </div>
        <h2 class="text-2xl font-bold">Application Request</h2>
       <div class="flex justify-between items-start gap-4">
            <div class="w-full text-center">
                <p class="mb-2">The Employeee requests the application to start and end at the following dates:</p>
                <div class="p-2 bg-gray-300 text-black rounded-lg mb-2"> {{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</div>
            </div>
            <div class="w-full text-center">
                <p class="mb-2">The Application request applied for the number of days to be taken:</p>
                <div class="p-2 bg-gray-300 text-black rounded-lg">Applied days: {{ $leave->days_applied}}</div>
            </div>
       </div>
        <div class="flex justify-between items-start gap-4">
           <div class="w-full">
                <p>Commutations required:</p>
                <div class="flex justify-between items-start gap-4">
                    @if($leave->commutation == 1)
                        <div class="p-2 bg-blue-600 text-white rounded-lg mb-2 w-full text-center">
                            Yes
                        </div>
                    @else
                        <div class="p-1 border-4 border-blue-300 text-blue-600 font-bold rounded-lg mb-2 w-full text-center">
                            Yes
                        </div>
                    @endif
                    @if($leave->commutation == 0)
                        <div class="p-2 bg-blue-600 text-white rounded-lg mb-2 w-full text-center">
                            No
                        </div>
                    @else
                        <div class="p-1 border-4 border-blue-300 text-blue-600 font-bold rounded-lg mb-2 w-full text-center">
                            No
                        </div>
                    @endif
                </div>
           </div>
            <div class="w-full">
                <p>Type of Leave requested and details:</p>
                <div class="p-2 bg-gray-300 text-black rounded-lg mb-2 w-full text-center">{{ $leave->leave_type}}</div>
            </div>
        </div>
        <div>
            <p>Details:</p>
            @php
                $details = $leave->leave_details;
                $decodedDetails = is_string($details) ? json_decode($details, true) : $details;
            @endphp
        
            <div class="p-2 bg-gray-300 text-black rounded-lg mb-2 w-full">
                {{ !empty($decodedDetails) ? (is_array($decodedDetails) ? implode(', ', $decodedDetails) : $decodedDetails) : 'None' }}
            </div>
        </div>        
    </div>
    <div class="bg-white shadow-xl rounded-lg p-6 space-y-6 w-full h-auto">
        <p class="uppercase text-center text-[10px] font-bold mt-4">Total leave balances left here:</p>
        <div class="text-blue-600 rounded-lg text-center font-bold text-4xl">
            {{ 
                $leave->user->vacation_leave_balance + 
                $leave->user->sick_leave_balance + 
                $leave->user->maternity_leave + 
                $leave->user->paternity_leave + 
                $leave->user->solo_parent_leave + 
                $leave->user->study_leave + 
                $leave->user->vawc_leave + 
                $leave->user->rehabilitation_leave + 
                $leave->user->special_leave_benefit + 
                $leave->user->special_emergency_leave 
            }} days
        </div>
        <div class="flex justify-center items-start gap-2">
            <div class="p-2 bg-blue-600 text-white rounded-lg mb-2 w-[25%] text-center">
                Monthly
            </div>
            <div class="p-1 border-4 border-blue-300 text-blue-600 font-bold rounded-lg mb-2 w-[25%] text-center">
                Annually
            </div>
        </div>
       <div class="flex justify-between items-start gap-4 px-4">
                <!-- Canvas for Pie Chart -->
            <canvas id="leaveBalanceChart"></canvas>
        </div>
        <div class="border-2 border-gray"></div>
        <h1 class="text-blue-600 font-bold text-center">Request Verification complete? Proceed to HR!</h1>
        <div class="py-2 px-4">
            <p class="text-sm text-gray-500">The request has been confirmed and will be transfered to the HR for approval. Make sure to look carefully before proceeding and finalize the verification.</p>
        </div>
       <div class="flex justify-center items-center">
        <form action="{{ route('leave.admin-review', $leave->id) }}" method="POST" class="space-y-2">
            @csrf 
            <div class="flex gap-2">
                <!-- Approve Button -->
                <button type="submit" name="admin_status" value="Approved" 
                    class="bg-blue-600 text-white py-2 px-4 rounded-lg mr-3">
                    Proceed to HR
                </button>
        
                <!-- Reject Button -->
                {{-- <button type="button" id="rejectBtn" 
                    class="bg-orange-600 text-white py-2 px-4 rounded-lg">
                    Reject Request
                </button> --}}
            </div>
        
            <!-- Hidden Disapproval Reason Field -->
            {{-- <div id="disapprovalSection" class="mt-3 hidden h-auto">
                <label class="block text-gray-700 font-medium text-xs">Disapproval Reason:</label>
                <textarea name="disapproval_reason" id="disapproval_reason" 
                    class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200"></textarea>
                
                <div class="flex gap-2 mt-2">
                    <button type="submit" name="admin_status" value="Rejected" id="finalRejectBtn"
                        class="bg-red-600 text-white py-2 px-4 rounded-lg">
                        Confirm Rejection
                    </button>
                    
                    <button type="button" id="cancelDisapprovalBtn" class="bg-gray-500 text-white py-2 px-4 rounded-lg">
                        Cancel
                    </button>
                </div>
            </div>             --}}
        </form>
        
        {{-- <script>
            document.getElementById('rejectBtn').addEventListener('click', function() {
                document.getElementById('disapprovalSection').classList.remove('hidden');
            });
        
            document.getElementById('cancelDisapprovalBtn').addEventListener('click', function() {
                document.getElementById('disapprovalSection').classList.add('hidden');
                document.getElementById('disapproval_reason').value = ""; // Clear text area
            });
        </script> --}}
       </div>
    </div>
</div>
@endsection

<style>
.animate-fade-in {
    animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
#leaveBalanceChart {
    max-width: 200px;  /* Reduce size */
    max-height: 200px;
    display: block;
    margin: 0 auto;  /* Center the chart */
}

</style> 

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Get leave balances from PHP
    let leaveBalances = {
        vacation_leave: {{ $leave->user->vacation_leave_balance }},
        sick_leave: {{ $leave->user->sick_leave_balance }},
        maternity_leave: {{ $leave->user->maternity_leave }},
        paternity_leave: {{ $leave->user->paternity_leave }},
        solo_parent_leave: {{ $leave->user->solo_parent_leave }},
        study_leave: {{ $leave->user->study_leave }},
        vawc_leave: {{ $leave->user->vawc_leave }},
        rehabilitation_leave: {{ $leave->user->rehabilitation_leave }},
        special_leave_benefit: {{ $leave->user->special_leave_benefit }},
        special_emergency_leave: {{ $leave->user->special_emergency_leave }}
    };

    let appliedDays = {{ $leave->days_applied }};
    
    // Calculate total balance and percentage
    let totalBalance = Object.values(leaveBalances).reduce((a, b) => a + b, 0);
    let remainingBalance = totalBalance - appliedDays;
    let remainingPercentage = ((remainingBalance / totalBalance) * 100).toFixed(1); // Fixed to 1 decimal place

    // Chart Data
    let data = {
        labels: ["Remaining Balance", "Applied Days"],
        datasets: [{
            data: [remainingBalance, appliedDays],
            backgroundColor: ["#ff3b3b", "#bbbbbb"]
        }]
    };

    // Custom plugin to display percentage in center
    const centerText = {
        id: "centerText",
        beforeDraw(chart) {
            let { width } = chart;
            let { height } = chart;
            let ctx = chart.ctx;
            ctx.restore();

            // Set font properties
            let fontSize = (height / 100).toFixed(2);
            ctx.font = `bold ${fontSize * 12}px Arial`;
            ctx.textBaseline = "middle";
            ctx.textAlign = "center";

            // Display remaining leave percentage in center
            let text = `${remainingPercentage}%`;
            let x = width / 2;
            let y = height / 2.6;

            ctx.fillStyle = "#333"; // Text color
            ctx.fillText(text, x, y);
            ctx.save();
        }
    };

    // Get canvas
    let ctx = document.getElementById("leaveBalanceChart").getContext("2d");

    // Create Doughnut Chart
    new Chart(ctx, {
        type: "doughnut",
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "60%", // Adjust to make space for text
            plugins: {
                legend: {
                    position: "bottom"
                }
            }
        },
        plugins: [centerText] // Register custom plugin
    });
});

</script>

    