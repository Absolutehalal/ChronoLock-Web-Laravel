<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">

    <!-- SWEET ALERT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.1/sweetalert2.min.js" integrity="sha512-Ozu7Km+muKCuIaPcOyNyW8yOw+KvkwsQyehcEnE5nrr0V4IuUqGZUKJDavjSCAA/667Dt2z05WmHHoVVb7Bi+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- FAVICON -->
    <link href="{{asset('images/chronolock-small.png')}}" rel="shortcut icon" />

    <!-- Font Awesome -->
    <script src="{{asset('https://kit.fontawesome.com/fc44043d19.js')}}" crossorigin="anonymous"></script>

    <script defer src="{{asset('js/password.js')}}"></script>

    <title>Admin Registration Only</title>
</head>

<body>


    <!----------------------- Main Container -------------------------->
    <div class="background-container">

        <!----------------------- Login Container -------------------------->
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="row border rounded-5 p-3 bg-white shadow-lg p-3 bg-body-tertiary box-area">
                <!--------------------------- Left Box ----------------------------->

                <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style=" background: linear-gradient(
                    120deg, /* Angle of the gradient */
                    #04082ee8, 
                    #174699e8, 
                    #174699e8, 
                    #04082ee8
                  );">
                    <p class="text-white mt-2 animate-text" style="font-weight: 700; font-size: 60px;">ChronoLock</p>
                    <div class="featured-image">
                        <img src="{{asset('images/UNCERTAIN LOGO (50 x 50 px).png')}}" class="img-fluid animate-text" style="width: 500px;">
                    </div>
                </div>

                <!----------------------------- Right Box ---------------------------->

                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text  text-center">
                            <h2 style="font-weight: 600; font-size: 40px;">Register Admin</h2>
                            <p>Please register your admin credentials.</p>
                        </div>

                        @include('sweetalert::alert')

                        <form method="post" id="createAdminForm" action="{{ route('addOnlyAdmin') }}" onsubmit="return validatePassword()">
                            @csrf
                            <label class="form-label fw-bold">ID Number</label>
                            <div class="form-group mb-3 position-relative">
                                <input id="idNumber" name="idNumber" type="text" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="ID Number" required>
                            </div>

                            <label class="form-label fw-bold">Admin Email</label>
                            <div class="form-group mb-3">
                                <input id="email" name="email" type="text" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Email address" required>
                            </div>

                            <label class="form-label fw-bold">Admin Password</label>
                            <div class="form-group mb-1 position-relative">
                                <input id="password" name="password" type="password" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Password" required>
                                <i class="fa fa-eye-slash" id="show-password"></i>
                            </div>

                            <div class="input-group mb-3">
                                <button type="button" id="confirmButton" class="btn btn-lg btn-primary w-100 fs-6 mt-4">Create Admin Account</button>
                            </div>
                        </form>

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('confirmButton').addEventListener('click', function(event) {
            // Get form elements
            var idNumber = document.getElementById('idNumber').value.trim();
            var email = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value.trim();

            // Check if any field is empty
            if (!idNumber || !email || !password) {
                // Show SweetAlert if any field is empty
                Swal.fire({
                    title: 'Validation Error',
                    text: 'Please fill in all fields before submitting.',
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                    timer: 3000, // Display for 3 seconds
                    timerProgressBar: true,
                });
                return; // Prevent form submission
            }

            // If all fields are filled, confirm the action
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to create this admin account?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('createAdminForm').submit();
                }
            });
        });
    </script>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;


            if (password.length !== 6 || isNaN(password)) {
                Swal.fire({
                    icon: "info",
                    title: "Info",
                    text: "Password must be 6 digits",
                    timer: 5000,
                    timerProgressBar: true
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

    <!-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');

            form.addEventListener('submit', function(event) {
                const emailValue = emailInput.value;
                const cpscDomain = '@my.cpsc.edu.ph';

                if (!emailValue.endsWith(cpscDomain)) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Please provide a valid CPSC email address.",
                        timer: 5000,
                        timerProgressBar: true
                    });
                    event.preventDefault(); // Prevent form submission
                }
            });
        });
    </script> -->


</body>

</html>