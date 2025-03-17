@extends('layouts.hr.sidebar-header')

@section('content')
<div class="max-w-6xl bg-white p-8 rounded-">
    <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
        <i class="lucide lucide-file-text"></i> Review Leave Applications
    </h2>

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
                        <p class="text-md font-semibold text-gray-800">{{ $leave->user->first_name }} {{$leave->user->last_name}}</p>
                        <p class="text-xs text-gray-500">{{ $leave->leave_type }} - {{ $leave->days_applied }} Days</p>
                        <p class="text-gray-700 text-xs rounded-md">
                            Date of filing:
                            {{ \Carbon\Carbon::parse($leave->date_filing)->format('F j, Y') }}
                        </p>  
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

                    <div class="flex gap-2">
                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 text-xs rounded-lg transition">
                        Submit
                    </button>
                    <a href="{{ route('hr.leave_details', ['id' => $leave->id]) }}" 
                        class="px-4 w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 text-xs rounded-lg transition">
                         View
                     </a>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    @endif
        <!-- Pagination -->
        <div class="mt-4">
            <p class="text-gray-600 text-sm">
                Showing {{ $leaveApplications->firstItem() }} to {{ $leaveApplications->lastItem() }} of {{ $leaveApplications->total() }} Leave Applications
            </p>
           <div class="mt-4 flex justify-end">
    @if ($leaveApplications->hasPages())
        <nav class="flex space-x-2">
            {{-- Previous Page Link --}}
            @if ($leaveApplications->onFirstPage())
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                    &larr; Prev
                </span>
            @else
                <a href="{{ $leaveApplications->previousPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                    &larr; Prev
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($leaveApplications->getUrlRange(1, $leaveApplications->lastPage()) as $page => $url)
                @if ($page == $leaveApplications->currentPage())
                    <span class="px-4 py-2 bg-blue-500 text-white rounded-md">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($leaveApplications->hasMorePages())
                <a href="{{ $leaveApplications->nextPageUrl() }}" class="px-4 py-2 text-gray-700 bg-white border rounded-md hover:bg-gray-100">
                    Next &rarr;
                </a>
            @else
                <span class="px-4 py-2 text-gray-400 bg-gray-100 rounded-md cursor-not-allowed">
                    Next &rarr;
                </span>
            @endif
        </nav>
    @endif
</div>

        </div>
    
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
