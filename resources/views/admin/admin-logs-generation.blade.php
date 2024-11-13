<!DOCTYPE html>
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
              <li class="breadcrumb-item active"><a href="{{ route('logsGeneration') }}">Logs Report Generation</a></li>
            </ol>
          </nav>
          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-9 col-md-9">
            <!-- Example single primary button -->
            <form action="{{ url('/user-logs-generation') }}" method="GET">
              <div class="dropdown d-inline-block">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="userTypeButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                  <i class="mdi mdi-account-box"></i> UserType
                </button>
                <div class="dropdown-menu" aria-labelledby="userTypeButton">
                  @forelse ($userType as $userType)
                  <a class="dropdown-item userType-item @if ($userType == $userType->userType) active @endif" href="#" data-value="{{ $userType->userType }}">
                    {{ $userType->userType }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="user_type" id="user_type" value="">
              </div>
              <div class="dropdown d-inline-block">
                <div class="input-group date" id="month-picker">
                  <input class="form-control border-primary" type="search" name="name_id" value="" placeholder="Name/ID" autocomplete="false" id="name_id">
                  <div class="input-group-append">
                    <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                      <i class="mdi mdi-database-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="dropdown d-inline-block">
                <div class="input-group date" id="month-picker">
                  <input class="form-control border-primary" type="search" name="action" id="action" value="" placeholder="Action" autocomplete="false">
                  <div class="input-group-append">
                    <div class="input-group text-light btn btn-primary btn-sm" id="dateIcon">
                      <i class="mdi mdi-database-search"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-9 col-md-9 mt-1 mb-2">
                  <div class="dropdown d-inline-block">
                    <div class="input-group date" id="month-picker">
                      <input type="datetime-local" class="form-control border border-primary" placeholder="Start Date" value="{{ $selected_StartDate }}" name="selected_StartDate" id="selectedStartDate">
                      <div class="input-group-append">
                        <div class="input-group text-light btn btn-primary btn-sm" id="dateIconStart">
                          <i class="mdi mdi-calendar"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="dropdown d-inline-block">
                    <div class="input-group date" id="month-picker">
                      <input type="datetime-local" class="form-control border border-primary" placeholder="End Date" value="{{ $selected_EndDate }}" name="selected_EndDate" id="selectedEndDate">
                      <div class="input-group-append">
                        <div class="input-group text-light btn btn-primary btn-sm" id="dateIconEnd">
                          <i class="mdi mdi-calendar "></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- END -->
          </div>
          <div class="col-xl-3 col-md-3 d-flex justify-content-end">
            <div class="dropdown d-inline-block mb-3 mr-1">
              <button class="btn btn-danger btn-sm fw-bold" type="submit">
                <i class="mdi mdi-sort"></i> Filter
              </button>
            </div>
            </form>
            <div class="dropdown d-inline-block mb-3 ">
              <a class="btn btn-warning btn-sm fw-bold" href="{{ url('/user-logs-generation') }}" type="submit">
                <i class="mdi mdi-alpha-r-box"></i>
                RESET
              </a>
            </div>
          </div>
        </div>
        <!-- END -->
        <div class="card card-default shadow">
          <div class="card-header">
            <h1>User Logs</h1>
            <div class="justify-content-end">
              <div class="dropdown d-inline-block mb-3">
                <button data-toggle="tooltip" title="PDF" class="btn btn-outline-dark btn-sm fw-bold"
                  onclick='window.location = "{{ route("logsReportGenerationPDF", ["name_id" => $name_id, "action" => $action, "selected_StartDate" => $selected_StartDate, "selected_EndDate" => $selected_EndDate, "user_type" => $user_type]) }}"'
                  type="button">
                  <i class="mdi mdi-feature-search"></i>
                  PDF
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table id="adminTable" class="table table-hover table-bordered no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>User ID</th>
                  <th>Name</th>
                  <th>Action</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>UserType</th>
                </tr>
              </thead>
              <tbody>
                @php $counter = 1; @endphp
                @foreach ($userLogs as $userLog)
                <tr>
                  <td> {{$counter}} </td>
                  <td> {{ ucwords($userLog->userID) }} </td>
                  <td> {{ ucwords($userLog->firstName)}} {{ucwords($userLog->lastName) }} </td>
                  <td> {{$userLog->action}} </td>
                  <td>{{ date('F j, Y', strtotime($userLog->date)) }}</td>
                  <td>{{ date('h:i A', strtotime($userLog->time)) }}</td>
                  <td>
                    @if($userLog->userType == 'Admin')
                    <span class="badge badge-primary">Admin</span>
                    @elseif($userLog->userType == 'Dean')
                    <span class="badge badge-primary">Dean</span>
                    @elseif($userLog->userType == 'Program Chair')
                    <span class="badge badge-primary">Program Chair</span>
                    @elseif($userLog->userType == 'Technician')
                    <span class="badge badge-primary">Technician</span>
                    @elseif($userLog->userType == 'Lab-in-Charge')
                    <span class="badge badge-primary">Lab-in-Charge</span>
                    @elseif($userLog->userType == 'Faculty')
                    <span class="badge badge-danger">Faculty</span>
                    @elseif($userLog->userType == 'Student')
                    <span class="badge badge-warning">Student</span>
                    @endif
                  </td>
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
  <script>
    document.querySelectorAll('.userType-item').forEach(function(item) {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('userTypeButton').innerHTML = `<i class="mdi mdi-account-box"></i> ${this.textContent}`;
        document.getElementById('user_type').value = this.getAttribute('data-value');
      });
    });
  </script>
  </div>
  </div>
  </div>
  @include('footer')