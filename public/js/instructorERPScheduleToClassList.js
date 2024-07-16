$(document).ready(function() {

    $(document).on('click', '.editERPSchedule', function(e) {
        e.preventDefault();
        var id = $(this).val();
        console.log(id);
        // // alert(userID);
   

        $.ajax({
          type: "GET",
          url: "/editInstructorClassList/"+id,
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Schedule Record Found!!!",
                timer: 5000,
                timerProgressBar: true
              });
              $("#scheduleModal .close").click()
            } else {
              //  console.log(response.attendance);
              $('#scheduleID').val(response.schedule.scheduleID); 
              $('#courseCode').val(response.schedule.courseCode);
              $('#courseName').val(response.schedule.courseName); 
              $('#program').val(response.schedule.program); 
              $('#year').val(response.schedule.year); 
              $('#section').val(response.schedule.section); 
              
            }
          }
        });

      });


      $(document).on('click', '.createClassList', function(e) {
        e.preventDefault();
        $(this).text('Creating....');
        var data = {
          'courseCode': $('#courseCode').val(),
          'program': $('#program').val(),
          'year': $('#year').val(),
          'section': $('#section').val(),
          'userID': $('#userID').val(),
          'semester': $('#semester').val(),
          'enrollmentKey': $('#enrollmentKey').val(),
          'scheduleID': $('#scheduleID').val(),
        }
       console.log(data);
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "POST",
          url: "/instructorClassSchedules",
          data: data,
          dataType: "json",
          success: function(response) {
            console.log(response);
            if (response.status == 400) {
              $('#courseCodeError').html("");
              $('#courseCodeError').addClass('error');
              $('#programError').html("");
              $('#programError').addClass('error');
              $('#yearError').html("");
              $('#yearError').addClass('error');
              $('#sectionError').html("");
              $('#sectionError').addClass('error');
              $('#userIDError').html("");
              $('#userIDError').addClass('error');
              $('#semesterError').html("");
              $('#semesterError').addClass('error');
              $('#enrollmentKeyError').html("");
              $('#enrollmentKeyError').addClass('error');
              $('#scheduleIDError').html("");
              $('#scheduleIDError').addClass('error');
              $.each(response.errors.courseCode, function(key, err_value) {
                $('#courseCodeError').append('<li>' + err_value + '</li>');
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
              $.each(response.errors.userID, function(key, err_value) {
                $('#userIDError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.semester, function(key, err_value) {
                $('#semesterError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.enrollmentKey, function(key, err_value) {
                $('#enrollmentKeyError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.scheduleID, function(key, err_value) {
                $('#scheduleIDError').append('<li>' + err_value + '</li>');
              });
              $('.createClassList').text('Create');
            } else if (response.status == 200) {
              $('#courseCodeError').html("");
              $('#programError').html("");
              $('#yearError').html("");
              $('#sectionError').html("");
              $('#userIDError').html("");
              $('#semesterError').html("");
              $('#enrollmentKeyError').html("");
              $('#scheduleIDError').html("");
              Swal.fire({
                icon: "success",
                title: "Success",
                text: "Class List Created",
              });
              $('.createClassList').text('Create');
              $("#scheduleModal .close").click()

              location.reload();
            }else if (response.status == 300) {
              $('#courseCodeError').html("");
              $('#programError').html("");
              $('#yearError').html("");
              $('#sectionError').html("");
              $('#userIDError').html("");
              $('#semesterError').html("");
              $('#enrollmentKeyError').html("");
              $('#scheduleIDError').html("");
              $('.createClassList').text('Create');
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Invalid Schedule. This Schedule is not for you!!!",
                timer: 5000,
                timerProgressBar: true
              });


            }
          }
        });

      });


});