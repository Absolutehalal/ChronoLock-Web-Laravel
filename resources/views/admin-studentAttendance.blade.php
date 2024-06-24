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
  <meta name="csrf-token" content="{{ csrf_token() }}" />
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

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('studentAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="yearDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-developer-board"></i>
                  Year & Section
                </button>
                <div class="dropdown-menu" aria-labelledby="yearDropdown">
                  @foreach($students as $students)
                  @csrf
                  <a class="dropdown-item filter-year" data-value="{{ $year->year_section }}" href="#">
                    {{ $year->year_section }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="year" id="selectedYear">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('studentAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="courseDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-c-box"></i>
                  Course
                </button>
                <div class="dropdown-menu" aria-labelledby="courseDropdown">
                  @foreach($courses as $course)
                  @csrf
                  <a class="dropdown-item filter-course" data-value="{{ $course->course }}" href="#">
                    {{ $course->course }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="course" id="selectedCourse">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('studentAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="statusDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-s-box"></i>
                  Status
                </button>
                <div class="dropdown-menu" aria-labelledby="statusDropdown">
                  @foreach($status as $status)
                  @csrf
                  <a class="dropdown-item filter-status" data-value="{{ $status->status }}" href="#">
                    {{ $status->status }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="status" id="selectedStatus">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <div class="input-group date" id="datepicker">
                <input type="datetime-local" class="form-control border border-primary" placeholder="Date" id="selectedDate">
                <div class="input-group-append">
                  <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                    <i class="mdi mdi-calendar "></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <div class="input-group date" id="timepicker">
                <input type="datetime-local" class="form-control border border-primary" placeholder="Time" id="selectedTime">
                <div class="input-group-append">
                  <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                    <i class="mdi mdi-clock "></i>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-timer"></i>
                Date
              </button>
            </div> -->

            <!-- <div class="dropdown d-inline-block mb-3">
              <button id="mini-status-range" type="button" class="dropdown-toggle btn btn-primary">
                <i class="mdi mdi-calendar"></i>
                <span id="datepicker" class="date-holder text-light"></span>
              </button>
            </div> -->
          </div>

          <div class="col-xl-3 col-md-3 d-flex justify-content-end">
            <!-- Sort button -->
            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-warning btn-sm fw-bold" id="resetBtn" type="button">
                <i class="mdi mdi-alpha-r-box"></i>
                RESET
              </button>
            </div>
          </div>

        </div>
        <!-- END -->

        <div class="card card-default shadow">
          <div class="card-header">
            <h1>Student Realtime Attendance</h1>
          </div>
          <div class="card-body ">
            <table id="AttendanceTable" class="table table-bordered table-hover no-wrap" style="width:100%">
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
                @foreach($students as $student)
                @csrf
                <tr>
                  <td>{{ $student->date }}</td>
                  <td>{{ $student->time }}</td>
                  <td>{{ $student->student_name }}</td>
                  <td>{{ $student->student_id }}</td>
                  <td>{{ $student->course }}</td>
                  <td>{{ $student->year_section }}</td>
                  <td class="fw-bold">{{ $record->status }}</td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
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
                @endforeach

              </tbody>
            </table>


          </div>
        </div>
        <!-- END -->




      </div>
    </div>
  </div>


  </div>
  </div>

  </div>




  @include('footer')