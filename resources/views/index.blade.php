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
          <!-- Frist box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default bg-secondary">
              <div class="d-flex p-5 justify-content-between">
                <div class="icon-md bg-white rounded-circle mr-3">
                  <i class="mdi mdi-account-plus-outline text-secondary"></i>
                </div>
                <div class="text-right">
                  <span class="h2 d-block text-white">890</span>
                  <p class="text-white">New Users</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Second box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default bg-success">
              <div class="d-flex p-5 justify-content-between">
                <div class="icon-md bg-white rounded-circle mr-3">
                  <i class="mdi mdi-table-edit text-success"></i>
                </div>
                <div class="text-right">
                  <span class="h2 d-block text-white">350</span>
                  <p class="text-white">Order Placed</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Third box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default bg-primary">
              <div class="d-flex p-5 justify-content-between">
                <div class="icon-md bg-white rounded-circle mr-3">
                  <i class="mdi mdi-content-save-edit-outline text-primary"></i>
                </div>
                <div class="text-right">
                  <span class="h2 d-block text-white">1360</span>
                  <p class="text-white">Total Sales</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Fourth box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default bg-info">
              <div class="d-flex p-5 justify-content-between">
                <div class="icon-md bg-white rounded-circle mr-3">
                  <i class="mdi mdi-bell text-info"></i>
                </div>
                <div class="text-right">
                  <span class="h2 d-block text-white">$8930</span>
                  <p class="text-white">Monthly Revenue</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CALENDAR -->
        <div class="card card-default border border-dark">
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
            <div class="card card-default border border-dark">
              <div class="card-header">
                <h2>RFID Pending Request</h2>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">First</th>
                      <th scope="col">Last</th>
                      <th scope="col">Handle</th>
                      <th scope="col">Active</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td scope="row">1</td>
                      <td>Lucia</td>
                      <td>Christ</td>
                      <td>@Lucia</td>
                      <td>
                        <label class="switch switch-primary form-control-label mr-2">
                          <input type="checkbox" class="switch-input form-check-input" value="on" checked>
                          <span class="switch-label"></span>
                          <span class="switch-handle"></span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td scope="row">2</td>
                      <td>Catrin</td>
                      <td>Seidl</td>
                      <td>@catrin</td>
                      <td>
                        <label class="switch switch-primary form-control-label mr-2">
                          <input type="checkbox" class="switch-input form-check-input" value="on" />
                          <span class="switch-label"></span>
                          <span class="switch-handle"></span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td scope="row">3</td>
                      <td>Lilli</td>
                      <td>Kirsh</td>
                      <td>@lilli</td>
                      <td>
                        <label class="switch switch-primary form-control-label mr-2">
                          <input type="checkbox" class="switch-input form-check-input" value="on" checked />
                          <span class="switch-label"></span>
                          <span class="switch-handle"></span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td scope="row">4</td>
                      <td>Else</td>
                      <td>Voigt</td>
                      <td>@voigt</td>
                      <td>
                        <label class="switch switch-primary form-control-label mr-2">
                          <input type="checkbox" class="switch-input form-check-input" value="on" />
                          <span class="switch-label"></span>
                          <span class="switch-handle"></span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td scope="row">5</td>
                      <td>Ursel</td>
                      <td>Harms</td>
                      <td>@ursel</td>
                      <td>
                        <label class="switch switch-primary form-control-label  mr-2">
                          <input type="checkbox" class="switch-input form-check-input" value="on" checked />
                          <span class="switch-label"></span>
                          <span class="switch-handle"></span>
                        </label>
                      </td>
                    </tr>
                    <tr>
                      <td scope="row">6</td>
                      <td>Anke</td>
                      <td>Sauter</td>
                      <td>@Anke</td>
                      <td>
                        <label class="switch switch-primary form-control-label mr-2">
                          <input type="checkbox" class="switch-input form-check-input" value="on" />
                          <span class="switch-label"></span>
                          <span class="switch-handle"></span>
                        </label>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>


          <div class="col-xl-6">
            <!-- Striped Table -->
            <div class="card card-default border border-dark">
              <div class="card-header">
                <h2>Recent Added RFID</h2>
              </div>
              <div class="card-body">
                <table class="table table-bordered table-hover">
                  <thead class="thead-light">
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

  @include('footer')