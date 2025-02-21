@extends('main_resources.index')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded-lg">
    <h2 class="text-2xl font-bold mb-4">Admin Dashboard - Leave Requests</h2>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-4 p-2 bg-green-100 border-l-4 border-green-500 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-100 p-4 rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Employee</th>
                    <th class="p-2 text-left">Leave Type</th>
                    <th class="p-2 text-left">Start Date</th>
                    <th class="p-2 text-left">End Date</th>
                    <th class="p-2 text-left">Status</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaves as $leave)
                    <tr class="border-b">
                        <td class="p-2">{{ $leave->user->name }}</td>
                        <td class="p-2">{{ $leave->leave_type }}</td>
                        <td class="p-2">{{ $leave->start_date }}</td>
                        <td class="p-2">{{ $leave->end_date }}</td>
                        <td class="p-2">
                            <span class="py-1 rounded 
                            @if ($leave->status === 'approved') 
                                text-green-600 
                            @elseif ($leave->status === 'pending') 
                                text-yellow-600 
                            @elseif ($leave->status === 'rejected') 
                                text-red-600 
                            @endif">
                            {{ $leave->status }}
                            </span>
                        </td>
                        <td class="p-2">
                            @if ($leave->status == 'pending')
                                <form method="POST" action="{{ route('admin.leave.update', $leave->id) }}">
                                    @csrf
                                    <select name="status" class="p-1 border rounded">
                                        <option value="Approved">Approve</option>
                                        <option value="Rejected">Reject</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Update</button>
                                </form>
                            @else
                                <span class="text-gray-500">No Action Needed</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
