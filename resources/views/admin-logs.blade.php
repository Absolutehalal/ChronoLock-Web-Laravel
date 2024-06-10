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

  <title>ChronoLock Admin-RFID Account</title>

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
              <li class="breadcrumb-item active"><a href="admin-logs.php">Logs</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>


        <div class="card card-default">
          <div class="card-header">
            <h1>Logs</h1>
          </div>
          <div class="card-body col-md-12">
            <table id="example" class="table table-bordered table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>User Logs</th>
                  <th>Actions performed</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>User Type</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>User Logs</td>
                  <td>Actions performed</th>
                  <td>Date</td>
                  <td>Time</td>
                  <td>User Type</td>
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

  @include('footer')