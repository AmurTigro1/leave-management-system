@extends('layouts.sidebar-header')
@section('content')
<div class="max-w-3xl p-8 bg-white rounded-xl shadow-md">
        <!-- Back Button with Animation -->
        <a href="{{ route('employee.leave_request') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center mb-4 transition-transform duration-300 hover:translate-x-2">
            &larr; Back to Leave Requests
        </a>
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
        <div class="flex justify-between items-center">
            <p class="text-lg font-semibold">{{ $user->name }}</p>
            <a href="{{ route('profile.edit') }}" class="text-blue-500 hover:text-blue-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                </svg>
            </a>
        </div>
        <p class="text-gray-600">Role: {{ $user->role ?? 'User' }}</p>
    </div>
    
    <!-- Additional Details -->
    <div class="mt-6 bg-gray-50 rounded-lg shadow-sm">
        <p><span class="font-medium">Email:</span> {{ $user->email }}</p>
        <p><span class="font-medium">Department:</span> {{ $leaves->position ?? 'Not Assigned' }}</p>
        <p><span class="font-medium">Joined:</span> {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
    </div>
    
    <!-- Leave Balance -->
    <div class="mt-6 bg-blue-50 rounded-lg shadow-sm">
        <h3 class="text-lg font-semibold text-blue-700">Leave Balance</h3>
        <div class="flex gap-4 mt-3">
            <span class="bg-green-500 text-white px-3 py-1 rounded text-sm">Vacation: {{ $vacationBalance }} days</span>
            <span class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Sick: {{ $sickBalance }} days</span>
        </div>
    </div>
    
    <!-- Buttons -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('profile.edit') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Edit Profile</a>
        <a href="/" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Request Leave</a>
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
{{-- <div class="mt-4 p-4 bg-blue-50 rounded-lg shadow">
    <h3 class="text-lg font-semibold text-blue-700">Leave Balance</h3>
    <div class="flex gap-4 mt-2">
        <span class="bg-green-500 text-white px-3 py-1 rounded text-sm">{{ $user->leave_balance}} days</span>
        <span class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Sick: {{ $user->leave_balance['sick'] ?? 0 }} days</span>
        <span class="bg-red-500 text-white px-3 py-1 rounded text-sm">Casual: {{ $user->leave_balance['casual'] ?? 0 }} days</span>
    </div>
</div> --}}