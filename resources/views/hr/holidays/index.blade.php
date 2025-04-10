@extends('layouts.hr.sidebar-header')

@section('content')
@include('hr.holidays.modals.holidays_delete_request', ['holidays' => $holidays])
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">Manage Holidays</h1>
        <a href="{{ route('hr.holidays.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition duration-200 w-full sm:w-auto text-center">
            Add New Holiday
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($holidays->isEmpty())
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
            No holidays found.
        </div>
    @else
        <!-- Mobile Cards (hidden on larger screens) -->
        <div class="sm:hidden space-y-4">
            @foreach($holidays as $holiday)
            <div class="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div class="flex flex-col gap-3">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $holiday->name }}</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('hr.holidays.edit', $holiday->id) }}" 
                               class="text-yellow-600 hover:text-yellow-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <button type="button" class="text-red-600 hover:text-red-900" onclick="openDeleteHolidaysModal({{$holiday->id}})">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-xs text-gray-500">Date</p>
                            <p class="text-sm font-medium">{{ $holiday->date->format('M j, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Type</p>
                            <span class="text-xs font-semibold rounded-full px-2 py-1
                                @if($holiday->type === 'regular') bg-green-100 text-green-800
                                @elseif($holiday->type === 'special') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($holiday->type) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Repeats</p>
                            <span class="text-xs font-semibold rounded-full px-2 py-1
                                @if($holiday->repeats_annually) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $holiday->repeats_annually ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop Table (hidden on mobile) -->
        <div class="hidden sm:block bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repeats Annually</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($holidays as $holiday)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $holiday->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $holiday->date->format('F j, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($holiday->type === 'regular') bg-green-100 text-green-800
                                    @elseif($holiday->type === 'special') bg-yellow-100 text-yellow-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($holiday->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($holiday->repeats_annually)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Yes
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('hr.holidays.edit', $holiday->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                    <button type="button" class="text-red-600 hover:text-red-900" onclick="openDeleteHolidaysModal({{$holiday->id}})">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection