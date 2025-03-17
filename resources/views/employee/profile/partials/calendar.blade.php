<div class="w-full bg-white p-6">
    <div class="text-center">
        <div class="w-full max-w-full mx-auto bg-white p-8 shadow-md border">
            <div x-data="calendar()" x-init="init()" data-events='@json($events)' class="p-4">
                
                <!-- Leave & Overtime Cards -->
                <div class="mb-4 space-y-2">
                    <template x-for="leave in events.leave" :key="leave.id">
                        <div class="bg-white p-4 rounded-lg shadow-md flex items-center space-x-4 border border-gray-200">
                            <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-gray-300 bg-gray-100">
                                <img :src="leave.profile_image" class="w-full h-full object-cover" alt="Profile">
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 text-sm sm:text-md" x-text="leave.first_name + ' ' + leave.last_name"></p>
                                <p class="text-xs text-gray-600">
                                    Duration: <span class="text-green-500" x-text="leave.duration + ' day(s)'"></span>
                                </p>
                                <p class="text-xs text-gray-500">
                                    From: <span x-text="leave.start"></span> <br> 
                                    To: <span x-text="leave.end"></span>
                                </p>
            
                                <!-- Status Badge -->
                                <span class="text-sm px-4 rounded-md" 
                                    :class="leave.status === 'Approved' ? 'bg-green-500 text-white' : 
                                            leave.status === 'Pending' ? 'bg-yellow-500 text-white' : 
                                            'bg-red-500 text-white'"
                                    x-text="leave.status">
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Calendar Header -->
                <div class="flex justify-between items-center mb-4">
                    <button @click="prevMonth" class="px-3 py-1 bg-gray-300 rounded">← Prev</button>
                    <h2 class="text-lg font-bold" x-text="monthNames[month] + ' ' + year"></h2>
                    <button @click="nextMonth" class="px-3 py-1 bg-gray-300 rounded">Next →</button>
                </div>
                
                <!-- Weekdays Row -->
                <div class="grid grid-cols-7 text-center font-semibold text-gray-700">
                    <div class="p-2">Sun</div>
                    <div class="p-2">Mon</div>
                    <div class="p-2">Tue</div>
                    <div class="p-2">Wed</div>
                    <div class="p-2">Thu</div>
                    <div class="p-2">Fri</div>
                    <div class="p-2">Sat</div>
                </div>

                <div class="grid grid-cols-7 gap-1 text-center">
                    <template x-for="blank in blankDays">
                        <div class="p-4"></div>
                    </template>
                
                    <template x-for="day in daysInMonth" :key="day">
                        <div 
                            class="p-4 border rounded-md text-sm font-semibold relative aspect-square flex items-center justify-center"
                            :class="getDayColor(day)">
                            <span x-text="day"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('calendar', () => ({
            monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            year: new Date().getFullYear(),
            month: new Date().getMonth(),
            daysInMonth: [],
            blankDays: [],
            events: { leave: [], overtime: [] },

            init() {
                try {
                    this.events = JSON.parse(this.$el.getAttribute('data-events')) || { leave: [], overtime: [] };
                } catch (error) {
                    console.error("Error parsing events data:", error);
                }
                this.calculateDays();
            },

            calculateDays() {
                let firstDayOfMonth = new Date(this.year, this.month, 1).getDay();
                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

                this.blankDays = Array(firstDayOfMonth).fill(null);
                this.daysInMonth = Array.from({ length: daysInMonth }, (_, i) => i + 1);
            },

            prevMonth() {
                if (this.month === 0) {
                    this.month = 11;
                    this.year--;
                } else {
                    this.month--;
                }
                this.calculateDays();
            },

            nextMonth() {
                if (this.month === 11) {
                    this.month = 0;
                    this.year++;
                } else {
                    this.month++;
                }
                this.calculateDays();
            },

            getDayColor(day) {
                let formattedDate = new Date(this.year, this.month, day).toISOString().split('T')[0];

                let isWeekend = [0, 6].includes(new Date(this.year, this.month, day).getDay());
                if (isWeekend) return 'bg-gray-300';

                let isOvertime = this.events.overtime.some(event => {
                    let start = new Date(event.start).toISOString().split('T')[0];
                    let end = new Date(event.end).toISOString().split('T')[0];
                    return formattedDate >= start && formattedDate <= end;
                });

                let isLeave = this.events.leave.some(event => {
                    let start = new Date(event.start).toISOString().split('T')[0];
                    let end = new Date(event.end).toISOString().split('T')[0];
                    return formattedDate >= start && formattedDate <= end;
                });

                if (isOvertime) return 'bg-green-500 text-white';
                if (isLeave) return 'bg-blue-500 text-white';

                return 'bg-gray-100';
            },
        }));
    });
</script>