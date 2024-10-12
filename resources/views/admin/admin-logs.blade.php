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

  <title>ChronoLock Logs</title>
  <script defer src="{{asset('js/logs.js')}}"></script>

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
              <li class="breadcrumb-item active"><a href="{{ route('logs') }}">Logs</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <nav>
          <div class="nav nav-tabs justify-content-start" id="nav-tab" role="tablist">
            <button class="nav-link active shadow" id="nav-admin-tab" data-bs-toggle="tab" data-bs-target="#nav-admin" type="button" role="tab" aria-controls="nav-admin" aria-selected="true">
              <i class="mdi mdi-account-box text-danger"></i> Admin
            </button>
            <button class="nav-link shadow" id="nav-labInCharge-tab" data-bs-toggle="tab" data-bs-target="#nav-labInCharge" type="button" role="tab" aria-controls="nav-labInCharge" aria-selected="false">
              <i class="mdi mdi-account-box text-danger"></i> Lab-in-Charge
            </button>
            <button class="nav-link shadow" id="nav-technician-tab" data-bs-toggle="tab" data-bs-target="#nav-technician" type="button" role="tab" aria-controls="nav-technician" aria-selected="false">
              <i class="mdi mdi-account-box text-danger"></i> Technician
            </button>
            <button class="nav-link shadow" id="nav-faculty-tab" data-bs-toggle="tab" data-bs-target="#nav-faculty" type="button" role="tab" aria-controls="nav-faculty" aria-selected="false">
              <i class="mdi mdi-account-box text-danger"></i> Faculty
            </button>
            <button class="nav-link shadow" id="nav-student-tab" data-bs-toggle="tab" data-bs-target="#nav-student" type="button" role="tab" aria-controls="nav-student" aria-selected="false">
              <i class="mdi mdi-account-box text-danger"></i> Students
            </button>
          </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-admin" role="tabpanel" aria-labelledby="nav-admin-tab">

            <div class="card card-default shadow">
              <div class="card-header">
                <h1>Admin Logs</h1>

                <div class="col-xl-3 col-md-3 d-flex justify-content-end">
                  <div class="dropdown d-inline-block mb-3 ">
                    <button class="btn btn-warning btn-sm fw-bold" id="adminResetButton" type="button">
                      <i class="mdi mdi-alpha-r-box"></i>
                      RESET
                    </button>
                  </div>
                </div>
              </div>


              <div class="card-body">

                <div class="row">
                  <div class="col-xl-9 col-md-9 mr-1">

                    <div class="dropdown d-inline-block mb-3">
                      <form method="GET" action="">
                        <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="adminIDDropdown" data-toggle="dropdown" aria-expanded="false">
                          <i class="mdi mdi-alpha-i-box"></i>
                          User ID
                        </button>
                        <div class="dropdown-menu scrollable-dropdown" aria-labelledby="adminIDDropdown">
                          @foreach($adminIDS as $adminID)
                          @csrf
                          <a class="dropdown-item filter-admin-id admin-id" data-value="{{ $adminID->userID }}" href="#">
                          {{ ucwords($adminID->userID) }}-{{ ucwords($adminID->firstName) }} {{ ucwords($adminID->lastName) }}
                          </a>
                          @endforeach
                        </div>
                        <input type="hidden" name="adminID" id="selectedAdminID">
                      </form>
                    </div>

                  </div>
                </div>

                <table id="adminTable" class="table table-hover table-bordered nowrap" style="width:100%">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Action</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach ($adminLogs as $adminLog)
                    <tr>
                      <td> {{$counter}} </td>
                      <td> {{ ucwords($adminLog->userID) }} </td>
                      <td> {{ ucwords($adminLog->firstName)}} {{ucwords($adminLog->lastName) }} </td>
                      <td> {{$adminLog->action}} </td>
                      <td> {{$adminLog->formatted_date}} </td>
                      <td> {{$adminLog->formatted_time}} </td>

                    </tr>
                    @php $counter++; @endphp
                    @endforeach

                  </tbody>
                </table>

              </div>
            </div>
          </div>



          <div class="tab-pane fade" id="nav-labInCharge" role="tabpanel" aria-labelledby="nav-labInCharge-tab">

            <div class="card card-default shadow">
              <div class="card-header">
                <h1>Lab-in-Charge Logs</h1>

                <div class="col-xl-3 col-md-3 d-flex justify-content-end">
                  <div class="dropdown d-inline-block mb-3 ">
                    <button class="btn btn-warning btn-sm fw-bold" id="labInChargeResetButton" type="button">
                      <i class="mdi mdi-alpha-r-box"></i>
                      RESET
                    </button>
                  </div>
                </div>
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
                          <a class="dropdown-item filter-labInCharge-id lab-in-charge-id" data-value="{{ $labInChargeID->userID }}" href="#">
                          {{ ucwords($labInChargeID->userID) }}-{{ ucwords($labInChargeID->firstName) }} {{ ucwords($labInChargeID->lastName) }}
                          </a>
                          @endforeach
                        </div>
                        <input type="hidden" name="labInChargeID" id="selectedLabInChargeID">
                      </form>
                    </div>

                  </div>
                </div>

                <table id="labInChargeTable" class="table table-hover table-bordered nowrap" style="width:100%">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Action</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach ($labInChargeLogs as $labInChargeLog)
                    <tr>
                      <td> {{$counter}} </td>
                      <td> {{ ucwords($labInChargeLog->userID) }} </td>
                      <td> {{ucwords($labInChargeLog->firstName)}} {{ucwords($labInChargeLog->lastName)}} </td>
                      <td> {{$labInChargeLog->action}} </td>
                      <td> {{$labInChargeLog->formatted_date}} </td>
                      <td> {{$labInChargeLog->formatted_time}} </td>

                    </tr>
                    @php $counter++; @endphp
                    @endforeach

                  </tbody>
                </table>

              </div>
            </div>
          </div>


          <div class="tab-pane fade" id="nav-technician" role="tabpanel" aria-labelledby="nav-technician-tab">
            <div class="card card-default shadow">
              <div class="card-header">
                <h1>Technician Logs</h1>

                <div class="col-xl-3 col-md-3 d-flex justify-content-end">
                  <div class="dropdown d-inline-block mb-3 ">
                    <button class="btn btn-warning btn-sm fw-bold" id="technicianResetButton" type="button">
                      <i class="mdi mdi-alpha-r-box"></i>
                      RESET
                    </button>
                  </div>
                </div>
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
                          <a class="dropdown-item filter-technician-id technician-id" data-value="{{ $technicianID->userID }}" href="#">
                          {{ ucwords($technicianID->userID) }}-{{ ucwords($technicianID->firstName) }} {{ ucwords($technicianID->lastName) }}
                          </a>
                          @endforeach
                        </div>
                        <input type="hidden" name="technicianID" id="selectedTechnicianID">
                      </form>
                    </div>

                  </div>
                </div>

                <table id="technicianTable" class="table table-hover table-bordered nowrap" style="width:100%">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Action</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach ($technicianLogs as $technicianLog)
                    <tr>
                      <td> {{$counter}} </td>
                      <td> {{ ucwords($technicianLog->userID) }} </td>
                      <td> {{ ucwords($technicianLog->firstName)}} {{ucwords($technicianLog->lastName) }} </td>
                      <td> {{$technicianLog->action}} </td>
                      <td> {{$technicianLog->formatted_date}} </td>
                      <td> {{$technicianLog->formatted_time}} </td>

                    </tr>
                    @php $counter++; @endphp
                    @endforeach

                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="nav-faculty" role="tabpanel" aria-labelledby="nav-faculty-tab">
            <div class="card card-default shadow">
              <div class="card-header">
                <h1>Faculty Logs</h1>

                <div class="col-xl-3 col-md-3 d-flex justify-content-end">
                  <div class="dropdown d-inline-block mb-3 ">
                    <button class="btn btn-warning btn-sm fw-bold" id="facultyResetButton" type="button">
                      <i class="mdi mdi-alpha-r-box"></i>
                      RESET
                    </button>
                  </div>
                </div>
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
                          <a class="dropdown-item filter-faculty-id faculty-id" data-value="{{ $facultyID->userID }}" href="#">
                          {{ ucwords($facultyID->userID) }}-{{ ucwords($facultyID->firstName) }} {{ ucwords($facultyID->lastName) }}
                          </a>
                          @endforeach
                        </div>
                        <input type="hidden" name="facultyID" id="selectedFacultyID">
                      </form>
                    </div>

                  </div>
                </div>

                <table id="facultyTable" class="table table-hover table-bordered nowrap" style="width:100%">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Action</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach ($facultyLogs as $facultyLog)
                    <tr>
                      <td> {{$counter}} </td>
                      <td> {{ ucwords($facultyLog->userID) }} </td>
                      <td> {{ucwords($facultyLog->firstName)}} {{ucwords($facultyLog->lastName)}} </td>
                      <td> {{$facultyLog->action}} </td>
                      <td> {{$facultyLog->formatted_date}} </td>
                      <td> {{$facultyLog->formatted_time}} </td>
                    </tr>
                    @php $counter++; @endphp
                    @endforeach

                  </tbody>
                </table>

              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="nav-student" role="tabpanel" aria-labelledby="nav-student-tab">
            <div class="card card-default shadow">
              <div class="card-header">
                <h1>Student Logs</h1>

                <div class="col-xl-3 col-md-3 d-flex justify-content-end">
                  <div class="dropdown d-inline-block mb-3 ">
                    <button class="btn btn-warning btn-sm fw-bold" id="studentResetButton" type="button">
                      <i class="mdi mdi-alpha-r-box"></i>
                      RESET
                    </button>
                  </div>
                </div>
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
                          <a class="dropdown-item filter-student-id student-id" data-value="{{ $studentID->userID }}" href="#">
                          {{ ucwords($studentID->userID) }}-{{ ucwords($studentID->firstName) }} {{ ucwords($studentID->lastName) }}
                          </a>
                          @endforeach
                        </div>
                        <input type="hidden" name="studentID" id="selectedStudentID">
                      </form>
                    </div>

                  </div>
                </div>

                <table id="studentTable" class="table table-hover table-bordered nowrap" style="width:100%">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>User ID</th>
                      <th>Name</th>
                      <th>Action</th>
                      <th>Date</th>
                      <th>Time</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $counter = 1; @endphp
                    @foreach ($studentLogs as $studentLog)
                    <tr>
                      <td> {{$counter}} </td>
                      <td> {{ ucwords($studentLog->userID) }} </td>
                      <td> {{ ucwords($studentLog->firstName)}} {{ucwords($studentLog->lastName) }} </td>
                      <td> {{$studentLog->action}} </td>
                      <td> {{$studentLog->formatted_date}} </td>
                      <td> {{$studentLog->formatted_time}} </td>

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
  @include('footer')