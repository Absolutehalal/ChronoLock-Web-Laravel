// DataTable
$(document).ready(function () {
    var table = $("#userTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: true,
        pagingType: "simple_numbers",
        responsive: true,
        rowReorder: {
            selector: "td:nth-child(2)",
        },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
    });

    // Highlight search term
    table.on("draw", function () {
        var body = $(table.table().body());
        var searchTerm = table.search();

        // Clear previous highlights
        body.unmark();

        if (searchTerm) {
            // Highlight new search term in specific columns (excluding the Actions column)
            body.find("td").each(function () {
                var cell = $(this);
                // Highlight in all columns except the last one (assuming it's the Actions column)
                if (!cell.hasClass("action-cell")) {
                    cell.mark(searchTerm);
                }
            });
        }
    });

});

$(document).ready(function () {
    // Initialize DataTable
    var dataTable = $("#instructorAttendanceTable").DataTable();

    // Flatpckr
    var dateConfig = {
        dateFormat: "F j, Y",
    };

    var timeConfig = {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // 'h' for 12-hour format, 'i' for minutes, 'K' for AM/PM
        time_24hr: false, // Use 12-hour time format with AM/PM
    };

    // Initialize flatpickr instances
    flatpickr("#selectedDate", dateConfig);
    flatpickr("#selectedTime", timeConfig);

    // Focus on the date input when the date icon is clicked
    $("#dateIcon").on("click", function () {
        $("#selectedDate").focus();
    });

    // Focus on the time input when the time icon is clicked
    $("#timeIcon").on("click", function () {
        $("#selectedTime").focus();
    });

    // Custom filter date for the DataTable
    $("#datepicker").on("click", function (){
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var selectedDate = $("#datepicker input").val(); // Get the selected date from the datepicker input field
        var tableDate = data[0]; // Get the date from the first column of the table

        // If no date is selected, or the table date matches the selected date, display the row
        if (selectedDate === "" || tableDate === selectedDate) {
            return true;
        }
        // Otherwise, hide the row
        return false;
    });

});
    // Custom filter time for the DataTable
    $("#timepicker").on("click", function (){
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var selectedTime = $("#timepicker input").val(); // Get the selected time from the timepicker input field
        var tableTime = data[1]; // Get the time from the second column of the table

        if (selectedTime === "") {
            return true; // No time selected, show all rows
        }

        // Extract hours, minutes, and period (AM/PM) from selectedTime and tableTime
        var selectedTimeParts = selectedTime.split(" "); // Split selectedTime into time and period (AM/PM)
        var selectedHours = parseInt(selectedTimeParts[0].split(":")[0], 10); // Extract hours from selectedTime and convert to integer
        var selectedMinutes = parseInt(selectedTimeParts[0].split(":")[1], 10); // Extract minutes from selectedTime and convert to integer
        var selectedPeriod = selectedTimeParts[1]; // Extract period (AM or PM) from selectedTime

        var tableTimeParts = tableTime.split(" "); // Split tableTime into time and period (AM/PM)
        var tableHours = parseInt(tableTimeParts[0].split(":")[0], 10); // Extract hours from tableTime and convert to integer
        var tableMinutes = parseInt(tableTimeParts[0].split(":")[1], 10); // Extract minutes from tableTime and convert to integer
        var tablePeriod = tableTimeParts[1]; // Extract period (AM or PM) from tableTime

        // Compare hours and minutes to filter rows
        if (
            selectedPeriod === tablePeriod && // Ensure selected period matches table period
            tableHours === selectedHours &&
            tableMinutes >= selectedMinutes &&
            tableMinutes < selectedMinutes + 60 // Comparing within the hour range (10:00 AM to 10:59 AM)
        ) {
            return true;
        }

        // Otherwise, hide the row
        return false;
    });

});
    // Event listener for date change
    $("#selectedDate, #selectedTime").on("change", function () {
        dataTable.draw();
    });

    // Reset button click event handler
    $("#resetBtn").on("click", function (e) {
        e.preventDefault();

        // Clear the selected (if needed)
        $("#selectedYear").val("");
        $("#selectedCourse").val("");
        $("#selectedStatus").val("");

        $("#selectedInstName").val("");
        $("#selectedInstStatus").val("");

        $("#selectedDate").val("");
        $("#selectedTime").val("");

        // Clear DataTable filters and redraw
        dataTable.search("").columns().search("").draw();

        // Remove active class from all filter options
        $(".dropdown-item").removeClass("active");
    });

});