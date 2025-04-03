@extends('layouts.admin.sidebar-header')

@section('content')
<div class="container mx-auto px-4 py-6 animate-fade-in">
    <!-- Back Button -->
    <a href="{{ route('admin.requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300 mb-4">
        &larr; Back to Requests
    </a>

    <div class="flex flex-col lg:flex-row justify-between items-stretch gap-4">
        <!-- Left Side: Employee Request Details -->
        <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 space-y-6 w-full lg:w-3/5 flex flex-col">
            <div class="w-full">
                <h1 class="uppercase text-lg md:text-xl font-semibold">Employee Request Details</h1> 
                <p class="text-gray-500 mt-2">Date Filed: {{ \Carbon\Carbon::parse($cto->date_filed)->format('F d, Y') }}</p>
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

            <!-- Bar Chart -->
            <div class="w-full h-full">
                <canvas id="barChart"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            document.addEventListener("DOMContentLoaded", function () {
                const ctx = document.getElementById('barChart').getContext('2d');

                const labels = ["Working Hours Applied", "Earned Hours", "COC Balance"];
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
                            backgroundColor: ['#3b82f6', '#f97316', '#10b981'], // Blue, Orange, Green
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
                                display: false
                            }
                        }
                    }
                });
            });
            </script>
        </div>

        <!-- Right Side: Processing Steps -->
        <div class="bg-white shadow-xl rounded-lg p-6 w-full lg:w-2/5 h-full min-h-[865px] flex flex-col">
            <div class="flex justify-center items-center">
                <img src="{{ $cto->user->profile_image ? asset('storage/profile_images/' . $cto->user->profile_image) : asset('img/default-avatar.png') }}" 
                     class="w-[400px] h-[400px] object-cover" alt="{{ $cto->user->name }}">
            </div>

            <p class="font-semibold mt-4 text-gray-500">Employee: {{ $cto->user->first_name}} {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name}}</p>
            <p class="font-semibold text-gray-500">Email: {{ $cto->user->email }}</p>
            <p class="mb-4 font-semibold text-gray-500">Position: {{ $cto->user->position }}</p>

            <div class="border-2 border-gray mb-[15px]"></div>

            <h1 class="text-blue-600 font-bold text-center text-xl">Request Verification complete?</h1>
            <h1 class="text-blue-600 font-bold text-center text-xl mb-[15px]">Proceed to HR!</h1>

            <div class="py-2 px-4 flex-grow">
                <p class="text-sm text-gray-500">The request has been successfully reviewed and is now ready for submission to HR for final approval. Please take a moment to carefully verify all details to ensure accuracy and completeness before proceeding. Once submitted, any necessary changes may require additional processing time.</p>
            </div>

            <div class="flex justify-center items-center mt-auto">
                <form action="{{ route('cto.admin-review', $cto->id) }}" method="POST" class="space-y-2 w-full">
                    @csrf 
                    <div class="flex gap-2">
                        <!-- Approve Button -->
                        <button type="submit" name="admin_status" value="Ready for Review" 
                            class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full">
                            Proceed to HR
                        </button>
                    </div>
                </form>
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

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .flex-col.lg\:flex-row {
            flex-direction: column;
        }
        .w-full.lg\:w-3\/5, .w-full.lg\:w-2\/5 {
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .status-box {
            padding: 8px;
        }
    }
</style>

@endsection
