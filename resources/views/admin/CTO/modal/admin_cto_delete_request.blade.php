<!-- Delete CTO Modal -->
@if($overtime)
    <div id="deleteCtoModal{{ $overtime->id }}" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-[9999] overflow-y-auto">
        <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
            <div class="bg-gradient-to-r from-red-600 to-rose-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">
                            Delete CTO Request
                        </h3>
                        <p class="text-red-100 text-sm mt-1">
                            Are you sure you want to delete this CTO request?
                        </p>
                    </div>
                    <button onclick="closeDeleteCtoModal({{ $overtime->id }})" class="text-white/80 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.cto_delete', $overtime->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <p class="text-gray-700 text-sm mb-6">
                        This action will permanently delete the CTO request. This cannot be undone.
                    </p>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="closeDeleteCtoModal({{ $overtime->id }})"
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-5 py-2.5 bg-red-600 hover:bg-red-700 rounded-lg text-white font-medium transition duration-200 shadow-sm hover:shadow-md">
                            Confirm Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
function openDeleteCtoModal(overtimeId) {
    const modal = document.getElementById('deleteCtoModal' + overtimeId);
    modal.classList.remove('hidden');
}

function closeDeleteCtoModal(overtimeId) {
    const modal = document.getElementById('deleteCtoModal' + overtimeId);
    modal.classList.add('hidden');
}

document.addEventListener('click', function (event) {
    const modalBackground = document.querySelectorAll('.bg-gray-900/50');
    modalBackground.forEach(function (bg) {
        if (event.target === bg) {
            bg.classList.add('hidden');
        }
    });
});
</script>