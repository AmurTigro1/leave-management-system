@extends('layouts.sidebar-header')
    
@section('content')
<div class="max-w-6xl mx-auto p-6rounded-lg mt-4">
    <!-- Back Button with Animation -->
    <a href="{{ route('employee.leave_request') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center mb-4 transition-transform duration-300 hover:translate-x-2">
        &larr; Back to Leave Requests
    </a>
    <h2 class="text-2xl font-bold mb-4 text-center">Edit Leave Request Form</h2>

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

    <!-- Leave Request Form -->
    <form method="POST" action="{{ route('employee.leave_update', $leave->id) }}" class=" p-4 rounded-lg">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Leave Type</label>
                <select name="leave_type" id="leave_type" class="mt-1 w-full p-2 border rounded" onchange="handleLeaveType()">
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
            <div class="mb-4">
                <label class="block">Office/Department:</label>
                <input type="text" name="department" class="w-full border px-3 py-2 rounded" required value="{{$leave->department}}">
            </div>
            <div class="mb-4">
                <label class="block font-medium">Salary File</label>
                <input type="text" name="salary_file" class="w-full border p-2 rounded" required value="{{$leave->salary_file}}">
            </div>


            <div>
                <label class="block text-sm font-medium">Start of time-off</label>
                <input type="date" name="start_date" id="start_date" class="mt-1 w-full p-2 border rounded" required value="{{$leave->start_date}}">
                @error('start_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
                <div class="">
                    <input type="checkbox" id="one_day_leave" class="mr-2" onclick="toggleEndDate()">
                    <label for="one_day_leave" class="text-sm">One-day leave</label>
                </div>
            </div>
        
            
            <div>
                <label class="block text-sm font-medium">End of time-off</label>
                <input type="date" name="end_date" id="end_date" class="mt-1 w-full p-2 border rounded" required value="{{$leave->end_date}}">
                @error('end_date')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            
            <script>
            function toggleEndDate() {
                let startDate = document.getElementById("start_date");
                let endDate = document.getElementById("end_date");
                let oneDayLeave = document.getElementById("one_day_leave");
            
                if (oneDayLeave.checked) {
                    endDate.value = startDate.value;
                    endDate.readOnly = true;
                } else {
                    endDate.readOnly = false;
                }
            }
            
            // Ensure that when selecting a start date, the end date updates if one-day leave is checked
            document.getElementById("start_date").addEventListener("change", function() {
                if (document.getElementById("one_day_leave").checked) {
                    document.getElementById("end_date").value = this.value;
                }
            });
            </script>
            
            <div>
                <label class="block text-sm font-medium">Position</label>
                <input type="text" name="position" class="mt-1 w-full p-2 border rounded" value="{{$leave->position}}">
                @error('position')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div class="">
                <label class="block mt-2">Days Applied:</label>
                <input type="number" name="days_applied" class="w-full border p-2 rounded" min="1" required value="{{$leave->days_applied}}">
        
                <label class="block mt-2">Commutation:</label>
                <select name="commutation" class="w-full border p-2 rounded">
                    <option value="1" {{ old('commutation', $leave->commutation ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('commutation', $leave->commutation ?? '') == '0' ? 'selected' : '' }}>No</option>
                </select>
                
            </div>
            <div>
                <label class="block text-sm font-medium">Reason (Optional)</label>
                {{-- <input type="text" name="reason" class="mt-1 w-full p-2 border rounded"> --}}
                <textarea name="reason" id="reason" cols="15" rows="5" class="mt-1 w-full p-2 border rounded" placeholder="Enter reason">{{ old('reason', $leave->reason) }}</textarea>
            </div>
        </div>
        
    <!-- Vacation Leave & Special Privilege Leave -->
    <div id="vacation_options" class="{{ old('within_philippines', $leave->within_philippines ?? false) || old('abroad', $leave->abroad ?? false) ? '' : 'hidden' }}">
        <label><input type="checkbox" name="within_philippines" value="1" {{ old('within_philippines', $leave->within_philippines ?? false) ? 'checked' : '' }}> Within the Philippines</label>
        <input type="text" name="within_philippines" class="border rounded p-1">
        @error('within_philippines')
        <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
        <br>
        <label>
            <input type="checkbox" name="abroad" value="1" {{ old('abroad', $leave->abroad ?? false) ? 'checked' : '' }}> Abroad
            <input type="text" name="abroad_details" class="border rounded p-1" placeholder="Specify" value="{{ old('abroad_details', $leave->abroad_details ?? '') }}">
            @error('abroad_details')
            <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </label>
    </div>
<!-- Sick Leave -->
<div id="sick_leave_options" class="hidden">
    <label>
        <input type="checkbox" name="in_hospital" value="1"> In Hospital:
        <input type="text" name="in_hospital_details" class="border rounded p-1" placeholder="Specify Illness">
    </label>
    <br>
    <label>
        <input type="checkbox" name="out_patient" value="1"> Out Patient:
        <input type="text" name="out_patient_details" class="border rounded p-1" placeholder="Specify Illness">
    </label>
</div>

<!-- Study Leave -->
<div id="study_leave_options" class="{{ old('completion_masters', $leave->completion_masters ?? false) || old('bar_review', $leave->bar_review ?? false) ? '' : 'hidden' }}">
    <label><input type="checkbox" name="completion_masters" value="1" {{ old('completion_masters', $leave->completion_masters ?? false) ? 'checked' : '' }}> Completion of Master's Degree</label>
    <label><input type="checkbox" name="bar_review" value="1" {{ old('bar_review', $leave->bar_review ?? false) ? 'checked' : '' }}> BAR Review</label>
</div>

<!-- Other Purposes -->
<div id="other_purposes_options" class="{{ old('monetization', $leave->monetization ?? false) || old('terminal_leave', $leave->terminal_leave ?? false) ? '' : 'hidden' }}">
    <label><input type="checkbox" name="monetization" value="1" {{ old('monetization', $leave->monetization ?? false) ? 'checked' : '' }}> Monetization of Leave Credits</label>
    <label><input type="checkbox" name="terminal_leave" value="1" {{ old('terminal_leave', $leave->terminal_leave ?? false) ? 'checked' : '' }}> Terminal Leave</label>
</div>

    <!-- Input field for "Others" -->
<div id="others_input" class="{{ old('others_details', $leave->others_details ?? '') ? '' : 'hidden' }} mt-2">
    <label class="block text-sm font-medium">Specify Leave Details</label>
    {{-- <input type="text" name="others_details" class="mt-1 w-full p-2 border rounded" placeholder="Enter leave details"> --}}
    <textarea name="others_details" id="others_details" cols="30" rows="10" class="mt-1 w-full p-2 border rounded" placeholder="Enter leave details">{{ old('others_details', $leave->others_details ?? '') }}</textarea>
    
</div>  
<button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
</form>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const leaveType = document.querySelector('select[name="leave_type"]');
    const vacationOptions = document.getElementById("vacation_options");
    const sickLeaveOptions = document.getElementById("sick_leave_options");
    const studyLeaveOptions = document.getElementById("study_leave_options");
    const otherPurposesOptions = document.getElementById("other_purposes_options");

    function toggleOptions() {
        const selectedValue = leaveType.value;

        // Hide all sections
        vacationOptions.classList.add("hidden");
        sickLeaveOptions.classList.add("hidden");
        studyLeaveOptions.classList.add("hidden");
        otherPurposesOptions.classList.add("hidden");

        // Show the relevant options
        if (selectedValue === "Vacation Leave" || selectedValue === "Special Privilege Leave") {
            vacationOptions.classList.remove("hidden");
        } else if (selectedValue === "Sick Leave") {
            sickLeaveOptions.classList.remove("hidden");
        } else if (selectedValue === "Study Leave") {
            studyLeaveOptions.classList.remove("hidden");
        } else if (selectedValue === "Other Purposes") {
            otherPurposesOptions.classList.remove("hidden");
        }
    }

    // Run function on change
    leaveType.addEventListener("change", toggleOptions);

    // Run function on page load (if editing)
    toggleOptions();
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
