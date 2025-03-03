@extends('layouts.sidebar-header')
@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg">
    <h2 class="text-2xl text-start font-bold text-gray-800 mb-4 flex items-center">
        User Profile
        <button id="edit-profile" class="ml-2 text-gray-500 hover:text-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
              </svg>
               <!-- Font Awesome -->
        </button>
    </h2>
    

    <div>

            <!-- Display Profile Image -->
    <div class="flex items-center justify-start">
        <img id="profile-preview"
        src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}"
        class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">


        <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="ml-10">
            @csrf
            <label class="block text-gray-700 text-sm font-bold mb-2">Upload New Profile Image</label>
            <input type="file" name="profile_image" id="profile_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
            
            <button type="submit" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update Profile</button>
        </form>
    </div>
    

    <div class="text-start">
        <p class="text-lg font-semibold mt-3"><span>Name:</span> {{ $user->name }}</p>
             <p class="text-gray-600"><span>Role:</span> {{ $user->role ?? 'user' }}</p>
    </div>

    <h3 class="border mt-2 mb-2"></h3>
    <!-- Profile Image Upload Form -->

        <!-- user Details -->
        <div class="flex-1">
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                <p><span class="font-semibold">Department:</span> {{ $leaves->position ?? 'Not assigned' }}</p>
                <p><span class="font-semibold">Joined:</span> {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
            </div>

            <!-- Leave Balance -->
            {{-- <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold">Remaining Leave Balance</h3>
                <p class="text-3xl font-bold">{{ auth()->user()->leave_balance }} days</p>
            </div> --}}
            <div class="mt-4 p-4 bg-blue-50 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-blue-700">Leave Balance</h3>
                <div class="mt-4 p-4 bg-blue-50 rounded-lg shadow">
                    <div class="flex gap-4 mt-2">
                        <span class="bg-green-500 text-white px-3 py-1 rounded text-sm">Vacation: {{ $vacationBalance}} days</span>
                        <span class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Sick:{{ $sickBalance}} days</span>
                    </div>
                </div>
            </div>
        </div>
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