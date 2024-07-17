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

  <title>ChronoLock My Attendance</title>

  @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>
    @include('student.studentSideNav')
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
              <li class="breadcrumb-item"><a href="{{ route('studentIndex') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('studentViewAttendance') }}">My Attendance</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <div class="row">
        <div class="col-xl-8">
        <div class="card card-default shadow">
          <div class="card-header">
            <h1>Student Realtime Attendance</h1>
          </div>
          <div class="card-body" style=" overflow-x:auto;">
            <table  class="table table-bordered table-hover " >
              <thead class="table-dark">
                <tr>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                  <th>Hi</th>
                
                
                 
                </tr>
              </thead>
              <tbody>
                
                <tr>
                  <td>cell</td>
                  <td>cell</td>
                  <td>cell</td>
                  <td>cell</td>
                  <td>cell</td>
                  <td>cell</td>
                  <td>cell</td>
                  <td>cell</td>
                 
                  <td>
                 cell
                  </td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Actions
                        </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item editAttendanceBtn" type="button" data-toggle="modal" data-target="#updateAttendanceModal" value="">
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>
                          Edit</button>
                        <button class="dropdown-item deleteAttendanceBtn" type="button" data-toggle="modal" data-target="#deleteAttendanceModal" value="">
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
        <div class="col-xl-4">
                    
                    <!-- Page Views  -->
                    <div class="card card-default" id="page-views">
                      <div class="card-header">
                        <h2>Page Views</h2>
                      </div>
                      <div class="card-body py-0" data-simplebar style="height: 392px;">
                        <table class="table table-borderless table-thead-border">
                          <thead>
                            <tr>
                              <th  style="color: #000000;">Page</th>
                              <th class="text-right px-3"  style="color: #000000;">Page Views</th>
                              <th class="text-right"  style="color: #000000;">Avg Time</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td class="text-primary"><a class="link" href="analytics.html">/analytics.html</a></td>
                              <td class="text-right px-3">521</td>
                              <td class="text-right">2m:14s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="email-inbox.html">/email-inbox.html</a></td>
                              <td class="text-right px-3">356</td>
                              <td class="text-right">2m:23s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="email-compose.html">/email-compose.html</a></td>
                              <td class="text-right px-3">254</td>
                              <td class="text-right">2m:2s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="charts-chartjs.html">/charts-chartjs.html</a></td>
                              <td class="text-right px-3">126</td>
                              <td class="text-right">1m:15s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="profile.html">/profile.html</a></td>
                              <td class="text-right px-3">50</td>
                              <td class="text-right">1m:7s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="general-widgets.html">/general-widgets.html</a></td>
                              <td class="text-right px-3">50</td>
                              <td class="text-right">2m:35s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="card.html">/card.html</a></td>
                              <td class="text-right px-3">590</td>
                              <td class="text-right">5m:55s</td>
                            </tr>
                            <tr>
                              <td class="text-primary"><a class="link" href="email-inbox.html">/email-inbox.html</a></td>
                              <td class="text-right px-3">29</td>
                              <td class="text-right">8m:5s</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="card-footer bg-white py-4">
                        <a href="#" class="text-uppercase">Audience Overview</a>
                      </div>
                    </div>

                  </div>
      </div>
    </div>
  </div>
  @include('footer')