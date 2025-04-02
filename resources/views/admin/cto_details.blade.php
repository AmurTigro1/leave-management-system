@extends('layouts.admin.sidebar-header')

@section('content')
<div class="container mx-auto px-4 py-6 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300 mb-4">
        &larr; Back to Requests
    </a>

    <div class="flex flex-col lg:flex-row justify-between items-stretch gap-6">
        <!-- Left Side: Employee Request Details -->
        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 space-y-6 w-full lg:w-1/2 flex flex-col">
            <div class="w-full">
                <h1 class="uppercase text-lg md:text-xl font-semibold">Employee Request Details</h1> 
                <p class="text-gray-500 mt-2">Date Filed: {{ \Carbon\Carbon::parse($cto->date_filed)->format('F d, Y') }}</p>
            </div>

            <!-- CTO Type Selection - Responsive Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 md:gap-4">
                @foreach(['none' => 'None', 'halfday_morning' => 'Morning', 'halfday_afternoon' => 'Afternoon', 'wholeday' => 'Whole Day'] as $type => $label)
                    <div class="py-2 px-2 md:px-4 rounded-lg border-4 text-xs sm:text-sm text-center font-semibold w-full 
                        {{ $cto->cto_type == $type ? 'border-blue-500 bg-gradient-to-r from-blue-100 to-blue-300' : 'border-gray-400 bg-gradient-to-r from-gray-100 to-gray-300' }}">
                        {{ $label }}
                    </div>
                @endforeach
            </div>

            <!-- Working Hours - Stacked on Mobile -->
            <div class="flex-grow space-y-4">
                @foreach([
                    'Working Hours Applied' => $cto->working_hours_applied, 
                    'Earned Hours' => $cto->earned_hours, 
                    'COC Balance' => $cto->user->overtime_balance
                ] as $label => $value)
                    <div class="space-y-2">
                        <p class="font-semibold text-sm md:text-base">{{ $label }}</p>
                        <div class="flex flex-col sm:flex-row justify-start items-start sm:items-center gap-2 md:gap-4">
                            <div class="w-full sm:w-[45%]">
                                <input type="number" class="border-4 w-full border-gray-400 rounded-lg p-2" 
                                       placeholder="0" value="{{ $value }}" disabled> 
                            </div>
                            <div class="w-full sm:w-[55%] text-xs md:text-sm text-gray-600">
                                @if($label == 'Working Hours Applied') 
                                    The number of working hours requested by the employee. 
                                @elseif($label == 'Earned Hours') 
                                    Total accumulated overtime hours over the past months. 
                                @else 
                                    The employee's available COC balance for CTO requests. 
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Buttons - Centered on Mobile -->
            <div class="pt-4 mt-auto">
                <form action="{{ route('cto.admin-review', $cto->id) }}" method="POST">
                    @csrf 
                    <button type="submit" name="admin_status" value="Ready for Review" 
                        class="w-full sm:w-auto bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                        Proceed to HR
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Processing Steps -->
        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 space-y-6 w-full lg:w-1/2 flex flex-col">
            <div>
                <p class="text-gray-500 text-sm md:text-base">
                    Inclusive Dates: {{ \Carbon\Carbon::parse($cto->inclusive_date_start)->format('F d, Y') }} - 
                    {{ \Carbon\Carbon::parse($cto->inclusive_date_end)->format('F d, Y') }}
                </p>

                <h1 class="text-center font-bold text-xl md:text-2xl bg-gradient-to-r from-blue-500 to-blue-800 bg-clip-text text-transparent mt-4">
                    Steps for Processing the Request
                </h1>
            </div>

            <div class="flex-grow flex flex-col lg:flex-row justify-between items-stretch gap-4">
                <!-- Chart - Full width on mobile, 40% on desktop -->
                <div class="w-full lg:w-[40%] h-[250px] lg:h-auto flex items-center justify-center">
                    <canvas id="myChart" class="max-h-full w-full"></canvas>
                </div>

                <!-- Status Boxes - Full width on mobile, 60% on desktop -->
                <div class="w-full lg:w-[60%] space-y-4 flex flex-col justify-center">
                    @foreach([
                        'Admin Status' => ['status' => $cto->admin_status, 'color' => 'yellow', 'description' => 'The Admin reviews the request before sending it to HR.'],
                        'HR Status' => ['status' => $cto->hr_status, 'color' => 'gray', 'description' => 'HR verifies the request and forwards it to the Supervisor.'],
                        'Supervisor Status' => ['status' => $cto->supervisor_status, 'color' => 'gray', 'description' => 'The Supervisor conducts a final review before approval.']
                    ] as $step => $details)
                        <div class="status-box {{ $details['color'] }}-box flex-grow">
                            <p class="status-title">{{ $step }}</p>
                            <h1 class="text-xl md:text-2xl mt-2 mb-2">
                                <span class="status-text {{ $details['color'] }}-text">
                                    {{ $details['status'] }}
                                </span>
                            </h1>
                            <p class="text-xs md:text-sm">{{ $details['description'] }}</p>
                        </div>
                    @endforeach
                </div>                       
            </div>
        </div>
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .status-box {
        margin-top: 8px;
        padding: 12px;
        border-radius: 8px;
        border-width: 4px;
        text-align: center;
        font-weight: 600;
        display: flex;
        flex-direction: column;
    }
    
    .status-title {
        text-transform: uppercase;
        color: #303337;
        font-size: 14px;
    }
    
    .status-text {
        font-size: 20px;
        font-weight: bold;
        text-transform: capitalize;
        display: inline-block;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .yellow-box { 
        background: linear-gradient(to right, #FEF9C3, #FACC15); 
        border-color: #FACC15; 
    }
    
    .gray-box { 
        background: linear-gradient(to right, #E5E7EB, #9CA3AF); 
        border-color: #9CA3AF; 
    }
    
    .yellow-text { 
        background-image: linear-gradient(to right, #d8af0a, #8d6102); 
    }
    
    .gray-text { 
        background-image: linear-gradient(to right, #7e848f, #4a4f58); 
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .flex-col.lg\:flex-row {
            flex-direction: column;
        }
        .w-full.lg\:w-1\/2 {
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .status-box {
            padding: 8px;
        }
        .status-title {
            font-size: 12px;
        }
        .status-text {
            font-size: 18px;
        }
    }
</style>

@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('myChart').getContext('2d');

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
                y: { 
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script>