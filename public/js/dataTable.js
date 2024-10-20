// DataTable
$(document).ready(function () {
    var table = $("#exampleTable").DataTable({
        // scrollX: true,?
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
         // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[1, "asc"]],
        columnDefs: [{ type: "id", targets: 2 }],
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

    var tableTwo = $("#exampleTable2").DataTable({
        // scrollX: true,?
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        topEnd: null,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
    });

    // Highlight search term
    tableTwo.on("draw", function () {
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

    var rfidTable = $("#rfidTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[0, "asc"]],
        columnDefs: [{ type: "id", targets: 1 }],
    });

    // Highlight search term
    rfidTable.on("draw", function () {
        var body = $(rfidTable.table().body());
        var searchTerm = rfidTable.search();

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

    // Start Logs Table------------

    //admin table

    var adminTable = $("#adminTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[0, "asc"]],
        // columnDefs: [{ type: "id", targets: 1 }],
    });

    // Highlight search term
    adminTable.on("draw", function () {
        var body = $(adminTable.table().body());
        var searchTerm = adminTable.search();

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

    // end admin table

    //lab-In-Charge table

    var labInChargeTable = $("#labInChargeTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[0, "asc"]],
        columnDefs: [{ type: "id", targets: 1 }],
    });

    // Highlight search term
    labInChargeTable.on("draw", function () {
        var body = $(labInChargeTable.table().body());
        var searchTerm = labInChargeTable.search();

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

    //end lab-In-Charge table

    //technician table

    var technicianTable = $("#technicianTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[0, "asc"]],
        columnDefs: [{ type: "id", targets: 1 }],
    });

    // Highlight search term
    technicianTable.on("draw", function () {
        var body = $(technicianTable.table().body());
        var searchTerm = technicianTable.search();

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

    //end technician table

    //faculty table

    var facultyTable = $("#facultyTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[0, "asc"]],
        columnDefs: [{ type: "id", targets: 1 }],
    });

    // Highlight search term
    facultyTable.on("draw", function () {
        var body = $(facultyTable.table().body());
        var searchTerm = facultyTable.search();

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

    //end faculty table

    //student table

    var studentTable = $("#studentTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[0, "asc"]],
        columnDefs: [{ type: "id", targets: 1 }],
    });

    // Highlight search term
    studentTable.on("draw", function () {
        var body = $(studentTable.table().body());
        var searchTerm = studentTable.search();

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

    //end student table

    // End Logs Table------------

    //----Start Class List Table-----
    var studentListTable = $("#studentListTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
    });

    // Highlight search term
    studentListTable.on("draw", function () {
        var body = $(studentListTable.table().body());
        var searchTerm = studentListTable.search();

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

    //----End Class List Table-----

    // Initialize DataTable
    var attendanceTable = $("#AttendanceTable").DataTable({
        // scrollX: true,
        // "searching": false, order: [[0, 'asc']],
        rowReorder: false,
        pagingType: "simple_numbers",
        responsive: true,
        // rowReorder: {
        //     selector: "td:nth-child(2)",
        // },
        // stateSave: false,
        mark: true,
        language: {
            searchPlaceholder: "Search Here",
        },
        order: [[1, "asc"]], // Change 0 to the correct index if your date column is different
        columnDefs: [
            { type: "date", targets: 1 }, // Add this to specify the column type if your dates are in the first column
        ],
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

    //admin logs filter

    $(".filter-admin-id").on("click", function (e) {
        e.preventDefault();
        var adminID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedAdminID").val(adminID);

        // Filter DataTable based on the selected inst_name
        adminTable.column(1).search(adminID).draw();

        // Toggle active class for visual indication
        $(".filter-admin-id").removeClass("active");
        $(this).addClass("active");
    });

    //end admin logs filter

    //lab-In-Charge logs filter

    $(".filter-labInCharge-id").on("click", function (e) {
        e.preventDefault();
        var labInChargeID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedLabInChargeID").val(labInChargeID);

        // Filter DataTable based on the selected inst_name
        labInChargeTable.column(1).search(labInChargeID).draw();

        // Toggle active class for visual indication
        $(".filter-labInCharge-id").removeClass("active");
        $(this).addClass("active");
    });

    //end lab-In-Charge logs filter

    //technician logs filter

    $(".filter-technician-id").on("click", function (e) {
        e.preventDefault();
        var technicianID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedTechnicianID").val(technicianID);

        // Filter DataTable based on the selected inst_name
        technicianTable.column(1).search(technicianID).draw();

        // Toggle active class for visual indication
        $(".filter-technician-id").removeClass("active");
        $(this).addClass("active");
    });

    //end technician logs filter

    //faculty logs filter

    $(".filter-faculty-id").on("click", function (e) {
        e.preventDefault();
        var facultyID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedFacultyID").val(facultyID);

        // Filter DataTable based on the selected inst_name
        facultyTable.column(1).search(facultyID).draw();

        // Toggle active class for visual indication
        $(".filter-faculty-id").removeClass("active");
        $(this).addClass("active");
    });

    //end faculty logs filter

    //student filter

    $(".filter-student-id").on("click", function (e) {
        e.preventDefault();
        var studentID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedStudentID").val(studentID);

        // Filter DataTable based on the selected inst_name
        studentTable.column(1).search(studentID).draw();

        // Toggle active class for visual indication
        $(".filter-student-id").removeClass("active");
        $(this).addClass("active");
    });

    //end student filter

    //start schedule faculty filter

    $(".filter-faculty-id").on("click", function (e) {
        e.preventDefault();
        var facultyID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedFacultyID").val(facultyID);

        // Toggle active class for visual indication
        $(".filter-faculty-id").removeClass("active");
        $(this).addClass("active");
    });

    $(".makeUp-filter-faculty-id").on("click", function (e) {
        e.preventDefault();
        var facultyID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#editMakeUpSelectedFacultyID").val(facultyID);

        // Toggle active class for visual indication
        $(".makeUp-filter-faculty-id").removeClass("active");
        $(this).addClass("active");
    });

    //end schedule faculty filter
    //ADMIN FILTERS END-------------

    // STUDENT FILTERS
    // Year
    $(".filter-year").on("click", function (e) {
        e.preventDefault(); // Prevents the default action from navigating
        var year = $(this).data("value");

        // Update the selected year in a hidden input (if needed)
        $("#selectedYear").val(year);
        // Filter DataTable based on the selected year
        attendanceTable.column(6).search(year).draw();

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
        attendanceTable.column(5).search(course).draw();

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
        attendanceTable.column(7).search(status).draw();

        // Toggle active class for visual indication
        $(".filter-status").removeClass("active");
        $(this).addClass("active");
    });

    // Class Lists Status
    $(".filter-student-status").on("click", function (e) {
        e.preventDefault();
        var studentStatus = $(this).data("value");

        // Update the selected status in a hidden input (if needed)
        $("#selectedStudentStatus").val(studentStatus);

        // Filter DataTable based on the selected status

        studentListTable
            .column(4)
            .search("^" + studentStatus + "$", true, false)
            .draw();

        // Toggle active class for visual indication
        $(".filter-student-status").removeClass("active");
        $(this).addClass("active");
    });

    // INSTRUCTOR FILTERS
    // Instructor Name
    $(".filter-inst-id").on("click", function (e) {
        e.preventDefault();
        var instructorID = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedInstID").val(instructorID);

        // Filter DataTable based on the selected inst_name
        attendanceTable.column(6).search(instructorID).draw();

        // Toggle active class for visual indication
        $(".filter-inst-id").removeClass("active");
        $(this).addClass("active");
    });

    // Instructor Status
    $(".filter-inst-status").on("click", function (e) {
        e.preventDefault();
        var instructorStatus = $(this).data("value");

        // Update the selected inst_name in a hidden input (if needed)
        $("#selectedInstStatus").val(instructorStatus);

        // Filter DataTable based on the selected inst_name
        attendanceTable.column(7).search(instructorStatus).draw();

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
        $("#selectedStudentStatus").val("");

        $("#selectedDate").val("");
        $("#selectedTime").val("");

        // Clear DataTable filters and redraw
        attendanceTable.search("").columns().search("").draw();

        // Remove active class from all filter options
        $(".dropdown-item").removeClass("active");
    });

    $("#resetButton").on("click", function (e) {
        e.preventDefault();
        studentListTable.search("").columns().search("").draw();
    });

    $("#adminResetButton").on("click", function (e) {
        e.preventDefault();
        $(".admin-id").removeClass("active");
        adminTable.search("").columns().search("").draw();
    });

    $("#studentResetButton").on("click", function (e) {
        e.preventDefault();
        $(".student-id").removeClass("active");
        studentTable.search("").columns().search("").draw();
    });

    $("#LabInChargeResetButton").on("click", function (e) {
        e.preventDefault();
        $(".lab-in-charge-id").removeClass("active");
        labInChargeTable.search("").columns().search("").draw();
    });

    $("#technicianResetButton").on("click", function (e) {
        e.preventDefault();
        $(".technician-id").removeClass("active");
        technicianTable.search("").columns().search("").draw();
    });

    $("#facultyResetButton").on("click", function (e) {
        e.preventDefault();
        $(".faculty-id").removeClass("active");
        facultyTable.search("").columns().search("").draw();
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
        defaultHour: 8, // Set default hour (e.g., 9 AM)
        defaultMinute: 0, // Set default minute (e.g., 00 minutes)
        minDate: "today",
    };

    // Initialize flatpickr instances for the Datatable
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

    // Schedule Date [See Schedule Management Admin]
    flatpickr("#selectedDate1", dateConfig);
    flatpickr("#selectedDate2", dateConfig);
    flatpickr("#selectedDate3", dateConfig);
    flatpickr("#selectedDate4", dateConfig);

    flatpickr("#selectedTime1", timeConfig);
    flatpickr("#selectedTime2", timeConfig);
    flatpickr("#selectedTime3", timeConfig);
    flatpickr("#selectedTime4", timeConfig);
    flatpickr("#selectedTime5", timeConfig);
    flatpickr("#selectedTime6", timeConfig);
    flatpickr("#selectedTime7", timeConfig);
    flatpickr("#selectedTime8", timeConfig);

    flatpickr("#selectedStartDate", dateConfig);
    flatpickr("#selectedEndDate", dateConfig);

    //ADMIN FILTERS START-----------
});
