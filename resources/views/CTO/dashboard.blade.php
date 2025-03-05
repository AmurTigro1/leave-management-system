{{-- @extends('main_resources.index') --}}
@extends('CTO.layouts.sidebar-header')

@section('content')
<div class="container mx-auto p-6">

    <!-- Title -->
    <h1 class="text-3xl font-bold mb-6">🏆 Employee Overtime Dashboard</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-500 text-white p-4 rounded shadow">
            <h2 class="text-xl font-bold">Total Applied Hours</h2>
            <p class="text-2xl">{{ $totalAppliedHours }} hrs</p>
        </div>
        <div class="bg-green-500 text-white p-4 rounded shadow">
            <h2 class="text-xl font-bold">Total Earned Hours</h2>
            <p class="text-2xl">{{ $totalEarnedHours }} hrs</p>
        </div>
        <div class="bg-yellow-500 text-white p-4 rounded shadow">
            <h2 class="text-xl font-bold">Pending Requests</h2>
            <p class="text-2xl">{{ $pendingRequests }}</p>
        </div>
    </div>

    <!-- Recent Overtime Requests Table -->
    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-xl font-bold mb-4">📌 Recent Overtime Requests</h2>
        
        <table class="w-full border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Date Filed</th>
                    <th class="border p-2">Start Date</th>
                    <th class="border p-2">End Date</th>
                    <th class="border p-2">Hours Applied</th>
                    <th class="border p-2">Earned Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($overtimes as $overtime)
                    <tr class="border">
                        <td class="p-2">{{ $overtime->date_filed }}</td>
                        <td class="p-2">{{ $overtime->inclusive_date_start }}</td>
                        <td class="p-2">{{ $overtime->inclusive_date_end }}</td>
                        <td class="p-2">{{ $overtime->working_hours_applied }} hrs</td>
                        <td class="p-2 
                            @if($overtime->earned_hours > 0) text-green-500 font-bold 
                            @else text-red-500 font-bold @endif">
                            {{ $overtime->earned_hours }} hrs
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">No overtime requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection