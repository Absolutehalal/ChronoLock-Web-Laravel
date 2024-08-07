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
  @include('admin.adminSideNav')
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
              <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('instructorAttendanceGeneration') }}">Report Generation</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('instructorAttendanceGeneration') }}">Student Attendance</a></li>
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

            <form action="{{ url('/instructor-attendance-generation') }}" method="GET">

              <div class="dropdown d-inline-block">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="instructorIDButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                  <i class="mdi mdi-alpha-i-box"></i> Instructor ID
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instructorIDButton">
                  @forelse ($instructorID as $instructorID)
                  @csrf
                  <a class="dropdown-item id-item @if ($instructorID == $instructorID->userID) active @endif" data-value="{{ $instructorID->userID }}" href="#">
                    {{ $instructorID->userID }} - {{ $instructorID->instFirstName }} {{ $instructorID->instLastName }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="selected_id" id="selected_id" value="">
              </div>

              <div class="dropdown d-inline-block">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="instructorRemarksButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                  <i class="mdi mdi-alpha-r-box"></i> Remarks
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instructorRemarksButton">
                  @forelse ($instructorRemarks as $remarks)
                  @csrf
                  <a class="dropdown-item remark-item @if ($remarks == $remarks->remark) active @endif" href="#" data-value="{{ $remarks->remark }}">
                    {{ $remarks->remark }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="selected_remarks" id="selected_remarks" value="">
              </div>

              <div class="dropdown d-inline-block mb-2">
                <div class="input-group" id="month-picker">
                  <input class="form-control border border-primary" type="text" placeholder="Month" value="{{ $selectedMonth }}" name="selectedMonth" id="selectedMonth">
                  <div class="input-group-append">
                    <div class="input-group text text-light btn btn-primary btn-sm" id="monthIcon">
                      <i class="mdi mdi-calendar-search"></i>
                    </div>
                  </div>
                </div>
              </div>


          </div>


          <div class="col-xl-3 col-md-3 d-flex justify-content-end">

            <div class="dropdown d-inline-block mb-3 mr-1">
              <button class="btn btn-danger btn-sm fw-bold" type="submit">
                <i class="mdi mdi-sort"></i> Filter
              </button>
            </div>
            </form>

            <form action="{{ url('/instructor-attendance-generation')}}" method="GET">
              <div class="dropdown d-inline-block mb-3 ">
                <button class="btn btn-warning btn-sm fw-bold" type="submit">
                  <i class="mdi mdi-alpha-r-box"></i>
                  RESET
                </button>
              </div>
            </form>

          </div>
        </div>
        <!-- END -->


        <div class="card card-default shadow">
          <div class="card-header">
            <h1>Instructor Attendance Report</h1>

            <div class="d-inline-block mb-3 ms-2">
              <form action="{{ url('/instructor-attendance-export') }}" method="GET">
                <button class="btn btn-info btn-sm fw-bold" id="exportButton" type="submit">
                  <i class="mdi mdi-file-download"></i>
                  Excel
                </button>
              </form>
            </div>
          </div>

          <div class="card-body ">
            <table id="AttendanceTable" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Course Code</th>
                  <th>Course Name</th>
                  <th>Program & Section</th>
                  <th>Instructor</th>
                  <th>Instructor ID</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
                @foreach($instructorDetails as $instructors)
                @csrf
                <tr>
                  <td>{{ date('F j, Y', strtotime($instructors->date)) }}</td>
                  <td>{{ date('h:i A', strtotime($instructors->time)) }}</td>
                  <td>{{ $instructors->courseCode }}</td>
                  <td>{{ $instructors->courseName }}</td>
                  <td>{{ $instructors->program }} - {{ $instructors->year }}{{ $instructors->section }}</td>
                  <td>{{ $instructors->instFirstName }} {{ $instructors->instLastName }}</td>
                  <td>{{ $instructors->userID }}</td>
                  <td>
                    @if($instructors->remark == 'Present')
                    <span class="badge badge-success">Present</span>
                    @elseif($instructors->remark == 'Absent')
                    <span class="badge badge-danger">Absent</span>
                    @elseif($instructors->remark == 'Late')
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
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      document.querySelectorAll('.remark-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('instructorRemarksButton').innerHTML = `<i class="mdi mdi-alpha-r-box"></i> ${this.textContent}`;
          document.getElementById('selected_remarks').value = this.getAttribute('data-value');
        });
      });

      document.querySelectorAll('.id-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('instructorIDButton').innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
          document.getElementById('selected_id').value = this.getAttribute('data-value');
        });
      });

    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const monthPicker = flatpickr("#selectedMonth", {
        dateFormat: "F Y",
        plugins: [
          new monthSelectPlugin({
            shorthand: true, //defaults to false
            dateFormat: "F Y", //defaults to "F Y"
            altFormat: "F Y", //defaults to "F Y"
            theme: "light" // defaults to "light"
          })
        ]
      });
      // Add click event listener to the icon to toggle the date picker
      document.getElementById('monthIcon').addEventListener('click', function() {
        monthPicker.open();
      });
    });
  </script>


  @include('footer')