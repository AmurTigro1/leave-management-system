@extends('layouts.admin.sidebar-header')

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
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left hidden sm:table-cell">Author</th>
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
                                            {{ substr($log->user->first_name, 0, 1) }}
                                        </div>
                                        <div class="ml-2 sm:ml-4">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900 truncate max-w-[100px] sm:max-w-none">
                                                {{ $log->user->first_name }} {{ $log->user->last_name }}
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
                                        {{ $log->coc_earned }} hours
                                    </span>
                                </td>
                                
                                <!-- Issuance Column - Hidden on Mobile -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-gray-500 hidden sm:table-cell">
                                    <div class="truncate max-w-[100px]">{{ $log->issuance }}</div>
                                </td>
                                
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $log->creator->first_name }} {{ $log->creator->last_name }} on {{ $log->created_at->format('M d, Y') }}
                                    </span>
                                </td>
                                
                                <td class="p-3 text-center relative">
                                    <!-- Three-dot menu button -->
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = !open" 
                                                class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                      d="M12 6h.01M12 12h.01M12 18h.01" />
                                            </svg>
                                        </button>
                                
                                        <!-- Dropdown menu -->
                                        <div x-show="open" @click.away="open = false" 
                                        class="fixed transform -translate-x-1/2 mt-2 w-40 bg-white border rounded-lg shadow-lg z-50">
                                            
                                            <a href="{{ route('employee.leave_show', ['id' => $log->id]) }}" 
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                View
                                            </a>
                                
                                            <a href="{{ route('employee.leave_edit', $log->id) }}" 
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Edit
                                            </a>
                                
                                            <form action="{{ route('coc-logs.destroy', $log->id) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this leave request?')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
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
                        {{-- Showing <span class="font-medium">{{ $cocLogs->firstItem() }}</span> to <span class="font-medium">{{ $cocLogs->lastItem() }}</span> of <span class="font-medium">{{ $cocLogs->total() }}</span> --}}
                    </div>
                    <div class="flex flex-wrap justify-center gap-2 sm:gap-4">
                        {{ $cocLogs->links('pagination::tailwind') }}
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