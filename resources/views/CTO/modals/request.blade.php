<div id="requestModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-50 overflow-auto" onclick="closeRequestModal(event)">
    <div class="w-full max-w-2xl mx-4 bg-white shadow-xl rounded-lg p-4 md:p-4 relative max-h-70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="text-center border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-900">Overtime Request</h3>
            <p class="text-sm text-gray-500 mt-1">Fill out the form to submit your overtime request.</p>
        </div>

        <form id="overtimeForm" action="{{ route('overtime_request.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf

            <div class="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->first_name }} {{ strtoupper(substr(Auth::user()->middle_name , 0, 1)) }}. {{ Auth::user()->last_name }}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                </div>
            </div>

            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Overtime Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Filing</label>
                        <input type="date" name="date_filed" value="{{ old('date_filed', date('Y-m-d')) }}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CTO Type (Optional)</label>
                        <select name="cto_type" id="cto_type" 
                                class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="none" selected>None</option>
                            <option value="halfday_morning">Half-Day (Morning)</option>
                            <option value="halfday_afternoon">Half-Day (Afternoon)</option>
                            <option value="wholeday">Whole Day</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Working Hours Applied</label>
                        <input type="number" name="working_hours_applied" id="working_hours_applied" 
                               value="{{ old('working_hours_applied') }}" min="1" 
                               class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('working_hours_applied')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
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
                    
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selected Dates</label>
                        <input type="text" name="inclusive_dates" id="inclusive_dates" value="{{ old('inclusive_dates') }}" 
                               class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" readonly>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="button" onclick="closeRequestModal()" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 sm:py-2 text-white rounded-lg transition duration-300">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 sm:py-2 text-white font-semibold rounded-lg transition duration-300">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRequestModal() {
        document.getElementById("requestModal").classList.remove("hidden");
    }

    function closeRequestModal(event) {
        const modal = document.getElementById("requestModal");

        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            document.querySelector('input[name="working_hours_applied"]').value = "";
            
            let errorMessage = document.querySelector('input[name="working_hours_applied"] + p.text-red-500');
            if (errorMessage) {
                errorMessage.remove();
            }
        }
    }
    document.getElementById("cto_type").addEventListener("change", function () {
        let workingHoursField = document.getElementById("working_hours_applied");

        if (this.value === "halfday_morning" || this.value === "halfday_afternoon") {
            workingHoursField.value = 4;
        } else if (this.value === "wholeday") {
            workingHoursField.value = 8;
        } else {
            workingHoursField.value = "";
        }
    });
</script>