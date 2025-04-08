<div id="myCtoEditModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-[9999]">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all duration-300 ease-out" onclick="event.stopPropagation()">
        
        @if ($overtime)
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white">
                            Update COC Request
                        </h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-white/20 text-blue-100">
                                {{ $overtime->user->first_name ?? 'Unknown' }} {{ $overtime->user->last_name ?? '' }}
                            </span>
                            <span class="text-blue-100/90 text-sm">ID: #{{ $overtime->id }}</span>
                        </div>
                    </div>
                    <button onclick="closemyCtoEditModal()" class="text-white/80 hover:text-white transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-6 max-h-[65vh] overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Current COC Balance</p>
                        <p class="mt-1 text-2xl font-bold text-blue-800">
                            {{ $overtime->user->overtime_balance ?? '0' }} <span class="text-lg">hours</span>
                        </p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Date Filed</p>
                        <p class="mt-1 text-lg font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($overtime->date_filed)->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <form action="{{ route('hr.cto_update', $overtime->id) }}" method="POST" id="cocLogsForm">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Working Hours Applied</label>
                            <div class="relative">
                                <input type="number" 
                                       name="working_hours_applied" 
                                       value="{{ $overtime->working_hours_applied }}" 
                                       min="0"
                                       step="0.5"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="Enter hours"
                                       required>
                                <span class="absolute right-3 top-3 text-gray-500">hours</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Inclusive Dates</label>
                            <div class="relative">
                                <input type="text" 
                                       name="inclusive_dates" 
                                       value="{{ $overtime->inclusive_dates ? \Carbon\Carbon::parse($overtime->inclusive_dates)->format('M j, Y') : '' }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                       placeholder="Select date"
                                       required>
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-3.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-8 pt-5 border-t border-gray-200 flex flex-col sm:flex-row justify-end gap-3">
                        <button type="button" 
                                onclick="closemyCtoEditModal()" 
                                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition duration-200 shadow-sm hover:shadow-md order-1 sm:order-2">
                            Update COC Request
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No COC Log Found</h3>
                <p class="text-gray-500 mb-6">There is no COC log available for update at this time.</p>
                <button type="button" 
                        onclick="closemyCtoEditModal()" 
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition duration-200">
                    Close
                </button>
            </div>
        @endif
    </div>
</div> 

<script>
    function openmyCtoEditModal() {
        document.getElementById("myCtoEditModal").classList.remove("hidden");
    }

    function closemyCtoEditModal(event) {
        const modal = document.getElementById("myCtoEditModal");

        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            document.getElementById("cocLogsForm").reset();

            document.querySelectorAll('.text-red-500').forEach(el => el.remove());
        }
    }
</script>