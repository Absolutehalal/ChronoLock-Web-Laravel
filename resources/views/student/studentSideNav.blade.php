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
        <a href="{{ route('studentIndex') }}">
          <img src="{{asset('images/logo.png')}}" alt="Mono" />
          <span class="brand-name">ChronoLock</span>
        </a>
      </div>
      <!-- begin sidebar scrollbar -->
      <div class="sidebar-left" data-simplebar style="height: 100%">
        <!-- sidebar menu -->
        <ul class="nav sidebar-inner" id="sidebar-menu">
          <li class="section-title">Dashboard</li>
          <li>
            <a class="sidenav-item-link" href="{{ route('studentIndex') }}">
              <i class="mdi mdi-disqus-outline"></i>
              <span class="nav-text" data-toggle="tooltip" title="Student Dashboard">Student Dashboard</span>
            </a>
          </li>
          <li>
            <a class="sidenav-item-link" href="{{route('studentViewSchedule')}}">
              <i class="mdi mdi-calendar-clock"></i>
              <span class="nav-text" data-toggle="tooltip" title="Enroll Schedule">Enroll Schedule</span>
            </a>
          </li>
          <!-- Horizontal line with custom class -->
          <hr class="my-2 custom-hr">

          <li class="section-title">Classes</li>

          <li class="has-sub {{ request()->routeIs('studentViewAttendance') ? 'active' : '' }}">
            <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#users" aria-expanded="{{ request()->routeIs('studentViewAttendance') ? 'active' : '' }}" aria-controls="users">
              <i class="mdi mdi-folder-multiple-outline"></i>
              <span class="nav-text" data-toggle="tooltip" title="My Class Schedules">My Class Attendance</span> <b class="caret"></b>
            </a>
            <ul class="collapse {{ request()->routeIs('studentViewAttendance')  ? 'show' : ''  }}" id="users" data-parent="#sidebar-menu">
              <div class="sub-menu">
                @forelse($classSchedules as $classSchedule)
                @csrf

                @php
                $str = "{{$classSchedule->courseName}}";
                $length = strlen($str);
                @endphp

                @if($length > 15)
                <li>
                  <a class="section" href="{{ route('studentViewAttendance',  [ base64_encode( $classSchedule->classID)]) }}">{{strtoupper($classSchedule->courseCode)}} |<br> {{ucwords($classSchedule->courseName)}}</a>
                </li>
                @else
                <li>
                  <a class="section" href="{{ route('studentViewAttendance',  [ base64_encode( $classSchedule->classID)]) }}">{{strtoupper($classSchedule->courseCode)}} | {{ucwords($classSchedule->courseName)}}</a>
                </li>
                @endif
                @empty
                <li>
                  <a class="section" href="" # data-toggle="tooltip" title="Enroll Schedule First"> EMPTY </a>
                </li>
                @endforelse
          </li>
      </div>
    </div>
    <div class="sidebar-footer">
      <div class="sidebar-footer-content">
        <ul class="d-flex">
          <li>
            <span data-toggle="tooltip" title="Profile settings">
              <a href="#" data-toggle="modal" data-target="#modal-profile">
                <i class="mdi mdi-settings"></i>
              </a>
            </span>
          </li>
          <li>
            <!-- Logout form -->
            <form id="logout-student" action="{{ route('logout') }}" method="post" style="display: none;">
              @csrf
            </form>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-student').submit();" data-toggle="tooltip" title="Logout">
              <i class="mdi mdi-logout-variant"></i>
            </a>
          </li>
        </ul>
      </div>
    </div>
</div>
</aside>