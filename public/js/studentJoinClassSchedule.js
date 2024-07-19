$(document).ready(function() {

    $(document).on('pointerup', '.editClassSchedule', function(e) {
        e.preventDefault();

     

        var id = $(this).val();
        var hideID = btoa(id);
        let classState = $(this).find(".overlay").text();
        console.log(classState);
        if(classState =="Enrolled"){
        
          Swal.fire({
            title: 'Redirecting...',
            html: 'Please wait...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            timer:2000,
            didOpen: () => {
              Swal.showLoading()
            }
          });

       window.location.href = "/student-view-attendance/"+ hideID

        }else if(classState =="Get Access"){
        target = $(".editClassSchedule").attr('data-target');
          $(target).modal('show');
    
        $.ajax({
          type: "GET",
          url: "/studentEditSchedule/"+id,
          dataType: "json",
          success: function(response) {
            if (response.status == 404) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Class Schedule Found!!!",
                
              });
              $("#join-class-schedule-modal .close").click()
            } else {
             // console.log(response.classList);
             var strtTime = new Date("0000, 1, 1"+" "+response.classList.startTime);
             var ndTime = new Date("0000, 1, 1"+" "+response.classList.endTime);
           
             startTime=strtTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric',  hour12: true });
             endTime=ndTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric',  hour12: true });
            
              $('#instFirstNameAndLastName').val(response.classList.instFirstName+" "+response.classList.instLastName);
              $('#classID').val(response.classList.classID);
              $('#program').val(response.classList.program);
              $('#yearAndSection').val(response.classList.year+response.classList.section);
              $('#startTimeAndEndTime').val(startTime+"-"+ endTime);
              document.getElementById('instructorAvatar').src = response.classList.avatar;
            }
          }
        });
        }
        });

        $(document).on('click', '.createMasterList', function(e) {
          e.preventDefault();
          $(this).text('Enrolling...');
          var data = {

            'enrollmentKey': $('#enrollmentKey').val(),
            'classID': $('#classID').val(),
          }
         console.log(data);
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
  
          $.ajax({
            type: "POST",
            url: "/student-view-schedule",
            data: data,
            dataType: "json",
            success: function(response) {
              console.log(response);
              if (response.status == 400) {
                $('#enrollmentKeyError').html("");
                $('#enrollmentKeyError').addClass('error');
                $('#classIDError').html("");
                $('#classIDError').addClass('error');
                $.each(response.errors.enrollmentKey, function(key, err_value) {
                  $('#enrollmentKeyError').append('<li>' + err_value + '</li>');
                });
                $.each(response.errors.classID, function(key, err_value) {
                  $('#classIDError').append('<li>' + err_value + '</li>');
                });
                $('.createMasterList').text('Enroll');
              } else if (response.status == 200) {
              
                $('#enrollmentKeyError').html("");
                $('#classIDError').html("");
                Swal.fire({
                  icon: "success",
                  title: "Success",
                  text: "Successfully enrolled to Class Schedule",
                  confirmButtonText: "OK"
                }).then((result) => {
                  if (result.isConfirmed) { 
                     $('.createClassList').text('Enroll');
                $("#join-class-schedule-modal .close").click();
                location.reload()
                }
              
              });
               ;
              }else if (response.status == 300) {
                $('#enrollmentKeyError').html("");
                $('#classIDError').html("");
                $('.createMasterList').text('Enroll');
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Incorrect Enrollment Key!!!",
                });
  
              }
            }
          });
  
        });
});