@extends('layouts.sidebar-header')
@section('content')

<div class="animate-fade-in">
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 sm:p-6">
        <h3 class="text-xl sm:text-2xl font-bold mb-3 text-gray-800">Your Leave Requests</h3>
        
        <!-- Mobile Card View -->
        <div class="sm:hidden space-y-3">
            @foreach ($leaves as $leave)
            <div class="border rounded-lg p-4 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $leave->leave_type }}</p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} - 
                            {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                        </p>
                    </div>
                    
                    @php
                        $status_classes = [
                            'pending' => 'bg-yellow-500',
                            'approved' => 'bg-green-500',
                            'rejected' => 'bg-red-500',
                            'waiting' => 'bg-orange-500',
                            'cancelled' => 'bg-gray-500 line-through'
                        ];
                        $status = 'pending';
                        if ($leave->admin_status == 'approved' && $leave->hr_status == 'pending') {
                            $status = 'waiting';
                        } elseif ($leave->admin_status == 'rejected' || $leave->hr_status == 'rejected' || $leave->supervisor_status == 'rejected') {
                            $status = 'rejected';
                        } elseif ($leave->hr_status == 'approved' && $leave->supervisor_status == 'approved') {
                            $status = 'approved';
                        } elseif ($leave->status == 'cancelled' || $leave->supervisor_status == 'cancelled') {
                            $status = 'cancelled';
                        }
                    @endphp
                    <span class="px-2 py-1 text-xs text-white rounded-lg {{ $status_classes[$status] }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                
                <div class="mt-2 text-sm text-gray-600">
                    <p><span class="font-medium">Days:</span> {{ $leave->days_applied }}</p>
                    <p class="mt-1"><span class="font-medium">Reason:</span> {{ $leave->reason ?? 'N/A' }}</p>
                </div>
                
                <!-- Leave Details (Mobile) -->
                @php $details = json_decode($leave->leave_details, true); @endphp
                @if($details)
                <div class="mt-2">
                    <p class="text-xs font-medium text-gray-700">Details:</p>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @foreach($details as $key => $value)
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-xs">
                                {{ $key }}: {{ $value }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Mobile Actions -->
                <div class="mt-3 pt-2 border-t">
                    <div class="flex space-x-2">
                        <a href="{{ route('employee.leave_show', ['id' => $leave->id]) }}" 
                           class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200">
                            View
                        </a>
                        <a href="{{ route('employee.leave_edit', $leave->id) }}" 
                           class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hover:bg-gray-200">
                            Edit
                        </a>
                        @if($leave->status === 'pending')
                        <form action="{{ route('employee.leave_cancel', $leave->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to cancel this leave request?')"
                                    class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded hover:bg-yellow-200">
                                Cancel
                            </button>
                        </form>
                        @elseif($leave->status === 'cancelled')
                        <form action="{{ route('employee.leave_restore', $leave->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                onclick="return confirm('Do you want to restore this canceled request?')"
                                class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded hover:bg-green-200">
                                Restore
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('employee.leave_delete', $leave->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this leave request?')"
                                    class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded hover:bg-red-200">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden sm:block">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-gray-600 text-sm bg-gray-100 border-b">
                            <th class="p-3 text-left">Type</th>
                            <th class="p-3 text-left">Leave Details</th>
                            <th class="p-3 text-left">Reason</th>
                            <th class="p-3 text-left">Date Start</th>
                            <th class="p-3 text-left">Date End</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Days</th>
                            <th class="p-3 text-center">Actions</th>
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
                                <td class="p-3 text-gray-700 max-w-xs truncate">{{ $leave->reason ?? 'No reason provided' }}</td>
                                <td class="p-3 text-gray-700 whitespace-nowrap">{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                <td class="p-3 text-gray-700 whitespace-nowrap">{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                <td class="p-3 whitespace-nowrap">
                                    @php
                                        $status_classes = [
                                            'pending' => 'bg-yellow-500',
                                            'approved' => 'bg-green-500',
                                            'rejected' => 'bg-red-500',
                                            'waiting' => 'bg-orange-500',
                                            'cancelled' => 'bg-gray-500 line-through'
                                        ];
                                        $status = 'pending';
                                        if ($leave->admin_status == 'approved' && $leave->hr_status == 'pending') {
                                            $status = 'waiting';
                                        } elseif ($leave->admin_status == 'rejected' || $leave->hr_status == 'rejected' || $leave->supervisor_status == 'rejected') {
                                            $status = 'rejected';
                                        } elseif ($leave->hr_status == 'approved' && $leave->supervisor_status == 'approved') {
                                            $status = 'approved';
                                        } elseif ($leave->status == 'cancelled' || $leave->supervisor_status == 'cancelled') {
                                            $status = 'cancelled';
                                        }
                                    @endphp
                                    <span class="px-2 py-1 text-xs text-white rounded-lg {{ $status_classes[$status] }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="p-3 text-gray-800">{{ $leave->days_applied }}</td>
                                <td class="p-3 text-center relative">
                                    <!-- Three-dot menu button -->
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = !open" 
                                                class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" 
                                                      d="M12 6h.01M12 12h.01M12 18h.01" />
                                            </svg>
                                        </button>
                                
                                        <!-- Dropdown menu -->
                                        <div x-show="open" @click.away="open = false" 
                                        class="fixed transform -translate-x-1/2 mt-2 w-40 bg-white border rounded-lg shadow-lg z-50">
                                            
                                            <a href="{{ route('employee.leave_show', ['id' => $leave->id]) }}" 
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                View
                                            </a>
                                
                                            <a href="{{ route('employee.leave_edit', $leave->id) }}" 
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Edit
                                            </a>
                                
                                            @if($leave->status === 'pending')
                                            <form action="{{ route('employee.leave_cancel', $leave->id) }}" 
                                                  method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to cancel this leave request?')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Cancel Request
                                                </button>
                                            </form>
                                            @elseif($leave->status === 'cancelled')
                                            <form action="{{ route('employee.leave_restore', $leave->id) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" 
                                                    onclick="return confirm('Do you want to restore this canceled request?')"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Restore Request
                                                </button>
                                            </form>
                                            @endif
                                
                                            <form action="{{ route('employee.leave_delete', $leave->id) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('Are you sure you want to delete this leave request?')"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
        <p class="text-gray-600 text-xs sm:text-sm">
            Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} requests
        </p>
        <div class="text-xs sm:text-sm">
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

    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 3000);
    }
</script>

<style>
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection