<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script defer src="js/studentJoinClassSchedule.js"></script>

    <title>ChronoLock: Student Enroll Schedule</title>

    @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>
    @include('sweetalert::alert')
    @include('student.studentSideNav')
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
                            <li class="breadcrumb-item"><a href="{{ route('studentIndex') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('studentViewSchedule') }}">Enroll Schedule</a></li>
                        </ol>
                    </nav>
                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>
                <div class="card card-default shadow-sm">
                    <div class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
                        <h1 class="mb-4">Overview Schedule</h1>
                        <div class="d-flex align-items-center mb-3">
                            <form id="search" method="GET" action="{{ url('/student-view-schedule')}}" class="d-flex align-items-center me-1">
                                <div class="input-group">
                                    <input class="form-control border-primary bg-light" type="text" name="search" id="search" placeholder="Search Here">
                                    <button class="btn btn-danger btn-sm fw-bold" type="submit">
                                        <i class="mdi mdi-feature-search"></i> Search
                                    </button>
                                </div>
                            </form>
                            <form id="reset" action="{{ url('/student-view-schedule')}}" method="GET" class="d-flex align-items-center">
                                <button class="btn btn-warning btn-sm fw-bold" type="submit">
                                    <i class="mdi mdi-alpha-r-box"></i> Reset
                                </button>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="row">
                    @forelse($schedules as $schedule)
                    <div class="col-lg-6 col-xl-4 col-xxl-3" style="width: 200%;">
                        <div class="card card-default shadow-md border-dark mt-7">
                            <div class="card-body text-center">
                                <button class="editClassSchedule mb-2" href="javascript:void(0)" data-target="#join-class-schedule-modal" value="{{$schedule->classID}}">
                                    <div class="image mb-3 d-inline-flex mt-n8">
                                        <img src="{{$schedule->avatar ?? asset('images/User Icon.png') }}" width="100" height="100" class="img-fluid rounded-circle d-inline-block" alt="Avatar Image">
                                    </div>
                                    <h5 class="card-title">{{$schedule->instFirstName }} {{$schedule->instLastName }}</h5>
                                    <ul class="list-unstyled d-inline-block">
                                        <li class="d-flex mb-1">
                                            <i class="mdi mdi-alpha-c-box mr-1"></i>
                                            <label class="mr-1">Course:</label>
                                            <span>{{$schedule->courseName}}-{{$schedule->courseCode}}</span>
                                        </li>
                                        <li class="d-flex">
                                            <i class="mdi mdi-group mr-1"></i>
                                            <label class="mr-1">Program:</label>
                                            <span>{{$schedule->program}}</span>
                                        </li>
                                        <li class="d-flex">
                                            <i class="mdi mdi-alpha-s-box mr-1"></i>
                                            <label class="mr-1">Year & Section:</label>
                                            <span>{{$schedule->year}}-{{$schedule->section}}</span>
                                        </li>
                                        <li class="d-flex">
                                            <i class="mdi mdi-calendar-clock mr-1"></i>
                                            <label class="mr-1">Schedule:</label>
                                            <span>{{ date('h:i A', strtotime($schedule->startTime)) }}-{{ date('h:i A', strtotime($schedule->endTime)) }}</span>
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
                                        $dayName = $dayMapping[$schedule->day];
                                        @endphp
                                        <li class="d-flex">
                                            <i class="mdi mdi-calendar-today mr-1"></i>
                                            <label class="mr-1">Day:</label>
                                            <span>{{ $dayName }}</span>
                                        </li>
                                    </ul>
                                    @php
                                    $classID=$schedule->classID;
                                    $link= mysqli_connect("sql12.freesqldatabase.com","sql12728459","5bPPipjGJy");
                                    mysqli_select_db($link, "sql12728459");
                                    $query = "SELECT * FROM student_masterlists WHERE userID ='$userID' AND classID ='$classID'";
                                    $result = mysqli_query($link,$query);
                                    @endphp
                                    @if (mysqli_num_rows($result) === 1 )
                                    <div class="overlay" style="color: #31ce3c">Enrolled</div>
                                    @else
                                    <div class="overlay" style="color: #FF7F7F">Get Access</div>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    @empty
                    <div class="card-body justify-content-center">
                        <span class="text-center fw-bold">No schedules available.</span>
                    </div>
                    @endforelse

                </div>

                <!-- Join Class Modal -->
                <div class="modal fade" id="join-class-schedule-modal" tabindex="-1" role="dialog" aria-labelledby="joinClass" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header justify-content-end border-bottom-0">
                                <button type="button" class="btn-close-icon" onclick="$('#join-class-schedule-modal').modal('hide');" aria-label="Close">
                                    <i class="mdi mdi-close"></i>
                                </button>
                            </div>
                            <div class="modal-body pt-0">
                                <div class="row no-gutters">
                                    <div class="col-md-12">
                                        <div class="profile-content-left px-4">
                                            <div class="card text-center px-0 border-0">
                                                <div class="card-img mx-auto">
                                                    <img class="rounded-circle" id="instructorAvatar" alt="Instructor Profile" width="150" height="150" >
                                                </div>
                                                <div class="card-body">
                                                    <h4> <input type="text" class="input form-control" id="instFirstNameAndLastName" name="instFirstNameAndLastName" disable> </h4>
                                                    <form id="clearJoinClass" method="post">
                                                        @csrf
                                                        @method('post')
                                                        <ul id="classIDError"></ul>
                                                        <input type="hidden" id="classID" name="classID" class="id form-control ">
                                                        <ul id="enrollmentKeyError"></ul>
                                                        <input type="text" class="form-control border border-dark border border-dark" placeholder="Enter Enrollment Key" id="enrollmentKey" name="enrollmentKey">
                                                        <button type="submit" class="btn btn-primary btn-pill btn-lg my-4 createMasterList">Enroll</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <div class="text-center pb-4">
                                                    <h6 class="pb-2">Program</h6>
                                                    <div class="form-group">
                                                        <input type="text" class="input form-control" id="program" name="program" disable>
                                                    </div>
                                                </div>
                                                <div class="text-center pb-4">
                                                    <h6 class="pb-2">Year & Section</h6>
                                                    <div class="form-group">
                                                        <input type="text" class="input form-control" id="yearAndSection" name="yearAndSection" disable>
                                                    </div>
                                                </div>
                                                <div class="text-center pb-4">
                                                    <ul id="sectionError"></ul>
                                                    <h6 class="pb-2">Schedule</h6>
                                                    <div class="form-group">
                                                        <input type="text" class="input form-control" id="startTimeAndEndTime" name="startTimeAndEndTime" disable>
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
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#join-class-schedule-modal').on('hidden.bs.modal', function() {
                $('#clearJoinClass')[0].reset();
                clearJoinClassErrors();
            });

            function clearJoinClassErrors() {
                $('#enrollmentKeyError').empty();
            }
        });
    </script>
    @include('footer')