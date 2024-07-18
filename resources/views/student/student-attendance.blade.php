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
              <li class="breadcrumb-item active"><a href="{{ route('studentViewAttendance', request()->route('id')) }}">My Attendance</a></li>
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
@if($h1=='')           
<h1> My Attendance</h1>
@else
<h1> My @php echo $h1 @endphp Attendance</h1>
@endif            
          </div>
          <div class="card-body" style=" overflow-x:auto;">
            <table  class="table table-bordered table-hover " >
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Instructor</th>
                  <th>Program</th>
                  <th>Year & Section</th> 
                  <th>Course</th>
                  <th>Status</th>
                  <th>Remark</th>
                </tr>
              </thead>
              <tbody>
                @foreach($myAttendances as $myAttendance)
                <tr>
                  <td>{{$myAttendance->date}}</td>
                  <td>{{$myAttendance->time}}</td>
                  <td>{{$myAttendance->instFirstName}} {{$myAttendance->instLastName}}</td>
                  <td>{{$myAttendance->program}}</td>
                  <td>{{$myAttendance->year}}-{{$myAttendance->section}}</td>
                  <td>{{$myAttendance->courseCode}}-{{$myAttendance->courseName}}</td>
                  <td>{{$myAttendance->status}}</td>
                  <td>{{$myAttendance->remark}}</td>

                </tr>
                @endforeach
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