@extends('layouts.hr.sidebar-header')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg overflow-hidden">
        <div class="p-4 sm:p-6">
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-3 sm:p-4 mb-4 text-sm sm:text-base text-green-700 bg-green-100 border border-green-500 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="p-3 sm:p-4 mb-4 text-sm sm:text-base text-red-700 bg-red-100 border border-red-500 rounded">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 sm:mb-6 gap-3 sm:gap-4">
                <div class="order-1 md:order-none">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Employee Leave Balances</h1>
                    <p class="text-sm sm:text-base text-gray-600">Manage and update employee leave entitlements</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xs:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="bg-white rounded-lg shadow p-3 sm:p-4 border-l-4 border-blue-500">
                    <div class="text-xs sm:text-sm text-gray-500 font-medium">Total Employees</div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{($totalEmployees) }}</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 sm:p-4 border-l-4 border-green-500">
                    <div class="text-xs sm:text-sm text-gray-500 font-medium">Avg Vacation Leave</div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ number_format($employees->avg('vacation_leave_balance'), 2) }} days</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 sm:p-4 border-l-4 border-yellow-500">
                    <div class="text-xs sm:text-sm text-gray-500 font-medium">Avg Sick Leave</div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ number_format($employees->avg('sick_leave_balance'), 2) }} days</div>
                </div>
                <div class="bg-white rounded-lg shadow p-3 sm:p-4 border-l-4 border-purple-500">
                    <div class="text-xs sm:text-sm text-gray-500 font-medium">Active This Year</div>
                    <div class="text-xl sm:text-2xl font-bold text-gray-800 mt-1">{{ ($totalEmployees) }}</div>
                </div>
            </div>
            <div class="w-full md:w-auto order-0 md:order-none">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2" x-data="{ search: '' }">
                    <input 
                        type="text" 
                        x-model="search"
                        @input.debounce.500ms="fetchResults(search)"
                        placeholder="Search employees..."
                        class="flex-grow border border-gray-300 rounded-md px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500"
                    >
                    <button 
                        @click="search = ''; fetchResults('')"
                        class="bg-blue-500 text-white px-3 sm:px-4 py-2 rounded-md hover:bg-blue-600 transition text-sm sm:text-base">
                        Clear
                    </button>
                </div>
            </div>
            <!-- Search Results -->
            <div id="hr.employee-balances" class="overflow-x-auto">
                @include('hr.partials.employee-balances', ['employees' => $employees])
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:block sm:p-0">
        
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-3xl sm:my-8 sm:align-middle">
            
            <div class="bg-white px-4 py-6 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">
                    Edit Leave Balances for:
                </h3>

                <form id="balanceForm" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Regular Leaves -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-gray-700 border-b pb-2">Regular Leaves</h4>

                            <div>
                                <label for="vacation_leave_balance" class="block text-sm font-medium text-gray-700">Vacation Leave (days)</label>
                                <input type="number" step="0.01" name="vacation_leave_balance" id="vacation_leave_balance"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="mandatory_leave_balance" class="block text-sm font-medium text-gray-700">Mandatory Leave (days)</label>
                                <input type="number" step="0.01" name="mandatory_leave_balance" id="mandatory_leave_balance"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="sick_leave_balance" class="block text-sm font-medium text-gray-700">Sick Leave (days)</label>
                                <input type="number" step="0.01" name="sick_leave_balance" id="sick_leave_balance"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            {{-- <div>
                                <label for="overtime_balance" class="block text-sm font-medium text-gray-700">Overtime (hours)</label>
                                <input type="number" name="overtime_balance" id="overtime_balance"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div> --}}
                        </div>

                        <!-- Special Leaves -->
                        <div class="space-y-4">
                            <h4 class="text-md font-semibold text-gray-700 border-b pb-2">Special Leaves</h4>

                            <div>
                                <label for="maternity_leave" class="block text-sm font-medium text-gray-700">Maternity Leave (days)</label>
                                <input type="number" name="maternity_leave" id="maternity_leave"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="paternity_leave" class="block text-sm font-medium text-gray-700">Paternity Leave (days)</label>
                                <input type="number" name="paternity_leave" id="paternity_leave"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="solo_parent_leave" class="block text-sm font-medium text-gray-700">Solo Parent Leave (days)</label>
                                <input type="number" name="solo_parent_leave" id="solo_parent_leave"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="study_leave" class="block text-sm font-medium text-gray-700">Study Leave (days)</label>
                                <input type="number" name="study_leave" id="study_leave"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="vawc_leave" class="block text-sm font-medium text-gray-700">VAWC Leave (days)</label>
                                <input type="number" name="vawc_leave" id="vawc_leave"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- More Special Leaves -->
                        <div class="md:col-span-2 space-y-4">
                            <h4 class="text-md font-semibold text-gray-700 border-b pb-2">Other Leaves</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="rehabilitation_leave" class="block text-sm font-medium text-gray-700">Rehabilitation Leave (days)</label>
                                    <input type="number" name="rehabilitation_leave" id="rehabilitation_leave"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="special_leave_benefit" class="block text-sm font-medium text-gray-700">Special Leave Benefit (days)</label>
                                    <input type="number" name="special_leave_benefit" id="special_leave_benefit"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="special_privilege_leave" class="block text-sm font-medium text-gray-700">Special Privilege Leave (days)</label>
                                    <input type="number" name="special_privilege_leave" id="special_privilege_leave"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>

                                <div>
                                    <label for="special_emergency_leave" class="block text-sm font-medium text-gray-700">Special Emergency Leave (days)</label>
                                    <input type="number" name="special_emergency_leave" id="special_emergency_leave"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitForm()"
                    class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:ml-3 sm:text-sm">
                    Save Changes
                </button>
                <button type="button" onclick="closeModal()"
                    class="mt-3 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

</div>
<script>
    function fetchResults(searchTerm) {
        fetch(`{{ route('hr.employee-balances.index') }}?search=${searchTerm}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('hr.employee-balances').innerHTML = html;
        })
        .catch(error => console.error('Error:', error));
    }
</script>
<script>
    let currentEmployeeId = null;
    
    function openEditModal(employee) {
        currentEmployeeId = employee.id;
        
        document.getElementById('modalTitle').textContent = `Edit Leave Balances for ${employee.name}`;
        document.getElementById('balanceForm').action = `/hr/employee-balances/${employee.id}`; 
        document.getElementById('vacation_leave_balance').value = employee.vacation_leave_balance;
        document.getElementById('mandatory_leave_balance').value = employee.mandatory_leave_balance;
        document.getElementById('sick_leave_balance').value = employee.sick_leave_balance;
        // document.getElementById('overtime_balance').value = employee.overtime_balance;
        document.getElementById('maternity_leave').value = employee.maternity_leave;
        document.getElementById('paternity_leave').value = employee.paternity_leave;
        document.getElementById('solo_parent_leave').value = employee.solo_parent_leave;
        document.getElementById('study_leave').value = employee.study_leave;
        document.getElementById('vawc_leave').value = employee.vawc_leave;
        document.getElementById('rehabilitation_leave').value = employee.rehabilitation_leave;
        document.getElementById('special_leave_benefit').value = employee.special_leave_benefit;
        document.getElementById('special_privilege_leave').value = employee.special_privilege_leave;
        document.getElementById('special_emergency_leave').value = employee.special_emergency_leave;
        
        document.getElementById('editModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    function submitForm() {
    const submitButton = document.querySelector('#editModal button[type="button"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

    const form = document.getElementById('balanceForm');
    const formData = new FormData(form);
    
    const formDataObj = {};
    formData.forEach((value, key) => {
        formDataObj[key] = value;
    });

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            ...formDataObj,
            _method: 'PUT' 
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            closeModal();
            if (typeof showSuccessNotification === 'function') {
                showSuccessNotification(data.message);
            } else {
                alert(data.message);
            }
            
            updateEmployeeRow(currentEmployeeId, data.data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorMessage = error.message || 'Failed to update balances. Please try again.';
        if (typeof showErrorNotification === 'function') {
            showErrorNotification(errorMessage);
        } else {
            alert(errorMessage); 
        }
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
}

function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg';
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
    
    function updateEmployeeRow(employeeId, updatedData) {
        const row = document.querySelector(`tr[data-employee-id="${employeeId}"]`);
        if (!row) return;

        if (updatedData.vacation_leave_balance !== undefined && row.querySelector('.vacation-leave')) {
            const cell = row.querySelector('.vacation-leave');
            cell.textContent = `${updatedData.vacation_leave_balance} days`;
            highlightChange(cell);
        }
        
        if (updatedData.sick_leave_balance !== undefined && row.querySelector('.sick-leave')) {
            const cell = row.querySelector('.sick-leave');
            cell.textContent = `${updatedData.sick_leave_balance} days`;
            highlightChange(cell);
        }
        const leaveTypes = {
            'paternity_leave': 'P',
            'maternity_leave': 'M',
            // 'overtime_balance': 'OT',
            'study_leave': 'Study',
            'vawc_leave': 'VAWC',
            'rehabilitation_leave': 'Rehab',
            'mandatory_leave_balance': 'ML',
            'special_leave_benefit': 'SLB',
            'special_privilege_leave': 'SPL',
            'special_emergency_leave': 'SEL'
        };

        Object.entries(leaveTypes).forEach(([field, prefix]) => {
            if (updatedData[field] !== undefined) {
                const badges = row.querySelectorAll('.badge');
                const badge = Array.from(badges).find(b => 
                    b.textContent.startsWith(`${prefix}:`)
                );
                
                if (badge) {
                    const suffix = field === 'overtime_balance' ? 'h' : 'd';
                    badge.textContent = `${prefix}: ${updatedData[field]}${suffix}`;
                    highlightChange(badge);
                }
            }
        });
    }
    
    function highlightChange(element) {
        element.classList.add('bg-green-50', 'transition-colors', 'duration-300');
        setTimeout(() => {
            element.classList.remove('bg-green-50');
        }, 1000);
    }
    
    
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeModal();
        }
    });
    
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection