<div class="p-3 sm:p-6">
    <div class="sm:hidden space-y-4">
        @foreach($users as $user)
        <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <img src="{{ 
                            $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                                ? asset('storage/profile_images/' . $user->profile_image) 
                                : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                                    ? asset('storage/profile_pictures/' . $user->profile_image) 
                                    : asset('img/default-avatar.png')) 
                        }}" 
                        class="w-10 h-10 rounded-full object-cover" alt="{{ $user->name }}">
                    </div>
                    <div>
                        <div class="font-medium text-gray-900">
                            {{ $user->first_name }} {{ strtoupper(substr($user->middle_name, 0, 1)) }}. {{ $user->last_name }}
                        </div>
                    </td>
                    
              
                    <td class="px-2 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-blue-500">
                        {{ $user->email }}
                    </td>
                    
                    <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $user->position }} 
                        </span>
                    </td>
                    
                    <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                        <span class="px-2 inline-flex text-md leading-5 font-semibold rounded-full text-green-800">
                            {{ $user->department }} 
                        </span>
                    </td>
                    
                    <td class="p-3 text-center relative overflow-visible">
                        <div x-data="{ open: false }" class="relative inline-block">
                            <button @click="open = !open" 
                                    class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                          d="M12 6h.01M12 12h.01M12 18h.01" />
                                </svg>
                            </button>
                    
                            <div x-show="open" @click.away="open = false" 
                            class="absolute transform -translate-x-1/2 mt-2 w-40 bg-white border rounded-lg shadow-lg overflow-visible z-50">

                                <button id="editModalBtn-{{ $user->id }}" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                  View | Edit
                                </button>
        
                                <form action="{{ route('hr.users.destroy', $user->id) }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to delete this user?')"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                        Delete
                                    </button>
                                </form>                                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-t border-gray-200 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Employee Code:</span>
                    <span class="font-medium">{{ $user->employee_code ?: 'No Code' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Position:</span>
                    <span class="font-medium">{{ $user->position }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Department:</span>
                    <span class="font-medium">{{ $user->department }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

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
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium overflow-visible">
                                    <div class="flex justify-between items-center">
                                        @include('hr.modals.user-edit' , ['users' => $users])
                                        {{-- <button 
                                            onclick="openRequestModal()" 
                                            id="openModal" 
                                            x-show="selectedDays.length" 
                                            class="px-2 py-1 md:px-4 md:py-2 bg-blue-500 text-white rounded text-xs md:text-sm"
                                            @click="setModalDates()">
                                            Apply for OT Request
                                        </button> --}}
                                        <button onclick="openuserEditModal()" id="openModal" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                           View | Edit
                                        </button>
                                        <form action="{{ route('hr.users.destroy', $user->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    class="block w-full text-left mt-3 px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                Delete
                                            </button>
                                        </form>   
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

    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="text-xs sm:text-sm text-gray-500">
            Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span class="font-medium">{{ $users->lastItem() }}</span> of <span class="font-medium">{{ $users->total() }}</span>
        </div>
        <div class="flex flex-wrap justify-center gap-1">
            {{ $users->onEachSide(1)->links('pagination::tailwind') }}
        </div>
    </div>
</div>