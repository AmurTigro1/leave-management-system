<div class="p-6 bg-white shadow-md rounded-lg">
    <h3 class="text-2xl font-semibold text-gray-800 mb-4">Employee Balances</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 text-sm text-gray-700">
            <thead class="bg-gray-50 text-gray-700 font-semibold">
                <tr>
                    <th class="py-3 px-4 border-b">Employee</th>
                    <th class="py-3 px-4 border-b">Profile</th>
                    <th class="py-3 px-4 border-b">Vacation Leave</th>
                    <th class="py-3 px-4 border-b">Sick Leave</th>
                    <th class="py-3 px-4 border-b">Leave Balance</th>
                    <th class="py-3 px-4 border-b">Cocs</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($employees as $employee)
                    <tr class="hover:bg-gray-100 transition-colors">
                        <td class="py-3 px-4 border-b">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="py-3 px-4 border-b">
                            <div class="flex justify-center items-center">
                                @if ($employee->profile_image)
                                    <img src="{{ asset('storage/profile_images/' . $employee->profile_image) }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <img src="{{ asset('img/default-avatar.png') }}" alt="" class="w-10 h-10 rounded-full object-cover">
                                @endif
                            </div>
                        </td>
                        @if($employee->vacation_leave_balance)
                            <td class="py-3 px-4 border-b">{{ $employee->vacation_leave_balance }} days</td>
                        @else
                            <td class="py-3 px-4 border-b"> 0 days</td>
                        @endif
                        @if($employee->sick_leave_balance)
                            <td class="py-3 px-4 border-b">{{ $employee->sick_leave_balance }} days</td>
                        @else
                            <td class="py-3 px-4 border-b"> 0 days</td>
                        @endif
                        @if($employee->leave_balance)
                            <td class="py-3 px-4 border-b">{{ $employee->leave_balance }} days</td>
                        @else
                            <td class="py-3 px-4 border-b"> 0 days</td>
                        @endif
                        <td class="py-3 px-4 border-b">{{ $employee->overtime_balance }} hours</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="mt-4">
        <p class="text-gray-600 text-sm">
            Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }} employees
        </p>
       <div class="mt-4 flex justify-end">
@if ($employees->hasPages())
    <nav class="flex space-x-2">
        {{-- Previous Page Link --}}
        @if ($employees->onFirstPage())
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                &larr; Prev
            </span>
        @else
            <a href="{{ $employees->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                &larr; Prev
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
            @if ($page == $employees->currentPage())
                <span class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($employees->hasMorePages())
            <a href="{{ $employees->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                Next &rarr;
            </a>
        @else
            <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                Next &rarr;
            </span>
        @endif
    </nav>
@endif
</div>

    </div>
</div>