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

  <title>ChronoLock Admin-Student Attendance</title>

  @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>
   @include('adminSideNav')
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
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="admin-studattendance.php">Attendance</a></li>
              <li class="breadcrumb-item active"><a href="admin-studattendance.php">Student Attendance</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <!-- DROPRDOWN NAV -->

        <div class="row">
          <div class="col-xl-9 col-md-9">
            <!-- Example single primary button -->
            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-calendar"></i>
                Date <!-- CALENDAR TYPE -->
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </div>

            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-timer"></i>
                Time
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </div>

            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-timetable"></i>
                Year & Section
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </div>

            <!-- <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-alpha-s-box"></i>
                Section
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </div> -->

            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-alpha-c-box"></i>
                Course
              </button>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-3 d-flex justify-content-end">
            <!-- Sort button -->
            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-warning fw-bold" type="button">
                <i class="mdi mdi-sort"></i>
                SORT
              </button>
            </div>
          </div>

        </div>
        <!-- END -->

        <div class="card card-default">
          <div class="card-header">
            <h1>Student Realtime Attendance</h1>
          </div>
          <div class="card-body ">
            <table id="example" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Student Name</th>
                  <th>Student ID</th>
                  <th>Course</th>
                  <th>Year & Section</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>June 3, 2024</td>
                  <td>7:32 a.m.</td>
                  <td>Lorzano, Ralph H.</td>
                  <td>C21104744</td>
                  <td>BSIS</td>
                  <td>1A</td>
                  <th style="color: #31CE3C;">Present</th>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item">
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>
                          Edit</button>
                        <button class="dropdown-item">
                          <i class="mdi mdi-trash-can text-danger"></i>
                          Delete</button>
                      </div>
                    </div>
                  </th>
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



  <!-- Footer -->
  <!-- <footer class="footer mt-auto">
          <div class="copyright bg-white">
            <p>
              &copy; <span id="copy-year"></span> Copyright Mono Dashboard
              Bootstrap Template by
              <a
                class="text-primary"
                href="http://www.iamabdus.com/"
                target="_blank"
                >Abdus</a
              >.
            </p>
          </div>
          <script>
            var d = new Date();
            var year = d.getFullYear();
            document.getElementById("copy-year").innerHTML = year;
          </script>
        </footer> -->
  </div>
  </div>

  @include('footer')