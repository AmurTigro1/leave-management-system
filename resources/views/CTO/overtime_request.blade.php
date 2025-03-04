@extends('CTO.layouts.sidebar-header')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-md border">
    <div class="text-center">
        <div class="justify-center flex">
            <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[80px] w-[80px] mx-auto lg:mx-0 mb-4">
        </div>
        <div>
            <p>Republic of the Philippines</p>
            <h1 class="font-bold">DEPARTMENT OF THE INTERIOR AND LOCAL GOVERNMENT</h1>
            <h1>Rajah Sikatuna Avenue, Dampas, City of Tagbilaran, Bohol</h1>
        </div>
    </div>    
    <form action="{{ route('overtime_request.store') }}" method="POST" class="mt-4">
        @csrf
        
        <!-- Employee Information -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Position:</label>
                <input type="text" name="position" class="w-full border p-2" required>
            </div>
            <div>
                <label class="font-semibold">Office/Division:</label>
                <input type="text" name="office_division" class="w-full border p-2" required>
            </div>
        </div>
        
        <!-- Overtime Details -->
        <div class="mt-4 border-t pt-4">
            <h3 class="font-semibold">Overtime Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="font-semibold">Date Filed:</label>
                    <input type="date" name="date_filed" class="w-full border p-2" required>
                </div>
                <div>
                    <label class="font-semibold">Working Hours Applied:</label>
                    <input type="number" name="working_hours_applied" class="w-full border p-2" required>
                </div>
                <div>
                    <label class="font-semibold">Inclusive Dates:</label>
                    <input type="date" name="inclusive_date_start" class="w-full border p-2" required>
                </div>
                <div>
                    <input type="date" name="inclusive_date_end" class="w-full border p-2" required>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="mt-6 text-center">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Submit Request</button>
        </div>
    </form>
</div>
@endsection