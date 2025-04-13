<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacation Leave</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sick Leave</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($employees as $employee)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img src="{{ 
                                    $employee->profile_image && file_exists(storage_path('app/public/profile_images/' . $employee->profile_image)) 
                                        ? asset('storage/profile_images/' . $employee->profile_image) 
                                        : ($employee->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $employee->profile_pictures)) 
                                            ? asset('storage/profile_pictures/' . $employee->profile_image) 
                                            : asset('img/default-avatar.png')) 
                                }}" 
                                class="h-10 w-10 rounded-full object-cover" alt="{{ $employee->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $employee->first_name }} {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. {{ $employee->last_name }}
                                </div>
                            </div>
                        </div>
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $employee->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $employee->employee_code }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $employee->department }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $employee->vacation_leave_balance }} days
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $employee->sick_leave_balance }} days
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ json_encode($employee) }})" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

      <!-- Empty State -->
@if(count($employees) === 0)
<div class="text-center py-12">
<svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
<h3 class="mt-2 text-sm font-medium text-gray-900">No employees found</h3>
<p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
</div>
@endif

<!-- Pagination -->
@if($employees->hasPages())
<div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
<div class="flex-1 flex justify-between sm:hidden">
    @if($employees->onFirstPage())
        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
            Previous
        </span>
    @else
        <a href="{{ $employees->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Previous
        </a>
    @endif
    
    @if($employees->hasMorePages())
        <a href="{{ $employees->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Next
        </a>
    @else
        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
            Next
        </span>
    @endif
</div>
<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
    <div>
        <p class="text-sm text-gray-700">
            Showing <span class="font-medium">{{ $employees->firstItem() }}</span>
            to <span class="font-medium">{{ $employees->lastItem() }}</span>
            of <span class="font-medium">{{ $employees->total() }}</span> results
        </p>
    </div>
    <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
            {{ $employees->links() }}
        </nav>
    </div>
</div>
</div>
@endif