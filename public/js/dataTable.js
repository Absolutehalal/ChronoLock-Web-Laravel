// DataTable
$(document).ready(function () {
    var table = $("#exampleTable").DataTable({
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


// Attendance Table - Instructor/Student
$(document).ready(function () {
    // Initialize DataTable
    var attendanceTable = $("#AttendanceTable").DataTable({
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
    attendanceTable.on("draw", function () {
        var body = $(attendanceTable.table().body());
        var searchTerm = attendanceTable.search();

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
    $("#datepicker").on("click", function () {
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
    $("#timepicker").on("click", function () {
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            var selectedTime = $("#timepicker input").val(); // Get the selected time from the timepicker input field
            var tableTime = data[1]; // Get the time from the second column of the table

            if (selectedTime === "") {
                return true; // No time selected, show all rows
            }

            // Extract hours, minutes, and period (AM/PM) from selectedTime and tableTime
            var selectedTimeParts = selectedTime.split(" "); // Split selectedTime into time and period (AM/PM)
            var selectedHours = parseInt(
                selectedTimeParts[0].split(":")[0],
                10
            ); // Extract hours from selectedTime and convert to integer
            var selectedMinutes = parseInt(
                selectedTimeParts[0].split(":")[1],
                10
            ); // Extract minutes from selectedTime and convert to integer
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
        attendanceTable.draw();
    });

    // STUDENT FILTERS
    // Year
    $(".filter-year").on("click", function (e) {
        e.preventDefault(); // Prevents the default action from navigating
        var year = $(this).data("value");

        // Update the selected year in a hidden input (if needed)
        $("#selectedYear").val(year);
        // Filter DataTable based on the selected year
        attendanceTable.column(5).search(year).draw();

        // Toggle active class for visual indication
        $(".filter-year").removeClass("active");
        $(this).addClass("active");
    });

    // Course
    $(".filter-course").on("click", function (e) {
        e.preventDefault();
        var course = $(this).data("value");

        // Update the selected course in a hidden input (if needed)
        $("#selectedCourse").val(course);

        // Filter DataTable based on the selected course
        attendanceTable.column(4).search(course).draw();

        // Toggle active class for visual indication
        $(".filter-course").removeClass("active");
        $(this).addClass("active");
    });

    // Status
    $(".filter-status").on("click", function (e) {
        e.preventDefault();
        var status = $(this).data("value");

        // Update the selected status in a hidden input (if needed)
        $("#selectedStatus").val(status);

        // Filter DataTable based on the selected status
        attendanceTable.column(6).search(status).draw();

        // Toggle active class for visual indication
        $(".filter-status").removeClass("active");
        $(this).addClass("active");
    });

    // INSTRUCTOR FILTERS
    // Instructor Name
    $(".filter-inst-name").on("click", function (e) {
        e.preventDefault();
        var instructorName = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedInstName").val(instructorName);

        // Filter DataTable based on the selected inst_name
        attendanceTable.column(2).search(instructorName).draw();

        // Toggle active class for visual indication
        $(".filter-inst-name").removeClass("active");
        $(this).addClass("active");
    });

    // Instructor Status
    $(".filter-inst-status").on("click", function (e) {
        e.preventDefault();
        var instructorStatus = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedInstStatus").val(instructorStatus);

        // Filter DataTable based on the selected inst_name
        attendanceTable.column(4).search(instructorStatus).draw();

        // Toggle active class for visual indication
        $(".filter-inst-status").removeClass("active");
        $(this).addClass("active");
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
        attendanceTable.search("").columns().search("").draw();

        // Remove active class from all filter options
        $(".dropdown-item").removeClass("active");
    });
});

// CLASS LIST TABLE
$(document).ready(function () {
    var ClassListTable = $("#ClassListTable").DataTable({
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
    ClassListTable.on("draw", function () {
        var body = $(ClassListTable.table().body());
        var searchTerm = ClassListTable.search();

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

    // Course
    $(".filter-course").on("click", function (e) {
        e.preventDefault();
        var course = $(this).data("value");

        // Update the selected course in a hidden input (if needed)
        $("#selectedCourse").val(course);

        // Filter DataTable based on the selected course
        ClassListTable.column(2).search(course).draw();

        // Toggle active class for visual indication
        $(".filter-course").removeClass("active");
        $(this).addClass("active");
    });

    // Year
    $(".filter-year").on("click", function (e) {
        e.preventDefault(); // Prevents the default action from navigating
        var year = $(this).data("value");

        // Update the selected year in a hidden input (if needed)
        $("#selectedYear").val(year);

        // Filter DataTable based on the selected year
        ClassListTable.column(3).search(year).draw();

        // Toggle active class for visual indication
        $(".filter-year").removeClass("active");
        $(this).addClass("active");
    });

    // Subject Name
    $(".filter-subjectName").on("click", function (e) {
        e.preventDefault();
        var subjectName = $(this).data("value");

        // Update the selected subject name in a hidden input (if needed)
        $("#selectedSubjectName").val(subjectName);

        // Filter DataTable based on the selected subject name
        ClassListTable.column(4).search(subjectName).draw();

        // Toggle active class for visual indication
        $(".filter-subjectName").removeClass("active");
        $(this).addClass("active");
    });

    // Subject Code
    $(".filter-subjectCode").on("click", function (e) {
        e.preventDefault();
        var subjectCode = $(this).data("value");

        // Update the selected subject code in a hidden input (if needed)
        $("#selectedSubjectCode").val(subjectCode);

        // Filter DataTable based on the selected subject code
        ClassListTable.column(5).search(subjectCode).draw();

        // Toggle active class for visual indication
        $(".filter-subjectCode").removeClass("active");
        $(this).addClass("active");
    });

    // Reset button click event handler
    $("#resetBtn").on("click", function (e) {
        e.preventDefault();

        // Clear the selected (if needed)
        $("#selectedYear").val("");
        $("#selectedCourse").val("");
      
        $("#selectedSubjectName").val("");
        $("#selectedSubjectCode").val("");

        // Clear DataTable filters and redraw
        ClassListTable.search("").columns().search("").draw();

        // Remove active class from all filter options
        $(".dropdown-item").removeClass("active");
    });
});
