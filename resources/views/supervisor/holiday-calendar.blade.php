@extends('layouts.supervisor.sidebar-header')

@section('content')
<div class="bg-white p-6 rounded-xl border border-gray-100 shadow-lg mb-6 animate-fade-in">
    <h3 class="text-xl font-semibold text-gray-800 flex items-center mb-4">
        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" 
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 
            002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Upcoming Holidays
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($holidays as $holiday)
            <div class="bg-gray-50 text-center p-4 rounded-xl shadow-md border border-gray-100 
                hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">{{ $holiday->day_name }}</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $holiday->day }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $holiday->month }}</p>
                <p class="text-sm text-gray-700 font-semibold mt-2">{{ $holiday->name }}</p>
            </div>
        @endforeach
    </div>
</div>
<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection