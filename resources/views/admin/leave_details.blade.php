@extends('layouts.admin.sidebar-header')

@section('content')

<div class="flex justify-between items-center">
    <a href="{{ route('admin.requests') }}" class="inline-flex items-center text-blue-500 hover:underline transition duration-300 mb-4">
        &larr; Back to Requests
    </a>
    <div class="flex justify-end items-center">
        <a href="{{ route('admin.leave.viewPdf', $leave->id) }}" target="_blank" 
            class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">
            View & Download PDF
        </a>
    </div>
</div>

<div class="hidden md:block md:flex lg:flex justify-between items-start gap-4 h-full">
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
    <div class="bg-white shadow-xl rounded-lg p-6 w-[500px] h-full min-h-[865px] flex flex-col">
       <div class="flex justify-center items-center">
        @if ($leave->user->profile_image)
        @php
            $profileImage = null;

            if ($leave->user->profile_image) {
                $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;

                if (file_exists(public_path($imagePath1))) {
                    $profileImage = asset($imagePath1);
                } elseif (file_exists(public_path($imagePath2))) {
                    $profileImage = asset($imagePath2);
                }
            }
        @endphp

        <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
            class="w-[400px] h-[400px] object-cover"
            alt="{{ $leave->user->name }}">
        @else
            <img src="{{ asset('img/default-avatar.png') }}" 
                alt="" 
                class="w-[400px] h-[400px] object-cover">
        @endif
       </div>

        <p class="font-semibold mt-4 text-gray-500">Employee: {{ $leave->user->first_name}} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name}}</p>
        <p class="font-semibold text-gray-500">Email:{{ $leave->user->email }}</p>
        <p class="mb-4 font-semibold text-gray-500">Position: {{ $leave->user->position}}</p>

        <div class="border-2 border-gray mb-[15px]"></div>

        <h1 class="text-blue-600 font-bold text-center text-xl">Request Verification complete?</h1>
        <h1 class="text-blue-600 font-bold text-center text-xl mb-[15px]">Proceed to HR!</h1>

        <div class="py-2 px-4 flex-grow">
            <p class="text-sm text-gray-500">The request has been successfully reviewed and is now ready for submission to HR for final approval. Please take a moment to carefully verify all details to ensure accuracy and completeness before proceeding. Once submitted, any necessary changes may require additional processing time.</p>
        </div>

        <div class="flex justify-center items-center mt-auto">
            <form action="{{ route('leave.admin-review', $leave->id) }}" method="POST" class="space-y-2 w-full">
                @csrf 
                <div class="flex gap-2">
                    <!-- Approve Button -->
                    <button type="submit" name="admin_status" value="Approved" 
                        class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full">
                        Proceed to HR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile View (only on small devices) -->
<div class="block md:hidden space-y-6 bg-white p-4 rounded-lg shadow animate-fade-in">
    <div class="flex flex-col items-center">
        @if ($leave->user->profile_image)
        @php
            $profileImage = null;

            if ($leave->user->profile_image) {
                $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;

                if (file_exists(public_path($imagePath1))) {
                    $profileImage = asset($imagePath1);
                } elseif (file_exists(public_path($imagePath2))) {
                    $profileImage = asset($imagePath2);
                }
            }
        @endphp

        <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
            class="w-32 h-32 object-cover"
            alt="{{ $leave->user->name }}">
        @else
            <img src="{{ asset('img/default-avatar.png') }}" 
                alt="" 
                class="w-32 h-32 object-cover">
        @endif
        <p class="font-semibold text-gray-500">Employee: {{ $leave->user->first_name }} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name }}</p>
        <p class="text-gray-500 text-sm">Email: {{ $leave->user->email }}</p>
        <p class="text-gray-500 text-sm mb-4">Position: {{ $leave->user->position }}</p>
    </div>

    <div class="space-y-2">
        <h2 class="text-lg font-bold">Leave Balances</h2>
        @php
            $leaveTypes = [
                'Vacation Leave' => $leave->user->vacation_leave_balance,
                'Sick Leave' => $leave->user->sick_leave_balance,
                'Maternity Leave' => $leave->user->maternity_leave,
                'Paternity Leave' => $leave->user->paternity_leave,
                'Solo Parent Leave' => $leave->user->solo_parent_leave,
                'Study Leave' => $leave->user->study_leave,
                'VAWC Leave' => $leave->user->vawc_leave,
                'Rehabilitation Leave' => $leave->user->rehabilitation_leave,
                'Special Leave Benefit' => $leave->user->special_leave_benefit,
                'Special Emergency Leave' => $leave->user->special_emergency_leave,
            ];
        @endphp
        <ul class="grid grid-cols-2 gap-2">
            @foreach($leaveTypes as $type => $balance)
                <li class="bg-blue-600 text-white rounded-lg text-xs text-center p-2">
                    {{ $type }}<br>
                    <span class="block bg-gray-200 text-black rounded mt-1">{{ $balance }} days</span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="space-y-2">
        <h2 class="text-lg font-bold">Application Request</h2>
        <div class="bg-gray-300 text-black rounded p-2">
            {{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }} - 
            {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}
        </div>
        <div class="bg-gray-300 text-black rounded p-2">
            Applied Days: {{ $leave->days_applied }}
        </div>
        <div class="bg-gray-300 text-black rounded p-2">
            Type of Leave: {{ $leave->leave_type }}
        </div>
        <div class="bg-gray-300 text-black rounded p-2">
            Commutation: {{ $leave->commutation ? 'Yes' : 'No' }}
        </div>
    </div>

    @if(in_array($leave->leave_type, ['Sick Leave', 'Maternity Leave', 'Paternity Leave']))
        <div>
            <h3 class="font-semibold">Attached Documents</h3>
            @php $leaveFiles = json_decode($leave->leave_files, true); @endphp
            @if(!empty($leaveFiles))
                <div class="flex gap-2 overflow-x-auto">
                    @foreach($leaveFiles as $file)
                        <img src="{{ asset('storage/' . $file) }}"
                             onclick="openModal('{{ asset('storage/' . $file) }}')"
                             class="w-20 h-20 object-cover rounded border cursor-pointer">
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No images available</p>
            @endif
        </div>
    @endif

    <div>
        <h3 class="font-semibold">Details</h3>
        <textarea readonly class="w-full p-2 rounded border text-sm bg-gray-50">{{ 
            !empty($decodedDetails) ? (is_array($decodedDetails) ? implode(', ', $decodedDetails) : $decodedDetails) : 'None' 
        }}</textarea>
    </div>

    <form action="{{ route('leave.admin-review', $leave->id) }}" method="POST" class="w-full">
        @csrf
        <button type="submit" name="admin_status" value="Approved" 
                class="w-full py-2 bg-blue-600 text-white font-semibold rounded-lg">
            Proceed to HR
        </button>
    </form>
</div>

<!-- Modal (Same across both views) -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-[9999]" onclick="closeModal(event)">
    <div class="bg-white p-4 rounded-lg relative" onclick="event.stopPropagation()">
        <img id="modalImage" src="" class="w-[300px] sm:w-[600px] h-auto object-cover rounded-lg">
    </div>
</div>

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


    