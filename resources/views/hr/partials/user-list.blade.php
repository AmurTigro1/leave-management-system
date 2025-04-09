<div class="p-3 sm:p-6">
    <div class="hidden sm:block">
        <div class="overflow-x-auto">
            <div class="min-w-full inline-block align-middle">
                <div class="overflow-visible">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
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
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class=" inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->employee_code ?: 'No Code' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
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
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-blue-500 relative">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ $user->position }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->department }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-center text-sm font-medium overflow-visible">
                                    <div class="flex justify-between items-center space-x-2">
                                        <!-- Pass the individual user to the modal -->
                                        @include('hr.modals.user-edit', ['user' => $user]) 
                                        <button onclick="openuserEditModal({{ $user->id }})" id="openModal-{{ $user->id }}" class="block w-full text-left px-4 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded-lg">
                                            View | Edit
                                        </button>
                                        <form action="{{ route('hr.users.destroy', $user->id) }}" method="POST" class="w-full">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')"
                                                    class="block w-full text-white p-2 mt-3 text-sm hover:bg-red-600 bg-red-500 rounded-lg">
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