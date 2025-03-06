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

<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <!-- Leave Request List -->
<h3 class="text-2xl font-bold text-gray-800">Your Leave Requests</h3>


    <table class="w-full border-collapse">
        <thead>
            <tr class="text-gray-600 text-sm bg-gray-100 border-b">
                <th class="p-3 text-left">Type</th>
                <th class="p-3 text-left">Leave Details</th>
                <th class="p-3 text-left">Reason</th>
                <th class="p-3 text-left">Start Date</th>
                <th class="p-3 text-left">End Date</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Total Days</th>
                <th class="p-3 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                    <td class="p-3 font-medium text-gray-800">{{ $leave->leave_type }}</td>
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
                    <td class="p-3 text-gray-700">{{ $leave->reason ?? 'No reason provided' }}</td>
                    <td class="p-3 text-gray-700">{{ $leave->start_date }}</td>
                    <td class="p-3 text-gray-700">{{ $leave->end_date }}</td>
                    <td class="p-3">
                        @php
                            $status_classes = [
                                'pending' => 'bg-yellow-500',
                                'approved' => 'bg-green-500',
                                'rejected' => 'bg-red-500',
                                'waiting' => 'bg-orange-500',
                            ];
                            $status = 'pending';
                            if ($leave->hr_status == 'approved' && $leave->supervisor_status == 'pending') {
                                $status = 'waiting';
                            } elseif ($leave->hr_status == 'approved' && $leave->supervisor_status == 'approved') {
                                $status = 'approved';
                            } elseif ($leave->hr_status == 'rejected' || $leave->supervisor_status == 'rejected') {
                                $status = 'rejected';
                            }
                        @endphp
                        <span class="px-2 py-1 text-xs text-white rounded-lg {{ $status_classes[$status] }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="p-3 text-gray-800">
                        {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}
                    </td>
                    <td class="p-3 flex space-x-2 justify-center">
                        <a href="{{ route('employee.leave_show', ['id' => $leave->id]) }}" 
                           class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            View
                        </a>
                        <a href="{{ route('employee.leave_edit', $leave->id) }}" 
                           class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                            Edit
                        </a>
                        <form action="{{ route('employee.leave_delete', $leave->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this leave request?')" 
                                    class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
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