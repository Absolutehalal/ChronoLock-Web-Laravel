<script defer src="{{asset('js/activePage.js')}}"></script>


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
                <a href="{{route('instructorIndex')}}">
                    <img src="{{asset('images/logo.png')}}" alt="Mono" />
                    <span class="brand-name">ChronoLock</span>
                </a>
            </div>
            <div class="sidebar-left" data-simplebar style="height: 100%">
                <!-- sidebar menu -->
                <ul class="nav sidebar-inner" id="sidebar-menu">

                    <li class="section-title">Dashboard</li>

                    <li>
                        <a class="sidenav-item-link" href="{{route('instructorIndex')}}">
                            <i class="mdi mdi-disqus-outline"></i>
                            <span class="nav-text" data-toggle="tooltip" title="Instructor Dashboard">Instructor
                                Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a class="sidenav-item-link" href="{{route('classSchedules')}}">
                            <i class="mdi mdi-calendar-check"></i>
                            <span class="nav-text" data-toggle="tooltip" title="ERP Schedules">ERP
                                Schedules</span>
                        </a>
                    </li>
                    <li>
                        <a class="sidenav-item-link" href="{{route('classListManagement')}}">
                            <i class="mdi mdi-file-document-box-multiple"></i>
                            <span class="nav-text" data-toggle="tooltip" title="Class List">Class
                                List</span>
                        </a>
                    </li>
                    <li>
                        <a class="sidenav-item-link" href="{{route('instructorScheduleManagement')}}">
                            <i class="mdi mdi-calendar-today"></i>
                            <span class="nav-text" data-toggle="tooltip" title="My Schedule">My
                                Schedule</span>
                        </a>
                    </li>

                    <!-- Horizontal line with custom class -->
                    <hr class="my-2 custom-hr">

                    <li class="section-title">Section</li>

                    <li class="has-sub">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#users" aria-expanded="false" aria-controls="users">
                            <i class="mdi mdi-alpha-s-box"></i>
                            <span class="nav-text" data-toggle="tooltip" title="My Class List">My Class List</span> <b class="caret"></b>
                        </a>
                        <ul class="collapse" id="users" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @foreach($classes as $classes)
                                @csrf
                                <li>
                                    <a class="section" href="{{ route('instructorClassAttendanceAndList',  [ base64_encode( $classes->classID)]) }}">{{$classes->course}} - {{$classes->year}}{{$classes->section}}</a>
                                </li>
                                @endforeach
                    </li>
            </div>
        </div>

        <div class="sidebar-footer">
            <div class="sidebar-footer-content">
                <ul class="d-flex">
                    <li>
                        <a href="user-account-settings.php" data-toggle="tooltip" title="Profile settings"><i class="mdi mdi-settings"></i></a>
                    </li>
                    <li>
                        <!-- Logout form -->
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tooltip" title="Logout">
                            <i class="mdi mdi-logout-variant"></i>
                        </a>
                </ul>
            </div>
        </div>
</div>
</aside>