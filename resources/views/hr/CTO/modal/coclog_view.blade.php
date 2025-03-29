<div id="cocViewModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-[9999] overflow-auto" onclick="closecocViewModal(event)">
    @isset($log)
    <div class="w-full max-w-2xl mx-4 bg-white shadow-xl rounded-lg p-6 relative max-h-[70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="text-center border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-900">
                <span class="text-green-500">{{ $log->user->first_name }} {{ $log->user->last_name }}</span> COC Log
            </h3>
        </div>

        <!-- Details Section -->
        <div class="mt-6 space-y-6">
            <div class="pt-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Activity Name</label>
                    <p class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ $log->activity_name }}</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Activity Date</label>
                        <p class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ $log->activity_date }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">COC Earned</label>
                        <p class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ $log->coc_earned }} hours</p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Issuance</label>
                    <p class="text-sm text-gray-900 p-2 bg-gray-50 rounded">{{ $log->issuance }}</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created By</label>
                        <p class="text-sm text-gray-900 p-2 bg-gray-50 rounded">
                            {{ $log->creator->first_name }} {{ $log->creator->last_name }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Created At</label>
                        <p class="text-sm text-gray-900 p-2 bg-gray-50 rounded">
                            {{ $log->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="button" onclick="closecocViewModal()" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 text-white rounded-lg transition duration-300">
                    Close
                </button>
            </div>
        </div>
    </div>
    @endisset
</div>

<script>
    function opencocViewModal() {
        document.getElementById("cocViewModal").classList.remove("hidden");
    }

    function closecocViewModal(event) {
        const modal = document.getElementById("cocViewModal");

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