<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css')}}" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">

    <!-- SWEET ALERT -->
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.11.1/sweetalert2.min.js')}}" integrity="sha512-Ozu7Km+muKCuIaPcOyNyW8yOw+KvkwsQyehcEnE5nrr0V4IuUqGZUKJDavjSCAA/667Dt2z05WmHHoVVb7Bi+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css')}}" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="{{asset('https://kit.fontawesome.com/fc44043d19.js')}}" crossorigin="anonymous"></script>

    <script defer src="{{asset('js/password.js')}}"></script>

    <!-- FAVICON -->
    <link href="{{asset('images/chronolock-small.png')}}" rel="shortcut icon" />

    <title>Reset Password</title>
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
                        <div class="header-text mb-3 text-center">
                            <h2 style="font-weight: 600; font-size: 40px;">Hello, User!</h2>
                            <p>Reset your password.</p>
                        </div>

                        @include('sweetalert::alert')


                        <form method="post" action="{{ route('updatePassword') }}" onsubmit="return validatePassword()">
                            @csrf

                            <input type="hidden" id="token" name="token" value="{{ $token }}">
                            
                            <label class="form-label fw-bold">Email</label>
                            <div class="form-group mb-3">
                                <input id="email" name="email" type="text" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Email address" required>
                            </div>

                            <label class="form-label fw-bold ">Password</label>
                            <div class="form-group mb-1 position-relative  mb-3">
                                <input id="password" name="password" type="password" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Password" required>
                                <i class="fa fa-eye-slash" id="show-password"></i>
                            </div>

                            <label class="form-label fw-bold ">Confirm Password</label>
                            <div class="form-group mb-1 position-relative mb-3">
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Confirm Password"" required>
                                <i class=" fa fa-eye-slash" id="show-password-confirm"></i>
                            </div>
                            <!-- <div class="form-group mb-3">
                                <label class="form-label fw-bold">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Confirm Password" required>
                            </div> -->
                            <div class="input-group mt-4 mb-3">
                                <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Reset Password</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (password.length !== 6 || isNaN(password)) {
                Swal.fire({
                    icon: "info",
                    title: "Info",
                    text: "Password must be 6 digits",
                    timer: 5000,
                    timerProgressBar: true
                });
                return false; // Prevent form submission
            } else if (passwordConfirmation.length !== 6 || isNaN(passwordConfirmation)) {
                Swal.fire({
                    icon: "info",
                    title: "Info",
                    text: "Confirm Password must be 6 digits",
                    timer: 5000,
                    timerProgressBar: true
                });
                return false; // Prevent form submission
            } else if (password !== passwordConfirmation) {
                Swal.fire({
                    icon: "info",
                    title: "Info",
                    text: "Passwords do not match",
                    timer: 5000,
                    timerProgressBar: true
                });
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>

</body>

</html>