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
                    $("#edit-password").val(response.user.password);
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
            profile_password: $("#edit-password").val(),
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
                    $("#passwordError").html("");

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
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Something went wrong! Please try again later.",
                        timer: 5000,
                        timerProgressBar: true,
                    });
                    // alert("Something went wrong! Please try again later.");
                    // console.log(response.errors);
                }
            },
        });
    });
});

// PROFILE FUNCTION
document.addEventListener("DOMContentLoaded", function () {
    $("#update-modal-profile").on("hidden.bs.modal", function () {
        $("#clearProfile")[0].reset();
        clearProfileErrors();
    });
    function clearProfileErrors() {
        $("#emailError").empty();
        $("#idNumberError").empty();
    }
});

const profileShowPassword = document.querySelector("#show-password-profile");
const profilePasswordField = document.querySelector("#edit-password");
profileShowPassword.addEventListener("click", function () {
    this.classList.toggle("fa-eye");
    const type =
        profilePasswordField.getAttribute("type") === "password"
            ? "text"
            : "password";
    // Toggle password field visibility
    profilePasswordField.setAttribute("type", type);
});

// function validateFieldPassword() {
//     var passwordInput = document.getElementById("edit-password").value;
//     if (!/^\d*$/.test(passwordInput)) {
//         Swal.fire({
//             timer: 5000,
//             timerProgressBar: true,
//             icon: "error",
//             title: "Invalid Input",
//             text: "Only numerical values are allowed.",
//             confirmButtonText: "OK",
//         });
//         document.getElementById("edit-password").value = passwordInput.replace(
//             /\D/g,
//             ""
//         ); // Remove non-numeric characters
//         return false; // Prevent form submission
//     }

//     return true; // Allow form submission
// }

document
    .querySelector(".update-profile")
    .addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        const passwordField = document.getElementById("edit-password");
        const password = passwordField.value;

        // Check if the password is less than 6 characters
        if (password !== "" && (password.length < 6 || password.length > 10)) {
            Swal.fire({
                icon: "warning",
                title: "Invalid Password",
                text: "Password must be between 6-10 characters long.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Okay",
            });
            return; // Exit the function to prevent form submission
        }
            

        // Check if the password is more than 6 characters
        if (password.length > 10) {
            Swal.fire({
                icon: "warning",
                title: "Invalid Password",
                text: "Password must not exceed 10 characters.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Okay",
            });
            return; // Exit the function to prevent form submission
        }
    });
