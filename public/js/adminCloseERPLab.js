$(document).ready(function() {

    $(document).on('click', '.closeERPButton', function(e) {
        e.preventDefault();
        $(this).text('Closing..');
       
       
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
        $.ajax({
          type: "PUT",
          url: "/closeERPLaboratory",
          success: function(response) {
            // console.log(response);
            if (response.status == 200) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "ERP Laboratory Closed",
                    confirmButtonText: "OK"
                    }).then((result) => {
                    if (result.isConfirmed) {
                            location.reload();
                    }

                  });
            } else if (response.status == 404) {

              Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to Close ERP Laboratory",
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