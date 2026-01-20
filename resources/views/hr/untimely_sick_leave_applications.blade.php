@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="animate-fade-in p-6">
        <h1 class="text-2xl font-semibold mb-4">
            Untimely Sick Leave Applications
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
                        <th class="text-gray-500 border p-3 text-left">Total Untimely Sick Leave Applications</th>
                        <th class="text-gray-500 border p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersWithViolations as $user)
                        <tr>
                            <td class="border p-3">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </td>
                            <td class="border p-3">
                                {{ $user->department }}
                            </td>
                            <td class="border p-3">
                                {{ $user->leave_violations_count }}
                            </td>
                            <td class="border p-3 text-center">
                                <button type="button"
                                    onclick="openViewModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}')"
                                    class="text-blue-600 hover:text-blue-800 hover:underline">
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $usersWithViolations->links() }}
        </div>
    </div>

    {{-- Modal Container --}}
    <div id="simpleModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-7xl p-6 mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Untimely Sicke Leave Applications</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>

            {{-- Employee Info --}}
            <div class="mb-4">
                <p class="font-semibold text-gray-600">Employee</p>
                <p class="text-lg" id="modalEmployee"></p>
            </div>

            {{-- Loading State --}}
            <div id="loadingState" class="text-center py-8">
                <p class="text-gray-500">Loading...</p>
            </div>

            {{-- Details Table --}}
            <div id="detailsTable" class="mb-6 overflow-x-auto hidden">
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
                            <th class="text-gray-500 border p-3 text-left">Filed Date</th>
                            <th class="text-gray-500 border p-3 text-left">Files</th>


                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Rows will be inserted here -->
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t">
                <button onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Close</button>
            </div>
        </div>
    </div>

    <script>
        async function openViewModal(userId, employeeName) {
            // Show modal
            document.getElementById('simpleModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Set employee name
            document.getElementById('modalEmployee').textContent = employeeName;

            // Show loading, hide table
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('detailsTable').classList.add('hidden');

            try {
                // Get current filter values
                const fromDate = new URLSearchParams(window.location.search).get('from_date') || '';
                const toDate = new URLSearchParams(window.location.search).get('to_date') || '';

                // Fetch leave applications for this user
                const response = await fetch(
                    `/hr/my-untimely-sick-leave-applications/${userId}?from_date=${fromDate}&to_date=${toDate}`);
                const applications = await response.json();

                // Hide loading, show table
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('detailsTable').classList.remove('hidden');

                // Populate table
                const tbody = document.getElementById('tableBody');
                tbody.innerHTML = '';

                applications.forEach(app => {
                    const row = document.createElement('tr');

                    // Status badge classes
                    let statusClasses = 'px-2 py-1 rounded text-sm ';
                    const status = app.status.toLowerCase();
                    if (status === 'approved') {
                        statusClasses += 'bg-green-100 text-green-800';
                    } else if (status === 'rejected') {
                        statusClasses += 'bg-red-100 text-red-800';
                    } else if (status === 'pending') {
                        statusClasses += 'bg-yellow-100 text-yellow-800';
                    } else {
                        statusClasses += 'bg-gray-100 text-gray-800';
                    }

                    row.innerHTML = `
                <td class="border p-3 align-top">${app.type}</td>
                <td class="border p-3 align-top text-xs text-blue-500">
                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                        ${app.leave_details}
                    </span>
                </td>
                <td class="border p-3 align-top"> ${app.reason}</td>
                <td class="border p-3 align-top">${app.start_date}</td>
                <td class="border p-3 align-top">${app.end_date}</td>
                <td class="border p-3 align-top">
                    <span class="${statusClasses}">${app.status}</span>
                </td>
                <td class="border p-3 align-top">${app.days_applied}</td>
                <td class="border p-3 align-top">${app.filed_date}</td>
                <td class="border p-3 align-top">
                    ${renderLeaveFiles(app.leave_files)}
                </td>
            `;

                    tbody.appendChild(row);
                });

            } catch (error) {
                console.error('Error fetching leave applications:', error);
                document.getElementById('loadingState').innerHTML = '<p class="text-red-500">Error loading data</p>';
            }
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


        function renderLeaveFiles(files) {
            if (!files) return '<span class="text-gray-400 text-sm">No files</span>';

            // If files come as JSON string, parse it
            if (typeof files === 'string') {
                try {
                    files = JSON.parse(files);
                } catch (e) {
                    return '<span class="text-red-400 text-sm">Invalid file data</span>';
                }
            }

            if (!Array.isArray(files) || files.length === 0) {
                return '<span class="text-gray-400 text-sm">No files</span>';
            }

            return files.map((file, index) => {
                const url = `/storage/${file}`;
                return `
            <a href="${url}"
               target="_blank"
               class="inline-block mb-1 px-3 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                View File ${index + 1}
            </a>
        `;
            }).join('');
        }
    </script>
@endsection
