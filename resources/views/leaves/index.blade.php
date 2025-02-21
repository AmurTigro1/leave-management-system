<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>leave system</title>
</head>
<body>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Leave Requests</h1>
        <table class="w-full table-auto border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border p-2">Employee</th>
                    <th class="border p-2">Start Date</th>
                    <th class="border p-2">End Date</th>
                    <th class="border p-2">Reason</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($leaves as $leave)
                <tr>
                    <td class="border p-2">{{ $leave->employee->name }}</td>
                    <td class="border p-2">{{ $leave->start_date }}</td>
                    <td class="border p-2">{{ $leave->end_date }}</td>
                    <td class="border p-2">{{ $leave->reason }}</td>
                    <td class="border p-2 text-{{ $leave->status == 'approved' ? 'green' : ($leave->status == 'rejected' ? 'red' : 'yellow') }}-500">{{ ucfirst($leave->status) }}</td>
                    <td class="border p-2">
                        @if($leave->status == 'pending')
                            <form method="POST" action="{{ route('leaves.approve', $leave->id) }}" class="inline-block">
                                @csrf @method('PATCH')
                                <button class="bg-green-500 text-white px-4 py-1 rounded">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('leaves.reject', $leave->id) }}" class="inline-block">
                                @csrf @method('PATCH')
                                <button class="bg-red-500 text-white px-4 py-1 rounded">Reject</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>