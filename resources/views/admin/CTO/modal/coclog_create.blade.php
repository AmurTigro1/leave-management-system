<div id="cocCreateLogsModal" class="{{ $errors->any() ? '' : 'hidden' }} fixed inset-0 bg-gray-900/70 z-[9999] flex items-center justify-center p-4" onclick="closecocCreateLogsModal(event)">
    
    <div class="w-full max-w-2xl mx-4 bg-white shadow-2xl rounded-xl overflow-hidden"
         onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create New COC Log
                </h3>
                <button onclick="closecocCreateLogsModal(event)" class="text-white/80 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 max-h-[65vh] overflow-y-auto">
            <form id="cocLogsForm" action="{{ route('coc-logs.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <h4 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Employee Information
                    </h4>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Employee*</label>
                        <select name="user_id" id="user_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->first_name }} {{ $user->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <h4 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Activity Information
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Name*</label>
                            <input type="text" name="activity_name" value="{{ old('activity_name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            @error('activity_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Activity Date*</label>
                                <input type="text" name="activity_date" value="{{ old('activity_date') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                @error('activity_date')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">COC Earned (hours)*</label>
                                <input type="number" step="0.01" name="coc_earned" value="{{ old('coc_earned') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                @error('coc_earned')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Issuance Details
                    </h4>
                    <textarea name="issuance" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ old('issuance') }}</textarea>
                    @error('issuance')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                    <button type="button" onclick="closecocCreateLogsModal(event)" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create COC Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function opencocCreateLogsModal() {
        const modal = document.getElementById('cocCreateLogsModal');
        modal.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    }

    function closecocCreateLogsModal(event) {
        event.stopPropagation();
        const modal = document.getElementById('cocCreateLogsModal');
        modal.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
        
        document.getElementById('cocLogsForm').reset();
        
        document.querySelectorAll('.text-red-500').forEach(el => el.remove());
    }
</script>