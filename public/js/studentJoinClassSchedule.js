$(document).ready(function() {

    $(document).on('click', '.editClassSchedule', function(e) {
        e.preventDefault();
    
        var id = $(this).val();
      
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
              $('#program').val(response.classList.course);
              $('#yearAndSection').val(response.classList.year+response.classList.section);
              $('#startTimeAndEndTime').val(startTime+"-"+ endTime);
              document.getElementById('instructorAvatar').src = response.classList.avatar;

              
            }
          }
        });
        });
});