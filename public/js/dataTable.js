// DataTable
$(document).ready(function () {
    var table = $("#example").DataTable({
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

    // STUDENT FILTERS
    // Year
    $(".filter-year").on("click", function (e) {
        e.preventDefault(); // Prevents the default action from navigating
        var year = $(this).data("value");

        // Update the selected year in a hidden input (if needed)
        $("#selectedYear").val(year);
        // Filter DataTable based on the selected year
        table.column(5).search(year).draw();

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
        table.column(4).search(course).draw();

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
        table.column(6).search(status).draw();

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
        table.column(2).search(instructorName).draw();

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
        table.column(4).search(instructorStatus).draw();

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
        table.search("").columns().search("").draw();

        // Remove active class from all filter options
        $(".dropdown-item").removeClass("active");
    });

});