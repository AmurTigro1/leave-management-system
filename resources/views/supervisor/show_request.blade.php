@extends('layouts.supervisor.sidebar-header')

@section('content')
<div class="p-12 space-y-6">
    <h2 class="text-2xl font-semibold text-gray-800">supervisor Dashboard - Leave Requests</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($leaves as $leave)
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl">
                <h3 class="text-lg font-semibold text-gray-900">{{ $leave->user->name }}</h3>
                <p class="text-gray-600 text-sm">{{ $leave->leave_type }}</p>
                <p class="text-gray-700 mt-2">{{ $leave->reason }}</p>
                <p class="text-gray-500 text-sm mt-2">
                    From: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->format('l, F j, Y') }}</span>
                    <br>
                    To: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->end_date)->format('l, F j, Y') }}</span>
                </p>
                <p class="text-gray-700 mt-2">Duration: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} days</span></p>
                
                <span class="inline-block px-3 py-1 mt-3 text-sm font-medium rounded
                    @if ($leave->status === 'approved') 
                        bg-green-100 text-green-600
                    @elseif ($leave->status === 'pending') 
                        bg-yellow-100 text-yellow-600
                    @elseif ($leave->status === 'rejected') 
                        bg-red-100 text-red-600
                    @endif">
                    {{ ucfirst($leave->status) }}
                </span>
                
                @if ($leave->status == 'pending')
                    <form method="POST" action="{{ route('supervisor.approve', $leave->id) }}" class="mt-4">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <select name="status" class="p-2 border rounded w-full">
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                            </select>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Update</button>
                        </div>
                    </form>
                @else
                    <p class="text-gray-400 text-sm mt-2">No Action Needed</p>
                @endif
            </div>
        @endforeach
    </div>
</div>

@endsection
