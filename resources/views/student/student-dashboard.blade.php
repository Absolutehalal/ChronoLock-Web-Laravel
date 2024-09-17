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
    <title>ChronoLock Student Dashboard</title>
    @include('head')
    <!-- TOASTER -->
    <link href="{{asset('plugins/toaster/toastr.min.css')}}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{asset('https://code.jquery.com/jquery-3.7.1.min.js')}}"></script>
    <!-- Toastr JS -->
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js')}}"></script>
    <!-- Custom JS -->
    <script src="{{asset('js/toastr.js')}}"></script>
    <!-- 
    <script src="{{asset('js/pieChart.js')}}"></script> -->

</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>
    @include('sweetalert::alert')
    <div id="toast"></div>
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
                            <li class="breadcrumb-item">
                                <a href="{{ route('studentIndex') }}">Dashboard</a>
                            </li>
                        </ol>
                    </nav>
                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>
                <div class="row">
                    <!-- Frist box -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-info rounded-circle mr-3">
                                    <i class="mdi mdi-notebook"></i>
                                </div>
                                <div class="text-left">
                                    <p>Enrolled Course</p>
                                    @if($enrolledStudent > 0)
                                    <span class="h2 d-block">{{ $enrolledStudent }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Second box -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-success rounded-circle mr-3">
                                    <i class="mdi mdi-alpha-p-box"></i>
                                </div>
                                <div class="text-left">
                                    <p>Presents</p>
                                    @if($attendanceCounts->present_count > 0)
                                    <span class="h2 d-block">{{ $attendanceCounts->present_count }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Third box -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-danger rounded-circle mr-3">
                                    <i class="mdi mdi-alpha-a-box"></i>
                                </div>
                                <div class="text-left">
                                    <p>Absents</p>
                                    @if($attendanceCounts->absent_count > 0)
                                    <span class="h2 d-block">{{ $attendanceCounts->absent_count }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fourth box -->
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-warning rounded-circle mr-3">
                                    <i class="mdi mdi-alpha-l-box"></i>
                                </div>
                                <div class="text-left">
                                    <p>Lates</p>
                                    @if($attendanceCounts->late_count > 0)
                                    <span class="h2 d-block">{{ $attendanceCounts->late_count }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>0</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- START TODAY'S SCHEDULE -->
                    <div class="col-xl-6">
                        <div class="card card-default shadow">
                            <div class="card-header">
                                <h2>Today's Class Schedule</h2>
                            </div>
                            <div class="card-body">
                                <ul class="list-group" style="max-height: 330px; overflow-y: auto;">
                                    @php $counter = 1; @endphp
                                    @forelse($todaySchedules as $schedule)
                                    <li class="list-group-item list-group-item-action">
                                        <div class="media media-sm mb-1">
                                            <div class="media-left d-flex align-items-center mt-1">
                                                <span class="mr-2 fw-bold">{{ $counter }}.</span>
                                                <img src="{{ $schedule->avatar  ?? asset('images/User Icon.png') }}" alt="Instructor Image" width="90" height="90" class="rounded">
                                            </div>

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

                                            <div class="media-body ml-4">
                                                <span class="title">Instructor: {{ $schedule->instFirstName }} {{ $schedule->instLastName }}</span>
                                                <p class="text-dark">Course Name: {{ $schedule->courseName }} </p>
                                                <p class="text-dark">Course Code: {{ $schedule->courseCode }} </p>
                                                <p class="text-dark">Time: {{ date('h:i A', strtotime($schedule->startTime)) }} -
                                                    {{ date('h:i A', strtotime($schedule->endTime)) }}
                                                </p>
                                                <p class="text-dark">Date: {{ $currentDate }} | {{ $dayName }} </p>
                                            </div>
                                        </div>
                                    </li>
                                    @php $counter++; @endphp
                                    @empty
                                    <li class="list-group-item fw-bold">No schedules for today.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <!-- END TODAY'S SCHEDULE -->
                    </div>
                    <div class="col-xl-6">
                        <div class="card card-default shadow">
                            <div class="card-header">
                                <h2>List of Enrolled Course</h2>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h5 class="ml-2">Course & Time</h5>
                                    <h5 class="mr-6">Status</h5>
                                </div>



                                @php $counter = 1; @endphp
                                @forelse($listEnrolledCourse as $course)

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
                                $dayName = $dayMapping[$course->day];
                                @endphp

                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                                        {{ $counter }}. {{ $course->courseName }} - {{ $course->courseCode }} | {{ date('h:i A', strtotime($course->startTime)) }} -
                                        {{ date('h:i A', strtotime($course->endTime)) }} | {{ $dayName }}
                                        <span>
                                            @if($course->status == 'Regular')
                                            <span class="badge badge-success">Regular</span>
                                            @elseif($course->status == 'Irregular')
                                            <span class="badge badge-warning">Irregular</span>
                                            @elseif($course->status == 'Drop')
                                            <span class="badge badge-danger">Drop</span>
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                                @php $counter++; @endphp
                                @empty
                                <li class="list-group-item fw-bold">No enrolled course yet.</li>
                                @endforelse
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
    @include('footer')