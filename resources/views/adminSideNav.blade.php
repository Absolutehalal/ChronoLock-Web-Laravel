@include('sweetalert::alert')

<script defer src="js/activePage.js"></script>
<!-- ====================================
    ——— WRAPPER
    ===================================== -->
<div class="wrapper">
  <!-- ====================================
          ——— LEFT SIDEBAR WITH OUT FOOTER
        ===================================== -->
  <aside class="left-sidebar sidebar-dark" id="left-sidebar">
    <div id="sidebar" class="sidebar sidebar-with-footer">
      <!-- Aplication Brand -->
      <div class="app-brand">
        <a href="{{route('index')}}">
          <img src="images/logo.png" alt="Mono" />
          <span class="brand-name">ChronoLock</span>
        </a>
      </div>
      <!-- begin sidebar scrollbar -->
      <div class="sidebar-left" data-simplebar style="height: 100%">
        <!-- sidebar menu -->
        <ul class="nav sidebar-inner" id="sidebar-menu">

          <li class="section-title">Dashboard</li>

          <li>
            <a class="sidenav-item-link" href="{{route('index')}}">
              <i class="mdi mdi-disqus-outline"></i>
              <span class="nav-text" data-toggle="tooltip" title="Admin Dashboard">Admin Dashboard</span>
            </a>
          </li>

          <li>
            <a class="sidenav-item-link" href="{{route('pendingRFID')}}">
              <i class="mdi mdi-folder-clock-outline"></i>
              <span class="nav-text" data-toggle="tooltip" title="Pending RFID Request">Pending RFID Request</span>
            </a>
          </li>

          <li>
            <a class="sidenav-item-link" href="{{route('userManagement')}}">
              <i class="mdi mdi-account-circle"></i>
              <span class="nav-text" data-toggle="tooltip" title="Users">Users</span>
            </a>
          </li>

          <!-- Horizontal line with custom class -->
          <hr class="my-2 custom-hr">

          <li class="section-title">Features</li>

          <li>
            <a class="sidenav-item-link" href="{{route('adminScheduleManagement')}}">
              <i class="mdi mdi-calendar-today "></i>
              <span class="nav-text" data-toggle="tooltip" title="Schedule">Schedule</span>
            </a>
          </li>

          <li class="has-sub">
            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#users" aria-expanded="false" aria-controls="users">
              <i class="mdi mdi-book-open-page-variant"></i>
              <span class="nav-text" data-toggle="tooltip" title="Attendance">Attendance</span> <b class="caret"></b>
            </a>
            <ul class="collapse show" id="users" data-parent="#sidebar-menu">
              <div class="sub-menu">
                <li>
                  <a class="sidenav-item-link" href="{{route('studentAttendanceManagement')}}">
                    <span class="nav-text">Student Attendance</span>

                  </a>
                </li>

                <li>
                  <a class="sidenav-item-link" href="{{route('instructorAttendanceManagement')}}">
                    <span class="nav-text">Instructor Attendance</span>

                  </a>
                </li>
          </li>
      </div>
      </ul>
      </li>

      <li>
        <a class="sidenav-item-link" href="{{route('RFIDManagement')}}">
          <i class="mdi mdi-radio-tower"></i>
          <span class="nav-text" data-toggle="tooltip" title="RFID Accounts">RFID Accounts</span>
        </a>
      </li>

      <!-- Horizontal line with custom class -->
      <hr class="my-2 custom-hr">

      <li class="section-title">Others</li>

      <li>
        <a class="sidenav-item-link" href="{{route('logs')}}">
          <i class="mdi mdi-alpha-l-box "></i>
          <span class="nav-text" data-toggle="tooltip" title="Logs">Logs</span>
        </a>
      </li>

      <li>
        <a class="sidenav-item-link" href="{{route('reportGeneration')}}">
          <i class="mdi mdi-file-export"></i>
          <span class="nav-text" data-toggle="tooltip" title="Report Generation">Report Generation</span>
        </a>
      </li>


      </ul>
    </div>

    <div class="sidebar-footer">
    <div class="sidebar-footer-content">
        <ul class="d-flex">
            <li>
                <a href="user-account-settings.php" data-toggle="tooltip" title="Profile settings">
                    <i class="mdi mdi-settings"></i>
                </a>
            </li>
            <li>
                <!-- Logout form -->
                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                    @csrf
                </form>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tooltip" title="Logout">
                    <i class="mdi mdi-logout-variant"></i>
                </a>
            </li>
        </ul>
    </div>
</div>

</div>
</aside>