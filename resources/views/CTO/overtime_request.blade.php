@extends('layouts.sidebar-header')

@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>
<div class="w-full bg-white p-6">
    <div class="text-center">
        <div class="w-full max-w-full mx-auto bg-white p-8 shadow-md border">
            {{-- <div class="justify-center flex">
                <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[80px] w-[80px] mx-auto lg:mx-0 mb-4">
            </div> --}}
            <div>
                <p>Republic of the Philippines</p>
                <h1 class="font-bold">DEPARTMENT OF THE INTERIOR AND LOCAL GOVERNMENT</h1>
                <h1>Rajah Sikatuna Avenue, Dampas, City of Tagbilaran, Bohol</h1>
            </div>
            <div class="text-center">
                <h2 class="font-bold text-lg">Select Overtime Dates</h2>
            </div>

            <div x-data="calendar()" class="mt-4 select-none p-4 rounded-lg shadow-lg border"
                style="background-image: url('/img/Background.png'); background-size: cover; background-position: center;">
                <!-- Calendar UI -->
                <div class="flex justify-between items-center mb-2">
                    <button @click="prevMonth()" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&lt;</button>
                    <span x-text="monthNames[month] + ' ' + year" class="text-lg font-bold"></span>
                    <button @click="nextMonth()" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&gt;</button>
                </div>
            
                <div class="grid grid-cols-7 text-center font-bold mb-2 border text-white text-xl">
                    <span>Sun</span> <span>Mon</span> <span>Tue</span> <span>Wed</span>
                    <span>Thu</span> <span>Fri</span> <span>Sat</span>
                </div>
            
                <div class="grid grid-cols-7 text-center border text-white font-bold">
                    <template x-for="blankDay in blankDays">
                        <div class="py-2"></div>
                    </template>
                    <template x-for="day in daysInMonth" :key="day">
                        <div 
                            @mousedown="!isPastDate(day) && startSelection(day)"
                            @mouseover="!isPastDate(day) && dragSelection(day)"
                            @mouseup="endSelection()"
                            class="cursor-pointer rounded transition duration-200 ease-in-out border p-16 bg-black bg-opacity-40 relative"
                            :class="{
                                'bg-gray-300 text-gray-500 cursor-not-allowed': isPastDate(day), 
                                'bg-red-600': selectedDays.includes(day) && !isPastDate(day), 
                                'hover:bg-gray-300 hover:bg-opacity-40': !selectedDays.includes(day) && !isPastDate(day)
                            }">
                            
                            <!-- Number positioned in the top-right corner -->
                            <span class="absolute top-1 right-2 text-xl font-bold">
                                <span x-text="day"></span>
                            </span>
                        </div>
                    </template>                                    
                </div>
            
                <div class="mt-4 text-center">
                    <p class="text-white" x-show="selectedDays.length">Selected Dates: <span x-text="selectedDates"></span></p>
            
                    <!-- Apply for OT Request Button (Only visible when dates are selected) -->
                    <div class="mt-2 space-x-2">
                        @include('CTO.modals.request', ['otModal' => $overtimereq])
                        <button 
                            onclick="openRequestModal()" 
                            id="openModal" 
                            x-show="selectedDays.length" 
                            class="px-4 py-2 bg-blue-500 text-white rounded"
                            @click="setModalDates()"> <!-- Add this line to pass dates to the modal -->
                            Apply for OT Request
                        </button>
            
                        <!-- Cancel Selection Button -->
                        <button 
                            x-show="selectedDays.length" 
                            @click="cancelSelection()" 
                            class="px-4 py-2 bg-red-500 text-white rounded">
                            Cancel Selection
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('calendar', () => ({
        monthNames: [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ],
        year: new Date().getFullYear(),
        month: new Date().getMonth(),
        today: new Date().getDate(),
        currentYear: new Date().getFullYear(),
        currentMonth: new Date().getMonth(),
        daysInMonth: [],
        blankDays: [],
        selectedDays: [],
        isDragging: false,
        isRemoving: false,
        firstSelectedDate: null,
        lastSelectedDate: null,

        init() {
            this.calculateDays();
        },

        calculateDays() {
            let firstDayOfMonth = new Date(this.year, this.month).getDay();
            let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

            this.blankDays = Array(firstDayOfMonth).fill(null);
            this.daysInMonth = Array.from({ length: daysInMonth }, (_, i) => i + 1);
        },

        isPastDate(day) {
            let date = new Date(this.year, this.month, day);
            let dayOfWeek = date.getDay(); // 0 = Sunday, 6 = Saturday

            return (
                dayOfWeek === 0 || dayOfWeek === 6 || // Disable weekends
                this.year < this.currentYear ||
                (this.year === this.currentYear && this.month < this.currentMonth) ||
                (this.year === this.currentYear && this.month === this.currentMonth && day < this.today)
            );
        },

        prevMonth() {
            this.month = this.month === 0 ? 11 : this.month - 1;
            if (this.month === 11) this.year--;
            this.calculateDays();
        },

        nextMonth() {
            this.month = this.month === 11 ? 0 : this.month + 1;
            if (this.month === 0) this.year++;
            this.calculateDays();
        },

        startSelection(day) {
            if (this.isPastDate(day)) return;

            this.isDragging = true;

            if (this.selectedDays.includes(day)) {
                this.isRemoving = true;
                this.selectedDays = this.selectedDays.filter(d => d !== day);
            } else {
                this.isRemoving = false;
                if (this.selectedDays.length >= 5) {
                    alert("You can only select up to 5 consecutive days for CTO.");
                    return;
                }
                this.selectedDays.push(day);
            }
            this.updateInclusiveDates();
        },

        dragSelection(day) {
            if (!this.isDragging || this.isPastDate(day)) return;

            if (this.isRemoving) {
                this.selectedDays = this.selectedDays.filter(d => d !== day);
            } else {
                if (!this.selectedDays.includes(day)) {
                    if (this.selectedDays.length >= 5) {
                        // Do not show an alert here; just block the selection
                        return;
                    }
                    this.selectedDays.push(day);
                    this.selectedDays.sort((a, b) => a - b);
                }
            }
            this.updateInclusiveDates();
        },

        endSelection() {
            this.isDragging = false;
            this.isRemoving = false;
            this.validateConsecutiveDays();
        },

        validateConsecutiveDays() {
            if (this.selectedDays.length > 0) {
                const sortedDays = [...this.selectedDays].sort((a, b) => a - b);
                let consecutiveCount = 1;
                for (let i = 1; i < sortedDays.length; i++) {
                    if (sortedDays[i] === sortedDays[i - 1] + 1) {
                        consecutiveCount++;
                        if (consecutiveCount > 5) {
                            alert("You can only select up to 5 consecutive days for CTO.");
                            this.cancelSelection(); // Ensure cancelSelection is properly triggered
                            return;
                        }
                    } else {
                        consecutiveCount = 1;
                    }
                }
            }
        },

        updateInclusiveDates() {
            if (this.selectedDays.length > 0) {
                let first = Math.min(...this.selectedDays);
                let last = Math.max(...this.selectedDays);

                this.firstSelectedDate = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(first).padStart(2, '0')}`;
                this.lastSelectedDate = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(last).padStart(2, '0')}`;
            } else {
                this.firstSelectedDate = null;
                this.lastSelectedDate = null;
            }
        },

        get selectedDates() {
            return this.selectedDays.map(day => `${this.monthNames[this.month]} ${day}`).join(", ");
        },

        setModalDates() {
            // Set the selected dates in the modal's input fields
            const modal = document.getElementById('requestModal');
            if (modal) {
                modal.querySelector('input[name="inclusive_date_start"]').value = this.firstSelectedDate;
                modal.querySelector('input[name="inclusive_date_end"]').value = this.lastSelectedDate;
            }
        },

        cancelSelection() {
            this.selectedDays = [];
            this.isDragging = false; // Ensure dragging is reset
            this.isRemoving = false; // Reset removing flag
            this.updateInclusiveDates();
        }
    }));
});
</script>

@endsection
{{-- @notifyJs --}}
@notifyCss