<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  @if (Auth::check())
  <title>ChronoLock {{ Auth::user()->userType }} Dashboard</title>
  @endif

  @include('head')

  <!-- TOASTER -->
  <link href="plugins/toaster/toastr.min.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- Custom JS -->
  <script src="js/toastr.js"></script>

  <script src='{{asset('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js')}}'></script>

</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>

  <div id="toast"></div>

  @include('admin.adminSideNav')
  <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->
  <div class="page-wrapper">
    <!-- Header -->

    @include('sweetalert::alert')

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
              <!-- <i class="mdi mdi-home"></i> -->
              <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>
        <div class="row">

          @if (Auth::check())
          @if(Auth()->check() && (Auth()->user()->userType === 'Admin' || Auth()->user()->userType === 'Technician' || Auth()->user()->userType === 'Lab-in-Charge'))
          <!-- First box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-success rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-multiple-check"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countTotalUsers }} </span>
                  <p>Total Users</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Second box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-warning rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-group"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countStudents }} </span>
                  <p>Total Students</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Third box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-primary rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-group"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countFaculty }} </span>
                  <p>Total Faculty</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Fourth box -->
          <div class="col-xl-3 col-md-6">
            <div class="card card-default shadow">
              <div class="d-flex p-5 align-items-center flex-column">
                <div class="icon-md bg-info rounded-circle mb-2 fs-2">
                  <i class="mdi mdi-account-card-details"></i>
                </div>
                <div class="text-center">
                  <span class="h2 d-block"> {{ $countRegRFID }} </span>
                  <p>Registered RFID</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- CALENDAR -->
        <div class="card card-default shadow">
          <div class="card-header card-header-border-bottom d-flex justify-content-between align-items-center">
            <h1>Schedule</h1>
            <div class="row">

              <div class="col-xl-12 col-md-12 d-flex justify-content-end">
                
                <div class="dropdown d-inline-block mb-3 mr-3">
                  <a href="{{ route('adminScheduleManagement') }}" title="Add Regular Schedule" class="btn btn-primary btn-sm fw-bold" type="button">
                    <i class=" mdi mdi-calendar-plus"></i>
                    GO TO SCHEDULE
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="full-calendar mb-5">
              <div id="calendar"></div>
            </div>
          </div>
        </div> 
        @endif
        @endif



        <!--TABLES-->
        <div class="row">
          @if(Auth()->user()->userType === 'Admin')
          <div class="col-xl-6">
            <!-- Striped Table -->
            <div class="card card-default shadow">
              <div class="card-header">
                <h2>Recently Added Users</h2>
                <div class="row">
                  <div class="col-xl-12 col-md-12 d-flex justify-content-end">

                    <div class="dropdown d-inline-block ">
                      <a href="{{ route('userManagement') }}" class="btn-warning btn-sm fw-bold" type="button">
                        <i class=" mdi mdi-arrow-right"></i>
                        GO TO USER MANAGEMENT
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-wrapper">
                  <table class="table table-bordered table-hover">
                    <thead class="table-dark" id="tblDashboard">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Firstname</th>
                        <th scope="col">Lastname</th>
                        <th scope="col">Email</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $counter = 1; @endphp
                      @forelse ($tblUsers as $user)
                      @csrf
                      <tr>
                        <td> {{$counter}} </td>
                        <td> {{ucwords($user->firstName)}} </td>
                        <td> {{ucwords($user->lastName)}} </td>
                        <td> {{$user->email}} </td>
                      </tr>
                      @php $counter++; @endphp
                      @empty
                      <td colspan="5" class="text-center">No Recent Added RFID</td>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>


          <div class="col-xl-6">
            <!-- Striped Table -->
            <div class="card card-default shadow">
              <div class="card-header">
                <h2>Recently Added RFID</h2>
                <div class="row">
                  <div class="col-xl-12 col-md-12 d-flex justify-content-end">

                    <div class="dropdown d-inline-block ">
                      <a href="{{ route('RFIDManagement') }}" class="btn-warning btn-sm fw-bold" type="button">
                        <i class=" mdi mdi-arrow-right"></i>
                        GO TO RFID ACCOUNTS
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-wrapper">
                  <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">RFID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php $counter = 1; @endphp
                      @forelse ($tblRFID as $rfid)
                      @csrf
                      <tr>
                        <td> {{$counter}} </td>
                        <td> {{$rfid->RFID_Code}} </td>
                        <td> {{ucwords($rfid->firstName)}} {{ucwords($rfid->lastName)}} </td>
                        <td> {{$rfid->userType}} </td>
                        <td>
                          @if($rfid->RFID_Status == 'Activated')
                          <span class="badge badge-success">Activated</span>
                          @elseif($rfid->RFID_Status == 'Deactivated')
                          <span class="badge badge-danger">Deactivated</span>
                          @endif
                        </td>
                      </tr>
                      @php $counter++; @endphp
                      @empty
                      <tr>
                        <td colspan="5" class="text-center">No Recent Added RFID</td>
                      </tr>
                      @endforelse
                    </tbody>

                  </table>
                </div>
              </div>
            </div>
          </div>
          @endif
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