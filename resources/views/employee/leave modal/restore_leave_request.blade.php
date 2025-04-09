@foreach($leaves as $leave)
<div id="restoreLeaveModal{{ $leave->id }}" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-[9999] overflow-y-auto">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-5">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-white">
                        Restore Leave Request #{{ $leave->id }}
                    </h3>
                    <p class="text-green-100 text-sm mt-1">
                        Are you sure you want to restore this canceled leave request?
                    </p>
                </div>
                <button onclick="closeRestoreLeaveModal({{ $leave->id }})" class="text-white/80 hover:text-white transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6">
            <form action="{{ route('employee.leave_restore', $leave->id) }}" method="POST" class="w-full">
                @csrf
                <p class="text-gray-700 text-sm mb-6">
                    This will restore your canceled leave request. Are you sure?
                </p>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="closeRestoreLeaveModal({{ $leave->id }})"
                            class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                        Close
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 rounded-lg text-white font-medium transition duration-200 shadow-sm hover:shadow-md">
                        Confirm Restore
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function openRestoreLeaveModal(leaveId) {
    const modal = document.getElementById('restoreLeaveModal' + leaveId);
    modal.classList.remove('hidden'); 
}
function closeRestoreLeaveModal(leaveId) {
    const modal = document.getElementById('restoreLeaveModal' + leaveId);
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