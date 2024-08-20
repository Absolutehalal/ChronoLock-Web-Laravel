<!DOCTYPE html>

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
                <table id="AttendanceTable" class="table table-bordered table-hover no wrap" style="width: 100%;">
                  <thead class="table-dark">
                    <tr>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Instructor</th>
                      <th>Program</th>
                      <th>Year & Section</th>
                      <th>Course</th>
                      <!-- <th>Status</th> -->
                      <th>Remark</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($myAttendances as $myAttendance)
                    <tr>
                      <td>{{date('F j, Y', strtotime($myAttendance->date))}}</td>
                      <td>{{$myAttendance->time}}</td>
                      <td>{{$myAttendance->instFirstName}} {{$myAttendance->instLastName}}</td>
                      <td>{{$myAttendance->program}}</td>
                      <td>{{$myAttendance->year}}-{{$myAttendance->section}}</td>
                      <td>{{$myAttendance->courseCode}}-{{$myAttendance->courseName}}</td>
                      <!-- <td>{{$myAttendance->status}}</td> -->
                      <td>
                        @if($myAttendance->remark == 'Present')
                        <span class="badge badge-success">Present</span>
                        @elseif($myAttendance->remark == 'Absent')
                        <span class="badge badge-danger">Absent</span>
                        @elseif($myAttendance->remark == 'Late')
                        <span class="badge badge-warning">Late</span>
                        @endif
                      </td>

                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-xl-4">

            <!-- Page Views  -->
            <div class="card card-default shadow" id="page-views">
              <div class="card-header">
                @if($programTitle=='')
                <h2>Classmates Enrolled</h2>
                @else
                <h2>@php echo ($programTitle) @endphp Classmates Enrolled</h2>
                @endif
              </div>
              <div class="card-body py-0" data-simplebar style="height: 392px;">
                <table class="table table-bordered table-hover no wrap mt-1">
                  <thead class="table-dark text-center">
                    <tr>
                      <th>Name</th>
                      <th>Yr. & Sec.</th>
                      <th>Status</th>
                    </tr>
                  </thead>

                  <tbody class="text-center">
                    @foreach($myClassmates as $myClassmates)
                    <tr>
                      <td>{{$myClassmates->firstName}} {{$myClassmates->lastName}}</td>
                      <td>{{$myClassmates->year}}-{{$myClassmates->section}}</td>
                      <td>{{$myClassmates->status}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <div class="card-footer bg-white py-4">

              </div>
            </div>

          </div>
        </div>
      </div>
    </div>


    @include('footer')