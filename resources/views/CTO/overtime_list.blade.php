@extends('CTO.layouts.sidebar-header')

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
<h3 class="text-2xl font-bold mt-6 text-gray-800">Your Overtime Requests</h3>

<!-- Showing X out of Y -->
{{-- <p class="text-gray-600 text-sm mt-2">
    Showing {{ $overtimereq->firstItem() }} to {{ $overtimereq->lastItem() }} of {{ $overtimereq->total() }} overtime requests
</p> --}}

<div class="bg-white shadow-md rounded-lg overflow-hidden p-6">
    <table class="w-full border-collapse">
        <thead>
            <tr class="text-gray-600 text-sm bg-gray-100 border-b">
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Date Filled</th>
                <th class="p-3 text-left">Working Hours</th>
                <th class="p-3 text-left">Inclusive Date Start</th>
                <th class="p-3 text-left">Inclusive Date End</th>
                <th class="p-3 text-left">Earned Hours</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach ($overtimereq as $overtime) --}}
                <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                    <td class="p-3 font-medium text-gray-800">1</td>
                    <td class="p-3 text-gray-700">March 4, 2025</td>
                    <td class="p-3 text-gray-700">24 Hours</td>
                    <td class="p-3 text-gray-700">March 5, 2025</td>
                    <td class="p-3 text-gray-700">April 5, 2025</td>
                    <td class="p-3 text-gray-700">12</td>
                </tr>
            {{-- @endforeach --}}
        </tbody>
    </table>
</div>

<!-- Pagination -->
{{-- <div class="mt-4 flex justify-between items-center">
    <p class="text-gray-600 text-sm">
        Showing {{ $overtimereq->firstItem() }} to {{ $overtimereq->lastItem() }} of {{ $overtimereq->total() }} leave requests
    </p>
    <div class="text-sm">
        {{ $overtimereq->appends(request()->query())->links() }}
    </div>
</div> --}}
@endsection