<div id="cocUpdateModal-{{ $log->id }}" class="hidden fixed inset-0 bg-gray-900/70 z-[9999] flex items-center justify-center p-4">
    <!-- Modal Content -->
    <div class="w-full max-w-2xl mx-4 bg-white shadow-2xl rounded-xl overflow-hidden"
         onclick="event.stopPropagation()">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    @if($log->status === 'active')
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit COC Log
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    COC Log Details
                    @endif
                </h3>
                <button onclick="closecocUpdateModal(event, {{ $log->id }})" class="text-white/80 hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- User Summary -->
            <div class="mt-3 flex items-center">
                <div class="flex-shrink-0 h-10 w-10 bg-white/20 rounded-full flex items-center justify-center text-white font-bold">
                    {{ substr($log->user->first_name, 0, 1) }}{{ substr($log->user->last_name, 0, 1) }}
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ $log->user->first_name }} {{ $log->user->last_name }}</p>
                    <div class="flex space-x-2 text-xs text-blue-100">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Expires {{ $log->expires_at->format('M j, Y') }}
                        </span>
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $log->user->position ?? 'N/A' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body Content -->
        <div class="p-6 max-h-[65vh] overflow-y-auto">
            @if($log->status !== 'active')
                <!-- Read-only view -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                This COC log cannot be modified because it has been <strong>{{ $log->status }}</strong>.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Read-only details -->
                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Activity Information</h4>
                            <p class="font-medium">{{ $log->activity_name }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $log->activity_date }}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">COC Details</h4>
                            <div class="flex items-center justify-between">
                                <span class="font-medium">{{ $log->coc_earned }} hours</span>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($log->status === 'used') bg-red-100 text-red-800
                                    @elseif($log->status === 'expired') bg-orange-100 text-orange-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Issuance Details</h4>
                        <p class="text-gray-700">{{ $log->issuance }}</p>
                    </div>
                    
                    @if($log->status === 'used')
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-500 mb-2">Usage Information</h4>
                        <p class="text-gray-700">Used on {{ $log->updated_at->format('M j, Y \a\t g:i A') }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                    <button onclick="closecocUpdateModal(event, {{ $log->id }})" 
                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        Close
                    </button>
                </div>
            @else
                <!-- Editable form -->
                <form action="{{ route('coc-logs.update', $log->id) }}" method="POST" id="cocLogsForm-{{ $log->id }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ $log->user_id }}">

                    <div class="space-y-5">
                        <!-- Activity Section -->
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
                                    <input type="text" name="activity_name" value="{{ $log->activity_name }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Activity Date*</label>
                                        <input type="date" name="activity_date" value="{{ $log->activity_date }}" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">COC Earned (hours)*</label>
                                        <input type="number" name="coc_earned" value="{{ $log->coc_earned }}" min="0" step="0.5" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Issuance Section -->
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Issuance Details
                            </h4>
                            <textarea name="issuance" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">{{ $log->issuance }}</textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-6 pt-4 border-t border-gray-200 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                        <button type="button" onclick="closecocUpdateModal(event, {{ $log->id }})" 
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update COC Log
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
<script>
    function opencocUpdateModal(id) {
        const modal = document.getElementById(`cocUpdateModal-${id}`);
        modal.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    }

    function closecocUpdateModal(event, id) {
        event.stopPropagation();
        const modal = document.getElementById(`cocUpdateModal-${id}`);
        modal.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
        
        const form = document.getElementById(`cocLogsForm-${id}`);
        if (form) form.reset();
    }
</script>