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

  <!-- Ajax Student Attendance -->
  <script defer src="js/adminEditStudentAttendance.js"></script>

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
              <li class="breadcrumb-item active"><a href="{{ route('studentAttendanceManagement') }}">Attendance</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('studentAttendanceManagement') }}">Student Attendance</a></li>
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
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="yearDropdown">
                  @forelse($studentYears as $studentYears)
                  @csrf
                  <a class="dropdown-item year-item filter-year" data-value="{{ $studentYears->year }}-{{ $studentYears->section }}" href="#">
                    {{ $studentYears->year }}-{{ $studentYears->section }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="year" id="selectedYear">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('studentAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="courseDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-c-box"></i>
                  Program
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="courseDropdown">
                  @forelse($studentPrograms as $studentPrograms)
                  @csrf
                  <a class="dropdown-item course-item filter-course" data-value="{{ $studentPrograms->program }}" href="#">
                    {{ $studentPrograms->program }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="course" id="selectedCourse">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('studentAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="statusDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-r-box"></i>
                  Remark
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="statusDropdown">
                  @forelse($studentRemarks as $studentRemarks)
                  @csrf
                  <a class="dropdown-item remark-item filter-status" data-value="{{ $studentRemarks->remark }}" href="#">
                    {{ $studentRemarks->remark }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
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
                  <th>Program</th>
                  <th>Year & Section</th>
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($students as $students)
                @csrf
                <tr>
                  <td>{{ $students->formatted_date }}</td>
                  <td>{!! $students->formatted_time !!}</td>
                  <td>{{ ucwords($students->firstName) }} {{ ucwords($students->lastName) }}</td>
                  <td>{{ ucwords($students->idNumber) }}</td>
                  <td>{{ ucwords($students->courseName) }}</td>
                  <td>{{ $students->program }}</td>
                  <td>{{ $students->year }}-{{ $students->section }}</td>
                  <td>
                    @if($students->remark == 'Present')
                    <span class="badge badge-success">Present</span>
                    @elseif($students->remark == 'Absent')
                    <span class="badge badge-danger">Absent</span>
                    @elseif($students->remark == 'Late')
                    <span class="badge badge-warning">Late</span>
                    @endif
                  </td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item  btn-sm editAttendanceBtn" type="button" data-toggle="modal" data-target="#updateAttendanceModal" value="{{$students->attendanceID}}">
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>
                          Edit</button>
                        <button class="dropdown-item btn-sm deleteAttendanceBtn" type="button" data-toggle="modal" data-target="#deleteAttendanceModal" value="{{$students->attendanceID}}">
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
      </div>
    </div>
  </div>

  <!-- Delete Attendance Modal -->
  <div class="modal fade" id="deleteAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="deleteAttendance" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteAttendance" style="text-align:center;">Delete Student Attendance</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            @csrf
            @method('delete')
            <input type="hidden" id="deleteAttendanceID" class="id form-control ">
            <div class="row">
              <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to delete this Student Attendance?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-pill" id="close" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-pill deleteAttendance">Delete</button>
        </div>

        </form>

      </div>
    </div>
  </div>
  </div>

  <!-- Update Attendance Modal -->
  <div class="modal fade" id="updateAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="updateAttendance" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateAttendance">Edit Student Attendance</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <ul id="attendanceError"></ul>

          <form method="post">
            @csrf
            @method('put')
            <input type="hidden" id="attendance_ID" class="id form-control ">

            <div class="row">
              <div class="col-lg-6">
                <ul id="editIDError"></ul>
                <div class="form-group">
                  <label>Student ID</label>
                  <input type="text" class="updateUserID form-control border border-dark border border-dark" id="edit_studentID" name="update_studentID" disabled>

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editRemarkError"></ul>
                <div class="form-group">
                  <label>Remark</label>
                  <select class="updateRemark form-select form-control border border-dark" aria-label="Default select example" id="edit_Remark" name="update_Remark">
                    <option selected hidden></option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Late">Late</option>
                  </select>
                </div>
              </div>

              <!-- Modal Boday End-->

              <!-- Modal Footer -->
              <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-pill" id="updateClose" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-pill updateAttendance">Update</button>
              </div>

          </form>

        </div>
      </div>
    </div>
  </div>

  <script>
    const yearDropdown = `<i class="mdi mdi-developer-board"></i> Year & Section`;
    const courseDropdown = `<i class="mdi mdi-alpha-c-box"></i> Course`;
    const statusDropdown = `<i class="mdi mdi-alpha-r-box"></i> Remark`;
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.year-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('yearDropdown').innerHTML = `<i class="mdi mdi-developer-board"></i> ${this.textContent}`;
        });
      });
      document.querySelectorAll('.course-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('courseDropdown').innerHTML = `<i class="mdi mdi-alpha-c-box"></i> ${this.textContent}`;
        });
      });
      document.querySelectorAll('.remark-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('statusDropdown').innerHTML = `<i class="mdi mdi-alpha-r-box"></i> ${this.textContent}`;
        });
      });
    });
    $("#resetBtn").on("click", function(e) {
      e.preventDefault();
      document.getElementById("yearDropdown").innerHTML = yearDropdown;
      document.getElementById("courseDropdown").innerHTML = courseDropdown;
      document.getElementById("statusDropdown").innerHTML = statusDropdown;
    });
  </script>




  @include('footer')