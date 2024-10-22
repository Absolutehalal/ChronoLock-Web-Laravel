$(document).ready(function() {


    $('.nav-link').on('click', function (e) {
        localStorage.setItem('activeTab', $(this).attr('href'));
        $(this).tab('show');
    });
    
    var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                $('#pills-tab button[href="' + activeTab + '"]').tab('show');
            }
});
document.addEventListener("DOMContentLoaded", function () {
    $(document).ready(function () {
        var calendarEl = document.getElementById("calendar");
        var year = new Date().getFullYear();
        var month = new Date().getMonth() + 1;
        function n(n) {
            return n > 9 ? "" + n : "0" + n;
        }
        var month = n(month);

        $.ajax({
            type: "GET",
            url: "/get-Faculty-Schedule-Note",
            success: function (response) {
                var schedules = response.ERPSchedules;
                // console.log(schedules);

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: "prev,next today",
                        center: "title",
                        right: "dayGridMonth,timeGridWeek,timeGridDay",
                    },
                    events: schedules,
                    selectable: true,
                    eventTimeFormat: {
                        hour: "numeric",
                        minute: "2-digit",
                        meridiem: "short",
                    },
                    eventDidMount: function (info) {
                        var startTime = moment(info.event.start).format(
                            "h:mma"
                        );
                        var endTime = moment(info.event.end).format("h:mma");
                        var timeText = startTime + " - " + endTime;

                        var timeElement =
                            info.el.querySelector(".fc-event-time");
                        if (timeElement) {
                            timeElement.innerHTML = timeText;
                        }
                        var eventID = info.event.id;
                        // console.log(eventID);
                        // Add a tooltip with the description
                        var startStr = info.event.startStr; // Get the startStr

                        // Convert the startStr to a Date object
                        var date = new Date(startStr);

                        if (!isNaN(date.getTime())) { // Check if the date is valid
                            var eventDate = date.toISOString().split('T')[0]; // Format to YYYY-MM-DD
                            // console.log(eventDate); // Output the formatted date
                        } else {
                            console.error('Invalid date:', startStr);
                        }

                        $.ajax({
                            type: "GET",
                            url: "/getFacultyNotes/"+ eventID +"/"+ eventDate,
                            success: function (response) {
                                console.log(response);
                                if (response.status == 200) {
                                    const tooltip = document.createElement('div');
                                    tooltip.innerText = response.ERPNotes.note; // Use your note content here
                                    tooltip.style.position = 'absolute';
                                    tooltip.style.backgroundColor = 'black';
                                    tooltip.style.border = '1px solid black';
                                    tooltip.style.padding = '5px';
                                    tooltip.style.zIndex = 1000;
                                    tooltip.style.display = 'none'; // Initially hidden

                                    // New styles for text wrapping
                                    tooltip.style.maxWidth = '150px'; // Set a maximum width for the tooltip
                                    tooltip.style.whiteSpace = 'normal'; // Allow text wrapping
                                    tooltip.style.overflow = 'hidden'; // Hide overflow text

                                    info.el.appendChild(tooltip); // Append tooltip to event element

                                    // Show the tooltip on mouse enter
                                    info.el.addEventListener('mouseenter', function() {
                                        tooltip.style.display = 'block';
                                    });

                                    // Hide the tooltip on mouse leave
                                    info.el.addEventListener('mouseleave', function() {
                                        tooltip.style.display = 'none';
                                    });
                                  } else {
                                    const tooltip = document.createElement('div');
                                    tooltip.innerText = 'No Note';
                                    tooltip.style.position = 'absolute';
                                    tooltip.style.backgroundColor = 'black';
                                    tooltip.style.border = '1px solid black';
                                    tooltip.style.padding = '5px';
                                    tooltip.style.zIndex = 1000;
                                    tooltip.style.display = 'none'; // Initially hidden
            
                                    info.el.appendChild(tooltip); // Append tooltip to event element
                                     // Show the tooltip on mouse enter
                                    info.el.addEventListener('mouseenter', function() {
                                        tooltip.style.display = 'block';
                                    });

                                    // Hide the tooltip on mouse leave
                                    info.el.addEventListener('mouseleave', function() {
                                        tooltip.style.display = 'none';
                                    });
                                  }
                                }
                            });
                    },
                    dayCellClassNames: function (arg) {
                        var currentDate = moment().startOf("day");
                        var cellDate = moment(arg.date).startOf("day");
                        if (cellDate.isSame(currentDate, "day")) {
                            return "fc-today-highlight";
                        }
                        return "";
                    },

                    eventClick: function (info) {
                        var scheduleType = info.event.extendedProps.description;
                        var id = info.event.id;
                        var startStr = info.event.startStr; // Get the startStr
                        console.log(scheduleType)
                        console.log(id)
                        // Convert the startStr to a Date object
                        var date = new Date(startStr);

                        if (!isNaN(date.getTime())) { // Check if the date is valid
                            var eventDate = date.toISOString().split('T')[0]; // Format to YYYY-MM-DD
                            console.log(eventDate); // Output the formatted date
                        } else {
                            console.error('Invalid date:', startStr);
                        }
                        $.ajax({
                            type: "GET",
                            url: "/checkFacultyNotes/"+ id +"/"+ eventDate,
                            success: function (response) {
                                console.log(response);
                                if (response.status == 200) {
                                    $("#decisionNotesModal").modal("toggle");
                                    var noteID = response.checkERPNotes.noteID;
                                    // Start Edit Note
                                    $(document).on(
                                        "pointerup",
                                        ".editNote",
                                        function (e) {
                                            e.preventDefault();
            
                                            $("#decisionNotesModal").modal(
                                                "hide"
                                            );  
                                                        $("#editNote").val(
                                                            response.checkERPNotes.note
                                                        );
                                                        $("#noteID").val(
                                                            response.checkERPNotes.noteID
                                                        );
                                        }
                                    );

                                    $(document).on(
                                        "click",
                                        ".updateNoteBtn",
                                        function (e) {
                                            e.preventDefault();
                                            
                                            $(this).text("Updating..");
            
                                            var data = {
                                                id:
                                                    $(".id").val(),
                                                    updateNote:
                                                    $(".updateNote").val(),
                                            };
            
                                            $.ajaxSetup({
                                                headers: {
                                                    "X-CSRF-TOKEN": $(
                                                        'meta[name="csrf-token"]'
                                                    ).attr("content"),
                                                },
                                            });
            
                                            $.ajax({
                                                type: "PUT",
                                                url: "/updateNote/" + noteID,
                                                data: data,
                                                dataType: "json",
                                                success: function (response) {
                                                    if (response.status == 400) {
                                                        $("#editNoteIDError").html("");
                                                        $("#editNoteError").html("");
            
                                                        
                                                        $.each(response.errors.id, function(key, err_value) {
                                                            $('#editNoteIDError').append('<li>' + err_value + '</li>');
                                                          });
                                                          $.each(response.errors.updateNote, function(key, err_value) {
                                                            $('#editNoteError').append('<li>' + err_value + '</li>');
                                                          });
            
                                                        $(".updateNoteBtn").text(
                                                            "Update"
                                                        );
                                                    } else if (response.status == 200) {
                                                        Swal.fire({
                                                            icon: "success",
                                                            title: "Successful",
                                                            text: "Note Successfully Updated",
                                                            confirmButtonText: "OK",
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                $(
                                                                    ".updateNoteBtn"
                                                                ).text("Update");
                                                                $(
                                                                    "#updateNoteModal .close"
                                                                ).click();
                                                                location.reload();
                                                            }
                                                        });
                                                    } else if (response.status == 404) {
                                                        $(".updateRegularSchedule").text(
                                                            "Update"
                                                        );
                                                        $(
                                                            "#updateNoteModal .close"
                                                        ).click();
                                                        Swal.fire({
                                                            icon: "warning",
                                                            title: "Warning",
                                                            text: "No Note Found.",
                                                        })
                                                    }
                                                },
                                            });
                                        }
                                    );
                                    // End Edit Note

                                    // Start Delete Note
                                    $(document).on(
                                        "pointerup",
                                        ".deleteNote",
                                        function (e) {
                                            e.preventDefault();
            
                                            $("#decisionNotesModal").modal(
                                                "hide"
                                            );
            
                                            $(document).on(
                                                "click",
                                                ".forceDeleteNote",
                                                function (e) {
                                                    e.preventDefault();
            
                                                    $(this).text("Deleting..");
            
                                                    $.ajaxSetup({
                                                        headers: {
                                                            "X-CSRF-TOKEN": $(
                                                                'meta[name="csrf-token"]'
                                                            ).attr("content"),
                                                        },
                                                    });
            
                                                    $.ajax({
                                                        type: "DELETE",
                                                        url: "/deleteNote/" + noteID,
                                                        dataType: "json",
                                                        success: function (response) {
                                                            // console.log(response);
                                                            if (response.status == 404) {
                                                                $(
                                                                    ".forceDeleteNote"
                                                                ).text("Delete");
                                                                $(
                                                                    "#deleteNoteModal .close"
                                                                ).click();
                                                                Swal.fire({
                                                                    icon: "error",
                                                                    title: "Oops...",
                                                                    text: "No Note Found",
                                                                });
                                                            } else {
                                                                $(
                                                                    ".forceDeleteNote"
                                                                ).text("Delete");
                                                                $(
                                                                    "#deleteNoteModal .close"
                                                                ).click();
                                                                Swal.fire({
                                                                    icon: "success",
                                                                    title: "Successful",
                                                                    text: "Note Deleted",
                                                                    confirmButtonText: "OK",
                                                                }).then((result) => {
                                                                    if (
                                                                        result.isConfirmed
                                                                    ) {
                                                                        location.reload();
                                                                    }
                                                                });
                                                            }
                                                        },
                                                    });
                                                }
                                            );
                                        }
                                    );
                                    // End Delete Note


                                  } else {
                                    $("#addNotesModal").modal("toggle");
                                  }
                                }
                            })
                        
                        
                        $(document).on(
                            "click",
                            ".createNote",
                            function (e) {
                                e.preventDefault();

                     
                        $(this).text('Creating..');
                        var data = {
                            'note': $('.note').val(),
                            scheduleType,
                            id,
                            eventDate,
                        }
                        console.log(data);

                        $.ajaxSetup({
                            headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: "/add-Schedule-Note",
                            data: data,
                            dataType: "json",
                            success: function(response) {
                            console.log(response.errors);
                            if (response.status == 400) {
                                $('#noteError').html("");
                                $('#noteError').addClass('error');
                                $.each(response.errors.note, function(key, err_value) {
                                $('#noteError').append('<li>' + err_value + '</li>');
                                });
                                $('.createNote').text('Create');
                            } else if (response.status == 200) {
                                $('#noteError').html("");
                                $('.createNote').text('Create');
                                $("#addNotesModal .close").click()

                                Swal.fire({
                                icon: "success",
                                title: "Success",
                                text: "Note for Schedule Added Successfully",
                                confirmButtonText: "OK"
                                }).then((result) => {
                                if (result.isConfirmed) {
                                        location.reload();
                                }

                                });
                            }
                            }
                        });
                    });
                }
            });

                calendar.render();
            },
        });
        });
    });

