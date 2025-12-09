function toggleEndDate() {
    const startDate = document.getElementById("start_date");
    const endDate = document.getElementById("end_date");
    const oneDayLeave = document.getElementById("one_day_leave");

    if (oneDayLeave.checked && startDate.value) {
        endDate.value = startDate.value;
        endDate.readOnly = true;
    } else {
        endDate.readOnly = false;
    }
    calculateDays();
}

function calculateDays() {
    const startDate = document.getElementById("start_date");
    const endDate = document.getElementById("end_date");
    const oneDayLeave = document.getElementById("one_day_leave");
    const daysApplied = document.getElementById("days_applied");

    // Ensure dates are synced if checkbox is checked
    if (oneDayLeave.checked && startDate.value) {
        endDate.value = startDate.value;
    }

    // Only calculate if we have both dates
    if (startDate.value && endDate.value) {
        const start = new Date(startDate.value);
        const end = new Date(endDate.value);

        // Ensure end date is not before start date
        if (end < start) {
            endDate.value = startDate.value;
            // Recalculate with corrected date
            return calculateDays();
        }

        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        // Add 1 to include both start and end days
        daysApplied.value = diffDays + 1;
    } else {
        // Default to 1 day if no dates selected
        daysApplied.value = 1;
    }
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    // Get elements
    const startDate = document.getElementById("start_date");
    const endDate = document.getElementById("end_date");
    const oneDayLeave = document.getElementById("one_day_leave");

    // Check if elements exist before adding listeners
    if (startDate && endDate && oneDayLeave) {
        // Add event listeners
        startDate.addEventListener("change", calculateDays);
        endDate.addEventListener("change", calculateDays);
        oneDayLeave.addEventListener("change", toggleEndDate);

        // Initial calculation
        calculateDays();
    } else {
        console.error("One or more form elements not found");
    }
});
