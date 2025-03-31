<div id="cocRequestUpdateModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-[9999] overflow-auto" onclick="closecocRequestUpdateModal(event)">
    <div class="w-full max-w-2xl mx-4 bg-white shadow-xl rounded-lg p-6 relative max-h-[70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="text-center border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-900">
                Update COC Log for <span class="text-green-500">{{ $overtime->user->first_name }} {{ $overtime->user->last_name }}</span>
            </h3>
            <p class="text-sm text-gray-600 mt-2">
                Current Overtime Balance: <span class="font-bold">{{ $overtime->user->overtime_balance }} hours</span>
            </p>
            <p class="text-sm text-gray-600 mt-2">
                Date Filed: <span class="font-bold">{{ $overtime->date_filed }}</span>
            </p>
        </div>

        <!-- Form Section -->
        <form action="{{ route('coc-logs.update', $overtime->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mt-6 space-y-4">                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Working Hours Applied</label>
                    <input type="text" name="working_hours_applied" value="{{ $overtime->working_hours_applied }} hours" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Inclusive Dates</label>
                        <input type="text" name="inclusive_dates" value="{{ $overtime->inclusive_dates }}" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="closecocRequestUpdateModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Update CTO request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function opencocRequestUpdateModal() {
        document.getElementById("cocRequestUpdateModal").classList.remove("hidden");
    }

    function closecocRequestUpdateModal(event) {
        const modal = document.getElementById("cocRequestUpdateModal");

        // Close only if clicking outside or clicking cancel
        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            // Reset form inputs
            document.getElementById("cocLogsForm").reset();

            // Remove error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.remove());
        }
    }
</script>