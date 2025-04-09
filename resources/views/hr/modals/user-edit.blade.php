<!-- Add this to your main layout file or component -->
<div id="deleteUserModal-{{ $user->id }}" 
    class="hidden fixed inset-0 bg-gray-900/50 flex items-center justify-center p-4 z-[9999]">
    
 <div class="w-full max-w-md bg-white rounded-lg shadow-xl overflow-hidden">
   <!-- Modal Header -->
   <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4">
     <div class="flex items-center justify-between">
       <div>
         <h3 class="text-lg font-bold text-white">Confirm User Deletion</h3>
         <p class="text-rose-100 text-sm mt-1">
           Are you sure you want to delete this user?
         </p>
       </div>
       <button onclick="closeDeleteUserModal({{ $user->id }})" 
               class="text-white hover:text-gray-200">
         ✕
       </button>
     </div>
   </div>

   <!-- Modal Content -->
   <div class="p-6">
     <form action="{{ route('hr.users.destroy', $user->id) }}" method="POST">
       @csrf
       @method('DELETE')

       <div class="space-y-4">
         <p class="text-gray-700">
           You are about to delete user: <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>
         </p>
         <p class="text-gray-700">
           Employee ID: <code class="bg-gray-100 px-2 py-1 rounded">{{ $user->employee_code ?: 'N/A' }}</code>
         </p>
         <p class="text-red-600 font-medium">
           ⚠️ Warning: This action cannot be undone.
         </p>
       </div>

       <div class="mt-6 flex justify-end space-x-3">
         <button type="button"
                 onclick="closeDeleteUserModal({{ $user->id }})"
                 class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
           Cancel
         </button>
         <button type="submit"
                 class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
           Confirm Delete
         </button>
       </div>
     </form>
   </div>
 </div>
</div>

<!-- Add this script to your layout file -->
<script>
 function openDeleteUserModal(id) {
   document.getElementById(`deleteUserModal-${id}`).classList.remove('hidden');
   document.body.style.overflow = 'hidden';
 }

 function closeDeleteUserModal(id) {
   document.getElementById(`deleteUserModal-${id}`).classList.add('hidden');
   document.body.style.overflow = '';
 }

 // Close when clicking outside modal
 window.addEventListener('click', (event) => {
   if (event.target.classList.contains('bg-gray-900/50')) {
     event.target.classList.add('hidden');
     document.body.style.overflow = '';
   }
 });
</script>