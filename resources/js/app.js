import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
// import { Calendar } from '@fullcalendar/core';
// import dayGridPlugin from '@fullcalendar/daygrid';
// import interactionPlugin from '@fullcalendar/interaction';

// document.addEventListener('DOMContentLoaded', function() {
//     var calendarEl = document.getElementById('calendar');
//     if (calendarEl) {
//         var calendar = new Calendar(calendarEl, {
//             plugins: [dayGridPlugin, interactionPlugin],
//             initialView: 'dayGridMonth',
//             events: '/api/leaves',
//             headerToolbar: {
//                 left: 'prev,next today',
//                 center: 'title',
//                 right: 'dayGridMonth,timeGridWeek,timeGridDay'
//             },
//         });
//         calendar.render();
//     }
// });
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("leaveCalendar");

    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            events: "/api/leaves", // Fetch events from Laravel API
            
            eventDidMount: function (info) {
                let eventEl = info.el;
                eventEl.classList.add('rounded-lg', 'px-3', 'py-1', 'text-white', 'shadow-lg', 'cursor-pointer', 'transition-transform', 'duration-300', 'hover:scale-105');

                // Dynamic Event Styling
                if (info.event.extendedProps.status === 'approved') {
                    eventEl.classList.add('bg-green-500', 'hover:bg-green-600');
                } else if (info.event.extendedProps.status === 'pending') {
                    eventEl.classList.add('bg-yellow-400', 'hover:bg-yellow-500', 'text-black');
                } else {
                    eventEl.classList.add('bg-red-500', 'hover:bg-red-600');
                }

                // Tooltip for Employee Details
                let employeeName = info.event.title;
                let profileImg = info.event.extendedProps.profile;
                eventEl.setAttribute('title', `Leave approved for ${employeeName}`);
                eventEl.innerHTML = `
                    <div class="flex items-center gap-2">
                        <img src="${profileImg}" alt="Profile" class="w-6 h-6 rounded-full border-2 border-white shadow-md">
                        <span class="font-medium">${employeeName}</span>
                    </div>
                `;
            },

            eventClick: function (info) {
                let employeeName = info.event.title;
                let startDate = info.event.start ? new Date(info.event.start).toLocaleDateString() : "N/A";
                let endDate = info.event.end ? new Date(info.event.end).toLocaleDateString() : "N/A";
                let profilePicture = info.event.extendedProps.profile || "https://i.pinimg.com/474x/6b/cd/37/6bcd37954241e18f9000c5642b06ed66.jpg"; // Default image
            
                // Calculate leave duration (if both dates exist)
                let start = info.event.start ? new Date(info.event.start) : null;
                let end = info.event.end ? new Date(info.event.end) : null;
                let duration = start && end ? Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1 : "N/A";
            
                let modalContent = `
                    <div class="text-center p-6 bg-white rounded-2xl shadow-2xl transform transition duration-300 hover:scale-105 animate-fade-in">
                        <img src="${profilePicture}" alt="Profile Picture" class="rounded-full w-20 h-20 mx-auto border-4 border-blue-500 shadow-md">
                        <p class="text-xl font-bold text-gray-900 mt-4">${employeeName}</p>
                        <p class="text-md text-gray-600 mt-2"><span class="font-semibold">Start:</span> ${startDate}</p>
                        <p class="text-md text-gray-600 mt-2"><span class="font-semibold">End:</span> ${endDate}</p>
                        <p class="text-md text-gray-600 mt-2"><span class="font-semibold">Duration:</span> ${duration} days</p>
                    </div>
                `;

                document.getElementById("leaveModalContent").innerHTML = modalContent;
                let modal = document.getElementById("leaveModal");
                modal.classList.remove("hidden");
                modal.classList.add("opacity-100", "scale-100");
            }
        });

        calendar.render();
    } else {
        console.error("Calendar element not found");
    }
});

function closeModal() {
    let modal = document.getElementById("leaveModal");
    modal.classList.add("hidden");
    modal.classList.remove("opacity-100", "scale-100");
}
Alpine.start();
