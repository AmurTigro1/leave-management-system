<div id="cocCreateLogsModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-[9999] overflow-auto" onclick="closecocCreateLogsModal(event)">
    <div class="w-full max-w-2xl mx-4 bg-white shadow-xl rounded-lg p-4 md:p-4 relative max-h-70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="text-center border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-900">Create New COC Logs</h3>
        </div>

        <!-- Form Section -->
        <form id="cocLogsForm" action="{{ route('coc-logs.store') }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <!-- Employee Information -->
            <div class="">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Employee</label>
                    <select name="user_id" id="user_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} {{$user->last_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t pt-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activity Name</label>
                    <input type="text" name="activity_name" value="{{old('activity_name')}}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    @error('activity_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Date</label>
                        <input type="text" name="activity_date" value="{{ old('activity_date')}}" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('activity_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">COC earned</label>
                        <input type="number" name="coc_earned" value="{{ old('coc_earned') }}" min="4" class="w-full border border-gray-300 p-2 sm:p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        @error('coc_earned')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Issuance</label>
                    <input type="text" name="issuance" value="{{old('issuance')}}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    @error('issuance')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="button" onclick="closecocCreateLogsModal()" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 sm:py-2 text-white rounded-lg transition duration-300">
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
    function opencocCreateLogsModal() {
        document.getElementById("cocCreateLogsModal").classList.remove("hidden");
    }

    function closecocCreateLogsModal(event) {
        const modal = document.getElementById("cocCreateLogsModal");

        // Close only if clicking outside or clicking cancel
        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            // Reset form inputs
            document.getElementById("cocLogsForm").reset();

            // Remove error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.remove());
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