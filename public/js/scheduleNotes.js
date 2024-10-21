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

                        $("#addNotesModal").modal("toggle");
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

