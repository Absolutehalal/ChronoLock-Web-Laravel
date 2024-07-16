<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Ajax Instructor Schedule -->
  <script defer src="js/scheduleToClassList.js"></script>

  <title>ChronoLock Instructor-Class Schedules</title>

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

        <!-- <div class="card card-default shadow"> -->
        <div class="card-body">
          <div class="row">
            @forelse($schedules as $schedule)
            @csrf
            <div class="col-lg-6 col-xl-6">
              <div class="card card-default shadow p-4">
                <button href="javascript:0" class="editClassSchedule media text-secondary" data-toggle="modal" data-target="#scheduleModal" value="{{$schedule->scheduleID}}">
                  <img src="images/scheduleIcon.png" class="mr-3 mt-4 img-fluid rounded schedule" alt="Avatar Image">
                  <div class="media-body">
                    <h3 class="mt-0 mb-2 text-dark d-flex">Instructor: {{ $schedule->instFirstName }} {{ $schedule->instLastName }}</h3>
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
                        <span class="text-dark">{{ $schedule->course }}</span>
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


    <!-- Contact Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header justify-content-end border-bottom-0">
            <!-- <button type="button" class="btn-edit-icon" data-dismiss="modal" aria-label="Close">
                <i class="mdi mdi-pencil"></i>
              </button>

              <div class="dropdown">
                <button class="btn-dots-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="mdi mdi-dots-vertical"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="javascript:void(0)">Action</a>
                  <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                  <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                </div>
              </div> -->

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
                      <img class="rounded schedule" src="images/scheduleIcon.png" alt="user image">
                    </div>

                    <div class="card-body">
                      <form method="post">
                        @csrf
                        @method('post')
                        <ul id="courseCodeError"></ul>
                        <div class="form-group">
                          <h4> <input type="text" class="input form-control" id="courseName" name="courseName" readonly>
                            <h4>
                              <h4> <input type="text" class="input form-control" id="courseCode" name="courseCode" readonly>
                                <h4>
                        </div>
                    </div>
                  </div>

                  <div class="d-flex justify-content-between ">

                    <div class="text-center pb-4">
                      <ul id="programError"></ul>
                      <h6 class="pb-2">Program</h6>
                      <div class="form-group">
                        <input type="text" class="input form-control" id="program" name="program" readonly>
                      </div>
                    </div>

                    <div class="text-center pb-4">
                      <ul id="yearError"></ul>
                      <h6 class="pb-2">Year</h6>
                      <div class="form-group">
                        <input type="text" class="input form-control" id="year" name="year" readonly>
                      </div>
                    </div>

                    <div class="text-center pb-4">
                      <ul id="sectionError"></ul>
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
                    <input type="text" class="form-control border border-dark border border-dark" placeholder="Enter Your User ID" id="userID" name="userID">
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
                    <input type="text" class="form-control border border-dark border border-dark" placeholder="Enter Enrollment Key" id="enrollmentKey" name="enrollmentKey">
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

    @include('footer')

</body>

</html>