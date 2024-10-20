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

  <title>ChronoLock Faculty-Student Attendance Generation</title>

  @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>

  @include('faculty.instructorSideNav')
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
              <li class="breadcrumb-item"><a href="{{ route('instructorIndex') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('facultyStudentAttendanceGeneration') }}">Report Generation</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('facultyStudentAttendanceGeneration') }}">Student Attendance</a></li>
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

            <form action="{{ url('/faculty-student-attendance-generation') }}" method="GET">

              <div class="dropdown d-inline-block">
                <div class="input-group date" id="month-picker">
                  <input class="form-control border-primary" type="search" name="search_courses" value="" placeholder="Search Course" autocomplete="false" id="search_courses">
                  <div class="input-group-append">
                    <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                      <i class="mdi mdi-database-search"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="dropdown d-inline-block">
                <div class="input-group date" id="month-picker">
                  <input type="datetime-local" class="form-control border border-primary" placeholder="Start Date" value="{{ $selected_StartDate }}" name="selected_StartDate" id="selectedStartDate">
                  <div class="input-group-append">
                    <div class="input-group text-light btn btn-primary btn-sm" id="dateIconStart">
                      <i class="mdi mdi-calendar"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="dropdown d-inline-block">
                <div class="input-group date" id="month-picker">
                  <input type="datetime-local" class="form-control border border-primary" placeholder="End Date" value="{{ $selected_EndDate }}" name="selected_EndDate" id="selectedEndDate">
                  <div class="input-group-append">
                    <div class="input-group text-light btn btn-primary btn-sm" id="dateIconEnd">
                      <i class="mdi mdi-calendar "></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xl-9 col-md-9 mt-1 mb-2">

                  <div class="dropdown d-inline-block">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentYearsButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                      <i class="mdi mdi-developer-board"></i> Year & Section
                    </button>
                    <div class="dropdown-menu" aria-labelledby="studentYearsButton">
                      @forelse ($studentYears as $studentYear)
                      <a class="dropdown-item year-item @if ($selected_years == $studentYear->year . '-' . $studentYear->section) active @endif" href="#" data-value="{{ $studentYear->year }}-{{ $studentYear->section }}">
                        {{ $studentYear->year }}-{{ $studentYear->section }}
                      </a>
                      @empty
                      <a class="dropdown-item" data-value="None" href="#">
                        None
                      </a>
                      @endforelse
                    </div>
                    <input type="hidden" name="selected_years" id="selected_year" value="">
                  </div>

                  <div class="dropdown d-inline-block">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentProgramsButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                      <i class="mdi mdi-alpha-c-box"></i> Program
                    </button>
                    <div class="dropdown-menu scrollable-dropdown" aria-labelledby="studentProgramsButton">
                      @forelse ($studentPrograms as $studentPrograms)
                      <a class="dropdown-item course-item @if ($studentPrograms == $studentPrograms->program) active @endif" href="#" data-value="{{ $studentPrograms->program }}">
                        {{ $studentPrograms->program }}
                      </a>
                      @empty
                      <a class="dropdown-item" data-value="None" href="#">
                        None
                      </a>
                      @endforelse
                    </div>
                    <input type="hidden" name="selected_programs" id="selected_programs" value="">
                  </div>

                  <div class="dropdown d-inline-block">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentRemarksButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                      <i class="mdi mdi-alpha-r-box"></i> Remarks
                    </button>
                    <div class="dropdown-menu" aria-labelledby="studentRemarksButton">
                      @forelse ($studentRemarks as $remarks)
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


                </div>
              </div>
              <!-- END -->
          </div>


          <div class="col-xl-3 col-md-3 d-flex justify-content-end">

            <div class="dropdown d-inline-block mb-3 mr-1">
              <button class="btn btn-danger btn-sm fw-bold" type="submit">
                <i class="mdi mdi-sort"></i> Filter
              </button>
            </div>
            </form>

            <form action="{{ url('/faculty-student-attendance-generation')}}" method="GET">
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
            <h1>Student Attendance Report</h1>
            <div class="justify-content-end">

              <div class="dropdown d-inline-block mb-3">
                <button data-toggle="tooltip" title="PDF" class="btn btn-outline-dark btn-sm fw-bold"
                  onclick='window.location = "{{ route("facultyPreviewStudentAttendancePDF", ["selected_remarks" => $selected_remarks, "selected_programs" => $selected_programs, "selected_years" => $selected_years, "selected_StartDate" => $selected_StartDate, "selected_EndDate" => $selected_EndDate, "search_courses" => $search_courses]) }}"' type="button"> <i class="mdi mdi-feature-search"></i>
                  PDF
                </button>
              </div>

              <div class="d-inline-block mb-3">
                <form action="{{ url('/student-attendance-export') }}" method="GET">
                  <!-- <input type="text" class="form-control border border-primary" id="exportDate" name="selectedDate" value="{{ Request()->date }}"> -->
                  <button data-toggle="tooltip" title="EXCEL"  class="btn btn-outline-dark btn-sm fw-bold" id="exportButton" type="submit">
                    <i class="mdi mdi-file-download"></i>
                    Excel
                  </button>
                </form>
              </div>

            </div>
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
                  <th>Program</th>
                  <th>Year & Section</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($studentDetails as $student)
                <tr>
                  <td>{{ date('F j, Y', strtotime($student->date)) }}</td>
                  <td>
                    @if($student->time)
                    {{ date('h:i A', strtotime($student->time)) }}
                    @else
                    <span style="color: #cc0000; font-weight: bold;">No Record</span>
                    @endif
                  </td>
                  <td>{{ ucwords($student->firstName) }} {{ ucwords($student->lastName) }}</td>
                  <td>{{ ucwords($student->idNumber) }}</td>
                  <td>{{ ucwords($student->courseName) }}</td>
                  <td>{{ $student->program }}</td>
                  <td>{{ $student->year }}-{{ $student->section }}</td>
                  <td>
                    @if($student->remark == 'Present')
                    <span class="badge badge-success">Present</span>
                    @elseif($student->remark == 'Absent')
                    <span class="badge badge-danger">Absent</span>
                    @elseif($student->remark == 'Late')
                    <span class="badge badge-warning">Late</span>
                    @endif
                  </td>
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

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.course-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('studentProgramsButton').innerHTML = `<i class="mdi mdi-alpha-c-box"></i> ${this.textContent}`;
          document.getElementById('selected_programs').value = this.getAttribute('data-value');
        });
      });
      document.querySelectorAll('.year-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('studentYearsButton').innerHTML = `<i class="mdi mdi-developer-board"></i> ${this.textContent}`;
          document.getElementById('selected_year').value = this.getAttribute('data-value');
        });
      });
      document.querySelectorAll('.remark-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('studentRemarksButton').innerHTML = `<i class="mdi mdi-alpha-r-box"></i> ${this.textContent}`;
          document.getElementById('selected_remarks').value = this.getAttribute('data-value');
        });
      });
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const monthPicker = flatpickr("#selectedMonth", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
      });
      // Add click event listener to the icon to toggle the date picker
      document.getElementById('monthIcon').addEventListener('click', function() {
        monthPicker.open();
      });
    });
  </script>

  <script>
    document.querySelectorAll('.year-item').forEach(item => {
      item.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        document.getElementById('selected_year').value = value;
      });
    });

    // Optional: Add event listener to open Flatpickr on icon click
    document.getElementById("dateIconStart").addEventListener("click", function() {
      flatpickr("#selectedStartDate").open();
    });

    document.getElementById("dateIconEnd").addEventListener("click", function() {
      flatpickr("#selectedEndDate").open();
    });
  </script>



  @include('footer')