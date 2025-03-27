@if(auth()->user()->role === 'hr')
    @extends('layouts.hr.sidebar-header')
@elseif(auth()->user()->role === 'admin')
    @extends('layouts.admin.sidebar-header')
@endif

@section('content')
    <!-- Top-Right Header - Mobile Adjusted -->
    <div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
        <x-notify::notify />
    </div>
    
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 animate-fade-in">
        @notifyJs
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            <!-- Card Header - Stacked on Mobile -->
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <h2 class="text-lg sm:text-xl font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                        COC Logs Management
                    </h2>
                    @include('hr.CTO.modal.coclog_create', ['users' => $users])
                    <button onclick="opencocCreateLogsModal()" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-600 text-white text-sm sm:text-base rounded-md font-medium hover:bg-blue-700 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                        Add New
                    </button>
                </div>
            </div>

            <!-- Card Body - Responsive Table -->
            <div class="p-3 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm text-gray-700">
                        <thead class="bg-gray-50 text-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">User</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">Activity</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">Date</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">COC</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left hidden sm:table-cell">Issuance</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cocLogs as $log)
                            <tr class="hover:bg-gray-100 transition-colors duration-150 ease-in-out">
                                <!-- User Column - Stacked on Mobile -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-2 sm:ml-4">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900 truncate max-w-[100px] sm:max-w-none">
                                                {{ $log->user->name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <!-- Activity Column - Truncated on Mobile -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm text-gray-900 truncate max-w-[120px] sm:max-w-none">
                                        {{ $log->activity_name }}
                                    </div>
                                </td>
                                
                                <!-- Date Column -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500">
                                    {{ $log->activity_date }}
                                </td>
                                
                                <!-- COC Earned Column -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $log->coc_earned }}h
                                    </span>
                                </td>
                                
                                <!-- Issuance Column - Hidden on Mobile -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                    <div class="truncate max-w-[100px]">{{ $log->issuance }}</div>
                                </td>
                                
                                <!-- Actions Column - Icons Only on Mobile -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap text-center text-xs sm:text-sm font-medium">
                                    <div class="flex justify-center space-x-1 sm:space-x-2">
                                        <a href="{{ route('coc-logs.show', $log->id) }}" class="text-blue-600 hover:text-blue-900 p-1" title="View">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('coc-logs.edit', $log->id) }}" class="text-indigo-600 hover:text-indigo-900 p-1" title="Edit">
                                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('coc-logs.destroy', $log->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1" title="Delete" onclick="return confirm('Are you sure you want to delete this COC log?')">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination - Stacked on Mobile -->
                <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="text-xs sm:text-sm text-gray-500">
                        Showing <span class="font-medium">{{ $cocLogs->firstItem() }}</span> to <span class="font-medium">{{ $cocLogs->lastItem() }}</span> of <span class="font-medium">{{ $cocLogs->total() }}</span>
                    </div>
                    <div class="flex flex-wrap justify-center gap-1">
                        {{ $cocLogs->onEachSide(1)->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive table adjustments */
        @media (max-width: 640px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            th, td {
                min-width: 100px;
            }
        }
    </style>
@endsection
@notifyCss