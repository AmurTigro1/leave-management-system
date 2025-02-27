@extends('layouts.sidebar-header')
@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">user Profile</h2>

    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

            <!-- Display Profile Image -->
    <div class="flex items-center justify-center">
        <img id="profile-preview"
        src="{{ auth()->user()->profile_image ? Storage::url('public/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}"
        class="w-32 h-32 rounded-full object-cover border-2 border-gray-300">
           
             <p class="text-lg font-semibold mt-3">{{ $user->name }}</p>
             <p class="text-gray-600">{{ $user->role ?? 'user' }}</p>
    </div>

    <!-- Profile Image Upload Form -->
    <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <label class="block text-gray-700 text-sm font-bold mb-2">Upload New Profile Image</label>
        <input type="file" name="profile_image" id="profile_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
        
        <button type="submit" class="mt-3 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Update Profile</button>
    </form>

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

            

            <!-- Buttons -->
            <div class="mt-6 flex gap-4">
                <a href="" class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
                    Edit Profile
                </a>
                <a href="/" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    Request Leave
                </a>
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