@extends('layouts.sidebar-header')
<script src="https://unpkg.com/htmx.org@1.9.4"></script>

@section('content')
<div class="bg-white rounded-2xl p-6 w-full max-w-3xl mx-auto">
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
                <img src="{{ $employee->profile_image ? asset('storage/profile_images/' . $employee->profile_image) : 'https://cdn-icons-png.freepik.com/256/12533/12533276.png?ga=GA1.1.1609491871.1738904251&semt=ais_hybrid' }}" 
                     class="w-12 h-12 rounded-full" alt="{{ $employee->name }}">
                <div>
                    <p class="font-semibold">{{ $employee->name }}</p>
                    <p class="text-gray-500 text-sm">{{ $employee->leaves_count }} Absences</p>
                </div>
            </div>
        
            <!-- Replace Dummy Images with Meaningful Info -->
            <div class="text-right">
                <div class="flex space-x-2 text-xs text-gray-500 mb-4">
                    <span>Sick: {{ $employee->leaves->where('type', 'sick')->count() }}</span> |
                    <span>Vacation: {{ $employee->leaves->where('type', 'vacation')->count() }}</span> |
                    <span>Emergency: {{ $employee->leaves->where('type', 'emergency')->count() }}</span>
                </div>
          
                @if ($employee->leaves_count == 0)
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-lg">Perfect Attendance 🎯</span>
                @elseif ($employee->leaves_count <= 2)
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-lg">Excellent 🎖️</span>
                @else
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-lg">Good Effort 👍</span>
                @endif
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
<div id="modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white max-w-lg mx-auto p-6 rounded-lg shadow-lg">
        <div id="modal-content">
            <!-- Dynamic content from HTMX will be loaded here -->
        </div>
    </div>
</div>



</div>
@endsection

