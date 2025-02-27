@extends('layouts.admin.sidebar-header
')

@section('content')
<div class="p-12 space-y-6">
    <h2 class="text-xl font-bold mb-4">Final Approval for Leave Applications</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach ($leaveApplications as $leave)
    <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105 hover:shadow-xl">
        <h3 class="text-lg font-semibold text-gray-900 uppercase">{{ $leave->user->name }}</h3>
        <p class="text-gray-600 text-sm">Leave Type: {{ $leave->leave_type }}</p>
        <p class="text-gray-700 mt-2 text-sm">Reason: {{ $leave->reason }}</p>
        <p class="text-gray-500 text-sm mt-2">
            From: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->format('l, F j, Y') }}</span>
            <br>
            To: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->end_date)->format('l, F j, Y') }}</span>
        </p>
        <p class="text-gray-700 mt-2">Duration: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} days</span></p>

        <form action="{{ route('supervisor.approve', $leave->id) }}" method="POST">
            @csrf
            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:scale-105">Approve</button>
        </form>
    </div>
    @endforeach
    </div>
</div>
@endsection
