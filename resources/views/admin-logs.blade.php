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

  <title>ChronoLock Admin-RFID Account</title>

  @include('head')
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
              <li class="breadcrumb-item active"><a href="admin-logs.php">Logs</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <nav>
    <div class="nav nav-tabs justify-content-center" id="nav-tab" role="tablist">
    <button class="nav-link active" id="nav-admin-tab" data-bs-toggle="tab" data-bs-target="#nav-admin" type="button" role="tab" aria-controls="nav-admin" aria-selected="true">Admin</button>
    <button class="nav-link" id="nav-labInCharge-tab" data-bs-toggle="tab" data-bs-target="#nav-labInCharge" type="button" role="tab" aria-controls="nav-labInCharge" aria-selected="false">Lab-in-Charge</button>
    <button class="nav-link" id="nav-technician-tab" data-bs-toggle="tab" data-bs-target="#nav-technician" type="button" role="tab" aria-controls="nav-technician" aria-selected="false">Technician</button>
    <button class="nav-link" id="nav-faculty-tab" data-bs-toggle="tab" data-bs-target="#nav-faculty" type="button" role="tab" aria-controls="nav-faculty" aria-selected="false">Faculty</button>
    <button class="nav-link" id="nav-student-tab" data-bs-toggle="tab" data-bs-target="#nav-student" type="button" role="tab" aria-controls="nav-student" aria-selected="false">Students</button>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-admin" role="tabpanel" aria-labelledby="nav-admin-tab">

        <div class="card card-default">
          <div class="card-header">
            <h1>Admin Logs</h1>
          </div>
          <div class="card-body">

            <div class="row">
              <div class="col-xl-9 col-md-9">

                <div class="dropdown d-inline-block mb-3">
                  <form method="GET" action="">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="adminIDDropdown" data-toggle="dropdown" aria-expanded="false">
                      <i class="mdi mdi-alpha-i-box"></i>
                      User ID
                    </button>
                    <div class="dropdown-menu scrollable-dropdown" aria-labelledby="adminIDDropdown">
                    @foreach($adminIDS as $adminID)
                    @csrf
                      <a class="dropdown-item filter-admin-id" data-value="{{ $adminID->userID }}" href="#">
                      {{ $adminID->userID }}-{{ $adminID->firstName }} {{ $adminID->lastName }}
                      </a>
                      @endforeach
                    </div>
                    <input type="hidden" name="adminID" id="selectedAdminID">
                  </form>
                </div>

              </div>
            </div>

            <table id="adminTable" class="table table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>User ID</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($adminLogs as $adminLog)
                <tr>
                  <td> {{$adminLog->idLogs}} </td>
                  <td> {{$adminLog->userID}} </td>
                  <td> {{$adminLog->action}} </td>
                  <td> {{$adminLog->formatted_date}} </td>
                  <td> {{$adminLog->formatted_time}} </td>
                  
                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
        </div>
      </div>



<div class="tab-pane fade" id="nav-labInCharge" role="tabpanel" aria-labelledby="nav-labInCharge-tab">
        
    <div class="card card-default">
          <div class="card-header">
            <h1>Lab-in-Charge Logs</h1>
          </div>
          <div class="card-body">

          <div class="row">
              <div class="col-xl-9 col-md-9">

                <div class="dropdown d-inline-block mb-3">
                  <form method="GET" action="">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="labInChargeIDDropdown" data-toggle="dropdown" aria-expanded="false">
                      <i class="mdi mdi-alpha-i-box"></i>
                      User ID
                    </button>
                    <div class="dropdown-menu scrollable-dropdown" aria-labelledby="labInChargeIDDropdown">
                    @foreach($labInChargeIDS as $labInChargeID)
                    @csrf
                      <a class="dropdown-item filter-labInCharge-id" data-value="{{ $labInChargeID->userID }}" href="#">
                      {{ $labInChargeID->userID }}-{{ $labInChargeID->firstName }} {{ $labInChargeID->lastName }}
                      </a>
                      @endforeach
                    </div>
                    <input type="hidden" name="labInChargeID" id="selectedLabInChargeID">
                  </form>
                </div>

              </div>
            </div>

            <table id="labInChargeTable" class="table table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>User ID</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($labInChargeLogs as $labInChargeLog)
                <tr>
                  <td> {{$labInChargeLog->idLogs}} </td>
                  <td> {{$labInChargeLog->userID}} </td>
                  <td> {{$labInChargeLog->action}} </td>
                  <td> {{$labInChargeLog->formatted_date}} </td>
                  <td> {{$labInChargeLog->formatted_time}} </td>
                  
                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
        </div>
      </div>

 
  <div class="tab-pane fade" id="nav-technician" role="tabpanel" aria-labelledby="nav-technician-tab">
  <div class="card card-default">
          <div class="card-header">
            <h1>Technician Logs</h1>
          </div>
          <div class="card-body">

          <div class="row">
              <div class="col-xl-9 col-md-9">

                <div class="dropdown d-inline-block mb-3">
                  <form method="GET" action="">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="technicianIDDropdown" data-toggle="dropdown" aria-expanded="false">
                      <i class="mdi mdi-alpha-i-box"></i>
                      User ID
                    </button>
                    <div class="dropdown-menu scrollable-dropdown" aria-labelledby="technicianIDDropdown">
                    @foreach($technicianIDS as $technicianID)
                    @csrf
                      <a class="dropdown-item filter-technician-id" data-value="{{ $technicianID->userID }}" href="#">
                      {{ $technicianID->userID }}-{{ $technicianID->firstName }} {{ $technicianID->lastName }}
                      </a>
                      @endforeach
                    </div>
                    <input type="hidden" name="technicianID" id="selectedTechnicianID">
                  </form>
                </div>

              </div>
            </div>

            <table id="technicianTable" class="table table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>User ID</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($technicianLogs as $technicianLog)
                <tr>
                  <td> {{$technicianLog->idLogs}} </td>
                  <td> {{$technicianLog->userID}} </td>
                  <td> {{$technicianLog->action}} </td>
                  <td> {{$technicianLog->formatted_date}} </td>
                  <td> {{$technicianLog->formatted_time}} </td>
                  
                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
        </div>
  </div>
  <div class="tab-pane fade" id="nav-faculty" role="tabpanel" aria-labelledby="nav-faculty-tab">
  <div class="card card-default">
          <div class="card-header">
            <h1>Faculty Logs</h1>
          </div>
          <div class="card-body">

          <div class="row">
              <div class="col-xl-9 col-md-9">

                <div class="dropdown d-inline-block mb-3">
                  <form method="GET" action="">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="facultyIDDropdown" data-toggle="dropdown" aria-expanded="false">
                      <i class="mdi mdi-alpha-i-box"></i>
                      User ID
                    </button>
                    <div class="dropdown-menu scrollable-dropdown" aria-labelledby="facultyIDDropdown">
                    @foreach($facultyIDS as $facultyID)
                    @csrf
                      <a class="dropdown-item filter-faculty-id" data-value="{{ $facultyID->userID }}" href="#">
                      {{ $facultyID->userID }}-{{ $facultyID->firstName }} {{ $facultyID->lastName }}
                      </a>
                      @endforeach
                    </div>
                    <input type="hidden" name="facultyID" id="selectedFacultyID">
                  </form>
                </div>

              </div>
            </div>

            <table id="facultyTable" class="table table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>User ID</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($facultyLogs as $facultyLog)
                <tr>
                  <td> {{$facultyLog->idLogs}} </td>
                  <td> {{$facultyLog->userID}} </td>
                  <td> {{$facultyLog->action}} </td>
                  <td> {{$facultyLog->formatted_date}} </td>
                  <td> {{$facultyLog->formatted_time}} </td>
                  
                </tr>
                @endforeach

              </tbody>
            </table>

          </div>
        </div>
  </div>
  <div class="tab-pane fade" id="nav-student" role="tabpanel" aria-labelledby="nav-student-tab">
  <div class="card card-default">
          <div class="card-header">
            <h1>Student Logs</h1>
          </div>
          <div class="card-body">

          <div class="row">
              <div class="col-xl-9 col-md-9">

                <div class="dropdown d-inline-block mb-3">
                  <form method="GET" action="">
                    <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentIDDropdown" data-toggle="dropdown" aria-expanded="false">
                      <i class="mdi mdi-alpha-i-box"></i>
                      User ID
                    </button>
                    <div class="dropdown-menu scrollable-dropdown" aria-labelledby="studentIDDropdown">
                    @foreach($studentIDS as $studentID)
                    @csrf
                      <a class="dropdown-item filter-student-id" data-value="{{ $studentID->userID }}" href="#">
                      {{ $studentID->userID }}-{{ $studentID->firstName }} {{ $studentID->lastName }}
                      </a>
                      @endforeach
                    </div>
                    <input type="hidden" name="studentID" id="selectedStudentID">
                  </form>
                </div>

              </div>
            </div>

            <table id="studentTable" class="table table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>User ID</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
              @foreach ($studentLogs as $studentLog)
                <tr>
                  <td> {{$studentLog->idLogs}} </td>
                  <td> {{$studentLog->userID}} </td>
                  <td> {{$studentLog->action}} </td>
                  <td> {{$studentLog->formatted_date}} </td>
                  <td> {{$studentLog->formatted_time}} </td>
                  
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
  </div>
  @include('footer')