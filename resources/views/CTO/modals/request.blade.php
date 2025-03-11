<div id="requestModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-50" onclick="closeRequestModal(event)">
    <div class="w-full sm:w-[90%] md:max-w-4xl bg-white shadow-lg rounded-lg p-6 relative" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="text-center border-b pb-3">
            <h3 class="text-2xl font-bold text-gray-900">Overtime Request</h3>
        </div>

        <!-- Form Section -->
        <form action="{{ route('overtime_request.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <!-- Employee Information -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" disabled required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" name="position" class="w-full border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" required placeholder="Enter your position">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Office/Division</label>
                    <input type="text" name="office_division" class="w-full border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" required placeholder="Enter your office/division">
                </div>
            </div>

            <!-- Overtime Details -->
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-800">Overtime Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                    <div x-data="{ today: new Date().toISOString().split('T')[0] }">
                        <label class="block text-sm font-medium text-gray-700">Date Filing</label>
                        <input type="date" name="date_filed" x-model="today" class="w-full border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" required disabled>
                    </div>                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Working Hours Applied</label>
                        <input type="number" name="working_hours_applied" min="0" oninput="validity.valid||(value='');" class="w-full border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Inclusive Dates</label>
                        <div class="flex space-x-2">
                            <input type="text" name="inclusive_date_start" x-model="firstSelectedDate" class="w-1/2 border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" required disabled>
                            <input type="text" name="inclusive_date_end" x-model="lastSelectedDate" class="w-1/2 border border-gray-300 p-2 rounded-md focus:ring focus:ring-blue-300" required disabled>
                        </div>
                    </div>                    
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeRequestModal()" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 text-white rounded-md transition">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 text-white font-semibold rounded-md transition">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Script -->
<script>
    function openRequestModal() {
        document.getElementById("requestModal").classList.remove("hidden");
    }

    function closeRequestModal(event) {
        const modal = document.getElementById("requestModal");
        if (!event || event.target === modal) {
            modal.classList.add("hidden");
        }
    }
</script>
