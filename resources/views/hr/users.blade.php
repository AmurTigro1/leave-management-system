@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
        <x-notify::notify />
    </div>

    @if ($errors->any())
    <div id="error-message" class="alert alert-danger text-center bg-red-200 text-red-700 rounded-lg py-2 px-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('error-message').style.display = 'none';
        }, 3000);
    </script>
    @endif

    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 animate-fade-in">
        @notifyJs
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <h2 class="text-lg sm:text-xl font-bold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                          </svg>
                          
                        Users Account Management
                    </h2>
                   
                    <button id="openModalBtn" class="bg-blue-600 text-white font-bold py-2 px-4 rounded-lg flex justify-center items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                        </svg>
                          <p>Create New</p>
                    </button>

                    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-[9999]">
                        <div class="bg-white w-[50%] h-[800px] max-w-3xl rounded-2xl shadow-2xl p-6 relative transition-all transform scale-95 hover:scale-100 text-sm">
                            <!-- Close Button -->
                            <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
                    
                            <h2 class="text-3xl font-extrabold text-center text-gray-800">Input User Information</h2>
                    
                            <div class="flex justify-between items-center mt-4">
                                <div class="border-2 border-gray-200 w-[38%]"></div>
                                <span class="font-semibold text-[15px]">Employee Information</span>
                                <div class="border-2 border-gray-200 w-[38%]"></div>
                           </div>

                           <form action="{{ route('hr.users.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf                            
                    
                                <!-- Profile Picture Section -->
                                <div class="flex justify-center items-start mt-4">
                                   <div class=" w-full flex justify-center items-start">
                                        <div> 
                                            <div class="w-40 h-40 border-4 border-dashed border-gray-300 rounded-full overflow-hidden bg-gray-100 ml-[25%]">
                                                <img id="profilePreview" class="w-full h-full object-cover hidden" alt="Profile Preview">
                                            </div>
                                            <div class="">
                                                <label class=" text-gray-700 font-semibold">Profile Picture</label>
                                                <input type="file" name="profile_image" id="profile_image" class="mt-3 w-3/4 border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" accept="image/*">
                                            </div>
                                        </div>
                                   </div>
                                    <div class="mt-[15px] w-full">
                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Employee Code</label>
                                            <input type="text" name="employee_code" id="employee_code" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('employee_code')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Position</label>
                                            <input type="text" name="position" id="position" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('position')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Department</label>
                                            <input type="text" name="department" id="department" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('department')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                               <div class="flex justify-between items-center mb-4 mt-4">
                                    <div class="border-2 border-gray-200 w-[38%]"></div>
                                    <span class="font-semibold text-[15px]">Personal Information</span>
                                    <div class="border-2 border-gray-200 w-[38%]"></div>
                               </div>
                                <!-- Two-Column Form -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>                                
                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">First Name</label>
                                            <input type="text" name="first_name" id="first_name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('first_name')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                    
                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Middle Name</label>
                                            <input type="text" name="middle_name" id="middle_name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400">
                                            @error('middle_name')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                    
                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Last Name</label>
                                            <input type="text" name="last_name" id="last_name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('last_name')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Birthday</label>
                                            <input type="date" name="birthday" id="birthday" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('birthday')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                    
                                    <div>
                                        <div class="mb-3">
                                            <label class="block text-xs text-gray-700 font-semibold">Role</label>
                                            <select name="role" id="role" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                                <option value="">Select Role</option>
                                                <option value="employee">Employee</option>
                                                <option value="admin">Admin</option>
                                                <option value="hr">HR</option>
                                                <option value="supervisor">Supervisor</option>
                                            </select>
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">User Name</label>
                                            <input type="text" name="name" id="name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('name')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Email</label>
                                            <input type="email" name="email" id="email" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('email')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label class="block text-xs text-gray-700 font-semibold">Password</label>
                                            <input type="password" name="password" id="password" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400" required>
                                            @error('password')
                                            <p class="text-red-500 text-sm">{{ $message }}</p>
                                            @enderror
                                        </div>
                    
                                        <div>
                                            <div class="flex justify-end items-center gap-4">
                                                <button type="submit" class="bg-blue-600 text-white py-3 px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all flex justify-center items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Create
                                                </button>
                                                <button type="button" id="closeModalBtn2" class="bg-gray-500 text-white py-3 px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <script>
                        // Profile Picture Preview
                        document.getElementById('profile_image').addEventListener('change', function (event) {
                            const file = event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function (e) {
                                    const previewImage = document.getElementById('profilePreview');
                                    previewImage.src = e.target.result;
                                    previewImage.classList.remove('hidden');
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    </script>
                    
                    
                    <!-- JavaScript for Modal -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var modal = document.getElementById("userModal");
                            var openModalBtn = document.getElementById("openModalBtn");
                            var closeModalBtns = document.querySelectorAll("#closeModalBtn, #closeModalBtn2");
                    
                            // Open Modal
                            openModalBtn.addEventListener("click", function () {
                                modal.classList.remove("hidden");
                            });
                    
                            // Close Modal
                            closeModalBtns.forEach(btn => {
                                btn.addEventListener("click", function () {
                                    modal.classList.add("hidden");
                                });
                            });
                    
                            // Close modal if user clicks outside the modal content
                            modal.addEventListener("click", function (event) {
                                if (event.target === modal) {
                                    modal.classList.add("hidden");
                                }
                            });
                        });
                    </script>
                </div>
            </div>

            <!-- Card Body - Responsive Table -->
            <div class="p-3 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 text-xs sm:text-sm text-gray-700">
                        <thead class="bg-gray-50 text-gray-700 font-semibold">
                            <tr>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">Employee Code</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">Users</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">Email</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left">Position</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-left hidden sm:table-cell">Department/Office</th>
                                <th scope="col" class="py-2 px-2 sm:px-4 border-b text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-100 transition-colors duration-150 ease-in-out">
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ !empty($user->employee_code) ? $user->employee_code : 'No Code found' }}
                                    </span>                                    
                                </td>
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs sm:text-sm">
                                            <img src="{{ 
                                                $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                                                    ? asset('storage/profile_images/' . $user->profile_image) 
                                                    : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                                                        ? asset('storage/profile_pictures/' . $user->profile_image) 
                                                        : asset('img/default-avatar.png')) 
                                            }}" 
                                            class="w-10 h-10 rounded-full object-cover" alt="{{ $user->name }}">
                                            
                                        </div>
                                        <div class="ml-2 sm:ml-4">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900 truncate max-w-[100px] sm:max-w-none">
                                                {{ $user->first_name }} {{ strtoupper(substr($user->middle_name, 0, 1)) }}. {{ $user->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                          
                                <!-- Date Column -->
                                <td class="px-2 sm:px-4 py-3 whitespace-nowrap text-xs sm:text-sm text-blue-500">
                                    {{ $user->email }}
                                </td>
                                
                                <!-- COC Earned Column -->
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
                                            
                                            <a href="" 
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                View
                                            </a>
                                
                                            <a href="" 
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Edit
                                            </a>
                                
                                            <form action="" method="POST" class="w-full">
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
                        Showing <span class="font-medium">{{ $users->firstItem() }}</span> to <span class="font-medium">{{ $users->lastItem() }}</span> of <span class="font-medium">{{ $users->total() }}</span>
                    </div>
                    <div class="flex flex-wrap justify-center gap-1">
                        {{ $users->onEachSide(1)->links('pagination::tailwind') }}
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