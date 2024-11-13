<script defer src="{{asset('js/activePage.js')}}"></script>



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
                        <a class="sidenav-item-link" href="{{route('instructorScheduleManagement')}}">
                            <i class="mdi mdi-calendar-check"></i>
                            <span class="nav-text" data-toggle="tooltip" title="ERP Schedules">ERP Schedules</span>
                        </a>
                    </li>
                    <li>
                        <a class="sidenav-item-link" href="{{route('classListManagement')}}">
                            <i class="mdi mdi-file-document-box-multiple"></i>
                            <span class="nav-text" data-toggle="tooltip" title="Class Record">Class Record</span>
                        </a>
                    </li>
                    <li>
                        <a class="sidenav-item-link" href="{{route('classSchedules')}}">
                            <i class="mdi mdi-calendar-today"></i>
                            <span class="nav-text" data-toggle="tooltip" title="My Schedule">My Schedule</span>
                        </a>
                    </li>

                    <!-- Horizontal line with custom class -->
                    <hr class="my-2 custom-hr">


                    <li class="section-title">Sections Handled</li>

                    <li class="has-sub {{ request()->routeIs('instructorClassAttendanceAndList') ? 'active' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#users" aria-expanded="{{ request()->routeIs('instructorClassAttendanceAndList') ? 'true' : 'false' }}" aria-controls="users">
                            <i class="mdi mdi-alpha-s-box"></i>
                            <span class="nav-text" data-toggle="tooltip" title="Sections Handled">Sections</span> <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ request()->routeIs('instructorClassAttendanceAndList') ? 'show' : '' }}" id="users" data-parent="#sidebar-menu">
                            <div class="sub-menu">
                                @forelse($classes as $class)
                                @csrf
                                <li>
                                    <a class="section" href="{{ route('instructorClassAttendanceAndList', [base64_encode($class->classID)]) }}">
                                        {{strtoupper($class->courseCode)}} | {{$class->program}}-{{$class->year}}{{$class->section}}
                                    </a>
                                </li>
                                @empty
                                <li>
                                    <a class="section" href="">None</a>
                                </li>
                                @endforelse

                                </div>
                        </ul>

                    </li>

                    <li class="section-title">Others</li>

                    <li class="has-sub {{ request()->routeIs('facultyStudentAttendanceGeneration')  ? 'active' : '' }}">
                        <a class="sidenav-item-link" href="javascript:void(0)" data-toggle="collapse" data-target="#reports" aria-expanded="{{ request()->routeIs('facultyStudentAttendanceGeneration') || request()->routeIs('facultyStudentListGeneration')  ? 'true' : 'false' }}" aria-controls="reports">
                            <i class="mdi mdi-file-export"></i>
                            <span class="nav-text" data-toggle="tooltip" title="Report Generation">Report Generation</span> <b class="caret"></b>
                        </a>
                        <ul class="collapse {{ request()->routeIs('facultyStudentAttendanceGeneration') || request()->routeIs('facultyStudentListGeneration') ? 'show' : '' }}" id="reports">
                            <div class="sub-menu">
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('facultyStudentAttendanceGeneration') }}">
                                        <span class="nav-text">Student Attendance</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="sidenav-item-link" href="{{ route('facultyStudentListGeneration') }}">
                                        <span class="nav-text">Student List</span>
                                    </a>
                                </li>
                            </div>
                        </ul>
                    </li>

                </ul>

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
                            <form id="logout-faculty" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-faculty').submit();" data-toggle="tooltip" title="Logout">
                                <i class="mdi mdi-logout-variant"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

    </aside>