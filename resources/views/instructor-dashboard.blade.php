<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>ChronoLock Instructor Dashboard</title>

    @include('head')

    <!-- TOASTER -->
    <link href="plugins/toaster/toastr.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Custom JS -->
    <script src="js/toastr.js"></script>
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>

     @include('sweetalert::alert')

    <div id="toast"></div>
    
     @include('instructorSideNav')
    <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
    <div class="page-wrapper">
        <!-- Header -->
        @include('header')

        <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
        <div class="content-wrapper">
            <div class="content">

                <div class="d-flex justify-content-between align-items-center">
                    <!-- Navigation -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="inst-dashboard.php">Dashboard</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>

                <div class="row">
                    <!-- <div class="col-xl-4"> -->
                    <div class="col-xl-4 col-md-6" style="height: 50%;">
                        <!-- Pie Chart  -->
                        <div class="card card-default border border-dark">
                            <div class="card-header">
                                <!-- <h2>Pie Chart</h2> -->
                                <!-- Example single primary button -->
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-primary dropdown-toggle justify-content-end" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                        Select Section
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <button class="dropdown-item" data-toggle="modal" data-target="#exampleModalForm">
                                            <i class="mdi mdi-check"></i>
                                            BSIS 1A
                                        </button>

                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="simple-pie-chart" class="d-flex justify-content-center"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <!-- Horizontal Bar Chart  -->
                        <div class="card card-default border border-dark">
                            <div class="card-header">
                                <h2>Number of Students Per Section</h2>
                            </div>
                            <div class="card-body pb-0">
                                <div id="horizontal-bar-chart"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4">
                        <div class="card card-default border border-dark" style="height: 92%;">
                            <div class="card-header text-center">
                                <h2>Sections Handled</h2>
                            </div>
                            <div class="card-body static-number">
                                <h1 style="font-size: 100px;">10</h1>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card card-default border border-dark">
                    <div class="card-header">
                        <h2 style="font-size: 30px;">Class List</h2>

                        <div class="dropdown d-inline-block">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                Section
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item">
                                    <i class="mdi mdi-arrow-right"></i>
                                    BSIS 1A</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr class="text-center">
                                    <th scope="col">#</th>
                                    <th scope="col">RFID Code</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">User Type</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td scope="row">1</td>
                                    <td>210102192301923</td>
                                    <td>Lorzano, Ralph H.</td>
                                    <td>22</td>
                                    <td>Male</td>
                                    <td>Student</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="text-center">
                                    <td scope="row">1</td>
                                    <td>210102192301923</td>
                                    <td>Lorzano, Ralph H.</td>
                                    <td>22</td>
                                    <td>Male</td>
                                    <td>Student</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="text-center">
                                    <td scope="row">1</td>
                                    <td>210102192301923</td>
                                    <td>Lorzano, Ralph H.</td>
                                    <td>22</td>
                                    <td>Male</td>
                                    <td>Student</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                </div>


            </div>
        </div>


    </div>
    </div>
    </div>
    </div>


    </div>
    </div>
    </div>
    </div>

    @include('footer')