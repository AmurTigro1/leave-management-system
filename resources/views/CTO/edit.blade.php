@extends('layouts.sidebar-header')
    
@section('content')
<div class="max-w-6xl mx-auto p-6rounded-lg mt-4">
    <!-- Back Button with Animation -->
    <a href="{{ route('cto.overtime_list') }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center mb-4 transition-transform duration-300 hover:translate-x-2">
        &larr; Back to Overtime Requests
    </a>
    <h2 class="text-2xl font-bold mb-4 text-center">Edit Overtime Request Form</h2>

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

    <form method="POST" action="{{ route('cto.overtime_update', $overtime->id) }}" class=" p-4 rounded-lg">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block">Position:</label>
                <input type="text" name="position" class="w-full border px-3 py-2 rounded" required value="{{$overtime->position}}">
            </div>
            <div class="mb-4">
                <label class="block font-medium">Office Division</label>
                <input type="text" name="office_division" class="w-full border p-2 rounded" required value="{{$overtime->office_division}}">
            </div>
            <div>
                <label class="block text-sm font-medium">Inclusive Date Start</label>
                <input type="date" name="inclusive_date_start" id="inclusive_date_start" class="mt-1 w-full p-2 border rounded" required value="{{$overtime->inclusive_date_start}}">
                @error('inclusive_date_start')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium">Inclusive Date End</label>
                <input type="date" name="inclusive_date_end" id="inclusive_date_end" class="mt-1 w-full p-2 border rounded" required value="{{$overtime->inclusive_date_end}}">
                @error('inclusive_date_end')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block mt-2">Working Hours Applied:</label>
                <input type="number" name="working_hours_applied" class="w-full border p-2 rounded" min="1" required value="{{$overtime->working_hours_applied}}">
                @error('working_hours_applied')
                <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>
</div>  
        <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
    </form>
</div>
@endsection

