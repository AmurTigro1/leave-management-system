<div id="userEditModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center px-4 z-[9999] overflow-auto" onclick="closeuserEditModal(event)">
    <div class="bg-white w-full sm:w-[90%] md:w-[80%] lg:w-[60%] xl:w-[50%] h-[750px] max-w-3xl rounded-2xl shadow-2xl p-4 sm:p-6 relative transition-all transform scale-95 hover:scale-100 text-sm">
        
        <button type="button" onclick="closeuserEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>

        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-center text-gray-800">Update User Information</h2>

        <div class="flex justify-between items-center mt-4">
            <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
            <span class="font-semibold text-xs sm:text-[15px]">Employee Information</span>
            <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
        </div>

        <form action="{{ route('hr.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex flex-col sm:flex-row justify-center items-start mt-4 gap-4">
                <div class="w-full sm:w-auto flex flex-col items-center">
                    <div>
                        <div class="w-32 h-32 sm:w-40 sm:h-40 border-4 border-dashed border-gray-300 rounded-full overflow-hidden bg-gray-100 mx-auto">
                            <img id="profilePreview-{{ $user->id }}" 
                                                    src="{{ 
                                                        $user->profile_image && file_exists(storage_path('app/public/profile_images/' . $user->profile_image)) 
                                                            ? asset('storage/profile_images/' . $user->profile_image) 
                                                            : ($user->profile_image && file_exists(storage_path('app/public/profile_pictures/' . $user->profile_pictures)) 
                                                                ? asset('storage/profile_pictures/' . $user->profile_image) 
                                                                : asset('img/default-avatar.png')) 
                                                            }}" 
                                                    class="w-full h-full rounded-full object-cover" 
                                                    alt="{{ $user->name }}">

                        </div>
                        <div class="mt-3 text-center sm:text-left">
                            <label class="text-gray-700 font-semibold text-xs sm:text-sm">Profile Picture</label>
                            <input type="file" name="profile_image" id="profile_image-{{ $user->id }}" 
                            class="mt-2 w-full sm:w-48 border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                            accept="image/*" onchange="previewImage(event, {{ $user->id }})">                        
                        </div>
                    </div>
                </div>

                <div class="mt-2 sm:mt-[15px] w-full">
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Employee Code</label>
                        <input type="text" name="employee_code" value="{{ $user->employee_code }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Position</label>
                        <input type="text" name="position" value="{{ $user->position }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Department</label>
                        <input type="text" name="department" value="{{ $user->department }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="flex justify-between items-center mb-4 mt-4">
                <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
                <span class="font-semibold text-xs sm:text-[15px]">Personal Information</span>
                <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>                                
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">First Name</label>
                        <input type="text" name="first_name" value="{{ $user->first_name }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ $user->middle_name }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Last Name</label>
                        <input type="text" name="last_name" value="{{ $user->last_name }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Birthday</label>
                        <input type="date" name="birthday" value="{{ $user->birthday }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                </div>

                <div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Role</label>
                        <select name="role" id="role" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                            <option value="employee" {{ $user->role == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="hr" {{ $user->role == 'hr' ? 'selected' : '' }}>HR</option>
                            <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Username</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Password <span class="text-gray-500">(Leave blank if unchanged)</span></label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm">
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end items-center gap-2 sm:gap-4 mt-4">
                <button type="submit" class="bg-blue-600 text-white py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all w-full sm:w-auto">Save Changes</button>
                <button type="button" onclick="closeuserEditModal()" class="bg-gray-500 text-white py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all w-full sm:w-auto">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script>
    function openuserEditModal() {
        document.getElementById("userEditModal").classList.remove("hidden");
    }

    function closeuserEditModal(event) {
        const modal = document.getElementById("userEditModal");

        // Close only if clicking outside or clicking cancel
        if (!event || event.target === modal) {
            modal.classList.add("hidden");

            // Reset form inputs
            document.getElementById("cocLogsForm").reset();

            // Remove error messages
            document.querySelectorAll('.text-red-500').forEach(el => el.remove());
        }
    }
    function previewImage(event, userId) {
        const file = event.target.files[0]; // Get the selected file
        const reader = new FileReader(); // Create a FileReader object
        const imagePreview = document.getElementById('profilePreview-' + userId); // Get the image preview element

        reader.onload = function(e) {
            imagePreview.src = e.target.result; // Update the image source with the file data
        };

        if (file) {
            reader.readAsDataURL(file); // Read the file as a data URL (base64 encoded)
        }
    }
</script>