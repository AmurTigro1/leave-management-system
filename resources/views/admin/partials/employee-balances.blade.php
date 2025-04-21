<div class="w-full">
    {{-- Desktop Table --}}
    <div class="hidden sm:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Profile</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vacation Leave</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sick Leave</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($employees as $employee)
                <tr class="hover:bg-gray-50" data-employee-id="{{ $employee->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img src="{{ 
                                $employee->profile_image && file_exists(storage_path('app/public/profile_images/' . $employee->profile_image)) 
                                    ? asset('storage/profile_images/' . $employee->profile_image) 
                                    : ($employee->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $employee->profile_image)) 
                                        ? asset('storage/profile_pictures/' . $employee->profile_image) 
                                        : asset('img/default-avatar.png')) 
                            }}" 
                            class="h-10 w-10 rounded-full object-cover" alt="{{ $employee->name }}">
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $employee->first_name }} {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. {{ $employee->last_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->employee_code }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->department }}</td>
                
                    {{-- These must match the JS update selectors --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 vacation-leave">{{ $employee->vacation_leave_balance }} days</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 sick-leave">{{ $employee->sick_leave_balance }} days</td>
                
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-wrap gap-1">
                            @if($employee->maternity_leave) <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">M: {{ $employee->maternity_leave }}d</span> @endif
                            @if($employee->paternity_leave) <span class="badge bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">P: {{ $employee->paternity_leave }}d</span> @endif
                            @if($employee->overtime_balance) <span class="badge bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">OT: {{ $employee->overtime_balance }}h</span> @endif
                            @if($employee->study_leave) <span class="badge bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Study: {{ $employee->study_leave }}d</span> @endif
                            @if($employee->vawc_leave) <span class="badge bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">VAWC: {{ $employee->vawc_leave }}d</span> @endif
                            @if($employee->rehabilitation_leave) <span class="badge bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-0.5 rounded">Rehab: {{ $employee->rehabilitation_leave }}d</span> @endif
                            @if($employee->special_leave_benefit) <span class="badge bg-pink-100 text-pink-800 text-xs font-semibold px-2.5 py-0.5 rounded">SLB: {{ $employee->special_leave_benefit }}d</span> @endif
                            @if($employee->special_privilege_leave) <span class="badge bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">SPL: {{ $employee->special_privilege_leave }}d</span> @endif
                            @if($employee->special_emergency_leave) <span class="badge bg-orange-100 text-orange-800 text-xs font-semibold px-2.5 py-0.5 rounded">SEL: {{ $employee->special_emergency_leave }}d</span> @endif
                        </div>
                    </td>
                
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="openEditModal({{ json_encode($employee) }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Card Layout --}}
    <div class="sm:hidden space-y-4">
        @foreach($employees as $employee)
            <div class="bg-white shadow rounded-lg p-4">
                <div class="flex items-center space-x-4">
                    <img src="{{ 
                        $employee->profile_image && file_exists(storage_path('app/public/profile_images/' . $employee->profile_image)) 
                            ? asset('storage/profile_images/' . $employee->profile_image) 
                            : ($employee->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $employee->profile_image)) 
                                ? asset('storage/profile_pictures/' . $employee->profile_image) 
                                : asset('img/default-avatar.png')) 
                    }}" 
                    class="h-12 w-12 rounded-full object-cover" alt="{{ $employee->name }}">
                    <div>
                        <div class="font-semibold text-gray-900">{{ $employee->first_name }} {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. {{ $employee->last_name }}</div>
                        <div class="text-sm text-gray-500">{{ $employee->employee_code }}</div>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-700">
                    <div><span class="font-medium">Department:</span> {{ $employee->department }}</div>
                    <div><span class="font-medium">Vacation Leave:</span> {{ $employee->vacation_leave_balance }} days</div>
                    <div><span class="font-medium">Sick Leave:</span> {{ $employee->sick_leave_balance }} days</div>
                </div>

                <div class="mt-4 text-right">
                    <button onclick="openEditModal({{ json_encode($employee) }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                        Edit
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>


@if(count($employees) === 0)
    <div class="text-center py-12 px-4 sm:px-6 lg:px-8">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No employees found</h3>
        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
    </div>
@endif


@if($employees->hasPages())
    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex-1 flex justify-between sm:hidden">
            @if($employees->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    Previous
                </span>
            @else
                <a href="{{ $employees->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </a>
            @endif

            @if($employees->hasMorePages())
                <a href="{{ $employees->nextPageUrl() }}" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </a>
            @else
                <span class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between w-full">
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
