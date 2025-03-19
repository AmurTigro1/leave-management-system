@extends('layouts.sidebar-header')

@section('content')
<div id="alert-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>

<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>
<div class="w-full bg-white p-4 md:p-6 animate-fade-in">
    <div class="text-center">
        <div class="w-full max-w-full mx-auto bg-white p-4 md:p-6 shadow-md border">
            <div class="text-xs md:text-sm lg:text-base">
                <p>Republic of the Philippines</p>
                <h1 class="font-bold text-sm md:text-lg lg:text-xl">DEPARTMENT OF THE INTERIOR AND LOCAL GOVERNMENT</h1>
                <h1 class="text-sm md:text-base">Rajah Sikatuna Avenue, Dampas, City of Tagbilaran, Bohol</h1>
            </div>

            <div class="text-center mt-2 md:mt-4">
                <h2 class="font-bold text-sm md:text-lg">Select Overtime Dates</h2>
            </div>

            <!-- Calendar Section -->
            <div x-data="calendar()" 
                class="mt-4 select-none p-2 md:p-4 rounded-lg shadow-lg border"
                style="background-image: url('/img/Background.png'); background-size: cover; background-position: center;">
                
                <!-- Calendar Header -->
                <div class="flex justify-center space-x-4 items-center mb-2">
                    <button @click="prevMonth()" 
                        class="px-2 py-1 md:px-3 md:py-1 bg-gray-200 rounded hover:bg-gray-300 text-xs md:text-sm">
                        &lt;
                    </button>
                    <span x-text="monthNames[month] + ' ' + year" class="text-xs md:text-lg font-bold"></span>
                    <button @click="nextMonth()" 
                        class="px-2 py-1 md:px-3 md:py-1 bg-gray-200 rounded hover:bg-gray-300 text-xs md:text-sm">
                        &gt;
                    </button>
                </div>

                <!-- Week Days -->
                <div class="grid grid-cols-7 text-center font-bold mb-2 text-white text-xs md:text-sm lg:text-base">
                    <span>Sun</span> <span>Mon</span> <span>Tue</span> <span>Wed</span>
                    <span>Thu</span> <span>Fri</span> <span>Sat</span>
                </div>

                <!-- Days Grid -->
                <div class="grid grid-cols-7 text-center text-white font-bold gap-[1px] md:gap-1">
                    <template x-for="blankDay in blankDays">
                        <div class="py-1"></div>
                    </template>
                    <template x-for="day in daysInMonth" :key="day">
                        <div 
                            @mousedown="!isPastDate(day) && startSelection(day)"
                            @mouseover="!isPastDate(day) && dragSelection(day)"
                            @mouseup="endSelection()"
                            class="cursor-pointer rounded transition duration-200 ease-in-out border p-4 sm:p-6 md:p-8 lg:p-12 xl:p-16 bg-black bg-opacity-40 relative text-xs sm:text-sm md:text-base"
                            :class="{
                                'bg-gray-300 text-gray-500 cursor-not-allowed': isPastDate(day), 
                                'bg-red-600': selectedDays.includes(day) && !isPastDate(day), 
                                'hover:bg-gray-300 hover:bg-opacity-40': !selectedDays.includes(day) && !isPastDate(day),
                                'bg-green-600': isAppliedDate(day),
                                'bg-yellow-600': isHoliday(day)
                            }">
                            <span class="absolute top-1 right-2 text-xs sm:text-sm md:text-base font-bold">
                                <span x-text="day"></span>
                            </span>
                        </div>
                    </template>                                    
                </div>

                <!-- Selected Dates -->
                <div class="mt-4 text-center">
                    <p class="text-white text-xs md:text-sm" x-show="selectedDays.length">
                        Selected Dates: <span x-text="selectedDates"></span>
                    </p>

                    <!-- Buttons Section -->
                    <div class="mt-2 space-x-2 flex flex-wrap justify-center">
                        @include('CTO.modals.request', ['otModal' => $overtimereq])
                        <button 
                            onclick="openRequestModal()" 
                            id="openModal" 
                            x-show="selectedDays.length" 
                            class="px-2 py-1 md:px-4 md:py-2 bg-blue-500 text-white rounded text-xs md:text-sm"
                            @click="setModalDates()">
                            Apply for OT Request
                        </button>

                        <button 
                            x-show="selectedDays.length" 
                            @click="cancelSelection()" 
                            class="px-2 py-1 md:px-4 md:py-2 bg-red-500 text-white rounded text-xs md:text-sm">
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
        appliedDates: @json($appliedDates), // Pass applied dates from backend
        holidays: @json($holidays), // Pass holidays from backend

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

            // Check if the date is in the past
            if (
                this.year < this.currentYear ||
                (this.year === this.currentYear && this.month < this.currentMonth) ||
                (this.year === this.currentYear && this.month === this.currentMonth && day < this.today)
            ) {
                return true;
            }

            // Check if the date is within any of the applied date ranges
            return this.isAppliedDate(day);
        },

        isAppliedDate(day) {
            const date = new Date(this.year, this.month, day);
            const dateString = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            return this.appliedDates.some(applied => {
                const startDate = new Date(applied.start);
                const endDate = new Date(applied.end);
                const currentDate = new Date(dateString);
                return currentDate >= startDate && currentDate <= endDate;
            });
        },

        isHoliday(day) {
            const date = new Date(this.year, this.month, day);
            const dateString = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            return this.holidays.some(holiday => {
                const holidayDate = new Date(holiday.date);
                return dateString === `${holidayDate.getFullYear()}-${String(holidayDate.getMonth() + 1).padStart(2, '0')}-${String(holidayDate.getDate()).padStart(2, '0')}`;
            });
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
                    if (this.selectedDays.length >= 5) {
                        window.location.href = "/cto-limit-warning";
                        return;
                    }
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
                            if (this.selectedDays.length >= 5) {
                                window.location.href = "/cto-limit-warning";
                                return;
                            }
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
    function showBootstrapAlert(message, type = "warning") {
            const alertContainer = document.getElementById("alert-container");
            const alertElement = document.createElement("div");

            alertElement.className = `alert alert-${type} alert-dismissible fade show`;
            alertElement.role = "alert";
            alertElement.innerHTML = `
                <strong>Notice!</strong> ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;

            alertContainer.appendChild(alertElement);

            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                alertElement.classList.remove("show");
                alertElement.classList.add("fade");
                setTimeout(() => alertElement.remove(), 500);
            }, 3000);
    }
</script>

@endsection
@notifyCss

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>