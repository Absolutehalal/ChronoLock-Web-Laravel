<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css">
    <!-- FAVICON -->
    <link href="images/favicon.png" rel="shortcut icon" />
    <title>Login - ChronoLock</title>
</head>
<body>
    

    <!----------------------- Main Container -------------------------->
    <div class="background-container">

        <!----------------------- Login Container -------------------------->
        <div class="container d-flex justify-content-center align-items-center min-vh-100 animate-text">
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
                        <img src="images/UNCERTAIN LOGO (50 x 50 px).png" class="img-fluid" style="width: 500px;">
                    </div>  
                </div> 

                <!----------------------------- Right Box ---------------------------->
                
                <div class="col-md-6 right-box">
                    <div class="row align-items-center">
                        <div class="header-text mb-4 text-center">
                            <h2 style="font-weight: 600; font-size: 40px;">Hello, User!</h2>
                            <p>Please login your credentials.</p>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Email address">
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Password">
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="formCheck">
                                <label for="formCheck" class="form-check-label text-secondary"><small>Remember Me</small></label>
                            </div>
                            <div class="forgot">
                                <small><a href="#">Forgot Password?</a></small>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <a href="index.php" class="btn btn-lg btn-primary w-100 fs-6">Login</a>
                        </div>
                        <div class="row">
                            <small class="text-center w-100 mb-3">OR</small>
                        </div>
                        <div class="input-group mb-3">
                            <button class="btn btn-lg btn-light w-100 fs-6"><img src="images/google.png" style="width:20px" class="me-2"><small class="text-dark">Sign In with Google</small></button>
                        </div>
                       
                    </div>
                </div> 
            </div>
        </div>
    </div>

</body>
</html>
