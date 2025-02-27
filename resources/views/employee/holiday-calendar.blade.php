@extends('layouts.sidebar-header')

@section('content')
<div class="bg-white p-4 rounded-lg border border-gray-200 shadow mb-6">
    <h3 class="text-lg font-semibold text-gray-700 flex items-center mb-3">
        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" 
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 
            002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Upcoming Holidays
    </h3>

    <div class="grid grid-cols-4 gap-2">
        @foreach($holidays as $holiday)
            <div class="bg-gray-100 text-center p-3 rounded-lg shadow-sm border border-gray-300 
                hover:bg-gray-200 transition">
                <p class="text-sm font-bold text-gray-700">{{ $holiday->day_name }}</p>
                <p class="text-2xl font-semibold text-blue-600">{{ $holiday->day }}</p>
                <p class="text-xs text-gray-500">{{ $holiday->month }}</p>
                <p class="text-xs text-gray-600 mt-1">{{ $holiday->name }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection