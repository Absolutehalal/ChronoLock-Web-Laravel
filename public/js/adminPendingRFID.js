$(document).ready(function() {
$(document).on('pointerup', '.activateBtn', function(e) {
    e.preventDefault();
    var id = $(this).val();
    // console.log(userID);
    // // alert(userID);

    $.ajax({
      type: "GET",
      url: "/processPendingRFID/" + id,
      success: function(response) {
        // console.log(response);
        if (response.status == 404) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "No RFID Found!!!",
          });
          $("#pendingRFIDModal .close").click()
        } else {
          // console.log(response.student.name);
          $('#rfidCode').val(response.pendingRFID.RFID_Code);
          $('#pendingRFID_ID').val(response.pendingRFID.id);
        }
      }
    });
});

    $(document).on('click', '.activateRFID', function(e) {
        e.preventDefault();

        $(this).text('Activating..');
      
        // alert(id);

        var data = {
          'Rfid_Code': $('.Rfid_Code').val(),
          'userId': $('.userId').val(),
        }
        
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "PUT",
          url: "/activatePendingRFID",
          data: data,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 400) {
              $('#rfidError').html("");
              $('#rfidError').addClass('error');
              $('#userIDError').html("");
              $('#userIDError').addClass('error');
              $.each(response.errors.Rfid_Code, function(key, err_value) {
                $('#rfidError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.userId, function(key, err_value) {
                $('#userIDError').append('<li>' + err_value + '</li>');
              });
              $('.activateRFID').text('Activate');
            } else if ((response.status == 200)) {
              $('#rfidError').html("");
              $('#userIDError').html("");
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "RFID Successfully Activated",
              });

              $('.activateRFID').text('Activate');
              $("#pendingRFIDModal .close").click()

              // $('#updateUserModal').find('input').val('');

               window.location.href = "/RFIDManagementPage"
            }  else if ((response.status == 500)) {
                $('#rfidError').html("");
                $('#userIDError').html("");
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "User Already has Activated RFID",
                });
                $('.activateRFID').text('Activate');
              $("#pendingRFIDModal .close").click()
              }
          }
        });
      });





      $(document).on('pointerup', '.deleteBtn', function() {
        var id = $(this).val();
        $('#deletePendingRFID').val(id);
      });

      $(document).on('click', '.deleteRFID', function(e) {
        e.preventDefault();

        $(this).text('Deleting..');
        var id = $('#deletePendingRFID').val();

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "DELETE",
          url: "/deletePendingRFID/" + id,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              $('.deleteRFID').text('Delete');
              $("#deletePendingRFIDModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No RFID Found",
              });

            } else {
              $('.deleteRFID').text('Delete');
              $("#deletePendingRFIDModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "RFID Deleted",
              });


          location.reload();
            }
          }
        });
      });
    });
