$(document).ready(function() {

$(document).on('click', '.createRegularSchedule', function(e) {
    e.preventDefault();
    $(this).text('Creating..');
    var data = {
      'courseCode': $('.courseCode').val(),
      'courseName': $('.courseName').val(),
      'scheduleProgram': $('.scheduleProgram').val(),
      'scheduleYear': $('.scheduleYear').val(),
      'scheduleSection': $('.scheduleSection').val(),
      'scheduleStartTime': $('.scheduleStartTime').val(),
      'scheduleEndTime': $('.scheduleEndTime').val(),
      'scheduleStartDate': $('.scheduleStartDate').val(),
      'scheduleEndDate': $('.scheduleEndDate').val(),
      'scheduleWeekDay': $('.scheduleWeekDay').val(),
      'scheduleFaculty': $('.scheduleFaculty').val(),
     
    }
   
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "POST",
      url: "/createRegularSchedule",
      data: data,
      dataType: "json",
      success: function(response) {
        // console.log(response);
        if (response.status == 400) {
          $('#courseCodeError').html("");
          $('#courseCodeError').addClass('error');
          $('#courseNameError').html("");
          $('#courseNameError').addClass('error');
          $('#scheduleProgramError').html("");
          $('#scheduleProgramError').addClass('error');
          $('#scheduleYearError').html("");
          $('#scheduleYearError').addClass('error');
          $('#scheduleSectionError').html("");
          $('#scheduleSectionError').addClass('error');
          $('#scheduleStartTimeError').html("");
          $('#scheduleStartTimeError').addClass('error');
          $('#scheduleEndTimeError').html("");
          $('#scheduleEndTimeError').addClass('error');
          $('#scheduleStartDateError').html("");
          $('#scheduleStartDateError').addClass('error');
          $('#scheduleEndDateError').html("");
          $('#scheduleEndDateError').addClass('error');
          $('#scheduleEditWeekDayError').html("");
          $('#scheduleEditWeekDayError').addClass('error');
          $('#scheduleFacultyError').html("");
          $('#scheduleFacultyError').addClass('error');
          
          $.each(response.errors.courseCode, function(key, err_value) {
            $('#courseCodeError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.courseName, function(key, err_value) {
            $('#courseNameError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleProgram, function(key, err_value) {
            $('#scheduleProgramError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleYear, function(key, err_value) {
            $('#scheduleYearError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleSection, function(key, err_value) {
            $('#scheduleSectionError').append('<li>' + err_value + '</li>');
          });

          $.each(response.errors.scheduleStartTime, function(key, err_value) {
            $('#scheduleStartTimeError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleEndTime, function(key, err_value) {
            $('#scheduleEndTimeError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleStartDate, function(key, err_value) {
            $('#scheduleStartDateError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleEndDate, function(key, err_value) {
            $('#scheduleEndDateError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleWeekDay, function(key, err_value) {
            $('#scheduleEditWeekDayError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.scheduleFaculty, function(key, err_value) {
            $('#scheduleFacultyError').append('<li>' + err_value + '</li>');
          });
          $('.createRegularSchedule').text('Create');
        } else if (response.status == 200) {
            $('#courseCodeError').html("");
            $('#courseNameError').html("");
            $('#scheduleProgramError').html("");
            $('#scheduleYearError').html("");
            $('#scheduleSectionError').html("");
            $('#scheduleStartTimeError').html("");
            $('#scheduleEndTimeError').html("");
            $('#scheduleStartDateError').html("");
            $('#scheduleEndDateError').html("");
            $('#scheduleEditWeekDayError').html("");
            $('#scheduleFacultyError').html("");
            $('.createRegularSchedule').text('Create');
          $("#addRegularScheduleModal .close").click()

          Swal.fire({
            icon: "success",
            title: "Success",
            text: "Regular Schedule Created",
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