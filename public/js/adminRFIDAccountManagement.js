$(document).ready(function () {
  $(document).on("pointerup", ".deactivateBtn", function () {
      var id = $(this).val();
      $("#deactivateRFID_ID").val(id);
  });

  $(document).on("click", ".deactivate", function (e) {
      e.preventDefault();

      $(this).text("Deactivating...");
      var id = $("#deactivateRFID_ID").val();

      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
      });

      $.ajax({
          type: "PUT",
          url: "/deactivateRFID/" + id,
          dataType: "json",
          success: function (response) {
              // console.log(response);
              if (response.status == 404) {
                  $(".deactivate").text("Deactivate");
                  $("#deactivateRFIDModal .close").click();
                  Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "No RFID Account Found",
                  });
              } else {
                  $(".deactivate").text("Deactivate");
                  $("#deactivateRFIDModal .close").click();
                  Swal.fire({
                      icon: "success",
                      title: "Successful",
                      text: "Deactivated RFID",
                      buttons: false,
                  });

                  location.reload();
              }
          },
      });
  });

  $(document).on("pointerup", ".activateBtn", function () {
      var id = $(this).val();
      $("#activateRFID_ID").val(id);
  });

  $(document).on("click", ".activate", function (e) {
      e.preventDefault();

      $(this).text("Activating...");
      var id = $("#activateRFID_ID").val();

      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
      });

      $.ajax({
          type: "PUT",
          url: "/activateRFID/" + id,
          dataType: "json",
          success: function (response) {
              // console.log(response);
              if (response.status == 404) {
                  $(".activate").text("Activate");
                  $("#activateRFIDModal .close").click();
                  Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "No RFID Account Found",
                  });
              } else {
                  $(".activate").text("Activate");
                  $("#activateRFIDModal .close").click();
                  Swal.fire({
                      icon: "success",
                      title: "Successful",
                      text: "Activated RFID",
                      buttons: false,
                  });

                  location.reload();
              }
          },
      });
  });

  $(document).on("pointerup", ".deleteBtn", function () {
      var id = $(this).val();
      $("#deleteRFID_ID").val(id);
  });

  $(document).on("click", ".deleteUserRFID", function (e) {
      e.preventDefault();

      $(this).text("Deleting..");
      var id = $("#deleteRFID_ID").val();

      $.ajaxSetup({
          headers: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
      });

      $.ajax({
          type: "DELETE",
          url: "/deleteUserRFID/" + id, // Ensure this URL is correct
          dataType: "json",
          success: function (response) {
              if (response.status == 404) {
                  $(".deleteUserRFID").text("Delete");
                  $("#deleteRFIDModal .close").click();
                  Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "No RFID Found",
                  });
              } else if (response.status == 200) {
                  $(".deleteUserRFID").text("Delete");
                  $("#deleteRFIDModal .close").click();

                  Swal.fire({
                      icon: "success",
                      title: "Successful",
                      text: "RFID Account Deleted",
                      confirmButtonText: "OK",
                  }).then((result) => {
                      if (result.isConfirmed) {
                          location.reload();
                      }
                  });
              }
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert("Error deleting RFID: " + errorThrown);
          }
      });
  });
});
