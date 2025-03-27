<div id="requestModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-50 overflow-auto" onclick="closeRequestModal(event)">
    <div class="w-full max-w-2xl mx-4 bg-white shadow-xl rounded-lg p-4 md:p-4 relative max-h-70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="text-center border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-900">Overtime Request</h3>
            <p class="text-sm text-gray-500 mt-1">Fill out the form to submit your overtime request.</p>
        </div>

        <!-- Form Section -->
        <form id="overtimeForm" action="{{ route('overtime_request.store') }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <!-- Employee Information -->
            <div class="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->first_name }} {{ strtoupper(substr(Auth::user()->middle_name , 0, 1)) }}. {{ Auth::user()->last_name }}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                </div>
            </div>

            <!-- Overtime Details -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Overtime Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Filing</label>
                        <input type="date" name="date_filed" value="{{ old('date_filed', date('Y-m-d')) }}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Working Hours Applied</label>
                        <input type="number" name="working_hours_applied" value="{{ old('working_hours_applied') }}" min="4" max="{{Auth::user()->overtime_balance || 0}}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('working_hours_applied')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Selected Dates</label>
                        <input type="text" name="inclusive_dates" id="inclusive_dates" value="{{ old('inclusive_dates') }}" 
                               class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" readonly>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
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

        // Close only if clicking outside or clicking cancel
        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            // Reset validation errors
            document.querySelectorAll('.error-message').forEach(el => el.remove());
        }
    }

    document.getElementById("is_driver").addEventListener("change", function () {
        let distanceField = document.getElementById("distanceField");
        if (this.checked) {
            distanceField.classList.remove("hidden"); // Show distance input
        } else {
            distanceField.classList.add("hidden"); // Hide distance input
        }
    });
</script>