<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Class Attendance</title>

  @include('head')
</head>


  <body class="navbar-fixed sidebar-fixed" id="body">
    <script>
      NProgress.configure({ showSpinner: false });
      NProgress.start();
    </script>
  @include('instructorSideNav')
      <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
      <div class="page-wrapper">
      @include('header')

        <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
        <div class="content-wrapper">
          <div class="content"><!-- Card Profile -->


          <div class="d-flex justify-content-between align-items-center">
                    <!-- Navigation -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="inst-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="inst-class-record.php">Class Attendance & Class List</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-2" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>


<div class="card card-default card-profile">

  <div class="card-header-bg" style=""></div>

  <div class="card-body card-profile-body">

    <div class="profile-avata">
      <img class="rounded-circle" src="{{asset('images/user/user-md-01.jpg')}}" alt="Avata Image">
      <span class="h5 d-block mt-3 mb-2">Albrecht Straub</span>
      <span class="d-block">Albrecht.straub@gmail.com</span>
    </div>

    <ul class="nav nav-profile-follow">
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="h5 d-block">1503</span>
          <span class="text-color d-block">Friends</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="h5 d-block">2905</span>
          <span class="text-color d-block">Followers</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <span class="h5 d-block">1200</span>
          <span class="text-color d-block">Following</span>
        </a>
      </li>

    </ul>
    <div class="profile-button">
      <a class="btn btn-primary btn-pill" href="user-planing-settings.html">Upgrade Plan</a>
    </div>

  </div>

  <div class="card-footer card-profile-footer">
  <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="pills-attendance-tab" data-bs-toggle="pill" data-bs-target="#pills-attendance" type="button" role="tab" aria-controls="pills-attendance" aria-selected="true">Attendance</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="pills-classList-tab" data-bs-toggle="pill" data-bs-target="#pills-classList" type="button" role="tab" aria-controls="pills-classList" aria-selected="false">Class List</button>
    </li>
  </ul>


<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-attendance" role="tabpanel" aria-labelledby="pills-attendance-tab">

<div class="row">
  <div class="col-xl-3 col-md-6">
    <div class="card card-default">
      <div class="d-flex p-5">
        <div class="icon-md bg-secondary rounded-circle mr-3">
          <i class="mdi mdi-account-plus-outline"></i>
        </div>
        <div class="text-left">
          <span class="h2 d-block">890</span>
          <p>New Users</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card card-default">
      <div class="d-flex p-5">
        <div class="icon-md bg-success rounded-circle mr-3">
          <i class="mdi mdi-table-edit"></i>
        </div>
        <div class="text-left">
          <span class="h2 d-block">350</span>
          <p>Order Placed</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card card-default">
      <div class="d-flex p-5">
        <div class="icon-md bg-primary rounded-circle mr-3">
          <i class="mdi mdi-content-save-edit-outline"></i>
        </div>
        <div class="text-left">
          <span class="h2 d-block">1360</span>
          <p>Total Sales</p>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card card-default">
      <div class="d-flex p-5">
        <div class="icon-md bg-info rounded-circle mr-3">
          <i class="mdi mdi-bell"></i>
        </div>
        <div class="text-left">
          <span class="h2 d-block">$8930</span>
          <p>Monthly Revenue</p>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Table -->
<div class="card card-default">
                    <div class="card-header">
                        <h1>My Class Attendance</h1>
                    </div>
                    <div class="card-body ">


                        <table id="example" class="table table-bordered table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <th>Course</th>
                                    <th>Year & Section</th>
                                    <th>Remark</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($studAttendances as $studAttendance)
                                <tr>
                                    <td> {{$studAttendance->date}} </td>
                                    <td> {{$studAttendance->time}} </td>
                                    <td>{{$studAttendance->firstName}} {{$studAttendance->lastName}}</td>
                                    <td>{{$studAttendance->idNumber}}</td>
                                    <td>{{$studAttendance->course}}</td>
                                    <td>{{$studAttendance->year}}-{{$studAttendance->section}} </td>
                                    <td>{{$studAttendance->remark}}</td>
                                    <th>
                                        <!-- Example single primary button -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" type="button" data-toggle="modal" data-target="#exampleModalForm"">
                                                    <i class="mdi mdi-circle-edit-outline text-warning"></i>
                                                    Edit</a>
                                                <a class="dropdown-item">
                                                    <i class="mdi mdi-trash-can text-danger"></i>
                                                    Delete</a>
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
    
        
  
    
    
<div class="tab-pane fade" id="pills-classList" role="tabpanel" aria-labelledby="pills-classList-tab">

    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="card card-default">
          <div class="d-flex p-5">
            <div class="icon-md bg-secondary rounded-circle mr-3">
              <i class="mdi mdi-account-plus-outline"></i>
            </div>
            <div class="text-left">
              <span class="h2 d-block">890</span>
              <p>New Users</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-default">
          <div class="d-flex p-5">
            <div class="icon-md bg-success rounded-circle mr-3">
              <i class="mdi mdi-table-edit"></i>
            </div>
            <div class="text-left">
              <span class="h2 d-block">350</span>
              <p>Order Placed</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-default">
          <div class="d-flex p-5">
            <div class="icon-md bg-primary rounded-circle mr-3">
              <i class="mdi mdi-content-save-edit-outline"></i>
            </div>
            <div class="text-left">
              <span class="h2 d-block">1360</span>
              <p>Total Sales</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-default">
          <div class="d-flex p-5">
            <div class="icon-md bg-info rounded-circle mr-3">
              <i class="mdi mdi-bell"></i>
            </div>
            <div class="text-left">
              <span class="h2 d-block">$8930</span>
              <p>Monthly Revenue</p>
            </div>
          </div>
        </div>
      </div>
  </div>


<!-- Table -->
<div class="card card-default">
                    <div class="card-header">
                        <h1>My Class List</h1>
                    </div>
                    <div class="card-body ">


                        <table id="example" class="table table-bordered table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <th>Course</th>
                                    <th>Year & Section</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{$student->firstName}} {{$student->lastName}}</td>
                                    <td>{{$student->idNumber}}</td>
                                    <td>{{$student->course}}</td>
                                    <td>{{$student->year}}-{{$student->section}}</td>
                                    <td>{{$student->status}}</td>
                                    <th>
                                        <!-- Example single primary button -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item"  type="button" data-toggle="modal" data-target="#exampleModalForm"">
                                                    <i class="mdi mdi-circle-edit-outline text-warning"></i>
                                                    Edit</a>
                                                <a class="dropdown-item">
                                                    <i class="mdi mdi-trash-can text-danger"></i>
                                                    Delete</a>
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
   


@include('footer')