<!DOCTYPE html>


<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <script src="{{asset('js/instructorCalendar.js')}}"></script>
    <title>ChronoLock Instructor-Schedule</title>

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
                            <li class="breadcrumb-item active"><a href="{{ route('instructorScheduleManagement') }}">My Schedule</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>




                <div class="card card-default shadow">
                    <div class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
                        <h1>ERP Schedule</h1>
                    </div>

                    <div class="card-body">
                        <div class="full-calendar mb-5">
                            <div id="instructorCalendar"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

    </div>
    </div>

    <script src="{{asset('plugins/fullcalendar/core-4.3.1/main.min.js')}}"></script>
    <script src="{{asset('plugins/fullcalendar/daygrid-4.3.0/main.min.js')}}"></script>
    @include('footer')