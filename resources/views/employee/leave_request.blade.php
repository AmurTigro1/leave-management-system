@extends('layouts.sidebar-header')
@section('content')

    <!-- Success Message -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-green-700 bg-green-100 border border-green-500 rounded">
        {{ session('success') }}
    </div>
@endif

    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-4 mb-4 text-red-700 bg-red-100 border border-red-500 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<!-- Leave Request List -->
<div class=" overflow-hidden">
<!-- Leave Request List -->
<h3 class="text-2xl font-bold mt-6 text-gray-800">Your Leave Requests</h3>

<!-- Showing X out of Y -->
<p class="text-gray-600 text-sm mt-2">
    Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} leave requests
</p>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="w-full border-collapse">
        <thead>
            <tr class=" text-left">
                <th class="p-3">Type</th>
                <th class="p-3">Leave Details</th>
                <th class="p-3">Reason</th>
                <th class="p-3">Start Date</th>
                <th class="p-3">End Date</th>
                <th class="p-6">Status</th>
                <th class="p-3">Total Days</th>
                <th class="p-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition ">
                    <td class="p-3 font-medium">{{ $leave->leave_type }}</td>
                    <td class="p-3">
                        @php $details = json_decode($leave->leave_details, true); @endphp
                        @if($details)
                            <div class="flex flex-wrap gap-2">
                                @foreach($details as $key => $value)
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                                        {{ $key }}: {{ $value }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray-500">N/A</span>
                        @endif
                    </td>
                    <td class="p-3">{{ $leave->reason ?? 'No reason provided' }}</td>
                    <td class="p-3">{{ $leave->start_date }}</td>
                    <td class="p-3">{{ $leave->end_date }}</td>
                    {{-- < class="p-3">
                        <span class="px-3 py-1 rounded text-white text-sm
                        {{ $leave->status == 'approved' ? 'bg-green-500' : ($leave->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}">
                        {{ $leave->status }}
                        </span>
                    </> --}}
                    <td class="p-3">
                        @if ($leave->hr_status == 'approved' && $leave->supervisor_status == 'pending')
                            <span class="px-2 py-1 text-sm text-white bg-orange-500 rounded-lg">
                                Waiting for Supervisor Approval
                            </span>
                        @elseif ($leave->hr_status == 'approved' && $leave->supervisor_status == 'approved')
                            <span class="px-2 py-1 text-sm text-white bg-green-500 rounded-lg">
                                Approved
                            </span>
                        @elseif ($leave->hr_status == 'rejected' || $leave->supervisor_status == 'rejected')
                            <span class="px-2 py-1 text-sm text-white bg-red-500 rounded-lg">
                                Rejected
                            </span>
                        @else
                            <span class="px-2 py-1 text-sm text-white bg-yellow-500 rounded-lg">
                                Pending
                            </span>
                        @endif
                    </td>
                    
                    </td>
                    <td class="p-3">
                    <span class="ml-2">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</span>
                </td>
                <td class="p-3 flex space-x-2">
                    <a href="{{ route('employee.leave_show', ['id' => $leave->id]) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                        View
                    </a>
                
                    <a href="{{ route('employee.leave_edit', $leave->id) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                        Edit
                    </a>
                
                    <form action="{{ route('employee.leave_delete', $leave->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Are you sure you want to delete this leave request?')" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                            Delete
                        </button>
                    </form>
                </td>
                
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Edit Leave Modal -->
<div id="editModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-5 rounded shadow-lg">
        <h3 class="text-lg font-bold mb-3">Edit Leave Request</h3>
        <form id="editLeaveForm">
            <input type="hidden" id="editLeaveId">
            <label class="block">Start Date:</label>
            <input type="date" id="editStartDate" class="border p-2 w-full mb-2">
            <label class="block">End Date:</label>
            <input type="date" id="editEndDate" class="border p-2 w-full mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            <button type="button" onclick="closeEditModal()" class="ml-2 text-gray-500">Cancel</button>
        </form>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4 flex justify-between items-center">
    <p class="text-gray-600 text-sm">
        Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} leave requests
    </p>
    <div class="text-sm">
        {{ $leaves->appends(request()->query())->links() }}
    </div>
</div>
</div>

<script>
   
    function deleteLeave(id) {
        if (confirm("Are you sure you want to delete this leave request?")) {
            fetch(`/leave/delete/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 3000);
    }

    // Hide error message after 3 seconds
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 3000);
    }


</script>
@endsection