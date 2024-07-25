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

  <title>ChronoLock Admin-Schedule</title>

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
  </div>
        <div class="card card-default shadow">
          <div class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
            <h1>Schedule</h1>
            <div class="row">

              <div class="col-xl-12 col-md-12 d-flex justify-content-end">
                <!-- Sort button -->
                <div class="dropdown d-inline-block mb-3 mr-3">
                  <button class="btn btn-primary btn-sm fw-bold" type="button" data-toggle="modal" data-target="#modal-add-event">
                    <i class=" mdi mdi-calendar-plus"></i>
                    ADD SCHEDULE
                  </button>
                </div>
                <div class="dropdown d-inline-block mb-3">
                  <button class="btn btn-warning btn-sm fw-bold" type="button">
                    <i class="mdi mdi-cloud-print-outline"></i>
                    PRINT
                  </button>
                </div>
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

  <div class="modal fade" id="makeUpScheduleModal" tabindex="-1" role="dialog" aria-labelledby="MakeUpSchedule" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="MakeUpSchedule">Make Up Schedule</h5>
          <button type="button" class="close" onclick="$('#makeUpScheduleModal').modal('hide');" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{route('createSchedule')}}">
            @csrf
            @method('post')

            <div class="row">
              <div class="col-lg-12">
                <ul id="titleError"></ul>
                <div class="form-group">
                  <label>Schedule Title</label>
                  <input type="text" class="scheduleTitle form-control border border-dark border border-dark" id="scheduleTitle" name="scheduleTitle" placeholder="Enter Title" />
                </div>
              </div>

              <div class="col-lg-6">
              <ul id="startTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                <label>Start Time</label>
                <div class="input-group date" id="timepicker">
                  <input type="datetime-local" class="startTime form-control border border-primary" placeholder="Time" id="selectedTime" name="startTime">
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
                <label>End Time</label>
                <div class="input-group date" id="timepicker">
                  <input type="datetime-local" class="endTime form-control border border-primary" placeholder="Time" id="selectedTime" name="endTime">
                  <div class="input-group-append">
                    <div class="input-group text-light btn btn-primary btn-sm" id="timeIcon">
                      <i class="mdi mdi-clock "></i>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" onclick="$('#makeUpScheduleModal').modal('hide');">Close</button>
              <button type="submit" class="btn btn-primary btn-pill addMakeUpSchedule">Create</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
  <!-- Add Event Button  -->
  <div class="modal fade" id="modal-add-event" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <form>
          <div class="modal-header px-4">
            <h5 class="modal-title" id="exampleModalCenterTitle">
              Add New Event
            </h5>

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body px-4">
            <div class="form-group">
              <label for="firstName">Title</label>
              <input type="text" class="form-control  border border-dark" value="Meeting" />
            </div>

            <div class="row">
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

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleFormControlSelect3">Time</label>
                  <select class="form-control  border border-dark" id="exampleFormControlSelect3">
                    <option>10:00am</option>
                    <option>10:30am</option>
                    <option>11am</option>
                    <option>11:30am</option>
                    <option>12:00pm</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group mb-0">
              <label for="firstName">Description</label>
              <textarea class="form-control  border border-dark" id="exampleFormControlTextarea1" rows="5"></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-pill">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  </div>



<!-- Update REGULAR SCHEDULE MODAL -->
  <div class="modal fade" id="updateRegularScheduleModal" tabindex="-1" role="dialog" aria-labelledby="updateRegularSchedule" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateRegularSchedule">Edit Regular Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

         

      
          <form method="post">
            @csrf
            @method('put')
          <ul id="regularScheduleError"></ul>
            <input type="hidden" id="regularScheduleID" class="id form-control ">

            <div class="row">
              <div class="col-lg-6">
                <ul id="editCourseCodeError"></ul>
                <div class="form-group">
                  <label>Course Code</label>
                  <input type="text" class="updateCourseCode form-control border border-dark border border-dark" id="edit_course_code" name="updateCourseCode" placeholder="Enter New Course Code">
            
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editCourseNameError"></ul>
                <div class="form-group">
                  <label>Course Name</label>
                  <input type="text" class="updateCourseName form-control border border-dark border border-dark" id="edit_course_name" name="updateCourseName" placeholder="Enter New Course Name">
               
                </div>
              </div>

              <div class="col-lg-6">
              <ul id="startTimeError"></ul>
                <div class="dropdown d-inline-block mb-3">
                <label>Start Time</label>
                <div class="input-group date" id="timepicker">
                  <input type="datetime-local" class="startTime form-control border border-primary" placeholder="Time" id="selectedTime" name="startTime">
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
                <label>End Time</label>
                <div class="input-group date" id="timepicker">
                  <input type="datetime-local" class="endTime form-control border border-primary" placeholder="Time" id="selectedTime" name="endTime">
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
              <label>Start Date</label>
              <div class="input-group date" id="datepicker">
                <input type="datetime-local" class="startDate form-control border border-primary" placeholder="Date" id="selectedDate">
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
              <label>End Date</label>
              <div class="input-group date" id="datepicker">
                <input type="datetime-local" class="endDate form-control border border-primary" placeholder="Date" id="selectedDate">
                <div class="input-group-append">
                  <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                    <i class="mdi mdi-calendar "></i>
                  </div>
                </div>
              </div>
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
  <!-- Footer -->
  <!-- <footer class="footer mt-auto">
          <div class="copyright bg-white">
            <p>
              &copy; <span id="copy-year"></span> Copyright Mono Dashboard
              Bootstrap Template by
              <a
                class="text-primary"
                href="http://www.iamabdus.com/"
                target="_blank"
                >Abdus</a
              >.
            </p>
          </div>
          <script>
            var d = new Date();
            var year = d.getFullYear();
            document.getElementById("copy-year").innerHTML = year;
          </script>
        </footer> -->
  </div>
  </div>

  @include('footer')