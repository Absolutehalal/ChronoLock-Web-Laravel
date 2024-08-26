document.addEventListener("DOMContentLoaded", function () {
    var calendarEl = document.getElementById("instructorCalendar");

    $.ajax({
        type: "GET",
        url: "/get-Faculty-Schedules",
        success: function (response) {
            var schedules = response.ERPSchedules;
            console.log(schedules);
            var calendar= new FullCalendar.Calendar(calendarEl, {
                plugins: ["dayGrid"],
                defaultView: "dayGridMonth",

                events: schedules,
                selectable: true,
                // eventTimeFormat: {
                //     hour: "numeric",
                //     minute: "2-digit",
                //     meridiem: "short",
                // },
                eventRender: function (info) {
                    let startTime = info.event.start.toLocaleTimeString([], {
                        hour: "numeric",
                        minute: "2-digit",
                        meridiem: "short",
                    });
                    let endTime = info.event.end.toLocaleTimeString([], {
                        hour: "numeric",
                        minute: "2-digit",
                        meridiem: "short",
                    });

                    // Create custom content with time on top and title below
                    let timeElement = document.createElement("div");
                    timeElement.innerText = startTime + " - " + endTime;

                    let titleElement = document.createElement("div");
                    titleElement.innerText = info.event.title;

                    // Clear the existing content and append the custom content
                    info.el.innerHTML = "";
                    info.el.appendChild(timeElement);
                    info.el.appendChild(titleElement);
                },
            });

            calendar.render();
        },
    });
});
