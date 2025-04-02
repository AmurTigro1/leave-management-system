@extends('layouts.admin.sidebar-header')
<script src="https://unpkg.com/htmx.org@1.9.4"></script>

@section('content')
<div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 w-full max-w-3xl mx-auto animate-fade-in">
    <h2 class="text-xl sm:text-2xl font-bold text-center mb-3 sm:mb-4">🏆 Attendance Leaderboard</h2>

    <p class="text-center text-gray-600 text-sm sm:text-base mb-4 sm:mb-6">
        Recognizing our most dedicated employees! This leaderboard highlights those with the <strong>fewest absences</strong> in the past 30 days.
    </p>

    <div class="flex justify-center mb-4 sm:mb-6">
        <button class="bg-gray-900 text-white px-3 py-1 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-semibold focus:ring">Last 30 days</button>
    </div>

    <div class="space-y-3 sm:space-y-4">
        @foreach ($employees as $index => $employee)
        <div class="flex items-center justify-between p-3 sm:p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-2 sm:space-x-4 min-w-0">
                <span class="text-base sm:text-lg font-bold">{{ $index + 1 }}</span>
                <img src="{{ $employee->profile_image ? asset('storage/profile_images/' . $employee->profile_image) : asset('img/default-avatar.png') }}" 
                     class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0" 
                     alt="{{ $employee->name }}">
                <div class="min-w-0">
                    <p class="font-semibold text-sm sm:text-base truncate">
                        {{ $employee->first_name }} 
                        @if($employee->middle_name)
                        {{ strtoupper(substr($employee->middle_name, 0, 1)) }}. 
                        @endif
                        {{ $employee->last_name }}
                    </p>
                    <p class="text-gray-500 text-xs sm:text-sm truncate">{{ $employee->email }}</p>
                </div>
            </div>
        
            <div class="text-right flex-shrink-0 ml-2">
                @if ($employee->total_absences == 0)
                    <span class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-lg whitespace-nowrap">Perfect 🎯</span>
                @elseif ($employee->total_absences <= 2)
                    <span class="px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-lg whitespace-nowrap">Excellent 🎖️</span>
                @else
                    <span class="px-2 py-1 bg-gray-500 text-white text-xs font-semibold rounded-lg whitespace-nowrap">Good 👍</span>
                @endif
                <div class="text-xs text-gray-700 mt-1 sm:mt-2 whitespace-nowrap">  
                    {{ $employee->total_absences }} absence(s)
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-4 sm:mt-6">
        <button 
            class="bg-black text-white px-4 py-1.5 sm:px-6 sm:py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-gray-800"
            hx-get="{{ route('users.modal') }}"
            hx-target="#modal-content"
            hx-trigger="click">
            View everyone
        </button>
    </div>

    <div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center z-[9999] p-4">
        <div class="bg-white w-full max-w-md mx-auto p-4 sm:p-6 rounded-lg shadow-lg max-h-[80vh] overflow-y-auto">
            <div id="modal-content">
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
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 640px) {
        .leaderboard-entry {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>