@extends('layouts.sidebar-header')
    
@section('content')
<div class="max-w-6xl mx-auto p-6rounded-lg mt-4">
    <!-- Back Button with Animation -->
    <a href="{{ route('employee.leave_request') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center mb-4 transition-transform duration-300 hover:translate-x-2">
        &larr; Back to Leave Requests
    </a>
    <div class="text-center">
        <div class="justify-center flex">
            <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[80px] w-[80px] mx-auto lg:mx-0 mb-4">
        </div>
        <div>
            <p>Republic of the Philippines</p>
            <h1 class="font-bold">DEPARTMENT OF THE INTERIOR AND LOCAL GOVERNMENT</h1>
            <h1>Rajah Sikatuna Avenue, Dampas, City of Tagbilaran, Bohol</h1>
        </div>
    </div>
    <h2 class="text-2xl font-bold mb-4 text-center mt-4">Edit Leave Request Form</h2>

    <!-- Success Message -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-green-700 bg-green-100 border border-green-500 rounded">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-red-700 bg-red-100 border border-red-500 rounded">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
        <p class="font-bold">Having trouble editing?</p>
        <p>If you're experiencing issues updating this leave request, you may:</p>
        <ul class="list-disc pl-5 mt-2">
            <li>Try refreshing the page and try again</li>
            <li class="font-semibold">Or delete this request and create a new one</li>
        </ul>
        
    </div>

    <!-- Leave Request Form -->
    <form method="POST" action="{{ route('employee.leave_update', $leave->id) }}"  enctype="multipart/form-data" class=" p-4 rounded-lg">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Leave Type</label>
                <select name="leave_type" id="leave_type" class="mt-1 w-full p-2 border rounded">
                    <option value="">Select Leave Type</option>
                    <option value="Vacation Leave" {{ $leave->leave_type == 'Vacation Leave' ? 'selected' : '' }}>Vacation Leave (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)  </option>
                    <option value="Mandatory Leave" {{ $leave->leave_type == 'Mandatory Leave' ? 'selected' : '' }}>Mandatory/Forced Leave (Sec. 25, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</option>
                    <option value="Sick Leave" {{ $leave->leave_type == 'Sick Leave' ? 'selected' : '' }}>Sick Leave (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</option>
                    <option value="Maternity Leave" {{ $leave->leave_type == 'Maternity Leave' ? 'selected' : '' }}>Maternity Leave (R.A. No. 11210/IRR issued by CSC, DOLE and SSS)</option>
                    <option value="Paternity Leave" {{ $leave->leave_type == 'Paternity Leave' ? 'selected' : '' }}>Paternity Leave (R.A. 8187/CSC MC No. 71, s. 1998, as amended)</option>
                    <option value="Special Privilege Leave" {{ $leave->leave_type == 'Special Privilege Leave' ? 'selected' : '' }}>Special Privilege Leave (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</option>
                    <option value="Solo Parent Leave" {{ $leave->leave_type == 'Solo Parent Leave' ? 'selected' : '' }}>Solo Parent Leave (R.A No. 8972/CSC MC No. 8, s. 2004)</option>
                    <option value="Study Leave" {{ $leave->leave_type == 'Study Leave' ? 'selected' : '' }}>Study Leave (Sec. 68, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</option>
                    <option value="10-Day VAWC Leave" {{ $leave->leave_type == '10-Day VAWC Leave' ? 'selected' : '' }}>10-Day VAWC Leave (R.A. No. 9262/CSC MC No. 15, s. 2005)</option>
                    <option value="Rehabilitation Privilege" {{ $leave->leave_type == 'Rehabilitation Privilege' ? 'selected' : '' }}>Rehabilitation Privilege (Sec. 55, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</option>
                    <option value="Special Leave Benefits for Women Leave" {{ $leave->leave_type == 'Special Leave Benefits for Women Leave' ? 'selected' : '' }}>Special Leave Benefits for Women (R>A. No. 9710/CSC MC No. 25, s. 2010))</option>
                    <option value="Special Emergency Leave" {{ $leave->leave_type == 'Special Emergency Leave' ? 'selected' : '' }}>Special Emergency (Calamity) Leave (CSC MC No. 2, s. 2012, as amended)</option>
                    <option value="Adoption Leave" {{ $leave->leave_type == 'Adoption Leave' ? 'selected' : '' }}>Adoption Leave (R.A. No. 8552)</option>
                    <option value="Other Purposes" {{ $leave->leave_type == 'Other Purposes' ? 'selected' : '' }}>Other Purposes</option>
                    <option value="Others" {{ $leave->leave_type == 'Others' ? 'selected' : '' }}>Others</option> 
                </select>
                
                @error('leave_type')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- <div>
                <label class="block text-sm font-medium text-gray-700">Signature (Optional)</label>
                <div id="signature-preview-container" class="hidden">
                    <p class="text-sm text-gray-500 mb-1">Signature Preview:</p>
                    <div class="border border-gray-300 rounded-lg p-2 flex justify-center">
                        <img id="signature-preview" src="{{ asset($leave->signature_url) }}" alt="Signature Preview" class="max-h-32 object-contain hidden"/>
                        <p id="pdf-preview-message" class="text-sm text-gray-500 hidden">PDF file selected (preview not available)</p>
                    </div>
                </div>
    
                <div class="flex items-center space-x-4">
                    <label class="flex flex-col items-center px-4 py-3 bg-white rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-50">
                        <span class="text-sm font-medium text-gray-700">Choose File</span>
                        <input type="file" name="signature" id="signature-upload" class="hidden" accept="image/*,.pdf">
                    </label>
                    <span id="file-name" class="text-sm text-gray-500">{{ old('signature', $leave->signature ? 'File selected' : 'No file chosen') }}</span>
                </div>
                <p class="text-xs text-gray-500">Supports JPG, PNG, or PDF (max 5MB)</p>
            </div> --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Signature (Optional)</label>
                <div id="signature-preview-container">
                    @if($leave->signature)
                        <img id="signature-preview" src="{{ asset('storage/' . $leave->signature) }}" alt="Signature Preview" class="max-h-32 object-contain"/>
                    @else
                        <p class="text-sm text-gray-500">No signature uploaded</p>
                    @endif
                </div>
                <input type="file" name="signature" id="signature-upload" accept="image/*,.pdf" class="mt-2">
            </div>
    
            <div>
                <label class="block text-sm font-medium text-gray-700">Start of time-off</label>
                <input type="date" name="start_date" id="start_date" class="mt-1 w-full p-2 border rounded" value="{{ old('start_date', $leave->start_date) }}" required>
                @error('start_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <div>
                    <input type="checkbox" id="one_day_leave" class="mr-2" onclick="toggleEndDate()" {{ old('one_day_leave', $leave->is_one_day_leave) ? 'checked' : '' }}>
                    <label for="one_day_leave" class="text-sm">One-day leave</label>
                </div>
            </div>
    
            <div>
                <label class="block text-sm font-medium text-gray-700">End of time-off</label>
                <input type="date" name="end_date" id="end_date" class="mt-1 w-full p-2 border rounded" value="{{ old('end_date', $leave->end_date) }}" required>
                @error('end_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
    
            <div>
                <label class="block mt-2 text-sm font-medium text-gray-700">Commutation:</label>
                <select name="commutation" class="w-full border p-2 rounded">
                    <option value="1" {{ old('commutation', $leave->commutation) == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('commutation', $leave->commutation) == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
    
            <div>
                <label class="block mt-2 text-sm font-medium text-gray-700">Days Applied:</label>
                <input type="number" name="days_applied" class="w-full border p-2 rounded" min="1" value="{{ old('days_applied', $leave->days_applied) }}" required placeholder="Enter Days Applied">
            </div>
    
            <div class="mt-2">
                <label class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
                <textarea name="reason" id="reason" cols="15" rows="5" class="mt-1 w-full p-2 border rounded" placeholder="Enter Reason">{{ old('reason', $leave->reason) }}</textarea>
            </div>
    
            <!-- File Upload for Required Documents -->
            <div>
                <label class="block text-gray-700 font-bold">Required Documents</label>
                @if($leave->leave_files)
                    @php $leaveFiles = json_decode($leave->leave_files) @endphp
                    <div>
                        @foreach($leaveFiles as $file)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $file) }}" class="text-blue-500" target="_blank">View File</a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No documents uploaded</p>
                @endif
                <input type="file" name="leave_files[]" multiple class="mt-2" accept="image/*,.pdf">
            </div>
        </div>
    
        <div id="vacation_options" class="{{ in_array($leave->leave_type, ['Vacation Leave', 'Special Privilege Leave']) ? '' : 'hidden' }}">
            <div class="mt-4">
                <label>
                    <input type="checkbox" name="within_philippines" value="1"
    {{ old('within_philippines', $leave->within_philippines) == 1 ? 'checked' : '' }}>

                    Within the Philippines
                </label>
                <input type="text" name="within_philippines_details" class="border rounded p-1" 
                       value="{{ old('within_philippines_details', $leave->within_philippines_details) }}">
            </div>
        
            <div class="mt-2">
                <label>
                    <input type="checkbox" name="abroad" value="1" 
                    {{ old('abroad', $leave->abroad) == 1 ? 'checked' : '' }}>
                    Abroad
                </label>
                <input type="text" name="abroad_details" class="border rounded p-1" 
                       value="{{ old('abroad_details', $leave->abroad_details) }}">
            </div>
        </div>
        
        <div id="sick_leave_options" class="{{ $leave->leave_type === 'Sick Leave' ? '' : 'hidden' }}">
            <div class="mt-4">
                <label>
                    <input type="checkbox" name="in_hospital" value="1" 
                        {{ old('abrin_hospitaload', $leave->in_hospital) == 1 ? 'checked' : '' }}>
                    In Hospital
                </label>
                <input type="text" name="in_hospital_details" class="border rounded p-1" 
                       value="{{ old('in_hospital_details', $leave->in_hospital_details) }}">
            </div>
        
            <div class="mt-2">
                <label>
                    <input type="checkbox" name="out_patient" value="1" 
                        @checked(old('out_patient', $leave->out_patient))>
                    Out Patient
                </label>
                <input type="text" name="out_patient_details" class="border rounded p-1" 
                       value="{{ old('out_patient_details', $leave->out_patient_details) }}">
            </div>
        </div>
        
        <div id="study_leave_options" class="{{ $leave->leave_type === 'Study Leave' ? '' : 'hidden' }}">
            <div class="mt-4">
                <label>
                    <input type="checkbox" name="completion_masters" value="1" 
                        @checked(old('completion_masters', $leave->completion_masters))>
                    Completion of Master's Degree
                </label>
            </div>
        
            <div class="mt-2">
                <label>
                    <input type="checkbox" name="bar_review" value="1" 
                        @checked(old('bar_review', $leave->bar_review))>
                    BAR Review
                </label>
            </div>
        </div>
        
        <div id="other_purposes_options" class="{{ $leave->leave_type === 'Other Purposes' ? '' : 'hidden' }}">
            <div class="mt-4">
                <label>
                    <input type="checkbox" name="monetization" value="1" 
                        @checked(old('monetization', $leave->monetization))>
                    Monetization
                </label>
            </div>
        
            <div class="mt-2">
                <label>
                    <input type="checkbox" name="terminal_leave" value="1" 
                        @checked(old('terminal_leave', $leave->terminal_leave))>
                    Terminal Leave
                </label>
            </div>
        </div>
        
        <div id="others_options" class="{{ $leave->leave_type === 'Others' ? '' : 'hidden' }}">
            <div class="mt-4">
                <input type="text" name="others_details" class="border rounded p-1 w-full" 
                       value="{{ old('others_details', $leave->others_details) }}" 
                       placeholder="Please specify">
            </div>
        </div>
    
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Update Leave Request</button>
    </form>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function() {
    const leaveType = document.querySelector('select[name="leave_type"]');
    const vacationOptions = document.getElementById("vacation_options");
    const sickLeaveOptions = document.getElementById("sick_leave_options");
    const studyLeaveOptions = document.getElementById("study_leave_options");
    const otherPurposesOptions = document.getElementById("other_purposes_options");

    // Array of all option elements that might be hidden/shown
    const allOptions = [
        vacationOptions,
        sickLeaveOptions,
        studyLeaveOptions,
        otherPurposesOptions
    ].filter(el => el !== null); // Filter out null elements

    function toggleOptions() {
        if (!leaveType) return; // Exit if leaveType not found

        const selectedValue = leaveType.value;
        console.log('Selected leave type:', selectedValue);

        // Hide all sections (only for elements that exist)
        allOptions.forEach(el => {
            el.classList.add("hidden");
        });

        // Show the relevant options
        if (selectedValue === "Vacation Leave" || selectedValue === "Special Privilege Leave") {
            if (vacationOptions) vacationOptions.classList.remove("hidden");
        } else if (selectedValue === "Sick Leave") {
            if (sickLeaveOptions) sickLeaveOptions.classList.remove("hidden");
        } else if (selectedValue === "Study Leave") {
            if (studyLeaveOptions) studyLeaveOptions.classList.remove("hidden");
        } else if (selectedValue === "Other Purposes") {
            if (otherPurposesOptions) otherPurposesOptions.classList.remove("hidden");
        }
    }

    // Only add event listener if leaveType exists
    if (leaveType) {
        leaveType.addEventListener("change", toggleOptions);
        // Run function on page load (if editing)
        toggleOptions();
    }
});

const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);
    }

    // Hide error message after 3 seconds
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 3000);
    }

    document.addEventListener("DOMContentLoaded", function() {
    let leaveType = document.getElementById("leave_type");
    let othersInput = document.getElementById("others_input");

    leaveType.addEventListener("change", function() {
        if (this.value === "Others") {
            othersInput.classList.remove("hidden");
        } else {
            othersInput.classList.add("hidden");
        }
    });
});
</script>
