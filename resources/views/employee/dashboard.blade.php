@extends('main_resources.index')

@section('content')
<!-- Calendar Section -->
<section class="max-w-6xl mx-auto p-6 bg-white rounded-xl backdrop-blur-lg relative overflow-hidden">
    <div class="absolute inset-0  opacity-30"></div>
    <h2 class="text-2xl font-extrabold text-gray-900 text-center mb-6 relative z-10">Employee Leave Calendar</h2>
    <div id="leaveCalendar" class="p-6  rounded-xl shadow-inner relative z-10"></div>
    
    <!-- Legend -->
    <div class="mt-4 flex justify-center space-x-4 relative z-10">
        <div class="flex items-center space-x-2">
            <span class="w-4 h-4 bg-green-500 rounded-full"></span>
            <span class="text-gray-600 text-sm">Approved</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="w-4 h-4 bg-yellow-300 rounded-full"></span>
            <span class="text-gray-600 text-sm">Pending</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="w-4 h-4 bg-red-500 rounded-full"></span>
            <span class="text-gray-600 text-sm">Rejected</span>
        </div>
    </div>
</section>

<!-- Employee Leave Modal -->
<div id="leaveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <button onclick="document.getElementById('leaveModal').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">
            ✖
        </button>
        <div id="leaveModalContent"></div>
    </div>
</div>
@endsection

<style scoped>
.fc-event {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}
.fc-event:hover {
    transform: scale(1.07);
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
}

#leaveModal.show {
    display: flex;
}
#leaveModal .show {
    opacity: 1;
    transform: scale(1);
}
</style>

<script>
function closeModal() {
    const modal = document.getElementById('leaveModal');
    modal.classList.remove('show');
    setTimeout(() => modal.classList.add('hidden'), 300);
}
</script>
