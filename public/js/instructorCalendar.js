
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('instructorCalendar');
    
     $.ajax({
      type: "GET",
      url: "/get-Faculty-Schedules",
      success: function (response) {
          var schedules = response.ERPSchedules;
          console.log(schedules);
          var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid' ],
            defaultView: 'dayGridMonth', 
        
            events: schedules,
            selectable: true,
            eventTimeFormat: { 
                hour: "numeric",
                minute: "2-digit",
                meridiem: "short",
            }

})


    calendar.render();
  },
   })
})

