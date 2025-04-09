<div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-[9999]">
    <div class="bg-white w-full sm:w-[90%] md:w-[80%] lg:w-[60%] xl:w-[50%] h-[750px] overflow-y-auto max-w-3xl rounded-2xl shadow-2xl p-4 sm:p-6 relative transition-all transform scale-95 text-sm">

        <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-500 hover:text-gray-800 text-2xl">&times;</button>

        <h2 class="text-xl sm:text-2xl md:text-3xl font-extrabold text-center text-gray-800">Input User Information</h2>

        <div class="flex justify-between items-center mt-4">
            <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
            <span class="font-semibold text-xs sm:text-[15px]">Employee Information</span>
            <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
       </div>

       <form action="{{ route('hr.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf                            

            <div class="flex flex-col sm:flex-row justify-center items-start mt-4 gap-4">
               <div class="w-full sm:w-auto flex flex-col items-center">
                    <div> 
                        <div class="w-32 h-32 sm:w-40 sm:h-40 border-4 border-dashed border-gray-300 rounded-full overflow-hidden bg-gray-100 mx-auto">
                            <img id="profilePreview" class="w-full h-full object-cover hidden" alt="Profile Preview">
                        </div>
                        <div class="mt-3 text-center sm:text-left">
                            <label class="text-gray-700 font-semibold text-xs sm:text-sm">Profile Picture</label>
                            <input type="file" name="profile_image" id="profile_image" class="mt-2 w-full sm:w-48 border p-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" accept="image/*">
                        </div>
                    </div>
               </div>
                <div class="mt-2 sm:mt-[15px] w-full">
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Employee Code</label>
                        <input type="text" name="employee_code" id="employee_code" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('employee_code')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Position</label>
                        <input type="text" name="position" id="position" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('position')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Department</label>
                        <input type="text" name="department" id="department" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('department')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

           <div class="flex justify-between items-center mb-4 mt-4">
                <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
                <span class="font-semibold text-xs sm:text-[15px]">Personal Information</span>
                <div class="border-2 border-gray-200 w-[30%] sm:w-[38%]"></div>
           </div>

           <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>                                
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">First Name</label>
                        <input type="text" name="first_name" id="first_name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('first_name')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Middle Name</label>
                        <input type="text" name="middle_name" id="middle_name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm">
                        @error('middle_name')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('last_name')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Birthday</label>
                        <input type="date" name="birthday" id="birthday" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('birthday')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Role</label>
                        <select name="role" id="role" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                            <option value="">Select Role</option>
                            <option value="employee">Employee</option>
                            <option value="admin">Admin</option>
                            {{-- <option value="hr">HR</option>
                            <option value="supervisor">Supervisor</option> --}}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">User Name</label>
                        <input type="text" name="name" id="name" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('name')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs text-gray-700 font-semibold">Email</label>
                        <input type="email" name="email" id="email" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('email')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs text-gray-700 font-semibold">Password</label>
                        <input type="password" name="password" id="password" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-400 p-2 text-xs sm:text-sm" required>
                        @error('password')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex flex-col sm:flex-row justify-end items-center gap-2 sm:gap-4 mt-4">
                            <button type="submit" class="bg-blue-600 text-white py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all flex justify-center items-center w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Create
                            </button>
                            <button type="button" id="closeModalBtn2" class="bg-gray-500 text-white py-2 sm:py-3 px-4 sm:px-6 rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all w-full sm:w-auto">
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
    
    document.addEventListener("DOMContentLoaded", function () {
        var modal = document.getElementById("userModal");
        var openModalBtn = document.getElementById("openModalBtn");
        var closeModalBtns = document.querySelectorAll("#closeModalBtn, #closeModalBtn2");

        openModalBtn.addEventListener("click", function () {
            modal.classList.remove("hidden");
            document.body.style.overflow = 'hidden';
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener("click", function () {
                modal.classList.add("hidden");
                document.body.style.overflow = 'auto';
            });
        });

        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.classList.add("hidden");
                document.body.style.overflow = 'auto';
            }
        });
    });
</script>