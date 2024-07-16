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
  
  <!-- Ajax Instructor Attendance -->
  <script defer src="js/adminEditInstructorAttendance.js"></script>

  <title>ChronoLock Admin-Instructor Attendance</title>

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
              <li class="breadcrumb-item active"><a href="{{ route('instructorAttendanceManagement') }}">Attendance</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('instructorAttendanceManagement') }}">Instructor Attendance</a></li>
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
              <form method="GET" action="{{ route('instructorAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="instIDDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-i-box"></i>
                  Instructor ID
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instIDDropdown">
                @foreach($instructorsID as $instructorID)
                  @csrf
                  <a class="dropdown-item id-item filter-inst-id" data-value="{{ $instructorID->userID }}" href="#">
                  {{ $instructorID->userID }}-{{ $instructorID->instFirstName }}  {{ $instructorID->instLastName }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="instructorID" id="selectedInstID">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('instructorAttendanceManagement') }}">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="instStatusDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-s-box"></i>
                  Remark
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instStatusDropdown">
                @foreach($remarks as $remarks)
                  @csrf
                  <a class="dropdown-item remark-item filter-inst-status" data-value="{{ $remarks->remark }}" href="#">
                    {{ $remarks->remark }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="instructorStatus" id="selectedInstStatus">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <div class="input-group date " id="datepicker">
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

          </div>


          <div class="col-xl-3 col-md-3 d-flex justify-content-end">
            <!-- Reset button -->
            <div class="d-inline-block mb-3 ">
              <button class="btn btn-warning btn-sm fw-bold" id="resetBtn" type="button">
                <i class="mdi mdi-alpha-r-box"></i>
                Reset
              </button>
            </div>
          </div>

        </div>
        <!-- END -->


        <div class="card card-default shadow">
          <div class="card-header">
            <h1>Instructor Realtime Attendance</h1>
          </div>
          <div class="card-body ">
            <table id="AttendanceTable" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Course Code</th>
                  <th>Program & Section</th>
                  <th>Instructor</th>
                  <th>Instructor ID</th>
                  <th>Remarks</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @foreach($instructors as $instructors)
                @csrf
                <tr>
                  <td>{{ $instructors->formatted_date }}</td>
                  <td>{{ $instructors->formatted_time }}</td>
                  <td>{{ $instructors->courseCode }}</td>
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
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Actions
                        </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item editAttendanceBtn" type="button" data-toggle="modal" data-target="#updateAttendanceModal" value="{{$instructors->attendanceID}}">
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>
                          Edit</button>
                        <button class="dropdown-item deleteAttendanceBtn" type="button" data-toggle="modal" data-target="#deleteAttendanceModal" value="{{$instructors->attendanceID}}">
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
          <h5 class="modal-title" id="deleteAttendance" style="text-align:center;">Delete Instructor Attendance</h5>
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
              <h4 style="text-align:center;"> Are you sure you want to delete this Instructor Attendance?</h4>
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
          <h5 class="modal-title" id="updateAttendance">Edit Instructor Attendance</h5>
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
                  <label>Instructor ID</label>
                  <input type="text" class="updateUserID form-control border border-dark border border-dark" id="edit_instructorID" name="update_instructorID" readonly>
                 
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
    const instIDDropdown = `<i class="mdi mdi-alpha-i-box"></i> Instructor ID`;
    const instStatusDropdown = `<i class="mdi mdi-alpha-r-box"></i> Remark`;
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.remark-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('instStatusDropdown').innerHTML = `<i class="mdi mdi-alpha-r-box"></i> ${this.textContent}`;
        });
      });
      document.querySelectorAll('.id-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('instIDDropdown').innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
      });
    });
    $("#resetBtn").on("click", function (e) {
        e.preventDefault();
        // Reset the dropdown button to its default UI
        document.getElementById("instIDDropdown").innerHTML = instIDDropdown;
        document.getElementById("instStatusDropdown").innerHTML = instStatusDropdown;
    });
  </script>
  @include('footer')