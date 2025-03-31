@extends('layouts.supervisor.sidebar-header')

@section('content')
<div class="animate-fade-in">
    <h2 class="text-xl font-bold mb-4">Recent Request recommended by HR</h2>
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
    @if($leaveApplications->isEmpty())
        <div class="text-gray-600 mt-4">
            No Leave Applications found.
        </div>
    @else
    <h1>Leave Applications</h1>
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
        
                <iframe src="" frameborder="0"></iframe>
                
                @if($leave->leave_type == 'Mandatory Leave')
                    <form action="{{ route('supervisor.reject', $leave->id) }}" method="POST">
                        @csrf
                       <div class="flex justify-center items-center">
                            <button type="button" id="rejectBtn" 
                                class="bg-orange-600 text-white py-2 px-4 rounded-lg mt-4">
                                Reject Request
                            </button>
                       </div>

                        <div id="disapprovalSection" class="mt-3 hidden h-auto">
                            <label class="block text-gray-700 font-medium text-xs">Disapproval Reason:</label>
                            <textarea name="disapproval_reason" id="disapproval_reason" 
                                class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200"></textarea>
                            
                            <div class="flex gap-2 mt-2">
                                <button type="submit" name="status" value="Rejected" id="finalRejectBtn"
                                    class="bg-red-600 text-white py-2 px-4 rounded-lg">
                                    Confirm Rejection
                                </button>
                                
                                <button type="button" id="cancelDisapprovalBtn" class="bg-gray-500 text-white py-2 px-4 rounded-lg">
                                    Cancel
                                </button>
                            </div>
                        </div> 
                    </form> 
                    
                    <script>
                        document.getElementById('rejectBtn').addEventListener('click', function() {
                            document.getElementById('disapprovalSection').classList.remove('hidden');
                        });
                    
                        document.getElementById('cancelDisapprovalBtn').addEventListener('click', function() {
                            document.getElementById('disapprovalSection').classList.add('hidden');
                            document.getElementById('disapproval_reason').value = ""; // Clear text area
                        });
                    </script>
                @endif
            </div>
            @endforeach
        </div>
    @endif
    @if($ctoApplications->isEmpty())
        <div class="text-gray-600 mt-4">
            No CTO Applications found.
        </div>
    @else
    <h1 class="mt-4">CTO Applications</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($ctoApplications as $cto)
            <div class="bg-gray-100 p-4 rounded-lg shadow-md text-sm">
                <div class="flex items-center gap-3 border-b pb-3 mb-3">
                    <div class="bg-blue-500 text-white w-10 h-10 flex items-center justify-center rounded-full text-lg font-bold overflow-hidden">
                        <img src="{{ asset('storage/profile_images/' . $cto->user->profile_image) }}" 
                            alt="User Profile" 
                            class="w-full h-full object-cover rounded-full">
                    </div>
                    
                    <div>
                        <p class="text-md font-semibold text-gray-800">{{ $cto->user->first_name }} {{ $cto->user->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ $cto->cto_type }} - {{ $cto->working_hours_applied }} Hours</p>
                    </div>
                </div>

                <p class="text-gray-600 mb-2"><strong>Status:</strong> 
                    <span class="px-2 py-1 text-white text-xs rounded 
                        {{ $cto->status === 'pending' ? 'bg-yellow-500' : ($cto->status === 'approved' ? 'bg-green-500' : 'bg-yellow-500') }}">
                        {{ ucfirst($cto->status) }}
                    </span>
                </p>
            
                <p class="text-gray-500 text-sm mt-2">
                    From: <span class="font-semibold">{{ \Carbon\Carbon::parse($cto->inclusive_date_start)->format('l, F j, Y') }}</span>
                    <br>
                    To: <span class="font-semibold">{{ \Carbon\Carbon::parse($cto->inclusive_date_end)->format('l, F j, Y') }}</span>
                </p>
                <p class="text-gray-700 mt-2">Duration: <span class="font-semibold">{{ round(\Carbon\Carbon::parse($cto->inclusive_date_start)->diffInDays(\Carbon\Carbon::parse($cto->inclusive_date_end)) + 1) }} day(s)</span></p>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

<script>
    setTimeout(() => {
        document.getElementById('success-alert')?.remove();
        document.getElementById('error-alert')?.remove();
    }, 4000);
</script>
<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>