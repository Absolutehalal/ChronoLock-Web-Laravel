<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Ajax Instructor Schedule -->
  <script defer src="js/instructorERPScheduleToClassList.js"></script>

  <title>ChronoLock Instructor - Class Schedules</title>

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
              <li class="breadcrumb-item active"><a href="{{ route('classSchedules') }}">ERP Schedules</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <div class="card card-default shadow">
          <div class="card-header align-items-center px-3 px-md-5">
            <h1>ERP Schedule</h1>
            <form method="GET" action="{{ url('/instructorClassSchedules') }}">
              <div class="dropdown d-inline-block mb-3">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="nameDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-s-box"></i>
                  Sort My Schedule
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="nameDropdown">
                  @if(Auth::check())
                  <a class="dropdown-item name-item filter-name" data-value="{{ Auth::user()->accountName }}" href="#">
                    {{ Auth::user()->accountName }}
                  </a>
                  @endif
                </div>
                <input type="hidden" name="name" id="selectedName">
              </div>

              <div class="dropdown d-inline-block mb-3">
                <button class="btn btn-danger btn-sm fw-bold" type="submit">
                  <i class="mdi mdi-sort"></i> Filter
                </button>
              </div>

              <div class="dropdown d-inline-block mb-3">
                <button class="btn btn-warning btn-sm fw-bold" type="submit">
                  <i class="mdi mdi-alpha-r-box"></i>
                  RESET
                </button>
              </div>
            </form>
          </div>

          <d<div class="card-body px-3 px-md-5">
            <div class="row">
              @forelse($schedules as $schedule)
              @csrf
              <div class="col-lg-6 col-xl-6">
                <div class="card card-default border-dark p-4">
                  <button href="javascript:0" class="editERPSchedule media text-secondary" data-toggle="modal" data-target="#scheduleModal" data-avatar="{{ $schedule->avatar ?? asset('images/scheduleIcon.png') }}" data-inst-first-name="{{ $schedule->instFirstName }}" data-inst-last-name="{{ $schedule->instLastName }}" data-course-name="{{ $schedule->courseName }}" data-course-code="{{ $schedule->courseCode }}" data-program="{{ $schedule->program }}" data-year="{{ $schedule->year }}" data-section="{{ $schedule->section }}" data-schedule-id="{{ $schedule->scheduleID }}">
                    <img src="{{ $schedule->avatar ?? asset('images/scheduleIcon.png') }}" class="mr-3 mt-4 img-fluid rounded schedule" alt="Avatar Image">
                    <div class="media-body">
                      <h3 class="mt-0 mb-2 text-dark d-flex fw-bold">Instructor: {{ $schedule->instFirstName }} {{ $schedule->instLastName }}</h3>
                      <ul class="list-unstyled text-smoke">
                        <li class="d-flex">
                          <i class="mdi mdi-map mr-1"></i>
                          <label class="mr-1">Course Name:</label>
                          <span class="text-dark">{{ $schedule->courseName }}</span>
                        </li>
                        <li class="d-flex">
                          <i class="mdi mdi-map mr-1"></i>
                          <label class="mr-1">Course Code:</label>
                          <span class="text-dark">{{ $schedule->courseCode }}</span>
                        </li>
                        <li class="d-flex">
                          <i class="mdi mdi-group mr-1"></i>
                          <label class="mr-1">Program:</label>
                          <span class="text-dark">{{ $schedule->program }}</span>
                        </li>
                        <li class="d-flex">
                          <i class="mdi mdi-alpha-s-box mr-1"></i>
                          <label class="mr-1">Year & Section:</label>
                          <span class="text-dark">{{ $schedule->year }}-{{ $schedule->section }}</span>
                        </li>
                      </ul>
                    </div>
                  </button>
                </div>
              </div>
              @empty
              <div class="card-body">
                <p class="text-center fw-bold">No schedules available.</p>
              </div>
              @endforelse
            </div>
        </div>
      </div>

    </div>
  </div>
  </div>


  <!-- Schedule Modal -->
  <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header justify-content-end border-bottom-0">
          <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
            <i class="mdi mdi-close"></i>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="row no-gutters">
            <div class="col-md-6">
              <div class="profile-content-left px-4">
                <div class="card text-center px-0 border-0">
                  <div class="card-img mx-auto">
                    <img class="rounded schedule" id="modalAvatar" src="" alt="user image">
                  </div>
                  <div class="card-body">
                    <form id="clearScheduleForm" method="post">
                      @csrf
                      @method('post')
                      <!-- <ul id="courseCodeError"></ul> -->
                      <div class="form-group">
                        <h4>
                          <input type="text" class="input form-control" id="courseName" name="courseName" readonly>
                        </h4>
                        <h4>
                          <input type="text" class="input form-control" id="courseCode" name="courseCode" readonly>
                        </h4>
                      </div>
                  </div>
                </div>
                <div class="d-flex justify-content-between ">
                  <div class="text-center pb-4">
                    <!-- <ul id="programError"></ul> -->
                    <h6 class="pb-2">Program</h6>
                    <div class="form-group">
                      <input type="text" class="input form-control" id="program" name="program" readonly>
                    </div>
                  </div>
                  <div class="text-center pb-4">
                    <!-- <ul id="yearError"></ul> -->
                    <h6 class="pb-2">Year</h6>
                    <div class="form-group">
                      <input type="text" class="input form-control" id="year" name="year" readonly>
                    </div>
                  </div>
                  <div class="text-center pb-4">
                    <!-- <ul id="sectionError"></ul> -->
                    <h6 class="pb-2">Section</h6>
                    <div class="form-group">
                      <input type="text" class="input form-control" id="section" name="section" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="contact-info px-4">
                <h4 class="mb-2">Add Class List</h4>
                <input type="hidden" id="scheduleID" name="scheduleID" class="id form-control ">
                <ul id="userIDError"></ul>
                <div class="form-group">
                  <label>User ID</label>
                  <input type="text" class="form-control border border-dark" placeholder="Enter Your User ID" id="userID" name="userID">
                </div>
                <ul id="semesterError"></ul>
                <div class="form-group">
                  <label>Semester</label>
                  <select class="form-select form-control border border-dark" aria-label="Default select example" id="semester" name="semester">
                    <option selected value="" hidden>--Select Schedule Semester--</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value=" 2nd Semester">2nd Semester</option>
                  </select>
                </div>
                <ul id="enrollmentKeyError"></ul>
                <div class="form-group">
                  <label>Enrollment Key</label>
                  <input type="text" class="form-control border border-dark" placeholder="Enter Enrollment Key" id="enrollmentKey" name="enrollmentKey">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger btn-pill" id="close" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary btn-pill createClassList">Create</button>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.name-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('nameDropdown').innerHTML = `<i class="mdi mdi-alpha-s-box"></i> ${this.textContent}`;
          document.getElementById('selectedName').value = this.getAttribute('data-value');
        });
      });

    });

    $(document).ready(function() {
      $('.filter-name').on('click', function() {
        var selectedName = $(this).data('value');
        $('#selectedName').val(selectedName);
      });
    });

    $(document).ready(function() {
      $('.editERPSchedule').on('click', function() {
        let avatar = $(this).data('avatar');
        let instFirstName = $(this).data('inst-first-name');
        let instLastName = $(this).data('inst-last-name');
        let courseName = $(this).data('course-name');
        let courseCode = $(this).data('course-code');
        let program = $(this).data('program');
        let year = $(this).data('year');
        let section = $(this).data('section');
        let scheduleID = $(this).data('schedule-id');

        $('#modalAvatar').attr('src', avatar);
        $('#courseName').val(courseName);
        $('#courseCode').val(courseCode);
        $('#program').val(program);
        $('#year').val(year);
        $('#section').val(section);
        $('#scheduleID').val(scheduleID);
      });
    });
  </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

      $('#scheduleModal').on('hidden.bs.modal', function() {
        $('#clearScheduleForm')[0].reset();
        clearRFIDPendingErrors();
      });

      function clearRFIDPendingErrors() {
        $('#userIDError').empty();
        $('#semesterError').empty();
        $('#enrollmentKeyError').empty();
      }

    });
  </script>


  @include('footer')