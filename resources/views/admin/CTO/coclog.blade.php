@extends('layouts.admin.sidebar-header')
@section('content')
@include('admin.CTO.modal.coclog_view', ['cocLogs' => $cocLogs])
@include('admin.CTO.modal.coclog_create', ['users' => $users])
@foreach($cocLogs as $log)
@include('admin.CTO.modal.coclog_delete', ['log' => $log])
@include('admin.CTO.modal.coclog_update', ['log' => $log])
@endforeach
<div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
    <x-notify::notify />
</div>

<div class="container max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 py-3 sm:py-5 animate-fade-in">
    @notifyJs
    <div class="bg-white rounded-lg shadow-sm sm:shadow-md overflow-hidden">
        <!-- Header Section -->
        <div class="p-3 sm:p-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h2 class="text-lg font-bold flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    COC Logs Management
                </h2>
                <button onclick="opencocCreateLogsModal()" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs sm:text-sm rounded-md font-medium hover:bg-blue-700 transition">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Add New
                </button>
            </div>
        </div>

        <!-- Mobile Cards View (hidden on larger screens) -->
        <div class="sm:hidden p-3 space-y-3">
            @foreach($cocLogs as $log)
            <div class="bg-white border border-gray-200 rounded-lg shadow p-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white mr-3">
                            {{ substr($log->user->first_name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $log->user->first_name }} {{ $log->user->last_name }}</h3>
                            <p class="text-xs text-gray-500">{{ $log->user->position ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">{{ $log->activity_date }}</div>
                </div>
                
                <div class="mt-3 space-y-2">
                    <div>
                        <span class="text-xs font-medium">Activity:</span>
                        <p class="text-sm text-blue-500">{{ $log->activity_name }}</p>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-xs font-medium">COC Earned:</span>
                            @if($log->is_expired)
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">{{ $log->coc_earned }} hours</span>
                            @elseif($log->consumed)
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">{{ $log->coc_earned }} hours</span>
                            @else
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">{{ $log->coc_earned }} hours</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs font-medium">Status:</span>
                            @if($log->is_expired)
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Expired</span>
                            @elseif($log->consumed)
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Used</span>
                            @else
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Active</span>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <span class="text-xs font-medium">Created By:</span>
                        <p class="text-sm">{{ $log->creator->first_name }} {{ $log->creator->last_name }}</p>
                    </div>
                </div>
                
                <div class="mt-3 flex justify-end space-x-2">
                    <button onclick="opencocUpdateModal({{ $log->id }})" 
                            class="inline-flex items-center px-2 py-1 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </button>
                    <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent rounded text-xs font-medium text-white bg-red-600 hover:bg-red-700" onclick="openDeleteLogModal({{$log->id}})">Delete</button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div class="hidden sm:block p-3 sm:p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">COC Earned</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($cocLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                        {{ substr($log->user->first_name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $log->user->first_name }} {{ $log->user->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $log->user->position ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-blue-500">{{ $log->activity_name }}</div>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->activity_date }}</div>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($log->is_expired)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $log->coc_earned }} hours
                                    </span>
                                @elseif($log->consumed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $log->coc_earned }} hours
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $log->coc_earned }} hours
                                    </span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                @if($log->is_expired)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Expired</span>
                                @elseif($log->consumed)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Used</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @endif
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $log->creator->first_name }} {{ $log->creator->last_name }}</div>
                            </td>
                            
                            <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button onclick="opencocUpdateModal({{ $log->id }})" 
                                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 rounded-md shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                    
                                    <button type="submit" onclick="openDeleteLogModal({{$log->id}})" class="inline-flex items-center px-2.5 py-1.5 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-red-600 hover:bg-red-700">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- Pagination -->
        <div class="px-3 py-3 sm:px-4 sm:py-4 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="text-xs text-gray-500">
                    Showing <span class="font-medium">{{ $cocLogs->firstItem() }}</span> to <span class="font-medium">{{ $cocLogs->lastItem() }}</span> of <span class="font-medium">{{ $cocLogs->total() }}</span>
                </div>
                <div class="flex flex-wrap justify-center gap-1">
                    {{ $cocLogs->onEachSide(1)->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @media (max-width: 640px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }
        
        .bg-white.rounded-lg {
            border-radius: 0.5rem;
        }
    }
</style>
@notifyCss