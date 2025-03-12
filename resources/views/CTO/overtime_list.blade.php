@extends('layouts.sidebar-header')

@section('content')

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
    <h3 class="text-2xl font-bold mb-3 text-gray-800">Your Overtime Requests</h3>
    <table class="w-full border-collapse">
        <thead>
            <tr class="text-gray-600 text-sm bg-gray-100 border-b">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Date Filled</th>
                <th class="p-3 text-left">Working Hours</th>
                <th class="p-3 text-left">Inclusive Date Start</th>
                <th class="p-3 text-left">Inclusive Date End</th>
                <th class="p-3 text-left">Status</th>
                <th class="p-3 text-left">Earned Hours</th>
                <th class="p-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($overtimereq as $overtime)
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                    <td class="p-3 font-medium text-gray-800">{{ $overtime->id}}</td>
                    <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F d, Y') }}</td>
                    <td class="p-3 text-gray-700">{{ $overtime->working_hours_applied}}</td>
                    <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->format('F d, Y') }}</td>
                    <td class="p-3 text-gray-700">{{ \Carbon\Carbon::parse($overtime->inclusive_date_end)->format('F d, Y') }}</td>
                    <td class="p-3">
                        @php
                            $status_classes = [
                                'pending' => 'bg-yellow-500',
                                'approved' => 'bg-green-500',
                                'rejected' => 'bg-red-500',
                                'waiting' => 'bg-orange-500',
                            ];
                            $status = 'pending';
                            if ($overtime->hr_status == 'approved' && $overtime->supervisor_status == 'pending') {
                                $status = 'waiting';
                            } elseif ($overtime->hr_status == 'approved' && $overtime->supervisor_status == 'approved') {
                                $status = 'approved';
                            } elseif ($overtime->hr_status == 'rejected' || $overtime->supervisor_status == 'rejected') {
                                $status = 'rejected';
                            }
                        @endphp
                        <span class="px-2 py-1 text-xs text-white rounded-lg {{ $status_classes[$status] }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="p-3 text-gray-700">{{ $overtime->earned_hours}}</td>
                    <td class="p-3 flex space-x-2 justify-center">
                        <a href="{{ route('cto.overtime_show', ['id' => $overtime->id]) }}" 
                           class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            View
                        </a>
                        {{-- <a href="{{ route('cto.overtime_edit', $overtime->id) }}" 
                           class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                            Edit
                        </a>
                        <form action="{{ route('cto.overtime_delete', $overtime->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Are you sure you want to delete this overtime request?')" 
                                    class="px-4 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                Delete
                            </button>
                        </form> --}}
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

{{-- <script>
   
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


</script> --}}
@endsection