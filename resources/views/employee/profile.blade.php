@extends('main_resources.index')
@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">user Profile</h2>

    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
        <!-- Profile Image -->
        <div class="flex flex-col items-center">
            <img src="{{ asset($user->profile_photo ?? 'default-avatar.png') }}" 
                 class="w-32 h-32 rounded-full shadow-md border-4 border-gray-300" 
                 alt="Profile Photo">
            <p class="text-lg font-semibold mt-3">{{ $user->name }}</p>
            <p class="text-gray-600">{{ $user->role ?? 'user' }}</p>
        </div>

        <!-- user Details -->
        <div class="flex-1">
            <div class="bg-gray-50 p-4 rounded-lg shadow">
                <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
                <p><span class="font-semibold">Department:</span> {{ $user->department ?? 'Not assigned' }}</p>
                <p><span class="font-semibold">Joined:</span> {{ \Carbon\Carbon::parse($user->created_at)->format('F d, Y') }}</p>
            </div>

            <!-- Leave Balance -->
            {{-- <div class="mt-4 p-4 bg-blue-50 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-blue-700">Leave Balance</h3>
                <div class="flex gap-4 mt-2">
                    <span class="bg-green-500 text-white px-3 py-1 rounded text-sm">{{ $user->leave_balance}} days</span>
                    <span class="bg-yellow-500 text-white px-3 py-1 rounded text-sm">Sick: {{ $user->leave_balance['sick'] ?? 0 }} days</span>
                    <span class="bg-red-500 text-white px-3 py-1 rounded text-sm">Casual: {{ $user->leave_balance['casual'] ?? 0 }} days</span>
                </div>
            </div> --}}
            <div class="bg-purple-500 text-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold">Remaining Leave Balance</h3>
                <p class="text-3xl font-bold">{{ auth()->user()->leave_balance }} days</p>
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
@endsection