$(document).ready(function() {


  $('.nav-link').on('click', function (e) {
    localStorage.setItem('activeTab', $(this).attr('href'));
    $(this).tab('show');
});

var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#pills-tab button[href="' + activeTab + '"]').tab('show');
        }

$(document).on('mouseover', '.editAttendanceBtn', function(e) {
    e.preventDefault();

    var id = $(this).val();
  
    $.ajax({
      type: "GET",
      url: "/instructorEditStudentAttendance/"+id,
      dataType: "json",
      success: function(response) {
        if (response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No Attendance Record Found!!!",
          });
          $("#studentUpdateAttendanceModal .close").click()
        } else {
          //  console.log(response.attendance);
          $('#attendanceID').val(response.attendance.attendanceID);
          $('#edit_studentID').val(response.attendance.userID);
          $('#edit_remark').val(response.attendance.remark); 
        }
      }
    });
    });




    $(document).on('click', '.updateAttendance', function(e) {
      e.preventDefault();
  
      $(this).text('Updating..');
      var attendanceID = $('#attendanceID').val();
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
            url:"/instructorUpdateStudentAttendance/" + attendanceID,
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
            $("#studentUpdateAttendanceModal .close").click()
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
            $("#studentUpdateAttendanceModal .close").click()
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
        url: "/instructorDeleteStudentAttendance/" + id,
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
            $("#studentDeleteAttendanceModal .close").click()
            Swal.fire({
              icon: "success",
              title: "Successful",
              text: "Attendance Deleted",
              timer: 5000,
              timerProgressBar: true
            });
            location.reload();
          }
        }
      });
    });




    $(document).on('mouseover', '.editListBtn', function(e) {
      e.preventDefault();
  
      var listID = $(this).val();
    
      $.ajax({
        type: "GET",
        url: "/instructorEditStudentList/"+listID,
        dataType: "json",
        success: function(response) {
          if (response.status == 404) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "No Student Record Found!!!",
              timer: 5000,
              timerProgressBar: true
            });
            $("#studentUpdateListModal .close").click()
          } else {
            //  console.log(response.attendance);
            $('#listID').val(response.record.MIT_ID);
            $('#edit_studentListID').val(response.record.userID);
            $('#edit_Status').val(response.record.status); 
          }
        }
      });
      });

      $(document).on('click', '.updateList', function(e) {
        e.preventDefault();
   
        $(this).text('Updating..');
        var recordID = $('#listID').val();
        // alert(attendanceID);
    
        var data = {
          'updateListUserID': $('.updateListUserID').val(),
          'updateStatus': $('.updateStatus').val(),
         
        }
       console.log(data);
        $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
    
        $.ajax({
              type:"PUT",
              url:"/instructorUpdateStudentList/" + recordID,
              data: data,
              dataType: "json",
              success: function(response) {
            // console.log(response);
            if (response.status == 400) {
              $('#editListIDError').html("");
              $('#editListIDError').addClass('error');
              $('#editStatusError').html("");
              $('#editStatusError').addClass('error');
              $.each(response.errors.updateListUserID, function(key, err_value) {
                $('#editListIDError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.updateStatus, function(key, err_value) {
                $('#editStatusError').append('<li>' + err_value + '</li>');
              });
              $('.updateList').text('Update');
    
            } else if((response.status == 404)){
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Student Record Found!!!",
                timer: 5000,
                timerProgressBar: true
              });
              $("#studentUpdateListModal .close").click()
              // console.log(response.message);
            }else if ((response.status == 200)) {
              $('#editListIDError').html("");
              $('#editStatusError').html("");
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "Student Record Successfully Updated!",
                timer: 5000,
                timerProgressBar: true
              });
              // console.log(response.attendance);
              $('.updateList').text('Update');
              $("#studentUpdateListModal .close").click();
              location.reload();
            // setTimeout(function(){
            //     window.location.reload();
            //  }, 5000);
            }
          }
        });
      });


      $(document).on('mouseover', '.deleteListBtn', function() {
        var id = $(this).val(); 
        $('#deleteListID').val(id);
      });
    
    
    
      $(document).on('click', '.deleteStudentRecordList', function(e) {
        e.preventDefault();
    
        $(this).text('Deleting..');
        var id = $('#deleteListID').val();
    
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
        $.ajax({
          type: "DELETE",
          url: "/instructorDeleteStudentList/" + id,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              $('.deleteStudentRecordList').text('Delete');
              $("#studentDeleteListModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Student Record Found",
                timer: 5000,
                timerProgressBar: true
              });
    
            } else {
              $('.deleteStudentRecordList').text('Delete');
              $("#studentDeleteListModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "Student Record Deleted",
                timer: 5000,
                timerProgressBar: true
              });
              location.reload();
            }
          }
        });
      });
});