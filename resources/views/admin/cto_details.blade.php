@extends('layouts.admin.sidebar-header')

@section('content')
    <div class="container mx-auto px-4 py-6 animate-fade-in">
        <!-- Back Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.requests') }}"
                class="inline-flex items-center text-blue-500 hover:underline transition duration-300 mb-4">
                &larr; Back to Requests
            </a>
            <div class="flex justify-end items-center">
                <a href="{{ route('admin.cto.viewPdf', $cto->id) }}" target="_blank"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
                    View & Download PDF
                </a>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row justify-between items-stretch gap-4">
            <!-- Left Side: Employee Request Details -->
            <div class="bg-white shadow-xl rounded-lg p-4 md:p-6 space-y-6 w-full lg:w-3/5 flex flex-col">
                <div class="w-full">
                    <h1 class="uppercase text-lg md:text-xl font-semibold">Employee Request Details</h1>
                    <p class="text-gray-500 mt-2">Date Filed:
                        {{ \Carbon\Carbon::parse($cto->date_filed)->format('F d, Y') }}</p>
                </div>

                <!-- Working Hours - Stacked on Mobile -->
                <div class="flex-grow space-y-4">
                    @foreach ([
            'Working Hours Applied' => $cto->working_hours_applied,
            'Earned Hours' => $cto->earned_hours,
            'COC Balance' => $cto->user->overtime_balance,
        ] as $label => $value)
                        <div class="space-y-2">
                            <p class="font-semibold text-sm md:text-base">{{ $label }}</p>
                            <div class="flex flex-col sm:flex-row justify-start items-start sm:items-center gap-2 md:gap-4">
                                <div class="w-full sm:w-[45%]">
                                    <input type="number" class="border-4 w-full border-gray-400 rounded-lg p-2"
                                        placeholder="0" value="{{ $value }}" disabled>
                                </div>
                                <div class="w-full sm:w-[55%] text-xs md:text-sm text-gray-600">
                                    @if ($label == 'Working Hours Applied')
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
                    document.addEventListener("DOMContentLoaded", function() {
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
            <div class="bg-white shadow-xl rounded-lg p-6 w-[500px] h-auto min-h-[865px] flex flex-col">
                <div class="flex justify-center items-center">
                    @if ($cto->user->profile_image)
                        @php
                            $profileImage = null;

                            if ($cto->user->profile_image) {
                                $imagePath1 = 'storage/profile_images/' . $cto->user->profile_image;
                                $imagePath2 = 'storage/profile_pictures/' . $cto->user->profile_image;

                                if (file_exists(public_path($imagePath1))) {
                                    $profileImage = asset($imagePath1);
                                } elseif (file_exists(public_path($imagePath2))) {
                                    $profileImage = asset($imagePath2);
                                }
                            }
                        @endphp

                        <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}"
                            class="w-[400px] h-[400px] object-cover" alt="{{ $cto->user->name }}">
                    @else
                        <img src="{{ asset('img/default-avatar.png') }}" alt=""
                            class="w-[400px] h-[400px] object-cover">
                    @endif
                </div>

                <p class="font-semibold mt-4 text-gray-500">Employee: {{ $cto->user->first_name }}
                    {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name }}</p>
                <p class="font-semibold text-gray-500">Email: {{ $cto->user->email }}</p>
                <p class="mb-4 font-semibold text-gray-500">Position: {{ $cto->user->position }}</p>

                <div class="border-2 border-gray mb-[15px]"></div>

                <h1 class="text-blue-600 font-bold text-center text-xl">Request Verification complete?</h1>
                <h1 class="text-blue-600 font-bold text-center text-xl mb-[15px]">Proceed to HR!</h1>

                <div class="py-2 px-4 flex-grow">
                    <p class="text-sm text-gray-500">The request has been successfully reviewed and is now ready for
                        submission to HR for final approval. Please take a moment to carefully verify all details to ensure
                        accuracy and completeness before proceeding. Once submitted, any necessary changes may require
                        additional processing time.</p>
                </div>

            <div class="border-2 border-gray mb-[15px]"></div>

            <h1 class="text-blue-600 font-bold text-center text-xl">Request Verification complete?</h1>
            <h1 class="text-blue-600 font-bold text-center text-xl mb-[15px]">Proceed to HR!</h1>

            <div class="py-2 px-4 flex-grow">
                <p class="text-sm text-gray-500">The request has been successfully reviewed and is now ready for submission to HR for final approval. Please take a moment to carefully verify all details to ensure accuracy and completeness before proceeding. Once submitted, any necessary changes may require additional processing time.</p>
            </div>

            <div class="flex justify-center items-center space-y-2 w-full">
                <form action="{{ route('cto.admin-review', $cto->id) }}" method="POST" class="space-y-2 w-auto flex flex-col items-center justify-center">
                    @csrf 
                    <div class="flex gap-2">
                        <!-- Approve Button -->
                        <button type="submit" name="admin_status" value="Ready for Review" 
                            class="bg-blue-600 text-white py-2 px-4 rounded-lg mr-3">
                            Proceed to HR
                        </button>

                        <button type="button" id="rejectBtn" 
                            class="bg-red-600 text-white py-2 px-4 rounded-lg">
                            Return Request
                        </button>
                    </div>

                        <!-- Hidden Disapproval Reason Field -->
                        <div id="disapprovalSection" class="mt-3 hidden h-auto w-full">
                            <label class="block text-gray-700 font-medium text-xs">Disapproval Reason:</label>
                            <textarea name="disapproval_reason" id="disapproval_reason"
                                class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200"></textarea>

                            <div class="flex gap-2 mt-2">
                                <button type="submit" name="admin_status" value="rejected" id="finalRejectBtn"
                                    class="bg-red-600 text-white py-2 px-4 rounded-lg">
                                    Confirm Return
                                </button>

                                <button type="button" id="cancelDisapprovalBtn"
                                    class="bg-gray-500 text-white py-2 px-4 rounded-lg">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    document.getElementById('rejectBtn').addEventListener('click', function() {
                        console.log('Reject button clicked');
                        document.getElementById('disapprovalSection').classList.remove('hidden');
                        document.getElementById('approvalSection').classList.add('hidden');
                    });

                    document.getElementById('cancelDisapprovalBtn').addEventListener('click', function() {
                        document.getElementById('disapprovalSection').classList.add('hidden');
                        document.getElementById('disapproval_reason').value = "";
                    });
                </script>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .flex-col.lg\:flex-row {
                flex-direction: column;
            }

            .w-full.lg\:w-3\/5,
            .w-full.lg\:w-2\/5 {
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
