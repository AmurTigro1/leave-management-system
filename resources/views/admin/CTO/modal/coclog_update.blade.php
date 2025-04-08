<div id="cocUpdateModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-[9999] flex items-center justify-center px-4 overflow-y-auto">
    <div class="w-full max-w-2xl mx-4 bg-white shadow-xl rounded-lg p-6 relative max-h-[70vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="text-center border-b pb-4">
            <h3 class="text-2xl font-bold text-gray-900">
                Update COC Log for <span class="text-green-500">{{ $log->user->first_name }} {{ $log->user->last_name }}</span>
            </h3>
            <p class="text-sm text-gray-600 mt-2">
                Current Overtime Balance: <span class="font-bold">{{ $log->user->overtime_balance }} hours</span>
            </p>
        </div>

        <form action="{{ route('coc-logs.update', $log->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mt-6 space-y-4">
                <input type="hidden" name="user_id" value="{{ $log->user_id }}">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Activity Name</label>
                    <input type="text" name="activity_name" value="{{ $log->activity_name }}" 
                           class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Date</label>
                        <input type="text" name="activity_date" value="{{ $log->activity_date }}"
                               class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">COC Earned (hours)</label>
                        <input type="number" name="coc_earned" value="{{ $log->coc_earned }}" min="0"
                               class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-blue-600 mt-1">
                            Changing this value will automatically adjust the user's overtime balance
                        </p>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Issuance</label>
                    <input type="text" name="issuance" value="{{ $log->issuance }}"
                           class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="button" onclick="closecocUpdateModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Update COC Log
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function opencocUpdateModal() {
        document.getElementById("cocUpdateModal").classList.remove("hidden");
    }

    function closecocUpdateModal(event) {
        const modal = document.getElementById("cocUpdateModal");

        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            document.getElementById("cocLogsForm").reset();

            document.querySelectorAll('.text-red-500').forEach(el => el.remove());
        }
    }
</script>