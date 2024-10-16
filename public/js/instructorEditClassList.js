$(document).ready(function () {


    $(document).on('pointerup', '.withoutClassBtn', function() {
        var id = $(this).val();
        $('#noClasses_ID').val(id);
      
      });


    $(document).on('click', '.noClasses', function(e) {
        e.preventDefault();
    
        $(this).text('Loading...');
        var id = $('#noClasses_ID').val();
        
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
    
        $.ajax({
          type: "PUT",
          url: "/noClass-Class-List/" + id,
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
          url: "/withClass-Class-List/" + id,
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
    // EDIT CLASS LIST
    $(document).on("mouseover", ".editClassListBtn", function (e) {
        e.preventDefault();

        var id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/edit-Class-List/" + id,
            dataType: "json",
            success: function (response) {
                if (response.status == 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No Class List Found!!!",
                        timer: 5000,
                        timerProgressBar: true,
                    });
                    $("#classListUpdateModal .close").click();
                } else {
                    // console.log(response.classList);
                    $("#classListUpdateID").val(response.classList.classID);
                    $("#edit_program").val(response.schedule.program);
                    $("#edit_yearSection").val(response.schedule.year + "-" + response.schedule.section);
                    $("#edit_courseCode").val(response.schedule.courseCode);
                    $("#edit_courseName").val(response.schedule.courseName);
                    $("#edit_semester").val(response.classList.semester);
                    $("#edit_enrollmentKey").val(response.classList.enrollmentKey);
                }
            },
        });
    });

    // UPDATE CLASS LIST
    $(document).on("click", ".updateClasslist", function (e) {
        e.preventDefault();

        $(this).text("Updating..");
        var classListID = $("#classListUpdateID").val();
        // alert(attendanceID);

        var data = {
            updateSemester: $(".updateSemester").val(),
            updateEnrollmentKey: $(".updateEnrollmentKey").val(),
        };
        console.log(data);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "PUT",
            url: "/update-Class-List/" + classListID,
            data: data,
            dataType: "json",
            success: function (response) {
                // console.log(response);
                if (response.status == 400) {
                    $("#editSemesterError").html("");
                    $("#editSemesterError").addClass("error");
                    $("#editEnrollmentKeyError").html("");
                    $("#editEnrollmentKeyError").addClass("error");
                    $.each(
                        response.errors.updateSemester,
                        function (key, err_value) {
                            $("#editSemesterError").append(
                                "<li>" + err_value + "</li>"
                            );
                        }
                    );
                    $.each(
                        response.errors.updateEnrollmentKey,
                        function (key, err_value) {
                            $("#editEnrollmentKeyError").append(
                                "<li>" + err_value + "</li>"
                            );
                        }
                    );
                    $(".updateClasslist").text("Update");
                } else if (response.status == 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No Class List Found!!!",
                        timer: 5000,
                        timerProgressBar: true
                    });
                    $("#classListUpdateModal .close").click();
                    // console.log(response.message);
                } else if (response.status == 200) {
                    $("#editSemesterError").html("");
                    $("#editEnrollmentKeyError").html("");
                    Swal.fire({
                        icon: "success",
                        title: "Successful",
                        text: "Class List Successfully Updated!",
                        timer: 5000,
                        timerProgressBar: true
                    });
                    // console.log(response.attendance);
                    $(".updateClasslist").text("Update");
                    $("#classListUpdateModal .close").click();
                    location.reload();
                }
            },
        });
    });


    // DELETE CLASS LIST
    $(document).on("mouseover", ".deleteClassListBtn", function () {
        var id = $(this).val();
        $("#deleteClassListID").val(id);
    });

    $(document).on("click", ".deleteClassList", function (e) {
        e.preventDefault();

        $(this).text("Deleting..");
        var id = $("#deleteClassListID").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "DELETE",
            url: "/delete-Class-List/" + id,
            dataType: "json",
            success: function (response) {
                // console.log(response);
                if (response.status == 404) {
                    $(".deleteClassList").text("Delete");
                    $("#classListDeleteModal .close").click();
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No Class List Found",
                        timer: 5000,
                        timerProgressBar: true
                    });
                } else {
                    $(".deleteClassList").text("Delete");
                    $("#classListDeleteModal .close").click();
                    Swal.fire({
                        icon: "success",
                        title: "Successful",
                        text: "Class List Deleted",
                        buttons: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                    location.reload();
                }
            },
        });
    });
});