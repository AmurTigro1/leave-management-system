@extends('layouts.hr.sidebar-header')

@section('content')
    <div class="animate-fade-in p-6">

        <h1 class="text-2xl font-semibold mb-4">
            HR Extend Leave Applications
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
                        <th class="border px-3 py-2 text-left">Employee</th>
                        <th class="border px-3 py-2 text-left">Department</th>
                        <th class="border px-3 py-2 text-left">Leave Dates</th>
                        <th class="border px-3 py-2 text-left">Days Applied</th>
                        <th class="border px-3 py-2 text-left">Reason</th>
                        <th class="border px-3 py-2 text-left">Filed Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaveApplications as $application)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">
                                {{ $application->user->name }}
                                {{ $application->user->last_name }}
                                <div class="text-xs text-gray-500">
                                    {{ $application->user->employee_code }}
                                </div>
                            </td>

                            <td class="border px-3 py-2">
                                {{ $application->user->department }}
                            </td>

                            <td class="border px-3 py-2">
                                {{ $application->leave->start_date }}
                                →
                                {{ $application->leave->end_date }}
                            </td>

                            <td class="border px-3 py-2">
                                {{ $application->leave->days_applied }}
                            </td>

                            <td class="border px-3 py-2">
                                {{ $application->leave->reason ?? '—' }}
                            </td>

                            <td class="border px-3 py-2">
                                {{ $application->leave->date_filing }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No extend leave applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $leaveApplications->links() }}
        </div>

    </div>
@endsection
