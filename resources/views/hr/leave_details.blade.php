@extends('layouts.hr.sidebar-header')

@section('content')

<div class="flex justify-between items-center m-4">
    <a href="{{ route('hr.requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300">
        &larr; Back to Requests
    </a>
    <div class="flex justify-between items-center">
        <button id="openModalBtn" class="text-blue-600 font-bold py-2 px-4 rounded-lg flex">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
              </svg>
              <p>Upload Info</p>
        </button>
        <a href="{{ route('hr.leave.viewPdf', $leave->id) }}" target="_blank" 
            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
            View & Download PDF
        </a>
    </div>
    
</div>

<div id="leaveModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-xl p-6 relative">
        <!-- Close Button -->
        <button id="closeModalBtn" class="absolute top-3 right-4 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>

        <h2 class="text-xl font-bold text-center mb-4">HR/Supervisor Information</h2>

        <form action="{{ route('hr-supervisor-info.update', $official->id ?? '') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Supervisor Name</label>
                <input type="text" name="supervisor_name" class="w-full border p-2 rounded-lg"
                    value="{{ $official->supervisor_name ?? '' }}" required>
            </div>
        
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">HR Name</label>
                <input type="text" name="hr_name" class="w-full border p-2 rounded-lg"
                    value="{{ $official->hr_name ?? '' }}" required>
            </div>
        
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Upload Supervisor Signature</label>
                <input type="file" name="supervisor_signature" class="w-full border p-2 rounded-lg" accept="image/*,.pdf">
                @if($official && $official->supervisor_signature)
                    <p class="mt-4">Current File: <a class="bg-blue-600 text-white rounded-lg py-2 px-4" href="{{ asset('storage/'.$official->supervisor_signature) }}" target="_blank">View</a></p>
                @endif
            </div>
        
            <div class="mb-[25px]">
                <label class="block text-gray-700 font-bold mb-2">Upload HR Signature</label>
                <input type="file" name="hr_signature" class="w-full border p-2 rounded-lg" accept="image/*,.pdf">
                @if($official && $official->hr_signature)
                    <p class="mt-4">Current File: <a class="bg-blue-600 text-white rounded-lg py-2 px-4" href="{{ asset('storage/'.$official->hr_signature) }}" target="_blank">View</a></p>
                @endif
            </div>
        
            <div class="flex justify-center gap-2">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg">Update</button>
                <button type="button" id="closeModalBtn2" class="bg-gray-600 text-white py-2 px-4 rounded-lg">Cancel</button>
            </div>
        </form>                  
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById("leaveModal");
        var openModalBtn = document.getElementById("openModalBtn");
        var closeModalBtns = document.querySelectorAll("#closeModalBtn, #closeModalBtn2");

        // Open Modal
        openModalBtn.addEventListener("click", function () {
            modal.classList.remove("hidden");
        });

        // Close Modal
        closeModalBtns.forEach(btn => {
            btn.addEventListener("click", function () {
                modal.classList.add("hidden");
            });
        });

        // Close modal if user clicks outside the modal content
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.classList.add("hidden");
            }
        });
    });
</script>

<div class="flex justify-between items-start gap-4 h-full">
    <!-- Right side -->
    <div class="bg-white shadow-xl rounded-lg p-6 space-y-6 w-[60%] min-h-[865px] h-full">
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
        @if(in_array($leave->leave_type, ['Sick Leave', 'Maternity Leave', 'Paternity Leave']))
            <div>
                <p>Attached Documents:</p>
                @php
                    $leaveFiles = json_decode($leave->leave_files, true); // Decode JSON to array
                @endphp
            
                @if(!empty($leaveFiles))
                    <ul class="flex gap-2 flex-wrap">
                        @foreach($leaveFiles as $file)
                            <li>
                                <button 
                                    class="w-[50px] h-auto border rounded-lg overflow-hidden hover:opacity-80"
                                    onclick="openModal('{{ asset('storage/' . $file) }}')">
                                    <img src="{{ asset('storage/' . $file) }}" class="w-full h-full object-cover" alt="Preview">
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p>No Image Available</p>
                @endif
            </div>
        @else
            {{ Null }}
        @endif
        <div>
            <p>Details:</p>
            @php
                $details = $leave->leave_details;
                $decodedDetails = is_string($details) ? json_decode($details, true) : $details;
            @endphp
        
        <textarea class="p-2 border text-black rounded-lg mb-2 w-full h-[100px] resize-none overflow-auto" 
        readonly>{{ !empty($decodedDetails) ? (is_array($decodedDetails) ? implode(', ', $decodedDetails) : $decodedDetails) : 'None' }}</textarea>
        </div>        
    </div>
        <!-- Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-[9999]" onclick="closeModal(event)">
            <div class="bg-white p-4 rounded-lg relative" onclick="event.stopPropagation()">
                <img id="modalImage" src="" class="w-[600px] h-auto object-cover rounded-lg">
            </div>
        </div>

        <!-- JavaScript for Modal -->
        <script>
            function openModal(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            function closeModal(event) {
                if (event.target.id === 'imageModal') {
                    document.getElementById('imageModal').classList.add('hidden');
                }
            }
        </script>

    <!-- Left side -->
    <div class="bg-white shadow-xl rounded-lg p-6 w-[500px] h-auto min-h-[865px] flex flex-col">
       <div class="flex justify-center items-center">
            <img src="{{ $leave->user->profile_image ? asset('storage/profile_images/' . $leave->user->profile_image) : asset('img/default-avatar.png') }}" 
            class="w-[400px] h-[400px] object-cover" alt="{{ $leave->user->name }}">
       </div>

        <p class="font-semibold mt-4">Employee: {{ $leave->user->first_name}} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name}}</p>
        <p class="font-semibold">Email: <span class="text-blue-600">{{ $leave->user->email }}</span></p>
        <p class="mb-4 font-semibold">Position: {{ $leave->user->position}}</p>

        <div class="border-2 border-gray mb-[15px]"></div>

        <h1 class="text-blue-600 font-bold text-center text-xl">Request Verification complete? </h1>
        <h1 class="text-blue-600 font-bold text-center text-xl mb-[15px]">Process your Recommendation!</h1>

        <div class="py-2 px-4 flex-grow">
            <p class="text-sm text-gray-500">The request has been successfully reviewed and is now ready for submission for final approval. Please take a moment to carefully verify all details to ensure accuracy and completeness before proceeding. Once submitted, any necessary changes may require additional processing time.</p>
        </div>

        <div class="flex justify-center items-center mt-auto">
            
        <form action="{{ route('leave.review', $leave->id) }}" method="POST" class="space-y-2">
            @csrf 
            <div class="flex gap-2">
                <!-- Approve Button -->
                <button type="submit" name="status" value="Approved" 
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
            <div id="disapprovalSection" class="mt-3 hidden h-auto">
                <label class="block text-gray-700 font-medium text-xs">Disapproval Reason:</label>
                <textarea name="disapproval_reason" id="disapproval_reason" 
                    class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200"></textarea>
                
                <div class="flex gap-2 mt-2">
                    <button type="submit" name="status" value="Rejected" id="finalRejectBtn"
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
</style> 