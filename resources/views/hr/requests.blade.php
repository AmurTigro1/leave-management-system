@extends('layouts.hr.sidebar-header')

@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="flex justify-end items-center">
    <button id="openModalBtn" class="text-blue-600 font-bold py-2 px-4 rounded-lg flex">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
          </svg>
          <p>Upload Info</p>
    </button>
</div>

<div id="leaveModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center z-[9999]">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-xl p-6 relative">
        <!-- Close Button -->
        <button id="closeModalBtn" class="absolute top-3 right-4 text-gray-600 hover:text-gray-900 text-2xl">&times;</button>

        <h2 class="text-xl font-bold text-center mb-4">HR/Supervisor Information</h2>

        @foreach ($officials as $official)
            <form action="{{ route('hr-supervisor-info.update', $official->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <p class="text-sm border-yellow-600 border-l-4 bg-yellow-100 text-yellow-900 py-2 px-4 rounded-lg mb-2">More than 10 days leave</p>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Supervisor Name</label>
                    <input type="text" name="supervisor_name" class="w-full border p-2 rounded-lg"
                        value="{{ $official->supervisor_name }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">HR Name</label>
                    <input type="text" name="hr_name" class="w-full border p-2 rounded-lg"
                        value="{{ $official->hr_name }}" required>
                </div>

                {{-- <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Upload Supervisor Signature</label>
                    <input type="file" name="supervisor_signature" class="w-full border p-2 rounded-lg" accept="image/*,.pdf">
                    @if($official->supervisor_signature)
                        <p class="mt-4">Current File: 
                            <a class="bg-blue-600 text-white rounded-lg py-2 px-4" href="{{ asset('storage/'.$official->supervisor_signature) }}" target="_blank">
                                View
                            </a>
                        </p>
                    @endif
                </div>

                <div class="mb-[25px]">
                    <label class="block text-gray-700 font-bold mb-2">Upload HR Signature</label>
                    <input type="file" name="hr_signature" class="w-full border p-2 rounded-lg" accept="image/*,.pdf">
                    @if($official->hr_signature)
                        <p class="mt-4">Current File: 
                            <a class="bg-blue-600 text-white rounded-lg py-2 px-4" href="{{ asset('storage/'.$official->hr_signature) }}" target="_blank">
                                View
                            </a>
                        </p>
                    @endif
                </div> --}}

                <div class="flex justify-center gap-2">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg">Update</button>
                    <button type="button" id="closeModalBtn2" class="bg-gray-600 text-white py-2 px-4 rounded-lg">Cancel</button>
                </div>
            </form>
        @endforeach       
    </div>
</div>

<!-- JavaScript for Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
    var modal = document.getElementById("leaveModal");
    var openModalBtns = document.querySelectorAll("#openModalBtn");
    var closeModalBtns = document.querySelectorAll("#closeModalBtn, #closeModalBtn2");

    // Open Modal
    openModalBtns.forEach(btn => {
        btn.addEventListener("click", function () {
            modal.classList.remove("hidden");
        });
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
<div class="w-full bg-white rounded animate-fade-in p-4 lg:p-6 flex flex-col lg:flex-row gap-6">

    <!-- Leave Applications Section -->
    <div class="shadow-lg rounded-lg py-4 px-4 w-full">
        <h2 class="text-xl lg:text-2xl font-bold text-gray-700 mb-4 lg:mb-6 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Review Leave Applications
        </h2>

        @if ($leaveApplications->isEmpty())
            <p class="text-gray-600">No leave applications available.</p>
        @else
            @php $leaveFound = false; @endphp
            <div class="space-y-4">
                @foreach ($leaveApplications as $leave)
                    @if (($leave->status == 'pending' && $leave->admin_status == 'approved') || 
                        ($leave->status != 'approved' || $leave->admin_status != 'approved') && 
                        $leave->hr_status != 'rejected')
                        @php $leaveFound = true; @endphp
                        <div class="bg-white shadow-md rounded-lg p-4 lg:p-6 transition-all hover:shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Status Section -->
                                <div class="flex items-center gap-2">
                                    <p class="text-sm lg:text-base font-bold text-gray-700">Status</p>
                                    <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">{{ $leave->status }}</span>
                                </div>
                            </div>
                            
                            <!-- User Info Section -->
                            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                                <!-- Profile Image -->
                                <div class="flex-shrink-0">
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
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0" 
                                        alt="{{ $leave->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                    @endif
                                </div>
                                
                                <!-- User Details -->
                                <div class="flex-grow">
                                    <h3 class="text-sm lg:text-base font-semibold text-gray-900 uppercase">
                                        {{ $leave->user->first_name }} {{ strtoupper(substr($leave->user->middle_name, 0, 1)) }}. {{ $leave->user->last_name }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-gray-600 text-sm">Leave Type: {{ $leave->leave_type }}</p>
                                    </div>
                                    <p class="text-gray-600 text-sm">Duration: <span class="font-semibold">{{$leave->days_applied}} days</span></p>
                                </div>
                                <a href="{{ route('hr.leave_details', ['id' => $leave->id]) }}" class="text-blue-600 text-sm lg:text-base sm:self-end">View Request</a>
                            </div>
                            
                            @if($leave->leave_type == 'Mandatory Leave' && $leave->supervisor_status == 'rejected')
                                <div class="mt-3 p-3 bg-red-50 rounded-lg">
                                    <p class="font-bold text-sm lg:text-base">Supervisor Status: <span class="text-red-500 capitalize">{{ $leave->supervisor_status}}</span></p>
                                    <p class="text-gray-600 text-sm">Reason: <span class="text-red-500">{{ $leave->disapproval_reason}}</span></p>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            @if (!$leaveFound)
                <p class="text-gray-600">No leave applications available.</p>
            @endif
        @endif
    </div>

    <!-- CTO Applications Section -->
    <div class="shadow-lg rounded-lg py-4 px-4 w-full">
        <h2 class="text-xl lg:text-2xl font-bold text-gray-700 mb-4 lg:mb-6 flex items-center gap-2">
            <i class="lucide lucide-file-text"></i> Review CTO Applications
        </h2>

        @if ($ctoApplications->isEmpty())
            <p class="text-gray-600">No CTO applications available.</p>
        @else
            @php $ctoFound = false; @endphp
            <div class="space-y-4">
                @foreach ($ctoApplications as $cto)
                    @if (($cto->status == 'pending' && $cto->admin_status == 'Ready for Review') || 
                        ($cto->status != 'approved' || $cto->admin_status != 'Ready for Review') && 
                        $cto->hr_status != 'rejected')
                        @php $ctoFound = true; @endphp
                        <div class="bg-white shadow-md rounded-lg p-4 lg:p-6 transition-all hover:shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <!-- Status Section -->
                                <div class="flex items-center gap-2">
                                    <p class="text-sm lg:text-base font-bold text-gray-700">Status</p>
                                    <span class="bg-yellow-500 text-white py-1 px-3 rounded-full text-sm">{{ $cto->status }}</span>
                                </div>
                            </div>
                            
                            <!-- User Info Section -->
                            <div class="mt-4 flex flex-col sm:flex-row gap-4">
                                <!-- Profile Image -->
                                <div class="flex-shrink-0">
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
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0" 
                                        alt="{{ $cto->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                    @endif
                                </div>
                                
                                <!-- User Details -->
                                <div class="flex-grow">
                                    <h3 class="text-sm lg:text-base font-semibold text-gray-900 uppercase">
                                        {{ $cto->user->first_name }} {{ strtoupper(substr($cto->user->middle_name, 0, 1)) }}. {{ $cto->user->last_name }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-gray-600 text-sm">Hours Applied: {{ $cto->working_hours_applied }} hours</p>
                                    </div>
                                    <p class="text-gray-600 text-sm">Duration: <span class="font-semibold">{{ round(\Carbon\Carbon::parse($cto->inclusive_date_start)->diffInDays(\Carbon\Carbon::parse($cto->inclusive_date_end))) + 1 }} days</span></p>
                                </div>
                                <a href="{{ route('hr.cto_details', ['id' => $cto->id]) }}" class="text-blue-600 text-sm lg:text-base sm:self-end">View Request</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @if (!$ctoFound)
                <p class="text-gray-600">No CTO applications available.</p>
            @endif
        @endif
    </div>
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
<script>
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        console.error("AJAX Error:", jqxhr.responseText);
    });
</script>
@endsection
@notifyCss