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

  <script src="{{asset('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js')}}"></script> 

  <!-- Ajax Student Attendance -->
  <script defer src="js/addRegularSchedule.js"></script>

  @if (Auth::check())
  <title>ChronoLock {{ Auth::user()->userType }} - Schedule</title>
  @endif

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
              <li class="breadcrumb-item active"><a href="{{ route('adminScheduleManagement') }}">Schedule</a></li>
            </ol>
          </nav>
          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-9 d-flex justify-content-start mb-2">
            <form action="{{ route('schedule.import') }}" method="post" enctype="multipart/form-data">
              @csrf

              <div class="dropdown d-inline-block mr-2">
                <button class="btn btn-primary btn-sm fw-bold" type="submit">
                  <i class="mdi mdi-file-check"></i>
                  Import
                </button>
              </div>

              <div class="dropdown d-inline-block">
                <div class="custom-file rounded">
                  <input type="file" class="custom-file-input" id="excel-file" name="excel-file" required>
                  <label class="custom-file-label" for="excel-file">Choose file</label>
                  <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
              </div>
            </form>
          </div>

          <div class="col-md-3 d-flex justify-content-end mb-2">
            <div class="dropdown d-inline-block mb-2 rounded-2">
              <button title="Add Regular Schedule" class="btn btn-primary btn-sm fw-bold" type="button" data-toggle="modal" data-target="#addRegularScheduleModal">
                <i class=" mdi mdi-calendar-plus"></i>
                ADD SCHEDULE
              </button>
            </div>
          </div>
        </div>

        <div class="card card-default shadow">
          <div class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
            <h1>Schedule</h1>
            <div class="row">

              <div class="col-xl-12 col-md-12 d-flex justify-content-end">
              @if($maintenance != null)
               
              <div class="d-inline-block mb-3">
                <form action="{{ url('/openERPLaboratory') }}" method="GET">
                  <!-- <input type="text" class="form-control border border-primary" id="exportDate" name="selectedDate" value="{{ Request()->date }}"> -->
                  <button class="openERPButton btn btn-outline-success btn-sm fw-bold" id="openERPButton" type="submit">
                    <i class="mdi mdi-lock-open-outline"></i>
                      Open Laboratory
                  </button>
                </form>
              </div>

              @else
              <div class="d-inline-block mb-3">
                <form action="{{ url('/closeERPLaboratory') }}" method="GET">
                  <!-- <input type="text" class="form-control border border-primary" id="exportDate" name="selectedDate" value="{{ Request()->date }}"> -->
                  <button class="closeERPButton btn btn-outline-danger btn-sm fw-bold" id="closeERPButton" type="submit">
                    <i class="mdi mdi-lock-outline"></i>
                      Close Laboratory
                  </button>
                </form>
              </div>
              @endif
        
              
                <!-- Sort button -->
                <!-- <div class="dropdown d-inline-block mb-3 mr-3">
                  <button title="Add Regular Schedule" class="btn btn-primary btn-sm fw-bold" type="button" data-toggle="modal" data-target="#addRegularScheduleModal">
                    <i class=" mdi mdi-calendar-plus"></i>
                    ADD SCHEDULE
                  </button>
                </div> -->
                <!-- <div class="dropdown d-inline-block mb-3 mr-3">
                  <button title="Export PDF" class="btn btn-warning btn-sm fw-bold" onclick='window.location = "{{ route("exportPDF") }}"' type="button">
                    <i class="mdi mdi-file-pdf"></i>
                    PDF
                  </button>
                </div> -->

                <!-- <div class="dropdown d-inline-block mb-3">
                  <button title="Preview" class="btn btn-outline-dark btn-sm fw-bold" onclick='window.location = "{{ route("previewPDF") }}"' type="button">
                    <i class="mdi mdi-feature-search"></i>
                    Preview
                  </button>
                </div> -->
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="full-calendar mb-5">
              <div id="calendar"></div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
  </div>
  </div>


  </div>
  </div>

  <!-- CREATE MAKE UP SCHEDULE MODAL -->
  <div class="modal fade" id="makeUpScheduleModal" role="dialog" aria-labelledby="MakeUpSchedule" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="MakeUpSchedule">Make Up Schedule</h5>
          <button type="button" class="close" onclick="$('#makeUpScheduleModal').modal('hide');" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="clearMakeUpSchedule" method="post" action="{{route('createSchedule')}}">
            @csrf
            @method('post')

            <div class="row">
              <div class="col-lg-6">
                <ul id="titleError"></ul>
                <div class="form-group">
                  <label for="scheduleTitle">Schedule Title</label>
                  <input type="text" class="scheduleTitle form-control border border-dark border border-dark" id="scheduleTitle" name="scheduleTitle" placeholder="Enter Title" />
                </div>
              </div>


              <div class="col-lg-6">
                <ul id="programError"></ul>
                <div class="form-group">
                  <label for="program">Program</label>
                  <select class="program form-select form-control border border-dark" aria-label="Default select example" id="program" name="program">
                    <option selected value="" hidden>-Select Program-</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSIS">BSIS</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BLIS">BLIS</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="makeUpCourseCodeError"></ul>
                <div class="form-group">
                  <label>Course Code</label>
                  <input type="text" class="makeUpCourseCode form-control border border-dark border border-dark" id="makeUpCourseCode" name="makeUpCourseCode" placeholder="Enter Course Code" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="makeUpCourseNameError"></ul>
                <div class="form-group">
                  <label>Course Name</label>
                  <input type="text" class="makeUpCourseName form-control border border-dark border border-dark" id="makeUpCourseName" name="makeUpCourseName" placeholder="Enter Course Name" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="yearError"></ul>
                <div class="form-group">
                  <label for="year">Year</label>
                  <select class="year form-select form-control border border-dark" aria-label="Default select example" id="year" name="year">
                    <option selected value="" hidden>-Select Year-</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="sectionError"></ul>
                <div class="form-group">
                  <label for="section">Section</label>
                  <select class="section form-select form-control border border-dark" aria-label="Default select example" id="section" name="section">
                    <option selected value="" hidden>-Select Section-</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="H">H</option>
                    <option value="I">I</option>
                    <option value="J">J</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="startTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime1">Start Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="makeUpScheduleStartTime form-control border border-primary" placeholder="Time" id="selectedTime1" name="startTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="endTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime2">End Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="makeUpScheduleEndTime form-control border border-primary" placeholder="Time" id="selectedTime2" name="endTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <label for="makeupinstIDDropdown">Instructor</label>
              <div class="col-lg-6">
                <ul id="facultyError"></ul>
                <form method="GET" action="{{ route('adminScheduleManagement') }}">

                  <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="makeupInstIDDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-alpha-i-box"></i>
                    Instructor ID
                  </button>
                  <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instIDDropdown">
                    @forelse($instructorsID as $instructorID)
                    @csrf
                    <a class="dropdown-item id-item filter-inst-id" data-value="{{ $instructorID->idNumber }}" href="#">
                    {{ $instructorID->idNumber }}-{{ ucwords($instructorID->firstName) }} {{ ucwords($instructorID->lastName) }}
                    </a>
                    @empty
                    <a class="dropdown-item filter-faculty-id" data-value="None" href="#">
                      None
                    </a>
                    @endforelse
                  </div>
                  <input type="hidden" class="faculty form-control" name="instructorID" id="selectedInstID">
                </form>
              </div>

            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer mt-2">
              <button type="button" class="btn btn-danger btn-pill" onclick="$('#makeUpScheduleModal').modal('hide');">Close</button>
              <button type="submit" class="btn btn-primary btn-pill addMakeUpSchedule">Create</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>



  <!-- Decision Regular Schedule Modal -->
  <div class="modal fade" id="decisionRegularScheduleModal" role="dialog" aria-labelledby="decisionRegularSchedule" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="decisionRegularSchedule" style="text-align:center;">Choose Action</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#decisionRegularScheduleModal').modal('hide');">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <i class="fa-solid fa-exclamation-circle" style="text-align:center; font-size:50px; padding:1rem;"></i>
            <h4 style="text-align:center;">What Would you like to do?</h4>
          </div>


          <div class="d-flex justify-content-center mt-5">
            <button class="btn btn-warning btn-pill mr-2 editRegularSchedule fw-bold" type="button" data-toggle="modal" data-target="#updateRegularScheduleModal">
              <i class="mdi mdi-circle-edit-outline"></i>
              Edit Schedule</button>
            <button class="btn btn-danger btn-pill deleteRegularSchedule fw-bold" type="button" data-toggle="modal" data-target="#deleteRegularScheduleModal">
              <i class="mdi mdi-trash-can"></i>
              Delete Schedule</button>

          </div>
        </div> <!-- Modal Boday End-->

      </div>
    </div>
  </div>


  <!-- Decision Make Up Schedule -->
  <div class="modal fade" id="decisionMakeUpScheduleModal" role="dialog" aria-labelledby="decisionMakeUpSchedule" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="decisionMakeUpSchedule" style="text-align:center;">Choose Action</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close" onclick="$('#decisionMakeUpScheduleModal').modal('hide');">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <i class="fa-solid fa-exclamation-circle" style="text-align:center; font-size:50px; padding:1rem;"></i>
            <h4 style="text-align:center;">What Would you like to do?</h4>
          </div>


          <div class="d-flex justify-content-center mt-5">
            <button class="btn btn-warning btn-pill mr-2 editMakeUpSchedule fw-bold" type="button" data-toggle="modal" data-target="#updateMakeUpScheduleModal">
              <i class="mdi mdi-circle-edit-outline text-light"></i>
              Edit Schedule</button>
            <button class="btn btn-danger btn-pill deleteMakeUpSchedule fw-bold" type="button" data-toggle="modal" data-target="#deleteMakeUpScheduleModal">
              <i class="mdi mdi-trash-can text-light"></i>
              Delete Schedule</button>

          </div>
        </div> <!-- Modal Boday End-->



      </div>
    </div>
  </div>
  </div>

  <!-- Add Regular Schedule Button  -->
  <div class="modal fade" id="addRegularScheduleModal" role="dialog" aria-labelledby="addRegularSchedule" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addRegularSchedule">Add Regular Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="clearRegularSchedule" method="post" action="{{route('createRegularSchedule')}}">
            @csrf
            @method('post')

            <div class="row">
              <div class="col-lg-6">
                <ul id="courseCodeError"></ul>
                <div class="form-group">
                  <label for="courseCode">Course Code</label>
                  <input required type="text" class="courseCode form-control border border-dark border border-dark" id="courseCode" name="courseCode" placeholder="Enter Course Code" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="courseNameError"></ul>
                <div class="form-group">
                  <label for="courseName">Course Name</label>
                  <input type="text" class="courseName form-control border border-dark border border-dark" id="courseName" name="courseName" placeholder="Enter Course Name" />
                </div>
              </div>


              <div class="col-lg-6">
                <ul id="scheduleProgramError"></ul>
                <div class="form-group">
                  <label for="scheduleProgram">Program</label>
                  <select class="scheduleProgram form-select form-control border border-dark" id="scheduleProgram" name="scheduleProgram">
                    <option selected value="" hidden>-Select Program-</option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSIS">BSIS</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BLIS">BLIS</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="scheduleYearError"></ul>
                <div class="form-group">
                  <label for="scheduleYear">Year</label>
                  <select class="scheduleYear form-select form-control border border-dark" id="scheduleYear" name="scheduleYear">
                    <option selected value="" hidden>-Select Year-</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="scheduleSectionError"></ul>
                <div class="form-group">
                  <label for="scheduleSection">Section</label>
                  <select class="scheduleSection form-select form-control border border-dark" id="scheduleSection" name="scheduleSection">
                    <option selected value="" hidden>-Select Section-</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="H">H</option>
                    <option value="I">I</option>
                    <option value="J">J</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="scheduleEditWeekDayError"></ul>
                <div class="form-group">
                  <label for="scheduleWeekDay">Day</label>
                  <select class="scheduleWeekDay form-select form-control border border-dark" aria-label="Default select example" id="scheduleWeekDay" name="scheduleWeekDay">
                    <option selected value="" hidden>--Select Day--</option>
                    <option value="0">Sunday</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-3">
                <ul id="scheduleStartTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime3">Start Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="scheduleStartTime form-control border border-primary" placeholder="Time" id="selectedTime3" name="ScheduleStartTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3">
                <ul id="scheduleEndTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime4">End Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="scheduleEndTime form-control border border-primary" placeholder="Time" id="selectedTime4" name="ScheduleEndTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <ul id="scheduleStartDateError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedDate1">Start Date</label>
                  <div class="input-group date" id="datepicker">
                    <input type="datetime-local" class="scheduleStartDate form-control border border-primary" placeholder="Date" id="selectedDate1">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                        <i class="mdi mdi-calendar "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3">
                <ul id="scheduleEndDateError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedDate2">End Date</label>
                  <div class="input-group date" id="datepicker">
                    <input type="datetime-local" class="scheduleEndDate form-control border border-primary" placeholder="Date" id="selectedDate2">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                        <i class="mdi mdi-calendar "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <label for="facultyIDDropdown">Instructor</label>

              <div class="col-lg-6">
                <ul id="scheduleFacultyError"></ul>
                <form id="" method="GET" action="{{ route('adminScheduleManagement') }}">

                  <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="facultyIDDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-alpha-i-box"></i>
                    Instructor ID
                  </button>
                  <div class="dropdown-menu scrollable-dropdown" aria-labelledby="facultyIDDropdown">
                    @forelse($instructorsID as $instructorID)
                    @csrf
                    <a class="dropdown-item id-faculty filter-faculty-id" data-value="{{ $instructorID->idNumber }}" href="#">
                      {{ $instructorID->idNumber }}-{{ $instructorID->firstName }} {{ $instructorID->lastName }}
                    </a>
                    @empty
                    <a class="dropdown-item filter-faculty-id" data-value="None" href="#">
                      None
                    </a>
                    @endforelse
                    <input type="hidden" class="scheduleFaculty form-control" name="facultyID" id="selectedFacultyID">
                  </div>
                </form>
              </div>
            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-pill createRegularSchedule">Create</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>


  <!-- Update REGULAR SCHEDULE MODAL -->
  <div class="modal fade" id="updateRegularScheduleModal" role="dialog" aria-labelledby="updateRegularSchedule" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateRegularSchedule">Edit Regular Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="clearForm" method="post">
            @csrf
            @method('put')
            <ul id="regularScheduleError"></ul>
            <input type="hidden" id="regularScheduleID" class="id form-control ">

            <div class="row">
              <div class="col-lg-6">
                <ul id="editCourseCodeError"></ul>
                <div class="form-group">
                  <label for="edit_course_code">Course Code</label>
                  <input type="text" class="updateCourseCode form-control border border-dark border border-dark" id="edit_course_code" name="updateCourseCode" placeholder="Enter New Course Code">

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editCourseNameError"></ul>
                <div class="form-group">
                  <label for="edit_course_name">Course Name</label>
                  <input type="text" class="updateCourseName form-control border border-dark border border-dark" id="edit_course_name" name="updateCourseName" placeholder="Enter New Course Name">

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="startTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime5">Start Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="startTime form-control border border-primary" placeholder="Time" id="selectedTime5" name="startTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="endTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime6">End Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="endTime form-control border border-primary" placeholder="Time" id="selectedTime6" name="endTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="startDateError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedDate3">Start Date</label>
                  <div class="input-group date" id="datepicker">
                    <input type="datetime-local" class="startDate form-control border border-primary" placeholder="Date" id="selectedDate3">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                        <i class="mdi mdi-calendar "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="endDateError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedDate4">End Date</label>
                  <div class="input-group date" id="datepicker">
                    <input type="datetime-local" class="endDate form-control border border-primary" placeholder="Date" id="selectedDate4">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                        <i class="mdi mdi-calendar "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="col-lg-6">
                <ul id="editWeekDayError"></ul>
                <div class="form-group">
                  <label for="edit_weekday">Day</label>
                  <select class="updateWeekDay form-select form-control border border-dark" aria-label="Default select example" id="edit_weekday" name="updateWeekDay">

                    <option selected hidden></option>
                    <option value="0">Sunday</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                  </select>
                </div>
              </div>


            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" id="updateClose" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-pill updateRegularSchedule">Update</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>


  <!-- Update Make Up SCHEDULE MODAL -->
  <div class="modal fade" id="updateMakeUpScheduleModal" role="dialog" aria-labelledby="updateMakeUpSchedule" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateMakeUpScheduleModal">Edit Make Up Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            @csrf
            @method('put')
            <ul id="makeUpScheduleError"></ul>
            <input type="hidden" id="makeUpScheduleID" class="id form-control ">
            <input type="hidden" id="day" class="day form-control ">

            <div class="row">
              <div class="col-lg-6">
                <ul id="editMakeUpScheduleTitleError"></ul>
                <div class="form-group">
                  <label for="edit_schedule_title">Schedule Title</label>
                  <input type="text" class="updateScheduleTitle form-control border border-dark border border-dark" id="edit_schedule_title" name="updateScheduleTitle" placeholder="Enter New Schedule Title">

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editProgramError"></ul>
                <div class="form-group">
                  <label for="edit_schedule_program">Program</label>
                  <select class="updateProgram form-select form-control border border-dark" id="edit_schedule_program" name="updateProgram">
                    <option selected hidden></option>
                    <option value="BSIT">BSIT</option>
                    <option value="BSIS">BSIS</option>
                    <option value="BSCS">BSCS</option>
                    <option value="BLIS">BLIS</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editYearError"></ul>
                <div class="form-group">
                  <label for="edit_schedule_year">Year</label>
                  <select class="updateYear form-select form-control border border-dark" id="edit_schedule_year" name="updateYear">
                    <option selected hidden></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editSectionError"></ul>
                <div class="form-group">
                  <label for="edit_schedule_section">Section</label>
                  <select class="updateSection form-select form-control border border-dark" id="edit_schedule_section" name="updateSection">
                    <option selected hidden></option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="H">H</option>
                    <option value="I">I</option>
                    <option value="J">J</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editStartTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime7">Start Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="updateStartTime form-control border border-primary" placeholder="Time" id="selectedTime7" name="updateStartTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editEndTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                  <label for="selectedTime8">End Time</label>
                  <div class="input-group date" id="timepicker">
                    <input type="datetime-local" class="updateEndTime form-control border border-primary" placeholder="Time" id="selectedTime8" name="updateEndTime">
                    <div class="input-group-append">
                      <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                        <i class="mdi mdi-clock "></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- <label>Instructor</label>

              <div class="col-lg-6">
                <ul id="editScheduleFacultyError"></ul>
                <form id="" method="GET" action="{{ route('adminScheduleManagement') }}">

                  <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="editFacultyIDDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-alpha-i-box"></i>
                    Instructor ID
                  </button>
                  <div class="dropdown-menu scrollable-dropdown" aria-labelledby="facultyIDDropdown">
                    @forelse($instructorsID as $instructorID)
                    @csrf
                    <a class="dropdown-item edit-faculty makeUp-filter-faculty-id" data-value="{{ $instructorID->idNumber }}" href="#">
                      {{ $instructorID->idNumber }}-{{ $instructorID->firstName }} {{ $instructorID->lastName }}
                    </a>
                    @empty
                    <a class="dropdown-item filter-faculty-id" data-value="None" href="#">
                      None
                    </a>
                    @endforelse
                   <input type="text" class="updateFaculty form-control" name="facultyID" id="editMakeUpSelectedFacultyID">
                  </div>
                </form>
              </div> -->

            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" id="updateClose" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-pill updateMakeUpSchedule">Update</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>

  <!-- Delete Regular Schedule Modal -->
  <div class="modal fade" id="deleteRegularScheduleModal" role="dialog" aria-labelledby="deleteRegularSchedule" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteRegularSchedule" style="text-align:center;">Delete Regular Schedule</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            @csrf
            @method('delete')
            <div class="row">
              <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to delete this schedule?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-pill" id="deleteClose" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-pill forceDeleteRegularSchedule">Delete</button>
        </div>

        </form>

      </div>
    </div>
  </div>
  </div>


  <!-- Delete Make Up Schedule Modal -->
  <div class="modal fade" id="deleteMakeUpScheduleModal" role="dialog" aria-labelledby="deleteMakeUpSchedule" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteMakeUpSchedule" style="text-align:center;">Delete Make Up Schedule</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            @csrf
            @method('delete')
            <div class="row">
              <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to delete this schedule?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-pill" id="close" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-pill forceDeleteMakeUpSchedule">Delete</button>
        </div>

        </form>

      </div>
    </div>
  </div>
  </div>

  </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      document.querySelectorAll('.id-faculty').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('facultyIDDropdown').innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
      });

      document.querySelectorAll('.id-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('makeupInstIDDropdown').innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
        });
      });

      // document.querySelectorAll('.edit-faculty').forEach(function(item) {
      //   item.addEventListener('click', function(e) {
      //     e.preventDefault();
      //     document.getElementById('editFacultyIDDropdown').innerHTML = `<i class="mdi mdi-alpha-i-box"></i> ${this.textContent}`;
      //   });
      // });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Clear form and errors for Add Regular Schedule Modal
      $('#addRegularScheduleModal').on('hidden.bs.modal', function() {
        $('#clearRegularSchedule')[0].reset();
        clearRegularScheduleErrors();

      });

      // Clear form and errors for Make Up Schedule Modal
      $('#makeUpScheduleModal').on('hidden.bs.modal', function() {
        $('#clearMakeUpSchedule')[0].reset();
        clearMakeUpScheduleErrors();

        document.getElementById('makeupinstIDDropdown').innerHTML = '<i class="mdi mdi-alpha-i-box"></i> Instructor ID';
        document.getElementById('selectedFacultyID').value = ''; // Reset the hidden input value as well
      });

      // Clear form and errors for update make up Schedule Modal
      $('#updateMakeUpScheduleModal').on('hidden.bs.modal', function() {

        document.getElementById('editfacultyIDDropdown').innerHTML = '<i class="mdi mdi-alpha-i-box"></i> Instructor ID';
        document.getElementById('updateFaculty').value = ''; // Reset the hidden input value as well
      });


      function clearRegularScheduleErrors() {
        $('#courseCodeError').empty();
        $('#courseNameError').empty();
        $('#scheduleProgramError').empty();
        $('#scheduleYearError').empty();
        $('#scheduleSectionError').empty();
        $('#scheduleEditWeekDayError').empty();
        $('#scheduleStartTimeError').empty();
        $('#scheduleEndTimeError').empty();
        $('#scheduleStartDateError').empty();
        $('#scheduleEndDateError').empty();
        $('#scheduleFacultyError').empty();
      }

      function clearMakeUpScheduleErrors() {
        $('#titleError').empty();
        $('#programError').empty();
        $('#yearError').empty();
        $('#sectionError').empty();
        $('#startTimeError').empty();
        $('#endTimeError').empty();
        $('#facultyError').empty();
      }
    });
  </script>

  <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
  @include('footer')