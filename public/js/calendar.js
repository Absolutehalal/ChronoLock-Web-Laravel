/**
 * WEBSITE: https://themefisher.com
 * TWITTER: https://twitter.com/themefisher
 * FACEBOOK: https://www.facebook.com/themefisher
 * GITHUB: https://github.com/themefisher/
 */

/* ====== Index ======

1. CALENDAR JS

====== End ======*/

$(document).ready(function() {

  var calendarEl = document.getElementById("calendar");
  var year = new Date().getFullYear();
  var month = new Date().getMonth() + 1;
  function n(n) {
    return n > 9 ? "" + n : "0" + n;
  }
  var month = n(month);

  $.ajax({
    type: "GET",
    url: "/getSchedules",
    success: function(response) {
    var schedules = response.ERPSchedules;
  console.log(schedules);
   
  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: schedules,
    selectable: true,
    select: function(info) {
   
      var start_date= info.startStr;
      var end_date=  info.endStr;

      var currentDate=moment().format('YYYY-MM-DD');
      if(start_date<currentDate){
        Swal.fire({
          icon: "warning",
          title: "Warning",
          text: "You Can't Create Schedule in past dates",
          timer: 3000,
          timerProgressBar: true
    })
      }else{
    $("#makeUpScheduleModal").modal('toggle');
   


    $(document).on('click', '.addMakeUpSchedule', function(e) {
    e.preventDefault();
   
      $(this).text('Creating...');
     
      var data = {
        'scheduleTitle': $('.scheduleTitle').val(),
        'program': $('.program').val(),
        'year': $('.year').val(),
        'section': $('.section').val(),
        'makeUpScheduleStartTime': $('.makeUpScheduleStartTime').val(),
        'makeUpScheduleEndTime': $('.makeUpScheduleEndTime').val(),
        start_date,
        end_date,
        'faculty': $('.faculty').val(),
      }
      // console.log(data)

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });


      $.ajax({
        type: "POST",
        url: "/createMakeUpSchedule",
        data: data,
        dataType: "json",
        success: function(response) {
          //  console.log(response);
          if (response.status == 400) {
            $('#titleError').html("");
            $('#titleError').addClass('error');
            $('#programError').html("");
            $('#programError').addClass('error');
            $('#yearError').html("");
            $('#yearError').addClass('error');
            $('#sectionError').html("");
            $('#sectionError').addClass('error');
            $('#startTimeError').html("");
            $('#startTimeError').addClass('error');
            $('#endTimeError').html("");
            $('#endTimeError').addClass('error');
            $('#facultyError').html("");
            $('#facultyError').addClass('error');

            $.each(response.errors.scheduleTitle, function(key, err_value) {
              $('#titleError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.program, function(key, err_value) {
              $('#programError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.year, function(key, err_value) {
              $('#yearError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.section, function(key, err_value) {
              $('#sectionError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.makeUpScheduleStartTime, function(key, err_value) {
              $('#startTimeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.makeUpScheduleEndTime, function(key, err_value) {
              $('#endTimeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.faculty, function(key, err_value) {
              $('#facultyError').append('<li>' + err_value + '</li>');
            });
            $('.addMakeUpSchedule').text('Create');
          }else if (response.status == 200) {
            $('#titleError').html("");
            $('#programError').html("");
            $('#yearError').html("");
            $('#sectionError').html("");
            $('#startTimeError').html("");
            $('#endTimeError').html("");
            $('#facultyError').html("");
            Swal.fire({
              icon: "success",
              title: "Success",
              text: "Make up Schedule Created",
              confirmButtonText: "OK"
        }).then((result) => {
      if (result.isConfirmed) {
       $('.addMakeUpSchedule').text('Create');
            $("#makeUpScheduleModal .close").click()

            location.reload();
        }
    });
          }
        }
        });
    });
  }
  },

  eventClick: function(info) {

    var scheduleType =info.event.extendedProps.description
    var id = info.event.id   

    if(scheduleType=="regularSchedule"){
    $("#decisionRegularScheduleModal").modal('toggle');
    }else if (scheduleType=="makeUpSchedule"){
      $("#decisionMakeUpScheduleModal").modal('toggle');
    }
  // -----------Start delete make up schedule-----------
    $(document).on('click', '.deleteMakeUpSchedule', function(e) {
      e.preventDefault();

        $("#decisionMakeUpScheduleModal").modal('hide');

      $(document).on('click', '.forceDeleteMakeUpSchedule', function(e) {
        e.preventDefault();

        $(this).text('Deleting..');

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "DELETE",
          url: "/deleteMakeUpSchedule/" + id,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              $('.forceDeleteMakeUpSchedule').text('Delete');
              $("#deleteMakeUpScheduleModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Make Up Schedule Found",
              });

            } else {
              $('.forceDeleteMakeUpSchedule').text('Delete');
              $("#deleteMakeUpScheduleModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "Make Up Schedule Deleted",
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

    });
  // -----------End delete make up schedule-----------

    // -----------Start delete regular schedule-----------
    $(document).on('click', '.deleteRegularSchedule', function(e) {
      e.preventDefault();

        $("#decisionRegularScheduleModal").modal('hide');

      $(document).on('click', '.forceDeleteRegularSchedule', function(e) {
        e.preventDefault();

        $(this).text('Deleting..');

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "DELETE",
          url: "/deleteRegularSchedule/" + id,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              $('.forceDeleteRegularSchedule').text('Delete');
              $("#deleteUserModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Schedule Found",
              });

            } else {
              $('.forceDeleteRegularSchedule').text('Delete');
              $("#deleteRegularScheduleModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "Schedule Deleted",
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

    });

      // -----------End delete regular schedule-----------


       // -----------Start edit regular schedule-----------
    $(document).on('mouseover', '.editRegularSchedule', function(e) {
      e.preventDefault();
      
      $(".editRegularSchedule").click(function(){
        $("#decisionRegularScheduleModal").modal('hide');
      })

      $.ajax({
        type: "GET",
        url: "/editRegularSchedule/" + id,
        success: function(response) {
          //  console.log(response.schedule.day);
          if (response.status == 404) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "No Schedule Found!!!",
            });
            $("#updateRegularScheduleModal .close").click()
          } else {
            var strtTime = new Date("0000, 1, 1"+" "+response.schedule.startTime);
            var ndTime = new Date("0000, 1, 1"+" "+response.schedule.endTime);

            var strtDate = new Date(response.schedule.startDate);
            var ndDate = new Date(response.schedule.endDate);


            startDate=strtDate.toDateString().replace(/^\S+\s/,'');
            endDate=ndDate.toDateString().replace(/^\S+\s/,'');

            startTime=strtTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric',  hour12: true });
            endTime=ndTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric',  hour12: true });

             console.log(response.schedule);
            $('#edit_course_code').val(response.schedule.courseCode);
            $('#edit_course_name').val(response.schedule.courseName);
            $('#edit_weekday').val(response.schedule.day);
            $('.startTime').val(startTime);
            $('.endTime').val(endTime);
            $('.startDate').val(startDate);
            $('.endDate').val(endDate);
            $('#regularScheduleID').val(response.schedule.scheduleID);
          }
        }
      });


    $(document).on('click', '.updateRegularSchedule', function(e) {
      e.preventDefault();

      $(this).text('Updating..');
      
      // alert(id);

      var data = {
        'updateCourseCode': $('.updateCourseCode').val(),
        'updateCourseName': $('.updateCourseName').val(),
        'startTime': $('.startTime').val(),
        'endTime': $('.endTime').val(),
        'startDate': $('.startDate').val(),
        'endDate': $('.endDate').val(),
        'updateWeekDay': $('.updateWeekDay').val(),
      }
      console.log(data)
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "PUT",
        url: "/updateRegularSchedule/" +id,
        data: data,
        dataType: "json",
        success: function(response) {
          // console.log(response);
          if (response.status == 400) {
            $('#editCourseCodeError').html("");
            $('#editCourseCodeError').addClass('error');
            $('#editCourseNameError').html("");
            $('#editCourseNameError').addClass('error');
            $('#startTimeError').html("");
            $('#startTimeError').addClass('error');
            $('#endTimeError').html("");
            $('#endTimeError').addClass('error');
            $('#startDateError').html("");
            $('#startDateError').addClass('error');
            $('#endDateError').html("");
            $('#endDateError').addClass('error');
            $('#editWeekDayError').html("");
            $('#editWeekDayError').addClass('error');
            $.each(response.errors.updateCourseCode, function(key, err_value) {
              $('#editCourseCodeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.updateCourseName, function(key, err_value) {
              $('#editCourseNameError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.startTime, function(key, err_value) {
              $('#startTimeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.endTime, function(key, err_value) {
              $('#endTimeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.startDate, function(key, err_value) {
              $('#startDateError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.endDate, function(key, err_value) {
              $('#endDateError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.updateWeekDay, function(key, err_value) {
              $('#editWeekDayError').append('<li>' + err_value + '</li>');
            });
            $('.updateRegularSchedule').text('Update');
          } else if ((response.status == 200)) {
            $('#editCourseCodeError').html("");
            $('#editCourseNameError').html("");
            $('#startTimeError').html("");
            $('#endTimeError').html("");
            $('#startDateError').html("");
            $('#endDateError').html("");
            $('#editWeekDayError').html("");
            Swal.fire({
              icon: "success",
              title: "Successful",
              text: "Regular Schedule Updated",
              confirmButtonText: "OK"
        }).then((result) => {
      if (result.isConfirmed) {
        $('.updateRegularSchedule').text('Update');
            $("#updateRegularScheduleModal .close").click()

            location.reload();
        }
    });

          }
        }
      });
    });

      });
 // -----------End edit regular schedule-----------

 // -----------Start edit Make up schedule-----------

 $(document).on('mouseover', '.editMakeUpSchedule', function(e) {
  e.preventDefault();
  
  $(".editMakeUpSchedule").click(function(){
    $("#decisionMakeUpScheduleModal").modal('hide');
  })

  $.ajax({
    type: "GET",
    url: "/editMakeUpSchedule/" + id,
    success: function(response) {
      //  console.log(response.schedule.day);
      if (response.status == 404) {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "No Make Up Schedule Found!!!",
        });
        $("#updateMakeUpScheduleModal .close").click()
      } else {
        var strtTime = new Date("0000, 1, 1"+" "+response.makeUpSchedule.startTime);
        var ndTime = new Date("0000, 1, 1"+" "+response.makeUpSchedule.endTime);

        startTime=strtTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric',  hour12: true });
        endTime=ndTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric',  hour12: true });

         console.log(response.makeUpSchedule);
        $('#edit_schedule_title').val(response.makeUpSchedule.scheduleTitle);
        $('#edit_schedule_program').val(response.makeUpSchedule.program);
        $('#edit_schedule_year').val(response.makeUpSchedule.year);
        $('#edit_schedule_section').val(response.makeUpSchedule.section);
        $('.updateStartTime').val(startTime);
        $('.updateEndTime').val(endTime);
        $('#makeUpScheduleID').val(response.makeUpSchedule.scheduleID);
      }
    }
  });


$(document).on('click', '.updateMakeUpSchedule', function(e) {
  e.preventDefault();

  $(this).text('Updating..');
  
  // alert(id);

  var data = {
    'updateScheduleTitle': $('.updateScheduleTitle').val(),
    'updateProgram': $('.updateProgram').val(),
    'updateYear': $('.updateYear').val(),
    'updateSection': $('.updateSection').val(),
    'updateStartTime': $('.updateStartTime').val(),
    'updateEndTime': $('.updateEndTime').val(),
  }
  console.log(data)
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  $.ajax({
    type: "PUT",
    url: "/updateMakeUpSchedule/" +id,
    data: data,
    dataType: "json",
    success: function(response) {
      // console.log(response);
      if (response.status == 400) {
        $('#editMakeUpScheduleTitleError').html("");
        $('#editMakeUpScheduleTitleError').addClass('error');
        $('#editProgramError').html("");
        $('#editProgramError').addClass('error');
        $('#editYearError').html("");
        $('#editYearError').addClass('error');
        $('#editSectionError').html("");
        $('#editSectionError').addClass('error');
        $('#editStartTimeError').html("");
        $('#editStartTimeError').addClass('error');
        $('#editEndTimeError').html("");
        $('#editEndTimeError').addClass('error');
        $.each(response.errors.updateScheduleTitle, function(key, err_value) {
          $('#editCourseCodeError').append('<li>' + err_value + '</li>');
        });
        $.each(response.errors.updateProgram, function(key, err_value) {
          $('#editCourseNameError').append('<li>' + err_value + '</li>');
        });
        $.each(response.errors.updateYear, function(key, err_value) {
          $('#startTimeError').append('<li>' + err_value + '</li>');
        });
        $.each(response.errors.updateSection, function(key, err_value) {
          $('#endTimeError').append('<li>' + err_value + '</li>');
        });
        $.each(response.errors.updateStartTime, function(key, err_value) {
          $('#startDateError').append('<li>' + err_value + '</li>');
        });
        $.each(response.errors.updateEndTime, function(key, err_value) {
          $('#endDateError').append('<li>' + err_value + '</li>');
        });
        $('.updateMakeUpSchedule').text('Update');
      } else if ((response.status == 200)) {
        $('#updateScheduleTitle').html("");
        $('#updateProgram').html("");
        $('#updateYear').html("");
        $('#updateSection').html("");
        $('#updateStartTime').html("");
        $('#updateEndTime').html("");
        $("#updateMakeUpScheduleModal .close").click()
        $('.updateMakeUpSchedule').text('Update');
        Swal.fire({
          icon: "success",
          title: "Successful",
          text: "Make Up Schedule Updated",
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

  });


  // -----------End edit Make up schedule-----------

    }
});

calendar.render();
}

});

});


