<div class="p-3 sm:p-6">
    <!-- Mobile Cards (hidden on larger screens) -->
    <div class="sm:hidden space-y-4">
        @foreach($users as $user)
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- Profile & Name --}}
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-12 w-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <img src="{{ 
                            $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                                ? asset('storage/profile_images/' . $user->profile_image) 
                                : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                                    ? asset('storage/profile_pictures/' . $user->profile_image) 
                                    : asset('img/default-avatar.png')) 
                        }}" 
                        class="h-12 w-12 rounded-full object-cover" alt="{{ $user->name }}">
                    </div>
                    <div>
                        <div class="text-base font-medium text-gray-900">
                            {{ $user->first_name }} {{ strtoupper(substr($user->middle_name, 0, 1)) }}. {{ $user->last_name }}
                        </div>
                        <div class="text-sm text-blue-500 break-words">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>
        
                {{-- Position & Department --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 text-sm">
                    <div>
                        <span class="inline-block px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                            {{ $user->position }}
                        </span>
                    </div>
                    <div class="mt-2 sm:mt-0">
                        <span class="inline-block px-2 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-semibold">
                            {{ $user->department }}
                        </span>
                    </div>
                </div>
        
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mt-3 sm:mt-0">
                    <button 
                        onclick="openuserEditModal({{ $user->id }})" 
                        id="openModal-{{ $user->id }}" 
                        class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition">
                        View | Edit
                    </button>

                    @php
                        $supervisorCount = App\Models\User::where('role', 'supervisor')->count();
                    @endphp
                    @if(auth()->user()->id !== $user->id && $user->role !== 'supervisor' || $supervisorCount > 1)
                        @include('hr.modals.user_delete_request', ['user' => $user])
                        <button type="button" onclick="openDeleteUserModal({{ $user->id }})" class="px-4 py-2 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition w-full sm:w-auto">Delete</button>
                    @endif
                </div>
            </div>
        
            <div class="mt-4 pt-4 border-t border-gray-200 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Employee Code:</span>
                    <span class="font-medium">{{ $user->employee_code ?: 'No Code' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Position:</span>
                    <span class="font-medium">{{ $user->position }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Department:</span>
                    <span class="font-medium">{{ $user->department }}</span>
                </div>
            </div>
        </div>        
        @endforeach
    </div>

    <!-- Desktop Table (hidden on mobile) -->
    <div class="hidden sm:block">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-visible">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee Code</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->employee_code ?: 'No Code' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img src="{{ 
                                                $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                                                    ? asset('storage/profile_images/' . $user->profile_image) 
                                                    : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                                                        ? asset('storage/profile_pictures/' . $user->profile_image) 
                                                        : asset('img/default-avatar.png')) 
                                            }}" 
                                            class="h-10 w-10 rounded-full object-cover" alt="{{ $user->name }}">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $user->first_name }} {{ strtoupper(substr($user->middle_name, 0, 1)) }}. {{ $user->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 relative">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->position }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->department }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center space-x-4">
                                        <button 
                                            onclick="openuserEditModal({{ $user->id }})" 
                                            class="text-blue-500 hover:text-blue-700">
                                            View | Edit
                                        </button>
                                        @php
                                            $supervisorCount = App\Models\User::where('role', 'supervisor')->count();
                                        @endphp
                                        @if(auth()->user()->id !== $user->id && $user->role !== 'supervisor' || $supervisorCount > 1)
                                            <span class="text-gray-400">|</span>
                                            @include('hr.modals.user_delete_request', ['user' => $user])
                                            <button type="button" onclick="openDeleteUserModal({{ $user->id }})" class="text-red-500 hover:text-red-700">Delete</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include modals once at the bottom -->
    @foreach($users as $user)
        @include('hr.modals.user-edit', ['user' => $user])
    @endforeach

    <!-- Pagination -->
    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs sm:text-sm text-gray-500">
            Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span class="font-medium">{{ $users->lastItem() }}</span> of <span class="font-medium">{{ $users->total() }}</span>
        </div>
        <div class="flex flex-wrap justify-center gap-1">
            {{ $users->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    </div>
</div>