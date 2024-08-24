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
                        <h1>Schedule</h1>
                        <div class="row">

                            <div class="col-xl-12 col-md-12 d-flex justify-content-end">
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
                                </div>

                                <div class="dropdown d-inline-block mb-3">
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