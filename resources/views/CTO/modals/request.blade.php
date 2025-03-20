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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" disabled>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                    <input type="text" name="position" value="{{ old('position') }}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Enter your position">
                    @error('position')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Office/Division</label>
                    <input type="text" name="office_division" value="{{Auth::user()->department}}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" readonly>
                    @error('office_division')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Inclusive Dates</label>
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            <input type="date" name="inclusive_date_start" value="{{ old('inclusive_date_start') }}" class="w-full sm:w-1/2 border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <input type="date" name="inclusive_date_end" value="{{ old('inclusive_date_end') }}" class="w-full sm:w-1/2 border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>
                    {{-- <!-- Checkbox to show distance field -->
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" name="is_driver" id="is_driver" value="1" {{ old('is_driver') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="is_driver" class="text-sm font-medium text-gray-700">Are you a driver?</label>
                    </div>

                    <!-- Distance field (hidden unless checkbox is checked) -->
                    <div id="distanceField" class="{{ old('is_driver') ? '' : 'hidden' }} sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Distance (Max 50 km)</label>
                        <input type="number" name="distance_km" value="{{ old('distance_km') }}" min="0" max="50" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div> --}}
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