@extends('layouts.admin.sidebar-header')
@section('content')
<div class="max-w-3xl p-8 bg-white rounded-xl shadow-md">
        <!-- Back Button with Animation -->
    <h2 class="text-3xl font-bold text-gray-900 mb-6 border-b pb-2">User Profile</h2>
    
    <!-- Profile Image & Upload -->
    <div class="flex items-center gap-6">
        <img id="profile-preview"
            src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}"
            class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
        
        <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-2">
            @csrf
            <label class="text-gray-700 text-sm font-medium">Update Profile Image</label>
            <input type="file" name="profile_image" id="profile_image" class="w-full text-sm text-gray-500 file:mr-2 file:py-2 file:px-4 file:border-0 file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 ">Update</button>
        </form>
    </div>
    
    <!-- User Info -->
    <div class="mt-6 space-y-3">
        <div class="flex justify-start items-center">
            <p class="text-lg font-semibold">{{ $user->name }}</p>
            <a href="/supervisor-profile-edit" class="text-blue-500 hover:text-blue-600 ml-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                  </svg>                  
            </a>
        </div>
        <p class="text-gray-600">Role: {{ $user->role ?? 'User' }}</p>
    </div>
    
    <!-- Additional Details -->
    <div class="mt-6 bg-gray-50 rounded-lg shadow-sm">
        <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
        <p><span class="font-medium">Joined:</span> {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
    </div>
    
</div>
<!-- script -->
<script>
    document.getElementById("profile_image").onchange = function(event) {
        let reader = new FileReader();
        reader.onload = function() {
            document.getElementById("profile-preview").src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
</script>
@endsection
