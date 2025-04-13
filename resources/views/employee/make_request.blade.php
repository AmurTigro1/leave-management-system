@extends('layouts.sidebar-header')
    
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
        <form method="POST" action="{{ route('request.leave') }}" class=" p-4 rounded-lg" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Enhanced Info Message -->
            <div id="info_message" class="col-span-2 hidden">
                <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-md shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800" id="info_title">Information</h3>
                                <div class="mt-1 text-sm text-blue-700">
                                    <p id="info_text"></p>
                                </div>
                                <div class="mt-2">
                                    <button type="button" onclick="document.getElementById('info_message').classList.add('hidden')" class="text-blue-700 hover:text-blue-600 text-sm font-medium focus:outline-none">
                                        Dismiss
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Leave Type</label>
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
                        {{-- <option value="Adoption Leave ">Adoption Leave (R.A. No. 8552)</option> --}}
                        {{-- <option value="Other Purposes">Other Purposes</option>
                        <option value="Others">Others</option>  --}}
                    </select>
                    @error('leave_type')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Signature <span class="text-red-600">(Required)</span></label>
                    
                    <!-- Preview container (hidden by default) -->
                    <div id="signature-preview-container" class="hidden">
                        <p class="text-sm text-gray-500 mb-1">Signature Preview:</p>
                        <div class="border border-gray-300 rounded-lg p-2 flex justify-center">
                            <img id="signature-preview" src="#" alt="Signature Preview" class="max-h-32 object-contain hidden"/>
                            <p id="pdf-preview-message" class="text-sm text-gray-500 hidden">PDF file selected (preview not available)</p>
                        </div>
                    </div>
                    
                    <!-- File input with better styling -->
                    <div class="flex items-center space-x-4">
                        <label class="flex flex-col items-center px-4 py-3 bg-white rounded-lg border border-gray-300 cursor-pointer hover:bg-gray-50">
                            <span class="text-sm font-medium text-gray-700">Choose File</span>
                            <input type="file" name="signature" id="signature-upload" class="hidden" accept="image/*,.pdf">
                        </label>
                        <span id="file-name" class="text-sm text-gray-500">No file chosen</span>
                    </div>
                    <p class="text-xs text-gray-500">Supports JPG, PNG, or PDF (max 5MB)</p>
                    @error('signature')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                
                <script>
                document.getElementById('signature-upload').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const previewContainer = document.getElementById('signature-preview-container');
                    const imgPreview = document.getElementById('signature-preview');
                    const pdfMessage = document.getElementById('pdf-preview-message');
                    const fileNameDisplay = document.getElementById('file-name');
                    
                    fileNameDisplay.textContent = file ? file.name : 'No file chosen';
                    
                    if (!file) {
                        previewContainer.classList.add('hidden');
                        return;
                    }
                    
                    previewContainer.classList.remove('hidden');
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imgPreview.src = e.target.result;
                            imgPreview.classList.remove('hidden');
                            pdfMessage.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    } else if (file.type === 'application/pdf') {
                        imgPreview.classList.add('hidden');
                        pdfMessage.classList.remove('hidden');
                    }
                });
                </script>
                {{-- <div class="mb-4">
                    <label class="block font-medium">Salary File</label>
                    <input type="text" name="salary_file" class="w-full border p-2 rounded" required placeholder="Enter Salary File">
                </div> --}}

                <div>
                    <label class="block text-sm font-medium text-gray-700">Start of time-off</label>
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
                    <label class="block text-sm font-medium text-gray-700">End of time-off</label>
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
                    <label class="block mt-2 text-sm font-medium text-gray-700">Commutation:</label>
                    <select name="commutation" class="w-full border p-2 rounded">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="">
                    <label class="block mt-2 text-sm font-medium text-gray-700">Days Applied:</label>
                    <input type="number" name="days_applied" class="w-full border p-2 rounded" min="1" required placeholder="Enter Days Applied">
                </div>
                <div class="mt-2">
                    <label class="block text-sm font-medium text-gray-700">Reason (Optional)</label>
                    {{-- <input type="text" name="reason" class="mt-1 w-full p-2 border rounded"> --}}
                    <textarea name="reason" id="reason" cols="15" rows="5" class="mt-1 w-full p-2 border rounded" placeholder="Enter Reason"></textarea>
                </div>
                            <!-- File Upload for Required Documents -->
                            <div id="file_upload_section" class="hidden">
                                <div>
                                    <label class="block text-gray-700 font-bold">Required Documents</label>
                                    <p class="text-sm text-gray-500">Upload multiple images or PDFs (max 5MB each)</p>
                                    
                                    <!-- File upload area -->
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors cursor-pointer" id="dropzone">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">Drag and drop files here or</p>
                                        <label for="leave_files" class="mt-4 px-4 py-2 text-blue-700 rounded-md text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 cursor-pointer">
                                            Select Files
                                        </label>
                                        <input type="file" name="leave_files[]" id="leave_files" multiple class="hidden" accept="image/*,.pdf">
                                    </div>
                                    
                                    <!-- Selected files preview -->
                                    <div id="file-previews" class="mt-4 space-y-3 hidden">
                                        <p class="text-sm font-medium text-gray-700">Selected files:</p>
                                        <div id="preview-container" class="grid grid-cols-1 sm:grid-cols-2 gap-3"></div>
                                    </div>
                                    
                                    @error('leave_files')
                                    <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const fileInput = document.getElementById('leave_files');
                                const dropzone = document.getElementById('dropzone');
                                const previewContainer = document.getElementById('preview-container');
                                const filePreviewsSection = document.getElementById('file-previews');
                                
                                // Handle file selection
                                fileInput.addEventListener('change', handleFiles);
                                
                                // Drag and drop functionality
                                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                                    dropzone.addEventListener(eventName, preventDefaults, false);
                                });
                                
                                function preventDefaults(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }
                                
                                ['dragenter', 'dragover'].forEach(eventName => {
                                    dropzone.addEventListener(eventName, highlight, false);
                                });
                                
                                ['dragleave', 'drop'].forEach(eventName => {
                                    dropzone.addEventListener(eventName, unhighlight, false);
                                });
                                
                                function highlight() {
                                    dropzone.classList.add('border-blue-500', 'bg-blue-50');
                                }
                                
                                function unhighlight() {
                                    dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                                }
                                
                                dropzone.addEventListener('drop', handleDrop, false);
                                
                                function handleDrop(e) {
                                    const dt = e.dataTransfer;
                                    const files = dt.files;
                                    fileInput.files = files;
                                    handleFiles({ target: fileInput });
                                }
                                
                                function handleFiles(e) {
                                    const files = e.target.files;
                                    if (!files || files.length === 0) return;
                                    
                                    previewContainer.innerHTML = '';
                                    filePreviewsSection.classList.remove('hidden');
                                    
                                    Array.from(files).forEach(file => {
                                        const previewElement = createPreviewElement(file);
                                        previewContainer.appendChild(previewElement);
                                    });
                                }
                                
                                function createPreviewElement(file) {
                                    const previewDiv = document.createElement('div');
                                    previewDiv.className = 'border border-gray-200 rounded-lg p-3 flex items-start space-x-3';
                                    
                                    // File icon or preview
                                    const previewContent = document.createElement('div');
                                    previewContent.className = 'flex-shrink-0';
                                    
                                    if (file.type.startsWith('image/')) {
                                        const imgPreview = document.createElement('img');
                                        imgPreview.className = 'h-12 w-12 object-cover rounded';
                                        imgPreview.alt = 'Preview';
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            imgPreview.src = e.target.result;
                                        };
                                        reader.readAsDataURL(file);
                                        previewContent.appendChild(imgPreview);
                                    } else {
                                        const iconDiv = document.createElement('div');
                                        iconDiv.className = 'h-12 w-12 bg-gray-100 rounded flex items-center justify-center';
                                        iconDiv.innerHTML = `
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        `;
                                        previewContent.appendChild(iconDiv);
                                    }
                                    
                                    // File info
                                    const fileInfo = document.createElement('div');
                                    fileInfo.className = 'flex-1 min-w-0';
                                    
                                    const fileName = document.createElement('p');
                                    fileName.className = 'text-sm font-medium text-gray-900 truncate';
                                    fileName.textContent = file.name;
                                    
                                    const fileSize = document.createElement('p');
                                    fileSize.className = 'text-xs text-gray-500';
                                    fileSize.textContent = formatFileSize(file.size);
                                    
                                    // Remove button
                                    const removeBtn = document.createElement('button');
                                    removeBtn.type = 'button';
                                    removeBtn.className = 'text-red-500 hover:text-red-700';
                                    removeBtn.innerHTML = `
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    `;
                                    removeBtn.addEventListener('click', () => {
                                        previewDiv.remove();
                                        updateFileInput();
                                        if (previewContainer.children.length === 0) {
                                            filePreviewsSection.classList.add('hidden');
                                        }
                                    });
                                    
                                    fileInfo.appendChild(fileName);
                                    fileInfo.appendChild(fileSize);
                                    
                                    previewDiv.appendChild(previewContent);
                                    previewDiv.appendChild(fileInfo);
                                    previewDiv.appendChild(removeBtn);
                                    
                                    return previewDiv;
                                }
                                
                                function updateFileInput() {
                                    // This would need more complex handling to actually remove files from the FileList
                                    // In a real implementation, you might need to use a different approach
                                    console.log('Update file input logic would go here');
                                }
                                
                                function formatFileSize(bytes) {
                                    if (bytes === 0) return '0 Bytes';
                                    const k = 1024;
                                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                                }
                            });
                            </script>
            </div>      
            
            <!-- Vacation Leave & Special Privilege Leave -->
            <div id="vacation_options" class="hidden">
                <div class="mt-4">
                    <label>
                        <input type="checkbox" name="within_philippines" value="1"> Within the Philippines
                    </label>
                    <input type="text" name="within_philippines" class="border rounded p-1">
                    @error('within_philippines')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mt-2">
                    <label>
                        <input type="checkbox" name="abroad" value="1"> Abroad
                    </label>
                    <input type="text" name="abroad_details" class="border rounded p-1" placeholder="Specify">
                    @error('abroad_details')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sick Leave -->
            <div id="sick_leave_options" class="hidden">
                <div class="mt-4">
                    <label>
                        <input type="checkbox" name="in_hospital" value="1"> In Hospital
                    </label>
                    <input type="text" name="in_hospital_details" class="border rounded p-1" placeholder="Specify Illness">
                    @error('in_hospital_details')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-2">
                    <label>
                        <input type="checkbox" name="out_patient" value="1"> Out Patient
                    </label>
                    <input type="text" name="out_patient_details" class="border rounded p-1" placeholder="Specify Illness">
                    @error('out_patient_details')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>


   <!-- Study Leave -->
<div id="study_leave_options" class="hidden">
    <label>
        <input type="checkbox" name="completion_masters" value="1"> Completion of Master's Degree
    </label>
    @error('completion_masters')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <label>
        <input type="checkbox" name="bar_review" value="1"> BAR Review
    </label>
    @error('bar_review')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>

<!-- Other Purposes -->
<div id="other_purposes_options" class="hidden">
    <label>
        <input type="checkbox" name="monetization" value="1"> Monetization of Leave Credits
    </label>
    @error('monetization')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror

    <label>
        <input type="checkbox" name="terminal_leave" value="1"> Terminal Leave
    </label>
    @error('terminal_leave')
    <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-4 rounded">Request Leave</button>
    </form>
 </div>


@endsection

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const leaveType = document.querySelector('select[name="leave_type"]');
        const vacationOptions = document.getElementById("vacation_options");
        const sickLeaveOptions = document.getElementById("sick_leave_options");
        const studyLeaveOptions = document.getElementById("study_leave_options");
        const otherPurposesOptions = document.getElementById("other_purposes_options");
        
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const infoMessage = document.getElementById('info_message');
        const infoText = document.getElementById('info_text');
        const fileUploadSection = document.getElementById('file_upload_section');
        const oneDayLeave = document.getElementById('one_day_leave');  

        // ✅ Leave messages
        const leaveMessages = {
            "Vacation Leave": "Vacation Leave must be filed at least <strong>5 days in advance</strong>.",
            "Special Privilege Leave": "Special Privilege Leave must be filed at least <strong>7 days prior</strong>, except for emergencies.",
            "Solo Parent Leave": "Solo Parent Leave must be filed <strong>5 days in advance</strong>. For emergencies, file as soon as possible.",
            "Special Leave Benefits for Women Leave": "Special Leave Benefits for Women must be filed <strong>5 days ahead</strong>",
            "Sick Leave": "Sick Leave exceeding 5 days or filed in advance requires a <strong>medical certificate</strong>.",
            "Maternity Leave": "Maternity Leave requires proof of pregnancy, such as <strong>ultrasound or doctor's certificate</strong>.",
            "Paternity Leave": "Paternity Leave requires proof of child's delivery, such as <strong>birth certificate</strong> or medical certificate.",
            "Mandatory Leave": "Mandatory Leave must be taken annually. Unused leave will be <strong>forfeited</strong> if not availed within the year."
        };

        // ✅ Display leave-specific message
        function updateInfoMessage() {
            const selectedType = leaveType.value;
            if (leaveMessages[selectedType]) {
                infoText.innerHTML = leaveMessages[selectedType];
                infoMessage.classList.remove('hidden');
            } else {
                infoMessage.classList.add('hidden');
                infoText.innerHTML = "";
            }
        }

        // ✅ Show/hide different leave sections
        function toggleOptions() {
            const selectedValue = leaveType.value;

            // Hide all sections
            vacationOptions.classList.add("hidden");
            sickLeaveOptions.classList.add("hidden");
            studyLeaveOptions.classList.add("hidden");
            otherPurposesOptions.classList.add("hidden");

            // Show the relevant section
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

        // ✅ Handle "One Day" checkbox and file upload visibility
        function toggleFileUpload() {
            const selectedType = leaveType.value;

            // Sync end date with start date when "One Day" is checked
            if (oneDayLeave && oneDayLeave.checked) {
                endDate.value = startDate.value;
                endDate.readOnly = true;
            } else {
                endDate.readOnly = false;
            }

            // Always show the file upload for Maternity and Paternity Leave
            if (selectedType === 'Maternity Leave' || selectedType === 'Paternity Leave') {
                fileUploadSection.classList.remove('hidden');
                return;
            }

            // Hide the upload section if no date is selected
            if (!startDate.value || !endDate.value) {
                fileUploadSection.classList.add('hidden');
                return;
            }

            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const today = new Date();
            
            // Calculate the number of requested days
            const daysRequested = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
            
            // Calculate how many days until the leave starts
            const daysUntilStart = Math.floor((start - today) / (1000 * 60 * 60 * 24));

            // ✅ Show file upload only for Sick Leave exceeding 5 days or filed in advance
            // if (selectedType === 'Sick Leave' && (daysRequested > 5 || daysUntilStart > 0)) {
            //     fileUploadSection.classList.remove('hidden');
            // } else {
            //     fileUploadSection.classList.add('hidden');
            // }
        }

        // ✅ Hide success and error messages after timeout
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 10000);
        }

        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 10000);
        }

        // ✅ Event listeners
        leaveType.addEventListener("change", () => {
            toggleOptions();
            updateInfoMessage();
            toggleFileUpload();
        });

        startDate.addEventListener("change", toggleFileUpload);
        endDate.addEventListener("change", toggleFileUpload);

        if (oneDayLeave) {
            oneDayLeave.addEventListener("change", toggleFileUpload);
        }

        // ✅ Initialize on page load
        toggleOptions();
        updateInfoMessage();
        toggleFileUpload();
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