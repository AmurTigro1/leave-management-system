@extends('layouts.admin.sidebar-header')
    
@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>
@notifyJs
    <div class="max-w-6xl mx-auto p-6rounded-lg shadow-lg animate-fade-in">
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
        <h2 class="text-2xl font-bold mb-4 text-center">Leave Request Form</h2>
    
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
        <form method="POST" action="{{ route('admin-request.leave') }}" class=" p-4 rounded-lg" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Leave Type</label>
                    <select name="leave_type" id="leave_type" class="mt-1 w-full p-2 border rounded" onchange="handleLeaveType()">
                        <option value="">Select Leave Type</option>
                        <option value="Vacation Leave">Vacation Leave (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)  </option>
                        <option value="Mandatory Leave">Mandatory/Forced Leave (Sec. 25, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</option>
                        <option value="Sick Leave">Sick Leave (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</option>
                        <option value="Maternity Leave">Maternity Leave (R.A. No. 11210/IRR issued by CSC, DOLE and SSS)</option>
                        <option value="Paternity Leave">Paternity Leave (R.A. 8187/CSC MC No. 71, s. 1998, as amended)</option>
                        <option value="Special Privilege Leave">Special Privilege Leave (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</option>
                        <option value="Solo Parent Leave">Solo Parent Leave (R.A No. 8972/CSC MC No. 8, s. 2004)</option>
                        <option value="Study Leave">Study Leave (Sec. 68, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</option>
                        <option value="10-Day VAWC Leave">10-Day VAWC Leave (R.A. No. 9262/CSC MC No. 15, s. 2005)</option>
                        <option value="Rehabilitation Privilege">Rehabilitation Privilege (Sec. 55, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</option>
                        <option value="Special Leave Benefits for Women Leave">Special Leave Benefits for Women (R>A. No. 9710/CSC MC No. 25, s. 2010))</option>
                        <option value="Special Emergency Leave">Special Emergency (Calamity) Leave (CSC MC No. 2, s. 2012, as amended)</option>
                        <option value="Adoption Leave ">Adoption Leave (R.A. No. 8552)</option>
                        <option value="Other Purposes">Other Purposes</option>
                        <option value="Others">Others</option> 
                    </select>
                    @error('leave_type')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
               
                <div class="mb-4">
                    <label class="block font-medium">Salary File</label>
                    <input type="text" name="salary_file" class="w-full border p-2 rounded" required placeholder="Enter Salary File">
                </div>

                <div>
                    <label class="block text-sm font-medium">Start of time-off</label>
                    <input type="date" name="start_date" id="start_date" class="mt-1 w-full p-2 border rounded" required>
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
                    <input type="date" name="end_date" id="end_date" class="mt-1 w-full p-2 border rounded" required>
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
                    <label class="block mt-2">Commutation:</label>
                    <select name="commutation" class="w-full border p-2 rounded">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="">
                    <label class="block mt-2">Days Applied:</label>
                    <input type="number" name="days_applied" class="w-full border p-2 rounded" min="1" required placeholder="Enter Days Applied">
                </div>
                <div class="mt-2">
                    <label class="block text-sm font-medium">Reason (Optional)</label>
                    {{-- <input type="text" name="reason" class="mt-1 w-full p-2 border rounded"> --}}
                    <textarea name="reason" id="reason" cols="15" rows="5" class="mt-1 w-full p-2 border rounded" placeholder="Enter Reason"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Signature</label>
                    <input type="file" name="signature" class="w-full border p-2 rounded-lg" accept="image/*,.pdf" required>
                </div>
            </div>      
            
        <!-- Vacation Leave & Special Privilege Leave -->
        <div id="vacation_options" class="hidden">
           <div class="mt-4">
                <label><input type="checkbox" name="within_philippines" value="1"> Within the Philippines</label>
                <input type="text" name="within_philippines" class="border rounded p-1">
                @error('within_philippines')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
           </div>
            <div class="mt-2">
                <label>
                    <input type="checkbox" name="abroad" value="1"> Abroad
                    <input type="text" name="abroad_details" class="border rounded p-1" placeholder="Specify">
                    @error('abroad_details')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </label>
            </div>
        </div>

<!-- Sick Leave -->
<div id="sick_leave_options" class="hidden">
   <div class="mt-4">
        <label>
            <input type="checkbox" name="in_hospital" value="1"> In Hospital:
            <input type="text" name="in_hospital_details" class="border rounded p-1" placeholder="Specify Illness">
        </label>
   </div>
    <div class="mt-2">
        <label>
            <input type="checkbox" name="out_patient" value="1"> Out Patient:
            <input type="text" name="out_patient_details" class="border rounded p-1" placeholder="Specify Illness">
        </label>
    </div>
</div>

    <!-- Study Leave -->
    <div id="study_leave_options" class="hidden">
        <label><input type="checkbox" name="completion_masters" value="1"> Completion of Master's Degree</label>
        <label><input type="checkbox" name="bar_review" value="1"> BAR Review</label>
    </div>

    <!-- Other Purposes -->
    <div id="other_purposes_options" class="hidden">
        <label><input type="checkbox" name="monetization" value="1"> Monetization of Leave Credits</label>
        <label><input type="checkbox" name="terminal_leave" value="1"> Terminal Leave</label>
    </div>

        <!-- Input field for "Others" -->
    <div id="others_input" class="hidden mt-2">
        <label class="block text-sm font-medium">Specify Leave Details</label>
        {{-- <input type="text" name="others_details" class="mt-1 w-full p-2 border rounded" placeholder="Enter leave details"> --}}
        <textarea name="others_details" id="others_details" cols="30" rows="10" class="mt-1 w-full p-2 border rounded" placeholder="Enter leave details"></textarea>
        
    </div>  
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Request Leave</button>
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
@notifyCss
<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>