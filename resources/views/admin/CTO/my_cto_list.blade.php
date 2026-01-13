@extends('layouts.admin.sidebar-header')

@section('content')
@include('admin.CTO.modal.my_cto_edit', ['overtime' => $overtime])
@include('admin.CTO.modal.admin_cto_cancel_request', ['overtime' => $overtime])
@include('admin.CTO.modal.admin_cto_restore_request', ['overtime' => $overtime])
@include('admin.CTO.modal.admin_cto_delete_request', ['overtime' => $overtime])
<x-notify::notify />

<div class="animate-fade-in">
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-4 sm:p-6">
        <h3 class="text-xl sm:text-2xl font-bold mb-3 text-gray-800">Your CTO Requests</h3>

        <div class="sm:hidden space-y-3">
            @foreach ($overtimereq as $overtime)
            <div class="border rounded-lg p-4 bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($overtime->date_filed)->format('M d, Y') }}
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $overtime->working_hours_applied }} hours
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

                        if ($overtime->status == 'cancelled' || $overtime->supervisor_status == 'cancelled') {
                            $status = 'cancelled';
                        } elseif ($overtime->hr_status == 'approved' && $overtime->supervisor_status == 'pending' && $overtime->admin_status == 'Ready for Review') {
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
                </div>

                <div class="mt-2 text-sm text-gray-600">
                    <p class="font-medium">Dates:</p>
                    {{$overtime->inclusive_dates}}
                </div>

                <div class="mt-3 pt-2 border-t">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.my_cto_requests', ['id' => $overtime->id]) }}"
                           class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-200">
                            View
                        </a>

                        <button onclick="openmyCocRequestModal()" class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded hover:bg-gray-200">
                            Edit
                        </button>
                        @if($overtime->status === 'pending')
                        <button type="button" onclick="openCancelCtoModal({{ $overtime->id }})"
                            class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded hover:bg-yellow-200">
                        Cancel
                        </button>

                        @elseif($overtime->status === 'cancelled')
                        <button type="button" onclick="openRestoreCtoModal({{ $overtime->id }})"
                            class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded hover:bg-green-200">
                            Restore
                        </button>
                        @endif
                        <button type="button" onclick="openDeleteCtoModal({{ $overtime->id }})"
                                class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded hover:bg-red-200">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="hidden sm:block">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-gray-600 text-sm bg-gray-100 border-b">
                            <th class="p-3 text-left">Date Filed</th>
                            <th class="p-3 text-left">Working Hours Applied</th>
                            <th class="p-3 text-left">Dates</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($overtimereq as $overtime)
                            <tr class="border-b even:bg-gray-50 hover:bg-gray-100 transition">
                                <td class="p-3 text-gray-700 whitespace-nowrap">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('M d, Y') }}</td>
                                <td class="p-3 text-gray-700">{{ $overtime->working_hours_applied}} hours</td>
                                <td class="p-3 text-gray-700 text-xs">
                                    {{$overtime->inclusive_dates}}
                                </td>
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

                                        if ($overtime->status == 'cancelled' || $overtime->supervisor_status == 'cancelled') {
                                            $status = 'cancelled';
                                        } elseif ($overtime->hr_status == 'approved' && $overtime->supervisor_status == 'pending' && $overtime->admin_status == 'Ready for Review') {
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
                                <td class="p-3 flex space-x-2 justify-center">
                                    <div x-data="{ open: false }" class="relative inline-block">
                                        <button @click="open = !open"
                                                class="text-gray-600 hover:text-gray-900 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M12 6h.01M12 12h.01M12 18h.01" />
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open = false"
                                        class="fixed transform -translate-x-1/2 mt-2 w-40 bg-white border rounded-lg shadow-lg z-50">
                                            @if($overtime->status === 'cancelled')
                                                @if($overtime->status === 'pending')
                                                    <button type="button" onclick="openCancelCtoModal({{ $overtime->id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Cancel Request
                                                    </button>
                                                    @elseif($overtime->status === 'cancelled')
                                                    <button type="button" onclick="openRestoreCtoModal({{ $overtime->id }})"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        Restore Request
                                                    </button>
                                                @endif

                                                <button type="button" onclick="openDeleteCtoModal({{ $overtime->id }})"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                    Delete
                                                </button>
                                            @else
                                            <a href="{{ route('admin.my_cto_requests', ['id' => $overtime->id]) }}"
                                               class="block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                View
                                            </a>

                                            <button onclick="openmyCocRequestModal()" class="w-full block text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Edit
                                            </button>

                                            @if($overtime->status === 'pending')
                                                <button type="button" onclick="openCancelCtoModal({{ $overtime->id }})"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Cancel Request
                                                </button>
                                                @elseif($overtime->status === 'cancelled')
                                                <button type="button" onclick="openRestoreCtoModal({{ $overtime->id }})"
                                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    Restore Request
                                                </button>
                                                @endif

                                                <button type="button" onclick="openDeleteCtoModal({{ $overtime->id }})"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                    Delete
                                                </button>
                                            @endif
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

    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
        <p class="text-gray-600 text-xs sm:text-sm">
            Showing {{ $overtimereq->firstItem() }} to {{ $overtimereq->lastItem() }} of {{ $overtimereq->total() }} requests
        </p>
        <div class="text-xs sm:text-sm">
            {{ $overtimereq->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
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
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 640px) {
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
@endsection
