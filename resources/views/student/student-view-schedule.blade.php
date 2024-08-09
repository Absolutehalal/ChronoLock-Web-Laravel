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
                        <h1 class="mb-3">Overview Schedule</h1>
                        <div class="row">
                            <div class="col-xl-12 col-md-12 d-flex justify-content-end">
                                <form method="GET" action="">
                                    <div class=" d-inline-block mb-3">
                                        <input class="form-control border-primary" type="text" name="search" id="search" placeholder="Search Here">
                                    </div>

                                    <div class="dropdown d-inline-block mb-3">
                                        <button class="btn btn-danger btn-sm fw-bold" type="submit">
                                            <i class="mdi mdi-feature-search"></i> Search
                                        </button>
                                    </div>

                                    <div class="dropdown d-inline-block mb-3">
                                        <button class="btn btn-warning btn-sm fw-bold" type="submit">
                                            <i class="mdi mdi-alpha-r-box"></i> Reset
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    @if($schedules->isEmpty())
                    @if(isset($search) && !empty($search))
                    <div class="card-body">
                        <p class="text-center fw-bold">Not Found. Please try again.</p>
                    </div>
                    @else
                    <div class="card-body">
                        <span class="text-center fw-bold">No schedules available.</span>
                    </div>
                    @endif
                    @else
                    @foreach($schedules as $schedule)
                    <div class="col-lg-6 col-xl-4 col-xxl-3">
                        <div class="card card-default shadow-md border-dark mt-7">
                            <div class="card-body text-center">
                                <button class="editClassSchedule mb-2" href="javascript:void(0)" data-target="#join-class-schedule-modal" value="{{$schedule->classID}}">
                                    <div class="image mb-3 d-inline-flex mt-n8">
                                        <img src="{{$schedule->avatar }}" class="img-fluid rounded-circle d-inline-block" alt="Avatar Image">
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
                                    $link= mysqli_connect("sql12.freesqldatabase.com","sql12724238","f8cI7wVnB5");
                                    mysqli_select_db($link, "sql12724238");
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
                    @endforeach
                    @endif

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
                                                    <img class="rounded-circle" id="instructorAvatar" alt="Instructor Profile">
                                                </div>

                                                <div class="card-body">
                                                    <h4> <input type="text" class="input form-control" id="instFirstNameAndLastName" name="instFirstNameAndLastName" readonly> </h4>
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
                                                        <input type="text" class="input form-control" id="program" name="program" readonly>
                                                    </div>
                                                </div>

                                                <div class="text-center pb-4">

                                                    <h6 class="pb-2">Year & Section</h6>
                                                    <div class="form-group">
                                                        <input type="text" class="input form-control" id="yearAndSection" name="yearAndSection" readonly>
                                                    </div>
                                                </div>

                                                <div class="text-center pb-4">
                                                    <ul id="sectionError"></ul>
                                                    <h6 class="pb-2">Schedule</h6>
                                                    <div class="form-group">
                                                        <input type="text" class="input form-control" id="startTimeAndEndTime" name="startTimeAndEndTime" readonly>
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