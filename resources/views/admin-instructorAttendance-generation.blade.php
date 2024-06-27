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

  <title>ChronoLock Admin-Instructor Attendance Generation</title>

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
              <li class="breadcrumb-item active"><a href="instructor-attendance-generation">Report Generation</a></li>
              <li class="breadcrumb-item active"><a href="instructor-attendance-generation">Instructor Attendance</a></li>
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

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="instNameDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-i-box"></i>
                  Instructor Name
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instNameDropdown">
                  @foreach($instructorsName as $instructor)
                  @csrf
                  <a class="dropdown-item filter-inst-name" data-value="{{ $instructor->firstName }} {{ $instructor->lastName }}" href="#">
                    {{ $instructor->firstName }} {{ $instructor->lastName }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="instructorName" id="selectedInstName">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="instStatusDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-s-box"></i>
                  Status
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instStatusDropdown">
                  @foreach($remarks as $remarks)
                  @csrf
                  <a class="dropdown-item filter-inst-status" data-value="{{ $remarks->remark }}" href="#">
                    {{ $remarks->remark }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="instructorStatus" id="selectedInstStatus">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <div class="input-group date " id="datepicker">
                <input type="datetime-local" class="form-control border border-primary" placeholder="Date" id="selectedDate" name="selectedDate">
                <div class="input-group-append">
                  <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                    <i class="mdi mdi-calendar "></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <div class="input-group date" id="timepicker">
                <input type="datetime-local" class="form-control border border-primary" placeholder="Time" id="selectedTime" name="selectedTime">
                <div class="input-group-append">
                  <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                    <i class="mdi mdi-clock "></i>
                  </div>
                </div>
              </div>
            </div>

          </div>


          <div class="col-xl-3 col-md-3 d-flex justify-content-end">
            <!-- Reset button -->
            <div class="d-inline-block mb-3">
              <button class="btn btn-warning btn-sm fw-bold" id="resetBtn" type="button">
                <i class="mdi mdi-alpha-r-box"></i>
                Reset
              </button>
            </div>

            <!-- Print button -->
            <div class="d-inline-block mb-3 ms-2">


                <button class="btn btn-info btn-sm fw-bold" id="exportButton">
                  <i class="mdi mdi-printer"></i>
                  Export
                </button>


            </div>
          </div>
          <!-- onclick="tablesToExcel(['AttendanceTable'], ['Instructor Attendance'], 'Instructor Attendance.xls', 'Excel', 'Instructor Attendance Report')" -->

        </div>
        <!-- END -->


        <div class="card card-default shadow">
          <div class="card-header">
            <h1>Instructor Attendance Report</h1>
          </div>
          <div class="card-body ">
            <table id="AttendanceTable" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Instructor Name</th>
                  <th>Instructor ID</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($instructors as $instructors)
                @csrf
                <tr>
                  <td>{{ $instructors->formatted_date }}</td>
                  <td>{{ $instructors->formatted_time }}</td>
                  <td>{{ $instructors->firstName }} {{ $instructors->lastName }}</td>
                  <td>{{ $instructors->idNumber }}</td>
                  <td class="fw-bold">{{ $instructors->remark }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>

          </div>
        </div>

      </div>
    </div>
  </div>


  <script src="js/exportExcel.js"></script>


  @include('footer')