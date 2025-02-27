@extends('main_resources.index')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4">Review Leave Applications</h2>

    @foreach ($leaveApplications as $leave)
    <div class="border p-4 rounded mb-4">
        <p><strong>Employee:</strong> {{ $leave->user->name }}</p>
        <p><strong>Leave Type:</strong> {{ $leave->leave_type }}</p>
        <p><strong>Days Applied:</strong> {{ $leave->days_applied }}</p>
        <p><strong>Status:</strong> {{ $leave->status }}</p>

        <form action="{{ route('leave.review', $leave->id) }}" method="POST">
            @csrf
            <label class="block mt-2">Approval:</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="Approved">Approve</option>
                <option value="Rejected">Reject</option>
            </select>

            <label class="block mt-2">Disapproval Reason (if rejected):</label>
            <textarea name="disapproval_reason" class="w-full border p-2 rounded"></textarea>

            <label class="block mt-2">Approved Days with Pay:</label>
            <input type="number" name="approved_days_with_pay" class="w-full border p-2 rounded">

            <label class="block mt-2">Approved Days without Pay:</label>
            <input type="number" name="approved_days_without_pay" class="w-full border p-2 rounded">

            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Submit Review</button>
        </form>
    </div>
    @endforeach
</div>
@endsection
