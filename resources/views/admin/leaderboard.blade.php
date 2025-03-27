@extends('layouts.admin.sidebar-header')
<script src="https://unpkg.com/htmx.org@1.9.4"></script>

@section('content')
<div class="bg-white rounded-2xl p-6 w-full max-w-3xl mx-auto animate-fade-in">
    <h2 class="text-2xl font-bold text-center mb-4">🏆 Attendance Leaderboard</h2>

    <p class="text-center text-gray-600 mb-4">
        Recognizing our most dedicated employees! This leaderboard highlights those with the **fewest absences** in the past 30 days, encouraging commitment and reliability.
    </p>

    <!-- Toggle Button -->
    <div class="flex justify-center space-x-2 mb-6">
        <button class="bg-gray-900 text-white px-4 py-2 rounded-full text-sm font-semibold focus:ring">Last 30 days</button>
    </div>

    <!-- Leaderboard List -->
    <div class="space-y-4">
        @foreach ($employees as $index => $employee)
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-4">
                <span class="text-lg font-bold">{{ $index + 1 }}</span>
                <img src="{{ $employee->profile_image ? asset('storage/profile_images/' . $employee->profile_image) : asset('img/default-avatar.png') }}" 
                     class="w-12 h-12 rounded-full object-cover" alt="{{ $employee->name }}">
                <div>
                    <p class="font-semibold">{{ $employee->first_name }} {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. {{ $employee->last_name }}</p>
                    <p class="text-gray-500 text-sm">{{ $employee->email }}</p>
                </div>
            </div>
        
            <!-- Replace Dummy Images with Meaningful Info -->
            <div class="text-right">
                @if ($employee->total_absences == 0)
                    <span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg">Perfect Attendance 🎯</span>
                @elseif ($employee->total_absences <= 2)
                    <span class="px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-lg">Excellent 🎖️</span>
                @else
                    <span class="px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-lg">Good Effort 👍</span>
                @endif
                <div class="text-xs text-gray-700 mt-2">  
                    total absences: {{ $employee->total_absences }} day(s)
                </div>
            </div>
        </div>
        @endforeach
    </div>

<!-- View More Button -->
<div class="text-center mt-6">
    <button 
        class="bg-black text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-gray-800"
        hx-get="{{ route('users.modal') }}"
        hx-target="#modal-content"
        hx-trigger="click">
        View everyone
    </button>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center z-[9999]">
    <div class="bg-white max-w-lg mx-auto p-6 rounded-lg shadow-lg max-h-[80vh] overflow-y-auto">
        <div id="modal-content">
            <!-- Dynamic content from HTMX will be loaded here -->
        </div>
    </div>
</div>




</div>
@endsection

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
