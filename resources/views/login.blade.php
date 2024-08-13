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

    <title>ChronoLock Login</title>
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
                            <h2 style="font-weight: 600; font-size: 40px;">Hello, User!</h2>
                            <p>Please login your credentials.</p>
                        </div>

                        @include('sweetalert::alert')

                        <!-- <div>
                            @if($errors->any())
                            <div class="col-lg-12">
                                @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{$error}}</div>
                                @endforeach
                            </div>
                            @endif

                            @if(session()->has('error'))
                            <div class="alert alert-danger">{{session('error')}}</div>
                            @endif

                            @if(session()->has('success'))
                            <div class="alert alert-success">{{session('success')}}</div>
                            @endif
                        </div> -->



                        <form method="post" action="{{ route('login.user') }}" onsubmit="return validatePassword()">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <input id="email" name="email" type="text" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Email address" required>
                            </div>
                            <label class="form-label fw-bold">Password</label>
                            <div class="form-group mb-1 position-relative">
                                <input id="password" name="password" type="password" class="form-control form-control-lg bg-light border-dark fs-6" placeholder="Password" required>
                                <i class="fa fa-eye-slash" id="show-password"></i>
                            </div>

                            <div class="input-group mb-2 d-flex justify-content-end">
                                <!-- <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="formCheck" name="remember">
                                    <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                                </div> -->
                                <div class="forgot">
                                    <small><a href="{{ route('forgotPassword') }}">Forgot Password?</a></small>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <button type="submit" class="btn btn-lg btn-primary w-100 fs-6">Login</button>
                            </div>
                        </form>

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


                        <div class="row">
                            <small class="col-lg-12 text-center w-100 mb-3 ml-3">OR</small>
                        </div>

                        <form>
                            @csrf
                            <div class="input-group">
                                <a href="{{route('login.google')}}" class="btn btn-lg btn-primary w-100 fs-6 shadow-lg">
                                    <img src="images/google.png" style="width:20px" class="me-2">
                                    <small class="text-light">Sign In with Google</small>
                                </a>
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
           

            if (password.length !== 6 || isNaN(password)) {
                Swal.fire({
                    icon: "info",
                    title: "Info",
                    text: "Enter Valid Password",
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