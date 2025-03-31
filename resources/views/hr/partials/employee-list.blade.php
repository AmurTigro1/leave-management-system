<a id="pagination"></a>
<div class="p-4 sm:p-6 bg-white shadow-md rounded-lg">
    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4">Employee Balances</h3>
    
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm text-gray-700">
            <thead class="bg-gray-50 text-gray-700 font-semibold">
                <tr>
                    <th class="py-2 px-2 sm:py-3 sm:px-4 border-b">Employee</th>
                    <th class="py-2 px-2 sm:py-3 sm:px-4 border-b">Profile</th>
                    <th class="py-2 px-2 sm:py-3 sm:px-4 border-b">Vacation</th>
                    <th class="py-2 px-2 sm:py-3 sm:px-4 border-b">Sick</th>
                    <th class="py-2 px-2 sm:py-3 sm:px-4 border-b hidden sm:table-cell">Leave Balance</th>
                    <th class="py-2 px-2 sm:py-3 sm:px-4 border-b">COCs</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($employees as $employee)
                    <tr class="hover:bg-gray-100 transition-colors">
                        <td class="py-2 px-2 sm:py-3 sm:px-4 border-b">
                            <div class="whitespace-nowrap">
                                {{ $employee->first_name }}<br class="sm:hidden">
                                {{ $employee->last_name }}
                            </div>
                        </td>
                        <td class="py-2 px-2 sm:py-3 sm:px-4 border-b">
                            <div class="flex justify-center items-center">
                                @if ($employee->profile_image)
                                    <img src="{{ asset('storage/profile_images/' . $employee->profile_image) }}" 
                                         class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" 
                                         alt="" 
                                         class="w-8 h-8 sm:w-10 sm:h-10 rounded-full object-cover">
                                @endif
                            </div>
                        </td>
                        <td class="py-2 px-2 sm:py-3 sm:px-4 border-b">
                            {{ $employee->vacation_leave_balance ? $employee->vacation_leave_balance.'d' : '0d' }}
                        </td>
                        <td class="py-2 px-2 sm:py-3 sm:px-4 border-b">
                            {{ $employee->sick_leave_balance ? $employee->sick_leave_balance.'d' : '0d' }}
                        </td>
                        <td class="py-2 px-2 sm:py-3 sm:px-4 border-b hidden sm:table-cell">
                            {{ $employee->leave_balance ? $employee->leave_balance.'d' : '0d' }}
                        </td>
                        <td class="py-2 px-2 sm:py-3 sm:px-4 border-b">
                            {{ $employee->overtime_balance ? $employee->overtime_balance.'h' : '0h' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4">
        <p class="text-gray-600 text-xs sm:text-sm">
            Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} employees
        </p>
        <div class="mt-2 sm:mt-4 flex justify-end">
            @if ($employees->hasPages())
                <nav class="flex space-x-1 sm:space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($employees->onFirstPage())
                        <span class="px-2 sm:px-4 py-1 sm:py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed text-xs sm:text-sm">
                            &larr; Prev
                        </span>
                    @else
                        <a href="{{ $employees->previousPageUrl() }}#pagination" 
                           class="px-2 sm:px-4 py-1 sm:py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100 text-xs sm:text-sm">
                            &larr; Prev
                        </a>
                    @endif
    
                    {{-- Page Numbers --}}
                    @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                        @if ($page == $employees->currentPage())
                            <span class="px-2 sm:px-4 py-1 sm:py-2 bg-blue-500 text-white rounded-md text-xs sm:text-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}#pagination" 
                               class="px-2 sm:px-4 py-1 sm:py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100 text-xs sm:text-sm hidden sm:inline-block">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
    
                    {{-- Next Page Link --}}
                    @if ($employees->hasMorePages())
                        <a href="{{ $employees->nextPageUrl() }}#pagination" 
                           class="px-2 sm:px-4 py-1 sm:py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100 text-xs sm:text-sm">
                            Next &rarr;
                        </a>
                    @else
                        <span class="px-2 sm:px-4 py-1 sm:py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed text-xs sm:text-sm">
                            Next &rarr;
                        </span>
                    @endif
                </nav>
            @endif
        </div>
    </div>    
</div>