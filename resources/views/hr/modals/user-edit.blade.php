@foreach($users as $user)
<div id="userEditModal-{{ $user->id }}" class="hidden fixed inset-0 w-full h-full bg-gray-900 bg-opacity-50 flex items-center justify-center px-2 sm:px-4 py-4 z-[9999] overflow-auto" onclick="closeuserEditModal(event, {{ $user->id }})">
    <div class="bg-white w-full max-w-4xl rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 relative transition-all transform scale-95 sm:scale-100 overflow-y-auto" style="max-height: 90vh;" onclick="event.stopPropagation()">
        
        <!-- Close Button -->
        <button type="button" onclick="closeuserEditModal(event, {{ $user->id }})" class="absolute top-2 right-2 sm:top-4 sm:right-4 text-gray-500 hover:text-gray-800 text-xl sm:text-2xl">&times;</button>

        <!-- Modal Title -->
        <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-center text-gray-800 mb-2 sm:mb-4">Update User Information</h2>

        <!-- Section Divider -->
        <div class="flex justify-between items-center my-3 sm:my-4">
            <div class="border border-gray-200 w-[25%] sm:w-[38%]"></div>
            <span class="font-semibold text-xs sm:text-sm px-2">Employee Information</span>
            <div class="border border-gray-200 w-[25%] sm:w-[38%]"></div>
        </div>

        <form action="{{ route('hr.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Picture and Basic Info -->
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                <!-- Profile Picture -->
                <div class="w-full sm:w-1/3 flex flex-col items-center">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 md:w-40 md:h-40 border-2 border-dashed border-gray-300 rounded-full overflow-hidden bg-gray-100 mx-auto">
                        <img id="profilePreview-{{ $user->id }}" 
                            src="{{ 
                                $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                                    ? asset('storage/profile_images/' . $user->profile_image) 
                                    : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                                        ? asset('storage/profile_pictures/' . $user->profile_image) 
                                        : asset('img/default-avatar.png')) 
                            }}" 
                            class="w-full h-full object-cover" 
                            alt="{{ $user->name }}">
                    </div>
                    <div class="mt-2 w-full text-center">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Profile Picture</label>
                        <input type="file" name="profile_image" id="profile_image-{{ $user->id }}" 
                            class="block w-full text-xs sm:text-sm text-gray-700
                                file:mr-2 file:py-1 file:px-3 sm:file:py-2 sm:file:px-4
                                file:rounded-lg file:border-0
                                file:text-xs sm:file:text-sm
                                file:font-medium
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                focus:outline-none focus:ring-2 focus:ring-blue-400"
                            accept="image/*">
                    </div>
                </div>

                <!-- Basic Info -->
                <div class="w-full sm:w-2/3 space-y-2 sm:space-y-3">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Employee Code</label>
                        <input type="text" name="employee_code" value="{{ $user->employee_code }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Position</label>
                        <input type="text" name="position" value="{{ $user->position }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Department</label>
                        <input type="text" name="department" value="{{ $user->department }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="flex justify-between items-center my-3 sm:my-4">
                <div class="border border-gray-200 w-[25%] sm:w-[38%]"></div>
                <span class="font-semibold text-xs sm:text-sm px-2">Personal Information</span>
                <div class="border border-gray-200 w-[25%] sm:w-[38%]"></div>
            </div>

            <!-- Personal Info Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <!-- Left Column -->
                <div class="space-y-2 sm:space-y-3">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" value="{{ $user->first_name }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ $user->middle_name }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" value="{{ $user->last_name }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Birthday</label>
                        <input type="date" name="birthday" value="{{ $user->birthday }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-2 sm:space-y-3">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Role</label>
                        <select name="role" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                            <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="hr" {{ $user->role == 'hr' ? 'selected' : '' }}>HR</option>
                            <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Username</label>
                        <input type="text" name="name" value="{{ $user->name }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Password <span class="text-gray-500 text-xs">(Leave blank if unchanged)</span></label>
                        <input type="password" name="password" placeholder="••••••••" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                focus:outline-none focus:ring-blue-400 focus:border-blue-400 text-xs sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                <button type="button" onclick="closeuserEditModal(event, {{ $user->id }})" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 
                        focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 
                        text-xs sm:text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 
                        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 
                        text-xs sm:text-sm font-medium transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

<script>
    function openuserEditModal(userId) {
        document.getElementById("userEditModal-" + userId).classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
    }

    function closeuserEditModal(event, userId) {
        // Close modal if clicking outside the modal content (on the backdrop)
        if (!event.target.closest(".bg-white")) {
            document.getElementById("userEditModal-" + userId).classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }
        
        // Close modal if clicking the close button
        if (event.target.closest('[onclick^="closeuserEditModal"]')) {
            document.getElementById("userEditModal-" + userId).classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }
    }

    // Image preview functionality
    document.querySelectorAll('[id^="profile_image-"]').forEach(input => {
        input.addEventListener('change', function() {
            const userId = this.id.split('-')[1];
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById(`profilePreview-${userId}`).src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>