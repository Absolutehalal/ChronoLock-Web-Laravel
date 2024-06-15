const firstName = document.getElementById
("firstName"); 
const lastName = document.getElementById
("lastName");
const userType = document.getElementById
("userType"); 
const email = document.getElementById
("email");
const password = document.getElementById
("password");

   $(document).ready(function () {
        $(document).on('click', '.addUser', function (e) {
            e.preventDefault();
            $(this).text('Sending..');
            var data = {
                'firstName': $(firstName).val(),
                'lastName': $(lastName).val(),
                'userType': $(userType).val(),
                'email': $(email).val(),
                'password': $(password).val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/userManagementPage",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#firstNameError').html("");
                        $('#firstNameError').addClass('error');
                        $('#lastNameError').html("");
                        $('#lastNameError').addClass('error');
                        $('#userTypeError').html("");
                        $('#userTypeError').addClass('error');
                        $('#emailError').html("");
                        $('#emailError').addClass('error');
                        $('#passwordError').html("");
                        $('#passwordError').addClass('error');
                        $.each(response.errors.firstName, function (key, err_value) {
                            $('#firstNameError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.lastName, function (key, err_value) {
                            $('#lastNameError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.userType, function (key, err_value) {
                            $('#userTypeError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.email, function (key, err_value) {
                            $('#emailError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.password, function (key, err_value) {
                            $('#passwordError').append('<li>' + err_value + '</li>');
                        });
                        $('.addUser').text('Save');
                    } else if(response.status == 200){
                        $('#firstNameError').html("");
                        $('#lastNameError').html("");
                        $('#userTypeError').html("");
                        $('#emailError').html("");
                        $('#passwordError').html("");
                        $('.addUser').text('Save');
                        $("#addUserModal .close").click()
                        fetchUsers();
                    }
                    else if(response.status == 300){
                        $('#firstNameError').html("");
                        $('#lastNameError').html("");
                        $('#userTypeError').html("");
                        $('#emailError').html("");
                        $('#passwordError').html("");
                        $('.addUser').text('Save');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Invalid email. Please use a CSPC email.",
                          });
                        $("#addUserModal .close").click()
                    }
                }

            });

        });
    });