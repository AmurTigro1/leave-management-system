@extends('layouts.sidebar-header')

@section('content')
<!-- Top-Right Header -->
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="animate-fade-in">
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-6 ">
        <h3 class="text-2xl font-bold mb-3 text-gray-800">Your Overtime Requests</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr class="text-gray-600 text-sm bg-gray-100 border-b">
                    <th class="p-3 text-left">Date Filed</th>
                    <th class="p-3 text-left">Working Hours Applied</th>
                    <th class="p-3 text-left">Inclusive Date Start</th>
                    <th class="p-3 text-left">Inclusive Date End</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($overtimereq as $overtime)
                    <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F d, Y') }}</td>
                        <td class="p-3 text-gray-700">{{ $overtime->working_hours_applied}} hours</td>
                        <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->format('F d, Y') }}</td>
                        <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_end)->format('F d, Y') }}</td>
                        <td class="p-3">
                            @php
                                $status_classes = [
                                    'pending' => 'bg-yellow-500',
                                    'approved' => 'bg-green-500',
                                    'rejected' => 'bg-red-500',
                                    'waiting' => 'bg-orange-500',
                                    'cancelled' => 'bg-gray-500 line-through'
                                ];
                                $status = 'pending';
                                if ($overtime->hr_status == 'approved' && $overtime->supervisor_status == 'pending' && $overtime->admin_status == 'Ready for Review') {
                                    $status = 'waiting';
                                } elseif ($overtime->hr_status == 'approved' && $overtime->supervisor_status == 'approved') {
                                    $status = 'approved';
                                } elseif ($overtime->hr_status == 'rejected' || $overtime->supervisor_status == 'rejected') {
                                    $status = 'rejected';
                                } elseif ($overtime->status == 'cancelled' || $overtime->supervisor_status == 'cancelled') {
                                    $status = 'cancelled';
                                } 
                            @endphp
                            <span class="px-2 py-1 text-xs text-white rounded-lg {{ $status_classes[$status] }}">
                                {{ ucfirst($status) }}
                            </span>
                        </td>
                        <td class="p-3 flex space-x-2 justify-center"> 
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
                                    
                                    <a href="{{ route('cto.overtime_show', ['id' => $overtime->id]) }}" 
                                       class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        View
                                    </a>
                        
                                    <a href="{{ route('cto.overtime_edit', $overtime->id) }}" 
                                       class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Edit
                                    </a>
                        
                                    @if($overtime->status === 'pending')
                                    <form action="{{ route('employee.cto_cancel', $overtime->id) }}" 
                                          method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to cancel this CTO request?')"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Cancel Request
                                        </button>
                                    </form>
                                    @elseif($overtime->status === 'cancelled')
                                    <form action="{{ route('employee.cto_restore', $overtime->id) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                            onclick="return confirm('Do you want to restore this canceled request?')"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Restore Request
                                        </button>
                                    </form>
                                    @endif
                        
                                    <form action="{{ route('cto.overtime_delete', $overtime->id) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this overtime request?')"
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
    
    <!-- Pagination -->
    <div class="mt-4 flex justify-between items-center">
        <p class="text-gray-600 text-sm">
            Showing {{ $overtimereq->firstItem() }} to {{ $overtimereq->lastItem() }} of {{ $overtimereq->total() }} overtime requests
        </p>
        <div class="text-sm">
            {{ $overtimereq->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
   
    function deleteOvertime(id) {
        if (confirm("Are you sure you want to delete this overtime request?")) {
            fetch(`/overtime/delete/${id}`, {
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
<style>
                 .animate-fade-in {
                animation: fadeIn 0.8s ease-in-out;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
</style>
@endsection
