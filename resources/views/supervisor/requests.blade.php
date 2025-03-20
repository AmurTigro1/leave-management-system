@extends('layouts.supervisor.sidebar-header')

@section('content')
<div class="">
    <h2 class="text-xl font-bold mb-4">Final Approval for Leave Applications</h2>
  <!-- Success & Error Messages -->
  @if(session('success'))
  <div id="success-alert" class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-md">
      <strong>Success!</strong> {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div id="error-alert" class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-md">
      <strong>Error!</strong> {{ session('error') }}
  </div>
@endif
{{-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
    </div> --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($leaveApplications as $leave)
        <div class="bg-gray-100 p-4 rounded-lg shadow-md text-sm">
            <div class="flex items-center gap-3 border-b pb-3 mb-3">
                <div class="bg-blue-500 text-white w-10 h-10 flex items-center justify-center rounded-full text-lg font-bold overflow-hidden">
                    <img src="{{ asset('storage/profile_images/' . $leave->user->profile_image) }}" 
                         alt="User Profile" 
                         class="w-full h-full object-cover rounded-full">
                </div>
                
                <div>
                    <p class="text-md font-semibold text-gray-800">{{ $leave->user->first_name }} {{ $leave->user->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ $leave->leave_type }} - {{ $leave->days_applied }} Days</p>
                </div>
            </div>

            <p class="text-gray-600 mb-2"><strong>Status:</strong> 
                <span class="px-2 py-1 text-white text-xs rounded 
                    {{ $leave->status === 'pending' ? 'bg-yellow-500' : ($leave->status === 'approved' ? 'bg-green-500' : 'bg-yellow-500') }}">
                    {{ ucfirst($leave->status) }}
                </span>
            </p>
            
            <p class="text-gray-700 mt-2 text-sm">Reason: {{ $leave->reason }}</p>
            <p class="text-gray-500 text-sm mt-2">
                From: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->format('l, F j, Y') }}</span>
                <br>
                To: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->end_date)->format('l, F j, Y') }}</span>
            </p>
            <p class="text-gray-700 mt-2">Duration: <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }} day(s)</span></p>
    
            <form action="{{ route('supervisor.approve', $leave->id) }}" method="POST">
                @csrf
                <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:scale-105">Approve</button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection

<script>
    setTimeout(() => {
        document.getElementById('success-alert')?.remove();
        document.getElementById('error-alert')?.remove();
    }, 4000);
</script>