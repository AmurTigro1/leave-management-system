@extends('layouts.hr.sidebar-header')

@section('content')

<div class="flex justify-between items-center">
    <div class="mt-3">
        <a href="{{ route('hr.requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300 py-4 px-4">
            &larr; Back to Requests
        </a>
    </div>
    <div class="mt-3 mr-4">
        <a href="{{ route('hr.overtime.viewPdf', $cto->id) }}" target="_blank" 
            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
            View & Download PDF
    </a>
    </div>
</div>

<div class="flex justify-between items-start gap-4 px-4">
    <!-- Left Side: Employee Request Details -->
    <div class="bg-white shadow-xl rounded-lg p-6 space-y-6 w-[640px] h-auto py-[38px]">
        <div class="w-[430px]">
            <h1 class="uppercase text-xl font-semibold">Employee Request Details</h1> 
            <br>
            <p class="text-gray-500">Date Filed: {{ \Carbon\Carbon::parse($cto->date_filed)->format('F d, Y') }}</p>
        </div>

        <!-- CTO Type Selection -->
        <div class="flex justify-between items-center gap-4">
            @foreach(['none' => 'None', 'halfday_morning' => 'Morning', 'halfday_afternoon' => 'Afternoon', 'wholeday' => 'Whole Day'] as $type => $label)
                <div class="py-2 px-4 rounded-lg border-4 text-sm text-center font-semibold w-[150px] 
                    {{ $cto->cto_type == $type ? 'border-blue-500 bg-gradient-to-r from-blue-100 to-blue-300' : 'border-gray-400 bg-gradient-to-r from-gray-100 to-gray-300' }}">
                    {{ $label }}
                </div>
            @endforeach
        </div>

        <!-- Working Hours -->
        @foreach([
            'Working Hours Applied' => $cto->working_hours_applied, 
            'Earned Hours' => $cto->earned_hours, 
            'COC Balance' => $cto->user->overtime_balance
        ] as $label => $value)
            <p class="font-semibold">{{ $label }}</p>
            <div class="flex justify-start items-center">
                <div class="w-[45%] mr-4">
                    <input type="number" class="border-4 w-full border-gray-400 rounded-lg" placeholder="0" value="{{ $value }}" {{ $label == 'Working Hours Applied' ? 'disabled' : '' }} {{ $label == 'COC Balance' ? 'disabled' : '' }}> 
                </div>
                <div class="w-[55%]">
                    <label>
                        @if($label == 'Working Hours Applied') 
                            The number of working hours requested by the employee. 
                        @elseif($label == 'Earned Hours') 
                            Total accumulated overtime hours over the past months. 
                        @elseif($label == 'COC Balance')
                            The employee's available overtime balance for CTO/COC requests. 
                        @endif
                    </label>
                </div>
            </div>
        @endforeach

        <!-- Buttons -->
        <br>
        <div>
            <form action="{{ route('cto.hr-review', $cto->id) }}" method="POST" class="space-y-2">
                @csrf 
                <div class="flex gap-2">
                    <!-- Approve Button -->
                    <button type="submit" name="hr_status" value="Approved" 
                        class="bg-blue-600 text-white py-2 px-4 rounded-lg mr-3">
                        Process Recommendation
                    </button>
            
                    <!-- Reject Button -->
                    <button type="button" id="rejectBtn" 
                        class="bg-orange-600 text-white py-2 px-4 rounded-lg">
                        Reject Request
                    </button>
                </div>
            
                <!-- Hidden Disapproval Reason Field -->
                <div id="disapprovalSection" class="hidden h-auto">
                    <label class="block text-gray-700 font-medium text-xs">Disapproval Reason:</label>
                    <textarea name="disapproval_reason" id="disapproval_reason" 
                        class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200"></textarea>
                    
                    <div class="flex gap-2 mt-2">
                        <button type="submit" name="hr_status" value="Rejected" id="finalRejectBtn"
                            class="bg-red-600 text-white py-2 px-4 rounded-lg">
                            Confirm Rejection
                        </button>
                        
                        <button type="button" id="cancelDisapprovalBtn" class="bg-gray-500 text-white py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </div>            
            </form>
            <script>
                document.getElementById('rejectBtn').addEventListener('click', function() {
                    document.getElementById('disapprovalSection').classList.remove('hidden');
                });
            
                document.getElementById('cancelDisapprovalBtn').addEventListener('click', function() {
                    document.getElementById('disapprovalSection').classList.add('hidden');
                    document.getElementById('disapproval_reason').value = ""; // Clear text area
                });
            </script>
        </div>
    </div>

    <!-- Right Side: Processing Steps and Chart -->
    <div class="bg-white shadow-xl rounded-lg p-6 space-y-6 w-full h-auto">
        <p class="text-gray-500">Inclusive Dates: {{ \Carbon\Carbon::parse($cto->inclusive_date_start)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($cto->inclusive_date_end)->format('F d, Y') }}</p>

        <h1 class="text-center font-bold text-2xl bg-gradient-to-r from-blue-500 to-blue-800 bg-clip-text text-transparent">
            Steps for Processing the Request
        </h1>

        <div class="flex justify-between items-center gap-2">
            <!-- Graph -->
            <div class="w-[40%] h-[500px] flex items-center mr-3 mt-4">
                <canvas id="myChart"></canvas>
            </div>

            <!-- Steps -->
            <style>
                .status-box {
                    margin-top: 8px;
                    padding: 8px 24px;
                    border-radius: 8px;
                    border-width: 4px;
                    text-align: center;
                    font-weight: 600;
                    font-size: 14px;
                }
            
                .status-title {
                    text-transform: uppercase;
                    font-size: 15px;
                }
            
                .status-text {
                    font-size: 24px;
                    font-weight: bold;
                    text-transform: capitalize;
                    display: inline-block;
                    background-clip: text;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }
            
                /* Define gradient backgrounds for different statuses */
                .yellow-box { background: linear-gradient(to right, #FEF9C3, #FACC15); border-color: #FACC15; }
                .gray-box { background: linear-gradient(to right, #E5E7EB, #9CA3AF); border-color: #9CA3AF; }
            
                /* Define gradient text colors */
                .yellow-text { background-image: linear-gradient(to right, #d8af0a, #8d6102); }
                .gray-text { background-image: linear-gradient(to right, #7e848f, #4a4f58); }
                
                .blue-text { background-image: linear-gradient(to right, #0a78d8, #024c8d); }
                .blue-box { background: linear-gradient(to right, #BFDBFE, #3B82F6); border-color: #3B82F6; }
            </style>
            
            <div style="width: 60%;">
                @foreach([
                    'Admin Status' => ['status' => $cto->admin_status, 'color' => 'blue', 'description' => 'The Admin reviews the request before sending it to HR.'],
                    'HR Status' => ['status' => $cto->hr_status, 'color' => 'yellow', 'description' => 'HR verifies the request and forwards it to the Supervisor.'],
                    'Supervisor Status' => ['status' => $cto->supervisor_status, 'color' => 'gray', 'description' => 'The Supervisor conducts a final review before approval.']
                ] as $step => $details)
                    <div class="status-box {{ $details['color'] }}-box">
                        <p class="status-title">{{ $step }}</p>
                        <br>
                        <h1 class="text-2xl">
                            <span class="status-text {{ $details['color'] }}-text">
                                {{ $details['status'] }}
                            </span>
                        </h1>
                        <br>
                        <p class="text-sm">{{ $details['description'] }}</p>
                    </div>
                @endforeach
            </div>               
        </div>
    </div>
</div>

@endsection

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart').getContext('2d');

    // Data for the chart
    const labels = ["Working Hours", "Earned Hours", "Overtime Balance"];
    const data = [
        {{ $cto->working_hours_applied }},
        {{ $cto->earned_hours }},
        {{ $cto->user->overtime_balance }}
    ];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Hours Overview',
                data: data,
                backgroundColor: ['#3b82f6', '#f97316', '#10b981'],
                borderColor: ['#1e40af', '#c2410c', '#065f46'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<!-- Animation -->
<style>
.animate-fade-in {
    animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
