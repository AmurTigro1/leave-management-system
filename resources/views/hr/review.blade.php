@extends('layouts.sidebar-header')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
        <i class="lucide lucide-file-text"></i> Review Leave Applications
    </h2>

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

    <!-- Check if there are any leave applications -->
    @if($leaveApplications->isEmpty())
        <div class="text-center py-10 text-gray-500">
            <i class="lucide lucide-folder-x w-12 h-12 mx-auto"></i>
            <p class="mt-2 text-lg">No leave applications found.</p>
        </div>
    @else
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
                        <p class="text-md font-semibold text-gray-800">{{ $leave->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $leave->leave_type }} - {{ $leave->days_applied }} Days</p>
                    </div>
                </div>

                <p class="text-gray-600 mb-2"><strong>Status:</strong> 
                    <span class="px-2 py-1 text-white text-xs rounded 
                        {{ $leave->status === 'pending' ? 'bg-yellow-500' : ($leave->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                        {{ ucfirst($leave->status) }}
                    </span>
                </p>

                <form action="{{ route('leave.review', $leave->id) }}" method="POST" class="space-y-2">
                    @csrf

                    <!-- Approval Status -->
                    <label class="block text-gray-700 font-medium text-xs">Approval:</label>
                    <select name="status" class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200" required>
                        <option value="Approved">Approve</option>
                        <option value="Rejected">Reject</option>
                    </select>

                    <!-- Disapproval Reason -->
                    <label class="block text-gray-700 font-medium text-xs">Disapproval Reason:</label>
                    <textarea name="disapproval_reason" class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200"></textarea>

                    <!-- Approved Days With Pay -->
                    <label class="block text-gray-700 font-medium text-xs">Days with Pay:</label>
                    <input type="number" name="approved_days_with_pay" class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200">

                    <!-- Approved Days Without Pay -->
                    <label class="block text-gray-700 font-medium text-xs">Days without Pay:</label>
                    <input type="number" name="approved_days_without_pay" class="w-full border rounded p-2 text-xs focus:ring focus:ring-blue-200">

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 text-xs rounded-lg transition">
                        Submit
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Hide Alerts after a few seconds -->
<script>
    setTimeout(() => {
        document.getElementById('success-alert')?.remove();
        document.getElementById('error-alert')?.remove();
    }, 4000);
</script>

<!-- Add Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
