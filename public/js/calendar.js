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
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
    },
    events: schedules,
    selectable: true,
    select: function(info) {
   
      var start_date= info.startStr;
      var end_date=  info.endStr;
    $("#makeUpScheduleModal").modal('toggle');
   


    $(document).on('click', '.addMakeUpSchedule', function(e) {
    e.preventDefault();
   
      $(this).text('Creating...');
     
      var data = {
        'scheduleTitle': $('.scheduleTitle').val(),
        'startTime': $('.startTime').val(),
        'endTime': $('.endTime').val(),
        start_date,
        end_date
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
            $('#startTimeError').html("");
            $('#startTimeError').addClass('error');
            $('#endTimeError').html("");
            $('#endTimeError').addClass('error');

            $.each(response.errors.scheduleTitle, function(key, err_value) {
              $('#titleError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.startTime, function(key, err_value) {
              $('#startTimeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.endTime, function(key, err_value) {
              $('#endTimeError').append('<li>' + err_value + '</li>');
            });
            $('.addMakeUpSchedule').text('Create');
          }else if (response.status == 200) {
            $('#titleError').html("");
            $('#startTimeError').html("");
            $('#endTimeError').html("");
            Swal.fire({
              icon: "success",
              title: "Success",
              text: "User Created",
              confirmButtonText: "OK"
        }).then((result) => {
      if (result.isConfirmed) {
       $('.addMakeUpSchedule').text('Create');
            $("#addUserModal .close").click()

            location.reload();
        }
    });
          }
        }
        });
    });

  },

  eventClick: function(info) {
    
    var scheduleType =info.event.extendedProps.description
    var id = info.event.id
    if(scheduleType=="regularSchedule"){
     $("#updateRegularScheduleModal").modal('toggle');



      $.ajax({
        type: "GET",
        url: "/createMakeUpSchedule/" + id,
        success: function(response) {
          // console.log(response);
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
            
            // console.log(response.student.name);
            $('#edit_course_code').val(response.schedule.courseCode);
            $('#edit_course_name').val(response.schedule.courseName);
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
      var id = $('#user_ID').val();
      // alert(id);

      var data = {
        'updateFirstName': $('.updateFirstName').val(),
        'updateLastName': $('.updateLastName').val(),
        'updateUserType': $('.updateUserType').val(),
        'updateEmail': $('.updateEmail').val(),
        'userIdNumber': $('.userIdNumber').val(),
      }
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        type: "PUT",
        url: "/updateUser/" + id,
        data: data,
        dataType: "json",
        success: function(response) {
          // console.log(response);
          if (response.status == 400) {
            $('#editFirstNameError').html("");
            $('#editFirstNameError').addClass('error');
            $('#editLastNameError').html("");
            $('#editLastNameError').addClass('error');
            $('#editUserTypeError').html("");
            $('#editUserTypeError').addClass('error');
            $('#editEmailError').html("");
            $('#editEmailError').addClass('error');
            $('#editUserIdError').html("");
            $('#editUserIdError').addClass('error');
            $.each(response.errors.updateFirstName, function(key, err_value) {
              $('#editFirstNameError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.updateLastName, function(key, err_value) {
              $('#editLastNameError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.updateUserType, function(key, err_value) {
              $('#editUserTypeError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.updateEmail, function(key, err_value) {
              $('#editEmailError').append('<li>' + err_value + '</li>');
            });
            $.each(response.errors.userIdNumber, function(key, err_value) {
              $('#editUserIdError').append('<li>' + err_value + '</li>');
            });
            $('.updateUser').text('Update');
          } else if ((response.status == 200)) {
            $('#editFirstNameError').html("");
            $('#editLastNameError').html("");
            $('#editUserTypeError').html("");
            $('#editEmailError').html("");
            $('#editUserIdError').html("");
            Swal.fire({
              icon: "success",
              title: "Successful",
              text: "User Updated",
              buttons: false,
            });

            $('.updateUser').text('Update');
            $("#updateUserModal .close").click()

            // $('#updateUserModal').find('input').val('');

            window.location.href = "{{route('userManagement')}}", 4000;
          } else if ((response.status == 300)) {
            $('#editFirstNameError').html("");
            $('#editLastNameError').html("");
            $('#editUserTypeError').html("");
            $('#editEmailError').html("");
            $('#editUserIdError').html("");
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Invalid email. Please use a CSPC email.",
            });
            $('.updateUser').text('Update');
            $("#updateUserModal .close").click()
          } else if ((response.status == 500)) {
            $('#editFirstNameError').html("");
            $('#editLastNameError').html("");
            $('#editUserTypeError').html("");
            $('#editEmailError').html("");
            $('#editUserIdError').html("");
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Email already exist. Please use another email.",
            });
            $('.updateUser').text('Update');
            $("#updateUserModal .close").click()
          }
        }
      });
    });




       }else if(scheduleType=="makeUpSchedule"){
        $("#modal-add-event").modal('toggle');
       }
  }
});

calendar.render();
}

});

});


