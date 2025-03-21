@extends('layouts.hr.sidebar-header')

@section('content')
<div class="p-8 w-full">
    <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
        <i class="lucide lucide-file-text"></i> Overtime Applications
    </h2>

    @if($overtimeRequests->isEmpty())
        <div class="text-center py-10 text-gray-500">
            <i class="lucide lucide-folder-x w-12 h-12 mx-auto"></i>
            <p class="mt-2 text-lg">No overtime requests found.</p>
        </div>
    @else
        <div class="overflow-x-auto rounded-lg">
            <table class="min-w-full border border-gray-200 shadow-lg rounded-lg text-center">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="p-3 border">Employee</th>
                        <th class="p-3 border">Position</th>
                        <th class="p-3 border">Office/Division</th>
                        <th class="p-3 border">Date Filed</th>
                        <th class="p-3 border">Working Hours</th>
                        <th class="p-3 border">Inclusive Dates</th>
                        <th class="p-3 border">HR Status</th>
                        <th class="p-3 border">Supervisor Status</th>
                        <th class="p-3 border text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @foreach ($overtimeRequests as $OT)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3 flex items-center gap-3">
                            <img src="{{ asset('storage/profile_images/' . $OT->user->profile_image) }}" 
                                 alt="User Profile" 
                                 class="w-10 h-10 object-cover rounded-full border border-gray-300 shadow-sm">
                            <span class="font-semibold">{{ $OT->user->name }}</span>
                        </td>
                        <td class="p-3">{{ $OT->position }}</td>
                        <td class="p-3">{{ $OT->office_division }}</td>
                        <td class="p-3">{{ $OT->date_filed }}</td>
                        <td class="p-3 text-center">{{ $OT->working_hours_applied }} hrs</td>
                        <td class="p-3">{{ $OT->inclusive_date_start }} - {{ $OT->inclusive_date_end }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 text-xs font-semibold text-white rounded-full
                                {{ $OT->hr_status === 'pending' ? 'bg-yellow-500' : 
                                   ($OT->hr_status === 'approved' ? 'bg-green-500' : 
                                   ($OT->hr_status === 'rejected' ? 'bg-red-500' : 'bg-gray-500')) }}">
                                {{ ucfirst($OT->hr_status) }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span class="px-3 py-1 text-xs font-semibold text-white rounded-full
                                {{ $OT->status === 'pending' ? 'bg-yellow-500' : 
                                   ($OT->status === 'approved' ? 'bg-green-500' : 
                                   ($OT->status === 'rejected' ? 'bg-red-500' : 'bg-gray-500')) }}">
                                {{ ucfirst($OT->status) }}
                            </span>
                        </td>
                        <td class="p-3 flex justify-center gap-2">
                            @if($OT->admin_status === 'Ready for Review')
                                <a href="{{ route('hr.overtime_details', ['id' => $OT->id]) }}" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md shadow transition inline-flex items-center justify-center">
                                    View
                                </a>
                            @else
                                <button disabled data-tooltip-target="tooltip-default"
                                    class="px-4 py-2 bg-gray-800 text-white text-xs font-medium rounded-md shadow cursor-not-allowed inline-flex items-center justify-center">
                                    Waiting for Admin Approval
                                </button>
                            @endif
                        </td>
                        {{-- <form action="" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-md shadow transition">
                                Delete
                            </button>
                        </form> --}}
                        {{-- {{ route('overtime.review', $OT->id) }} --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        {{ $overtimeRequests->links() }}
    </div>
</div>
@endsection
