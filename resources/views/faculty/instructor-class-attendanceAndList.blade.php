<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Ajax Student Attendance -->
  <script defer src="{{asset('js/instructorEditStudentClassAttendanceAndList.js')}}"></script>

  <title>Class Attendance & List</title>

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
    @include('header')

    <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
    <div class="content-wrapper">
      <div class="content"><!-- Card Profile -->


        <div class="d-flex justify-content-between align-items-center">
          <!-- Navigation -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('instructorIndex') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="inst-class-record.php">Class Attendance & Class List</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-2" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>


        <div class="card card-default shadow card-profile">

          <div class="card-header-bg" style=""></div>

          <div class="card-body card-profile-body">
            @if(Auth::check())
            <div class="profile-avata">
              <img class="rounded-circle" src="{{ Auth::user()->avatar ?? asset('images/User Icon.png') }}" alt="Avatar Image" style="width: 150px; height: 150px;">
              <span class="h3 d-block mt-3 mb-2">{{ Auth::user()->accountName }}</span>
              <span class="d-block">{{ Auth::user()->email }}</span>
              <span class="d-block">{{ Auth::user()->userType }}</span>
            </div>
            @endif
            <ul class="nav nav-profile-follow">
              <li class="nav-item">
                <div class="nav-link">
                  @if($studentCount == 1)
                  <span class="text-dark d-block">Student:</span>
                  @else($studentCount > 1)
                  <span class="text-dark d-block">Students:</span>
                  @endif

                  <span class="h5 d-block"> {{ $studentCount }} </span>

                </div>
              </li>
              <li class="nav-item">
                <div class="nav-link">
                  <span class="text-dark d-block">Course:</span>
                  @foreach ($classListData as $classTime)
                  <span class="h5 d-block"> {{ $classTime->program }} </span>
                  @endforeach
                </div>
              </li>
              <li class="nav-item">
                <div class="nav-link">
                  <span class="text-dark d-block">Year & Section:</span>
                  @foreach ($classListData as $classTime)
                  <span class="h5 d-block"> {{ $classTime->year }}-{{ $classTime->section}} </span>
                  @endforeach
                </div>
              </li>
              <li class="nav-item">
                <div class="nav-link">
                  <span class="text-dark d-block">Schedule:</span>
                  @foreach ($classListData as $classTime)
                  @php
                  $startTime = date('g:i A', strtotime($classTime->startTime));
                  $endTime = date('g:i A', strtotime($classTime->endTime));
                  @endphp
                  <span class="h5 d-block">{{ $startTime }} - {{ $endTime }}</span>
                  @endforeach
                </div>
              </li>


              @php
              // Mapping of day numbers
              $dayMapping = [
              0 => 'Sunday',
              1 => 'Monday',
              2 => 'Tuesday',
              3 => 'Wednesday',
              4 => 'Thursday',
              5 => 'Friday',
              6 => 'Saturday'
              ];

              // Get the day name from the mapping
              $dayName = $dayMapping[$classTime->day];
              @endphp

              <li class="nav-item">
                <div class="nav-link">
                  <span class="text-dark d-block">Day:</span>

                  <span class="h5 d-block"> {{ $dayName }} </span>

                </div>
              </li>

              <li class="nav-item">
                <div class="nav-link">
                  <span class="text-dark d-block">Semester:</span>
                  @foreach ($classListData as $classTime)
                  <span class="h5 d-block"> {{ $classTime->semester }} </span>
                  @endforeach
                </div>
              </li>

              <li class="nav-item">
                <div class="nav-link">
                  <span class="text-dark d-block">Course:</span>
                  @foreach ($classListData as $classTime)
                  <span class="h5 d-block"> {{ $classTime->courseCode }} - {{ $classTime->courseName }} </span>
                  @endforeach
                </div>
              </li>
            </ul>

            <div class="profile-button d-block justify-content-between">

              <div class="row">
                <div class="col-xl-4">
                  <h5 class="text-success d-block fw-bold">Regular: </h5>

                  @if($attendanceCounts->regular_count > 0)
                  <h5>{{ $attendanceCounts->regular_count }}</h5>
                  @else
                  <h5>0</h5>
                  @endif
                </div>
                <div class="col-xl-4">
                  <h5 class="text-warning d-block fw-bold">Irregular: </h5>

                  @if($attendanceCounts->irregular_count > 0)
                  <h5>{{ $attendanceCounts->irregular_count }}</h5>
                  @else
                  <h5>0</h5>
                  @endif
                </div>
                <div class="col-xl-4">
                  <h5 class="text-danger d-block fw-bold">Drop: </h5>

                  @if($attendanceCounts->drop_count > 0)
                  <h5>{{ $attendanceCounts->drop_count }}</h5>
                  @else
                  <h5>0</h5>
                  @endif
                </div>
              </div>

            </div>

          </div>

          <div class="card-footer card-profile-footer mb-4">
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-attendance-tab" data-bs-toggle="pill" href="attendanceTab" data-bs-target="#pills-attendance" type="button" role="tab" aria-controls="pills-attendance" aria-selected="true">Class Attendance</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-classList-tab" data-bs-toggle="pill" href="listTab" data-bs-target="#pills-classList" type="button" role="tab" aria-controls="pills-classList" aria-selected="false">Students</button>
              </li>
            </ul>


            <div class="tab-content" id="pills-tabContent">
              <div class="tab-pane fade" id="pills-attendance" role="tabpanel" aria-labelledby="pills-attendance-tab">
                <div class="row">
                  <div class="col-xl-9 col-md-9">

                    <div class="dropdown d-inline-block">
                      <form method="GET" action="">
                        <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="remarkDropdown" data-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-alpha-s-box"></i>
                          Remarks
                        </button>
                        <div class="dropdown-menu scrollable-dropdown" aria-labelledby="remarkDropdown">
                          @forelse ($studentRemarks as $studentRemarks)
                          @csrf
                          <a class="dropdown-item remark-item filter-status" data-value="{{ $studentRemarks->remark }}" href="#">
                            {{ $studentRemarks->remark }}
                          </a>
                          @empty
                          <button class="dropdown-item" data-value="NONE" type="button">
                            NONE
                          </button>
                          @endforelse
                        </div>
                        <input type="hidden" name="status" id="selectedStatus">
                      </form>
                    </div>

                    <div class="dropdown d-inline-block">
                      <div class="input-group date" id="datepicker">
                        <input type="datetime-local" class="form-control border border-primary" placeholder="Date" id="selectedDate">
                        <div class="input-group-append">
                          <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                            <i class="mdi mdi-calendar "></i>
                          </div>
                        </div>
                      </div>
                    </div>

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
                <!-- Table -->
                <div class="card card-default shadow">
                  <div class="card-header">
                    <h1>My Class Attendance</h1>
                  </div>
                  <div class="card-body ">


                    <table id="AttendanceTable" class="table table-bordered table-hover" style="width:100%">
                      <thead class="table-dark">
                        <tr>
                          <th>Date</th>
                          <th>Time</th>
                          <th>Student Name</th>
                          <th>Student ID</th>
                          <th>Course</th>
                          <th>Year & Section</th>
                          <th>Remark</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($studAttendances as $studAttendance)
                        @csrf
                        <tr>
                          <td>{{ date('F j, Y', strtotime($studAttendance->date)) }}</td>
                          <td>{{ date('h:i A', strtotime($studAttendance->time)) }}</td>
                          <td>{{$studAttendance->firstName}} {{$studAttendance->lastName}}</td>
                          <td>{{$studAttendance->idNumber}}</td>
                          <td>{{$studAttendance->program}}</td>
                          <td>{{$studAttendance->year}}-{{$studAttendance->section}} </td>
                          <td>
                            @if($studAttendance->remark == 'Present')
                            <span class="badge badge-success">Present</span>
                            @elseif($studAttendance->remark == 'Absent')
                            <span class="badge badge-danger">Absent</span>
                            @elseif($studAttendance->remark == 'Late')
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
                                <button class="dropdown-item btn-sm editAttendanceBtn" type="button" data-toggle="modal" data-target="#studentUpdateAttendanceModal" value="{{$studAttendance->attendanceID}}">
                                  <i class="mdi mdi-circle-edit-outline text-warning"></i>
                                  Edit</button>
                                <button class="dropdown-item btn-sm deleteAttendanceBtn" type="button" data-toggle="modal" data-target="#studentDeleteAttendanceModal" value="{{$studAttendance->attendanceID}}">
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


              <div class="tab-pane fade" id="pills-classList" role="tabpanel" aria-labelledby="pills-classList-tab">
                <div class="row">
                  <div class="col-xl-9 col-md-9">
                    <!-- Sort button -->
                    <div class="dropdown d-inline-block mb-3">
                      <form method="GET" action="">
                        <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentStatusDropdown" data-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-alpha-s-box"></i>
                          Status
                        </button>
                        <div class="dropdown-menu scrollable-dropdown" aria-labelledby="studentStatusDropdown">
                          @forelse($studentStatus as $studentStatus)
                          @csrf
                          <a class="dropdown-item status-item filter-student-status" data-value="{{ $studentStatus->status }}" href="#">
                            {{ $studentStatus->status }}
                          </a>
                          @empty
                          <button class="dropdown-item" data-value="NONE" type="button">
                            NONE
                          </button>
                          @endforelse
                        </div>
                        <input type="hidden" name="studentStatus" id="selectedStudentStatus">
                      </form>
                    </div>
                  </div>

                  <div class="col-xl-3 col-md-3 d-flex justify-content-end">
                    <!-- Sort button -->
                    <div class="dropdown d-inline-block mb-3 ">
                      <button class="btn btn-warning btn-sm fw-bold" id="resetButton" type="button">
                        <i class="mdi mdi-alpha-r-box"></i>
                        RESET
                      </button>
                    </div>
                  </div>
                </div>


                <!-- Class List Table -->
                <div class="card card-default shadow">
                  <div class="card-header">
                    <h1>My Class List</h1>
                  </div>
                  <div class="card-body ">
                    <table id="studentListTable" class="table table-bordered table-hover no-wrap" style="width:100%">
                      <thead class="table-dark">
                        <tr>
                          <th>Student Name</th>
                          <th>Student ID</th>
                          <th>Program</th>
                          <th>Year & Section</th>
                          <th>Status</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($students as $student)
                        @csrf
                        <tr>
                          <td>{{$student->firstName}} {{$student->lastName}}</td>
                          <td>{{$student->idNumber}}</td>
                          <td>{{$student->program}}</td>
                          <td>{{$student->year}}-{{$student->section}}</td>
                          <td>
                            @if($student->status == 'Regular')
                            <span class="badge badge-success">Regular</span>
                            @elseif($student->status == 'Irregular')
                            <span class="badge badge-warning">Irregular</span>
                            @elseif($student->status == 'Drop')
                            <span class="badge badge-danger">Drop</span>
                            @endif
                          </td>
                          <th>
                            <div class="dropdown d-inline-block">
                              <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                Actions
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item btn-sm editListBtn" type="button" data-toggle="modal" data-target="#studentUpdateListModal" value="{{$student->MIT_ID}}">
                                  <i class="mdi mdi-circle-edit-outline text-warning"></i>
                                  Edit </button>
                                <button class="dropdown-item btn-sm deleteListBtn" type="button" data-toggle="modal" data-target="#studentDeleteListModal" value="{{$student->MIT_ID}}">
                                  <i class="mdi mdi-trash-can text-danger"></i>
                                  Delete </button>
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

            <!-- Delete Attendance Modal -->
            <div class="modal fade" id="studentDeleteAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="deleteAttendance" aria-hidden="true">
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
          <div class="modal fade" id="studentUpdateAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="studentAttendance" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="studentAttendance">Edit Student Attendance</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">

                  <ul id="attendanceError"></ul>

                  <form method="post">
                    @csrf
                    @method('put')
                    <input type="hidden" id="attendanceID" class="id form-control ">

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
                          <select class="updateRemark form-select form-control border border-dark" aria-label="Default select example" id="edit_remark" name="update_remark">
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
        </div>


        <!-- Update List Modal -->
        <div class="modal fade" id="studentUpdateListModal" tabindex="-1" role="dialog" aria-labelledby="studentList" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="studentList">Edit Student Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">

                <ul id="ListError"></ul>

                <form method="post">
                  @csrf
                  @method('put')
                  <input type="hidden" id="listID" class="listID form-control ">

                  <div class="row">
                    <div class="col-lg-6">
                      <ul id="editListIDError"></ul>
                      <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" class="updateListUserID form-control border border-dark border border-dark" id="edit_studentListID" name="update_studentListID" disabled>
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <ul id="editStatusError"></ul>
                      <div class="form-group">
                        <label>Status</label>
                        <select class="updateStatus form-select form-control border border-dark" aria-label="Default select example" id="edit_Status" name="update_Status">
                          <option selected hidden></option>
                          <option value="Regular">Regular</option>
                          <option value="Irregular">Irregular</option>
                          <option value="Drop">Drop</option>
                        </select>
                      </div>
                    </div>

                    <!-- Modal Boday End-->

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger btn-pill" id="updateClose" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btn-pill updateList">Update</button>
                    </div>

                </form>

              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delete Attendance Modal -->
      <div class="modal fade" id="studentDeleteListModal" tabindex="-1" role="dialog" aria-labelledby="deleteStudentList" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteStudentList" style="text-align:center;">Delete Student Attendance</h5>
              <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="post">
                @csrf
                @method('delete')
                <input type="hidden" id="deleteListID" class="id form-control ">
                <div class="row">
                  <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
                </div>
                <div class="row">
                  <h4 style="text-align:center;"> Are you sure you want to delete this Student in your Class List?</h4>
                </div>
            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-primary btn-pill" id="close" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger btn-pill deleteStudentRecordList">Delete</button>
            </div>

            </form>

          </div>
        </div>
      </div>
    </div>

    <script>
      const remarkDropdown = `<i class="mdi mdi-alpha-r-box"></i> Remark`;
      const studentStatusDropdown = `<i class="mdi mdi-alpha-s-box"></i> Status`;
      document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.remark-item').forEach(function(item) {
          item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('remarkDropdown').innerHTML = `<i class="mdi mdi-alpha-r-box"></i> ${this.textContent}`;
          });
        });
        document.querySelectorAll('.status-item').forEach(function(item) {
          item.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('studentStatusDropdown').innerHTML = `<i class="mdi mdi-alpha-s-box"></i> ${this.textContent}`;
          });
        });
      });

      $("#resetBtn").on("click", function(e) {
        e.preventDefault();
        document.getElementById("remarkDropdown").innerHTML = remarkDropdown;
      });

      $("#resetButton").on("click", function(e) {
        e.preventDefault();
        document.getElementById("studentStatusDropdown").innerHTML = studentStatusDropdown;
      });
    </script>

    @include('footer')