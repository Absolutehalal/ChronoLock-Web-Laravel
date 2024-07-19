
  $(document).ready(function() {

$(document).on('mouseover', '.editAttendanceBtn', function(e) {
    e.preventDefault();
    var id = $(this).val();
    // console.log(userID);
    // // alert(userID);

    $.ajax({
      type: "GET",
      url: "/editInstructorAttendance/"+id,
      success: function(response) {
        // console.log(response);
        if (response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No Attendance Record Found!!!",
          });
          $("#updateAttendanceModal .close").click()
        } else {
          //  console.log(response.attendance);
          $('#edit_instructorID').val(response.attendance.userID);
          $('#edit_Remark').val(response.attendance.remark); 
          $('#attendance_ID').val(response.attendance.attendanceID); 
        }
      }
    });

  });


  $(document).on('click', '.updateAttendance', function(e) {
    e.preventDefault();

    $(this).text('Updating..');
    var attendanceID = $('#attendance_ID').val();
    // alert(attendanceID);

    var data = {
      'updateUserID': $('.updateUserID').val(),
      'updateRemark': $('.updateRemark').val(),
     
    }
   console.log(data);
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

    $.ajax({
          type:"PUT",
          url:"/updateInstructorAttendance/" + attendanceID,
          data: data,
          dataType: "json",
          success: function(response) {
        // console.log(response);
        if (response.status == 400) {
          $('#editIDError').html("");
          $('#editIDError').addClass('error');
          $('#editRemarkError').html("");
          $('#editRemarkError').addClass('error');
          $.each(response.errors.updateUserID, function(key, err_value) {
            $('#editIDError').append('<li>' + err_value + '</li>');
          });
          $.each(response.errors.updateRemark, function(key, err_value) {
            $('#editRemarkError').append('<li>' + err_value + '</li>');
          });
          $('.updateAttendance').text('Update');

        } else if((response.status == 404)){
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No Attendance Record Found!!!",
          });
          $("#updateAttendanceModal .close").click()
          // console.log(response.message);
        }else if ((response.status == 200)) {
          $('#editIDError').html("");
          $('#editRemarkError').html("");
          Swal.fire({
            icon: "success",
            title: "Successful",
            text: "Attendance Successfully Updated!",
          });
          // console.log(response.attendance);
          $('.updateAttendance').text('Update');
          $("#updateAttendanceModal .close").click()
        location.reload();
        // setTimeout(function(){
        //     window.location.reload();
        //  }, 5000);
        }
      }
    });
  });


  $(document).on('mouseover', '.deleteAttendanceBtn', function() {
    var id = $(this).val(); 
    $('#deleteAttendanceID').val(id);
  });



  $(document).on('click', '.deleteAttendance', function(e) {
    e.preventDefault();

    $(this).text('Deleting..');
    var id = $('#deleteAttendanceID').val();

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
      type: "DELETE",
      url: "/deleteInstructorAttendance/" + id,
      dataType: "json",
      success: function(response) {
        // console.log(response);
        if (response.status == 404) {
          $('.deleteAttendance').text('Delete');
          $("#deleteAttendanceModal .close").click()
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No Attendance Found",
          });

        } else {
          $('.deleteAttendance').text('Delete');
          $("#deleteAttendanceModal .close").click()
          Swal.fire({
            icon: "success",
            title: "Successful",
            text: "Attendance Deleted",
            buttons: false,
          });
          location.reload();
        }
      }
    });
  });
});

