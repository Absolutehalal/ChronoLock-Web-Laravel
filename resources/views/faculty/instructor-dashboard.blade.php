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
    <title>ChronoLock Instructor Dashboard</title>
    @include('head')
    <!-- TOASTER -->
    <link href="{{asset('plugins/toaster/toastr.min.css')}}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{asset('https://code.jquery.com/jquery-3.7.1.min.js')}}"></script>
    <!-- Toastr JS -->
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js')}}"></script>
    <!-- Custom JS -->
    <script src="{{asset('js/toastr.js')}}"></script>

    <script src="{{asset('js/pieChart.js')}}"></script>

    <script src="{{asset('js/horizontalBarChart.js')}}"></script>
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
                        </ol>
                    </nav>
                    <!-- Live Date and Time -->
                    <div>
                        <p class=" text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>


                <div class="row">
                    <!-- <div class="col-xl-4"> -->
                    <div class="col-xl-4">
                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-info rounded-circle mr-3">
                                    <i class="mdi mdi-notebook"></i>
                                </div>
                                <div class="text-left">
                                    @if($classCount == 1 || $classCount == 0)
                                    <p>Section Handled</p>
                                    @else($classCount > 1)
                                    <p>Section's Handled</p>
                                    @endif

                                    @if($classCount > 0)
                                    <span class="h2 d-block">{{ $classCount }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>None</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-success rounded-circle mr-3">
                                    <i class="mdi mdi-account-check"></i>
                                </div>
                                <div class="text-left">
                                    @if($studentCount == 1 || $studentCount == 0)
                                    <p>Student Handled</p>
                                    @else($studentCount > 1)
                                    <p>Student's Handled</p>
                                    @endif

                                    @if($studentCount > 0)
                                    <span class="h2 d-block">{{ $studentCount }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>None</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card card-default shadow">
                            <div class="d-flex p-5">
                                <div class="icon-md bg-warning rounded-circle mr-3">
                                    <i class="mdi mdi-calendar-today"></i>
                                </div>
                                <div class="text-left">
                                    @if($todaySchedulesCount == 1 || $todaySchedulesCount == 0)
                                    <p>Today's Schedule</p>
                                    @else($todaySchedulesCount > 1)
                                    <p>Today's Schedules</p>
                                    @endif

                                    @if($todaySchedulesCount > 0)
                                    <span class="h2 d-block">{{ $todaySchedulesCount }}</span>
                                    @else
                                    <span class="h2 d-block" style='color: #cc0000'>None</span>
                                    @endif
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="col-xl-4 col-md-6" style="height: 50%;">
                        <!-- Pie Chart  -->
                        <div class="card card-default shadow">
                            <div class="card-header">
                                <h2>Students Status Chart</h2>
                                <!-- Example single primary button -->
                                <!-- <div class="dropdown d-inline-block">
                                    <button class="btn btn-primary dropdown-toggle justify-content-end" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                        Select Section
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <button class="dropdown-item" data-toggle="modal" data-target="#exampleModalForm">
                                            <i class="mdi mdi-check"></i>
                                            BSIS 1A
                                        </button>
                                    </div>
                                </div> -->
                            </div>
                            <div class="card-body">
                                <div id="simple-pie-chart" class=""></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <!-- Horizontal Bar Chart  -->
                        <div class="card card-default shadow">
                            <div class="card-header">
                                <h2>Number of Students Per Section</h2>
                            </div>
                            <div class="card-body">
                                <div id="horizontal-bar-chart2" class=""></div>
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
                                                <img src="{{ $schedule->avatar }}" alt="Instructor Image" style="width: auto;" class="rounded">
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
                                <h2>Latest Student Enrollees</h2>
                            </div>
                            <div class="card-body">
                                <table id="exampleTable" class="table table-bordered table-hover no-wrap" style="width: 100%;">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Course</th>
                                            <!-- <th>Program</th> -->
                                            <th>Year & Section</th>
                                            <th>Enrollment Date</th>
                                            <!-- <th>Enrollment Time</th> -->
                                            <!-- Add other headers as needed -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 1; @endphp
                                        @foreach($latestStudents as $enrolee)
                                        <tr>
                                            <td>{{$counter}}</td>
                                            <td>{{ $enrolee->firstName }} {{ $enrolee->lastName }}</td>
                                            <td>{{ $enrolee->courseName }} - {{ $enrolee->courseCode }}</td>
                                           
                                            <td>{{ $enrolee->program }} | {{ $enrolee->year }}-{{ $enrolee->section }}</td>
                                            <td>{{ date('F j, Y', strtotime($enrolee->created_at)) }}</td>
                                            <!-- <td>{{ date('h:i A', strtotime($enrolee->created_at)) }}</td> -->
                                        </tr>
                                        @php $counter++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
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

    @include(' footer')