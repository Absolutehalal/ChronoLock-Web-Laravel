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
            <a class="sidenav-item-link" href="{{route('studentIndex')}}">
              <i class="mdi mdi-disqus-outline"></i>
              <span class="nav-text" data-toggle="tooltip" title="Student Dashboard">Student Dashboard</span>
            </a>
          </li>

          <li>
            <a class="sidenav-item-link" href="{{route('studentViewSchedule')}}">
              <i class="mdi mdi-calendar-clock"></i>
              <span class="nav-text" data-toggle="tooltip" title="View Schedule">View Schedule</span>
            </a>
          </li>

         

          <!-- Horizontal line with custom class -->
          <hr class="my-2 custom-hr">

          <li class="section-title">My Class Schedules</li>

          <li>
            <a class="sidenav-item-link" href="{{route('pendingRFID')}}">
              <i class="mdi mdi-folder-clock-outline"></i>
              <span class="nav-text" data-toggle="tooltip" title="Subject Here">ITEC 222</span>
            </a>
          </li>

      </ul>
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