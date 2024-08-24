$(document).ready(function () {


    $(document).on('pointerup', '.withoutClassBtn', function() {
        var id = $(this).val();
        $('#noClasses_ID').val(id);
      console.log(id)
      });


    $(document).on('click', '.noClasses', function(e) {
        e.preventDefault();
    
        $(this).text('Loading...');
        var id = $('#noClasses_ID').val();
        console.log(id)
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    
        $.ajax({
          type: "PUT",
          url: "/adminNoClass-Class-List/" + id,
          dataType: "json",
          success: function(response) {
          
            if (response.status == 404) {
              $('.noClasses').text('Sure');
              $("#withoutClassModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Class Found",
              });
    
            } else {
              $('.noClasses').text('Sure');
              $("#withoutClassModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "Class set to No classes",
                buttons: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            })
    
           
            }
          }
        });
      });

      $(document).on('pointerup', '.withClassBtn', function() {
        var id = $(this).val();
        $('#withClasses_ID').val(id);
       
      });


    $(document).on('click', '.withClasses', function(e) {
        e.preventDefault();
    
        $(this).text('Loading...');
        var id = $('#withClasses_ID').val();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    
        $.ajax({
          type: "PUT",
          url: "/adminWithClass-Class-List/" + id,
          dataType: "json",
          success: function(response) {
         
            if (response.status == 404) {
              $('.withClasses').text('Sure');
              $("#withClassModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No Class Found",
              });
    
            } else {
              $('.withClasses').text('Sure');
              $("#withClassModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "Class set to with Classes",
                buttons: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            })
            }
          }
        });
      });
});