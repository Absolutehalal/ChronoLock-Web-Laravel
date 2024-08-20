$(document).ready(function () {
    $(document).on("click", ".editProfileBtn", function (e) {
        e.preventDefault();

        var id = $(this).val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "GET",
            url: "/profile/edit/" + id,
            dataType: "json",
            success: function (response) {
                if (response.status == 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No Profile Found!!!",
                        timer: 5000,
                        timerProgressBar: true,
                    });
                    $("#update-modal-profile .close").click();
                } else {
                    $("#profile_userID").val(response.user.id);
                    $("#edit-firstName").val(response.user.firstName);
                    $("#edit-lastName").val(response.user.lastName);
                    $("#edit-idNumber").val(response.user.idNumber);
                    $("#edit-email").val(response.user.email);
                    $("#edit-avatar").attr("src", response.user.avatar);
                    $("#edit-userType").val(response.user.userType);
                }
            },
        });
    });

    $(document).on("click", ".update-profile", function (e) {
        e.preventDefault();

        $(this).text("Updating...");

        var profileID = $("#profile_userID").val();
        // alert(profileID);

        var data = {
            profile_firstName: $("#edit-firstName").val(),
            profile_lastName: $("#edit-lastName").val(),
            profile_idNumber: $("#edit-idNumber").val(),
            profile_email: $("#edit-email").val(),
        };

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            type: "PUT",
            url: "/profile/update/" + profileID,
            data: data,
            dataType: "json",
            success: function (response) {
                if (response.status === 400) {
                    // $('#firstNameError').addClass('error');
                    // $('#firstNameError').html("");

                    // $('#lastNameError').addClass('error');
                    // $('#lastNameError').html("");

                    $("#idNumberError").addClass("error");
                    $("#idNumberError").html("");

                    $("#emailError").addClass("error");
                    $("#emailError").html("");

                    $.each(
                        response.errors.profile_idNumber,
                        function (key, err_value) {
                            $("#idNumberError").append(
                                "<li>" + err_value + "</li>"
                            );
                        }
                    );
                    $.each(
                        response.errors.profile_email,
                        function (key, err_value) {
                            $("#emailError").append(
                                "<li>" + err_value + "</li>"
                            );
                        }
                    );

                    $(".update-profile").text("Update");
                } else if (response.status === 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "No Profile Found!!!",
                        timer: 5000,
                        timerProgressBar: true,
                    });
                    $("#update-modal-profile .close").click();
                } else if (response.status === 409) {
                    $("#firstNameError").html("");
                    $("#lastNameError").html("");
                    $("#idNumberError").html("");
                    $("#emailError").html("");
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: response.message,
                        timer: 5000,
                        timerProgressBar: true,
                    });

                    $(".update-profile").text("Update");
                    $("#update-modal-profile .close").click();
                } else if (response.status === 200) {
                    $("#firstNameError").html("");
                    $("#lastNameError").html("");
                    $("#idNumberError").html("");
                    $("#emailError").html("");

                    Swal.fire({
                        icon: "success",
                        title: "Successful",
                        text: "Profile Successfully Updated!",
                        timer: 5000,
                        timerProgressBar: true,
                    });

                    $(".update-profile").text("Update");
                    $("#update-modal-profile .close").click();
                    location.reload();
                }
            },

            error: function (response) {
                if (response.status === 500) {
                    alert("Something went wrong! Please try again later.");
                    // console.log(response.errors);
                }
            },
        });
    });
});
