<header class="main-header" id="header">
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar">
        <!-- Sidebar toggle button -->
        <button id="sidebar-toggler" class="sidebar-toggle">
            <span class="sr-only">Toggle navigation</span>
        </button>

        @if(Auth::check())
        <span class="page-title">Hello, {{ Auth::user()->firstName }} {{ Auth::user()->lastName }} | ID: {{ Auth::user()->idNumber }}</span>
        @endif

        <div class="navbar-right">

            <ul class="nav navbar-nav">

                <!-- User Account -->
                <li class="dropdown user-menu">
                    @if(Auth::check())
                    <button class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <img src="{{ Auth::user()->avatar ?? asset('images/User Icon.png') }}" class="user-image rounded-circle" alt="User Image" />
                        <span class="d-none d-lg-inline-block">{{ Auth::user()->userType }}</span>
                    </button>
                    @endif
                    <ul class="dropdown-menu dropdown-menu-right shadow-sm">

                        <li>
                            <a href="#" id="editProfile" data-toggle="modal" data-target="#modal-profile" data-toggle="tooltip" title="Profile settings">
                                <i class="mdi mdi-settings fw-bold text-warning"></i>
                                <span class="nav-text fw-bold text-dark">Account Setting</span>
                            </a>
                        </li>

                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="dropdown-link-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" data-toggle="tooltip" title="Logout">
                                <i class="mdi mdi-logout fw-bold text-info"></i>
                                <span class="nav-text fw-bold text-dark">Log Out</span>
                            </a>
                        </li>
                        <!-- <li class="dropdown-footer"> -->
                </li>
            </ul>
            </li>
            </ul>
        </div>
    </nav>
</header>