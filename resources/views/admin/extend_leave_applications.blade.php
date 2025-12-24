@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="animate-fade-in p-6">
        <h1 class="text-2xl font-semibold mb-4">
            Admin Extend Leave Applications
        </h1>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex gap-4">
            <div>
                <label class="block text-sm">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="border rounded px-2 py-1">
            </div>
            <div>
                <label class="block text-sm">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="border rounded px-2 py-1">
            </div>
            <div class="flex items-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Filter
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-gray-500 text-medium border p-3 text-left">Employee</th>
                        <th class="text-gray-500 border p-3 text-left">Department</th>
                        <th class="text-gray-500 border p-3 text-left">Leave Dates</th>
                        <th class="text-gray-500 border p-3 text-left">Days Applied</th>
                        <th class="text-gray-500 border p-3 text-left">Filed Date</th>
                        <th class="text-gray-500 border p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $applications = [
                            [
                                'employee' => 'Jeferson Bayking',
                                'department' => 'IT Department', 
                                'start_date' => '2025-01-10',
                                'end_date' => '2025-01-12',
                                'days' => '3',
                                'filed' => '2025-01-05',
                                'details' => [ 
                                    'type' => 'Special Privilege Leave',
                                    'leave_details' => 'WITHIN THE PHILIPPINES:
                                    BOHOL',
                                    'reason' => 'No reason provided',
                                    'status' => 'Pending',
                                    'days_applied' => 3
                                ]
                            ],
                            [
                                'employee' => 'Dan Aguillon',
                                'department' => 'CCJ Department',
                                'start_date' => '2025-01-15', 
                                'end_date' => '2025-01-18',
                                'days' => '4',
                                'filed' => '2025-01-03',
                                'details' => [  
                                    'type' => 'Special Privilege Leave',
                                    'leave_details' => 'Family vacation in Boracay',
                                    'reason' => 'No reason provided',
                                    'status' => 'Approved',
                                    'days_applied' => 4
                                    
                                ],
                            ],
                            [
                                'employee' => 'Jose Victor Alampayan',
                                'department' => 'HM Department',
                                'start_date' => '2025-01-15', 
                                'end_date' => '2025-01-18',
                                'days' => '4',
                                'filed' => '2025-01-03',
                                'details' => [  
                                    'type' => 'Special Privilege Leave',
                                    'leave_details' => 'Family emergency in province',
                                    'reason' => 'Need to attend to a family emergency at the province',
                                    'status' => 'Pending',
                                    'days_applied' => 4
                                ]
                            ]
                        ];
                    @endphp

                    @foreach($applications as $index => $app)
                    <tr>
                        <td class="border p-3">
                            {{ $app['employee'] }}
                        </td>
                        <td class="border p-3">
                            {{ $app['department'] }}
                        </td>
                        <td class="border p-3">
                            {{ $app['start_date'] }} → {{ $app['end_date'] }}
                        </td>
                        <td class="border p-3">
                            {{ $app['days'] }}
                        </td>
                        <td class="border p-3">
                            {{ $app['filed'] }}
                        </td>
                        <td class="border p-3 text-center">
                            <button
                                type="button"
                                onclick="openViewModal({{ $index }})"
                                class="text-blue-600 hover:text-blue-800 hover:underline"
                            >
                                View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $leaveApplications->links() }}
        </div>

        {{-- Modal Template --}}
        <div id="modalTemplate" class="hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Leave Application Details</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            {{-- Basic Info --}}
            <div class="mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold text-gray-600">Employee</p>
                        <p class="text-lg" id="modalEmployee"></p>
                    </div>  
                </div>
            </div>

            {{-- Details Table --}}
            <div class="mb-6 overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-gray-500 text-medium border p-3 text-left">Type</th>
                            <th class="text-gray-500 border p-3 text-left">Leave Details</th>
                            <th class="text-gray-500 border p-3 text-left">Reason</th>
                            <th class="text-gray-500 border p-3 text-left">Start Date</th>
                            <th class="text-gray-500 border p-3 text-left">End Date</th>
                            <th class="text-gray-500 border p-3 text-left">Status</th>
                            <th class="text-gray-500 border p-3 text-left">Days Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border p-3 align-top w-52" id="modalType"></td>
                            <td class="border p-3 align-top w-52 text-xs text-blue-500" id="modalLeaveDetails"></td>
                            <td class="border p-3 align-top w-52" id="modalReason"></td>
                            <td class="border p-3 align-top w-52" id="modalstartdate"></td>
                            <td class="border p-3 align-top w-52" id="modalenddate"></td>
                            <td class="border p-3">
                                <span id="modalStatus" class="px-2 py-1 rounded text-sm"></span>
                            </td>
                            <td class="border p-3" id="modalDaysApplied"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-end gap-2 pt-4 border-t">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>
@endsection

{{-- Modal Container --}}
<div id="simpleModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-lg max-w-6xl p-6 mx-4 max-h-[90vh] overflow-y-auto" id="modalContentContainer">
        <!-- Content will be inserted here -->
    </div>
</div>

<script>    
const applications = @json($applications);

function openViewModal(index) {
    const app = applications[index];
    const details = app.details || {}; 
    
    const template = document.getElementById('modalTemplate');
    const modalContent = template.cloneNode(true);
    modalContent.classList.remove('hidden');
    
    modalContent.querySelector('#modalEmployee').textContent = app.employee;    
    modalContent.querySelector('#modalType').textContent = details.type || 'No reason provided';
    modalContent.querySelector('#modalLeaveDetails').textContent = details.leave_details || 'No reason provided';
    modalContent.querySelector('#modalReason').textContent = details.reason || 'No reason provided';
    modalContent.querySelector('#modalstartdate').textContent = app.start_date
    modalContent.querySelector('#modalenddate').textContent = app.end_date
    modalContent.querySelector('#modalDaysApplied').textContent = details.days_applied || app.days;
    
    const statusElement = modalContent.querySelector('#modalStatus');
    const status = details.status || 'Pending';
    statusElement.textContent = status;
    
    if (status.toLowerCase() === 'approved') {
        statusElement.classList.add('bg-green-100', 'text-green-800');
    } else if (status.toLowerCase() === 'rejected') {
        statusElement.classList.add('bg-red-100', 'text-red-800');
    } else if (status.toLowerCase() === 'pending') {
        statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
    } else {
        statusElement.classList.add('bg-gray-100', 'text-gray-800');
    }
    
    const container = document.getElementById('modalContentContainer');
    container.innerHTML = '';
    container.appendChild(modalContent);

    document.getElementById('simpleModal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    document.getElementById('simpleModal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('simpleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('simpleModal').classList.contains('hidden')) {
        closeModal();
    }
});
</script>