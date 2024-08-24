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

    <!-- Ajax edit Class List -->
    <script defer src="{{asset('js/adminEditAppointedSchedule.js')}}"></script>

    <title>ChronoLock Instructor-Class Record</title>

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
                            <li class="breadcrumb-item active"><a href="{{ route('appointedSchedules') }}">Appointed Schedule</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-2" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xl-8">
                        <div class="card card-default shadow">
                            <div class="card-header">
                                <h1> Regular Schedule</h1>
                            </div>
                            <div class="card-body ">
                                <table id="exampleTable2" class="table table-bordered table-hover" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Prog,Yr & Sec</th>
                                            <th>Course</th>
                                            <th>Status</th>
                                            <th>Time</th>
                                            <th>Day</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regularClasses as $regularClasses)
                                        <tr>
                                            <td>{{ $regularClasses->program }} {{ $regularClasses->year }}-{{ $regularClasses->section }}</td>
                                            <td>{{ $regularClasses->courseCode }}-{{ $regularClasses->courseName }}</td>
                                            <td>{{ $regularClasses->scheduleStatus }}</td>
                                            <td>{{ date('h:i A', strtotime($regularClasses->startTime)) }}-{{ date('h:i A', strtotime($regularClasses->endTime)) }}</td>


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
                                              $dayName = $dayMapping[$regularClasses->day];
                                            @endphp


                                           
                                            <td>{{$dayName}}</td>
                                            <th>
                                                <!-- Example single primary button -->
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                        @if ($regularClasses->scheduleStatus == "With Class")
                                                            <button class="dropdown-item btn-sm withoutClassBtn" type="button" data-toggle="modal" data-target="#withoutClassModal" value="{{$regularClasses->classID}}">
                                                            <i class="mdi mdi-close text-danger"></i>
                                                            Without Class</button>
                                                        @elseif ($regularClasses->scheduleStatus == "Without Class")
                                                            <button class="dropdown-item btn-sm withClassBtn" type="button" data-toggle="modal" data-target="#withClassModal" value="{{$regularClasses->classID}}">
                                                            <i class="mdi mdi-check text-info"></i>
                                                            With Class
                                                            </button>
                                                        @endif
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

                    <div class="col-xl-4">
                        <div class="card card-default shadow">
                            <div class="card-header">
                                <h1> Make Up Schedule</h1>
                            </div>
                            <div class="card-body ">
                                <table id="exampleTable" class="table table-bordered table-hover" style="width:100%">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Time</th>
                                            <th>Day</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($makeUpClasses as $makeUpClasses)
                                        <tr>
                                         
                                            <td>{{ date('h:i A', strtotime($makeUpClasses->startTime)) }}-{{ date('h:i A', strtotime($makeUpClasses->endTime)) }}</td>
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
                                            $dayName = $dayMapping[$makeUpClasses->day];

                                            @endphp

                                            <td>{{$dayName}}</td>
                                            <td>{{ $makeUpClasses->scheduleStatus }}</td>
                                            <th>
                                                <!-- Example single primary button -->
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                        Actions
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                        @if ($makeUpClasses->scheduleStatus == "With Class")
                                                            <button class="dropdown-item btn-sm withoutClassBtn" type="button" data-toggle="modal" data-target="#withoutClassModal" value="{{$makeUpClasses->classID}}">
                                                            <i class="mdi mdi-close text-danger"></i>
                                                            Without Class</button>
                                                        @elseif ($makeUpClasses->scheduleStatus == "Without Class")
                                                            <button class="dropdown-item btn-sm withClassBtn" type="button" data-toggle="modal" data-target="#withClassModal" value="{{$makeUpClasses->classID}}">
                                                            <i class="mdi mdi-check text-info"></i>
                                                            With Class
                                                            </button>
                                                        @endif
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
            </div>


                </div>
            </div>
            </div>
            </div>
   
<!-- Update to Without Class Modal -->
    <div class="modal fade" id="withoutClassModal" tabindex="-1" role="dialog" aria-labelledby="withoutClass" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="withoutClass" style="text-align:center;">Class Schedule Status</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          @csrf
          @method('put')
            <input type="hidden" id="noClasses_ID" class="id form-control ">
            <div class="row">
              <i class="mdi mdi-close text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to set the class to no Classes?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-danger " id="close" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info noClasses"></i>Sure</button>
        </div>

        </form>

      </div>
    </div>
  </div>
  </div>


<!-- Update to With Class Modal -->
  <div class="modal fade" id="withClassModal" tabindex="-1" role="dialog" aria-labelledby="withClass" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="withClass" style="text-align:center;">Class Schedule Status</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
          @csrf
          @method('put')
            <input type="hidden" id="withClasses_ID" class="id form-control ">
            <div class="row">
              <i class="mdi mdi-check text-info" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to set the class to with Classes?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-danger " id="close" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-info withClasses"></i>Sure</button>
        </div>

        </form>

      </div>
    </div>
  </div>
  </div>

    @include('footer')