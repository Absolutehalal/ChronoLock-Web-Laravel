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

  <title>ChronoLock Admin Dashboard</title>

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
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>
        <div class="row">

          <!-- First box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-success rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-multiple-check"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countTotalUsers }} </span>
                  <p>Total Users</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Second box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-warning rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-group"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countStudents }} </span>
                  <p>Total Students</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Third box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-primary rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-group"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countInstructor }} </span>
                  <p>Total Instructors</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Fourth box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-info rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-card-details"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block">8930</span>
                  <p>Registered RFID</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CALENDAR -->
        <div class="card card-default shadow">
          <div class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
            <h2>Schedule</h2>
            <div class="card-body">
              <div class=" full-calendar mb-5">
                <div id="calendar"></div>
              </div>
            </div>
          </div>
        </div>


        <!--TABLES-->
        <div class="row">
          <div class="col-xl-6">
            <!-- Striped Table -->
            <div class="card card-default shadow">
              <div class="card-header">
                <h2>Users</h2>
              </div>
              <div class="card-body">
                <div class="table-wrapper">
                  <table class="table table-bordered table-hover">
                    <thead class="table-dark" id="tblDashboard">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Firstname</th>
                        <th scope="col">Lastname</th>
                        <th scope="col">Email</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($tblUsers as $user)
                      @csrf
                      <tr>
                        <td> {{$user->id}} </td>
                        <td> {{$user->firstName}} </td>
                        <td> {{$user->lastName}} </td>
                        <td> {{$user->email}} </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>


          <div class="col-xl-6">
            <!-- Striped Table -->
            <div class="card card-default shadow">
              <div class="card-header">
                <h2>Recent Added RFID</h2>
              </div>
              <div class="card-body">
                <div class="table-wrapper">
                <table class="table table-bordered table-hover">
                  <thead class="table-dark">
                   <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Handle</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td scope="row">1</td>
                      <td>Lucia</td>
                      <td>Christ</td>
                      <td>@Lucia</td>
                    </tr>
                    <tr>
                      <td scope="row">2</td>
                      <td>Catrin</td>
                      <td>Seidl</td>
                      <td>@catrin</td>
                    </tr>
                    <tr>
                      <td scope="row">3</td>
                      <td>Lilli</td>
                      <td>Kirsh</td>
                      <td>@lilli</td>
                    </tr>
                    <tr>
                      <td scope="row">4</td>
                      <td>Else</td>
                      <td>Voigt</td>
                      <td>@voigt</td>
                    </tr>
                    <tr>
                      <td scope="row">5</td>
                      <td>Ursel</td>
                      <td>Harms</td>
                      <td>@ursel</td>
                    </tr>
                    <tr>
                      <td scope="row">6</td>
                      <td>Anke</td>
                      <td>Sauter</td>
                      <td>@Anke</td>
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
  </div>

  @include('footer')