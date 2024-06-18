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

  <title>ChronoLock Admin-Instructor Attendance</title>

  @include('head')

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

  <script>
    $('.input-group.date').datepicker({
      todayHighlight: true
    });
  </script>
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>
  @include('adminSideNav')
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
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="admin-instattendance.php">Attendance</a></li>
              <li class="breadcrumb-item active"><a href="admin-instattendance.php">Instructor Attendance</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>
        <!-- DROPRDOWN NAV -->

        <div class="row">
          <div class="col-xl-9 col-md-9">
            <!-- Example single primary button -->

            <div class="dropdown d-inline-block mb-3 ">
              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                <i class="mdi mdi-timer"></i>
                Time
              </button>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('instructorAttendanceManagement') }}">
                <button class="btn btn-primary dropdown-toggle" type="button" id="instNameDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-i-box"></i>
                  Instructor Name
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instNameDropdown">
                  @foreach($instructor_name as $instructor_names)
                  @csrf
                  <a class="dropdown-item filter-inst-name" data-value="{{ $instructor_names->instructor_name }}" href="#">
                    {{ $instructor_names->instructor_name }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="instructorName" id="selectedInstName">
              </form>
            </div>

            <div class="dropdown d-inline-block mb-3">
              <form method="GET" action="{{ route('instructorAttendanceManagement') }}">
                <button class="btn btn-primary dropdown-toggle" type="button" id="instStatusDropdown" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-alpha-s-box"></i>
                  Status
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="instStatusDropdown">
                  @foreach($status as $instructor_status)
                  @csrf
                  <a class="dropdown-item filter-inst-status" data-value="{{ $instructor_status->status }}" href="#">
                    {{ $instructor_status->status }}
                  </a>
                  @endforeach
                </div>
                <input type="hidden" name="instructorStatus" id="selectedInstStatus">
              </form>
            </div>

            <div class="d-inline-block mb-3">
              <div class="input-group date" id="datepicker">
                <input type="text" class="form-control">
                <span class="input-group-append">
                  <span class="input-group-text bg-white">
                    <i class="fa fa-calendar"></i>
                  </span>
                </span>
              </div>

            </div>
          </div>


          <div class="col-xl-3 col-md-3 d-flex justify-content-end">
            <!-- Reset button -->
            <div class="d-inline-block mb-3 ">
              <button class="btn btn-warning fw-bold" id="resetBtn" type="button">
                <i class="mdi mdi-alpha-r-box"></i>
                Reset
              </button>
            </div>
          </div>

        </div>
        <!-- END -->


        <div class="card card-default">
          <div class="card-header">
            <h1>Instructor Realtime Attendance</h1>
          </div>
          <div class="card-body ">
            <table id="example" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Instructor Name</th>
                  <th>Instructor ID</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($inst_attendance as $record)
                @csrf
                <tr>
                  <td>{{ $record->date }}</td>
                  <td>{{ $record->time }}</td>
                  <td>{{ $record->instructor_name }}</td>
                  <td>{{ $record->instructor_id }}</td>
                  <td class="fw-bold">{{ $record->status }}</td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item">
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>
                          Edit</button>
                        <button class="dropdown-item">
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
    </div>
  </div>




  @include('footer')