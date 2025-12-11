<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Compact Responsive LMS Dashboard</title>
    <link rel="stylesheet" href="{{ asset('styles/dashboard.css') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">





</head>

<body>

    <!-- Outer wrapper; toggled 'sidebar-collapsed' class collapses sidebar on desktop -->
    <div id="appWrapper" class="app">

        <!-- SIDEBAR -->

        <aside id="sidebar" class="sidebar" aria-label="Primary navigation">
            <!-- Brand / Logo -->
            <div class="brand px-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-graduation-cap text-white fa-lg"></i>
                <div class="brand-text">
                    <span class="logo">MyLMS</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="nav-links px-1" aria-label="Main Navigation">
                <ul class="list-unstyled m-0 p-0">

                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge-high me-2"></i>
                            <span class="label">Dashboard</span>
                        </a>
                    </li>

                    <!-- Courses -->
                    <li class="nav-item">
                        <a href="{{ route('course.index') }}"
                            class="nav-link {{ request()->routeIs('course.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-book me-2"></i>
                            <span class="label">Courses</span>
                        </a>
                    </li>

                    <!-- Assignments -->
                    <li class="nav-item">
                        <a href="{{ route('assignments') }}"
                            class="nav-link {{ request()->routeIs('assignments.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-lines me-2"></i>
                            <span class="label">Assignments</span>
                        </a>
                    </li>

                    <!-- Calendar -->
                    <li class="nav-item has-submenu">

                        <a class="nav-link nav-main-link {{ request()->routeIs('time.table*') ? 'active' : '' }}"
                            href="#timetableMenu" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('time.table*') ? 'true' : 'false' }}"
                            aria-controls="timetableMenu">

                            <i class="fa-solid fa-calendar-days"></i>

                            <span class="label">Timetable</span>

                            <i
                                class="fa-solid fa-chevron-down arrow-icon
            {{ request()->routeIs('time.table*') ? 'rotate' : '' }}"></i>
                        </a>

                        <ul class="collapse submenu {{ request()->routeIs('time.table*') ? 'show' : '' }}"
                            id="timetableMenu">

                            <li>
                                <a href="{{ route('create.timetable.view') }}"
                                    class="nav-link {{ request()->routeIs('time.table.create') ? 'active' : '' }}">
                                    <i class="fa-solid fa-plus"></i>
                                    <span class="label">Create Timetable</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('time.table') }}"
                                    class="nav-link {{ request()->routeIs('time.table.view') ? 'active' : '' }}">
                                    <i class="fa-solid fa-eye"></i>
                                    <span class="label">View Timetable</span>
                                </a>
                            </li>

                        </ul>

                    </li>



                    <!-- Attendance -->
                    <li class="nav-item">
                        <a href="{{route('attendence.view')}}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-check me-2"></i>
                            <span class="label">Attendance</span>
                        </a>
                    </li>

                    <!-- Fees -->
                    <li class="nav-item">
                        <a href="{{route('fee.view')}}" class="nav-link {{ request()->routeIs('fees.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-money-bill-wave me-2"></i>
                            <span class="label">Fees / Upload Slip</span>
                        </a>
                    </li>

                    <!-- Grades -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('grades.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line me-2"></i>
                            <span class="label">Grades</span>
                        </a>
                    </li>

                    <!-- Messages -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope me-2"></i>
                            <span class="label">Messages</span>
                        </a>
                    </li>

                    <!-- Profile -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                            <i class="fa-solid fa-user me-2"></i>
                            <span class="label">Profile</span>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                            <i class="fa-solid fa-gear me-2"></i>
                            <span class="label">Settings</span>
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="nav-item">
                        <a href="#" class="nav-link text-danger">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>
                            <span class="label">Sign Out</span>
                        </a>
                    </li>

                </ul>
            </nav>

        </aside>

        <!-- HEADER -->
        <header class="app-header" role="banner">
            <div class="header-left">
                <!-- mobile menu opener -->
                <button id="mobileMenuBtn" class="btn btn-outline-secondary d-lg-none accessible-focus" type="button"
                    aria-label="Open menu" title="Open menu">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <!-- desktop toggle (alternate) -->
                <button id="desktopToggleBtn"
                    class="btn btn-outline-secondary d-none d-lg-inline-block accessible-focus me-1" type="button"
                    aria-label="Toggle sidebar" title="Toggle sidebar">
                    <i class="fa-solid fa-sliders"></i>
                </button>


                <!-- search (hidden on very small) -->
                <div class="search-input">
                    <input class="form-control form-control-sm" type="search"
                        placeholder="Search courses, students..." aria-label="Search" />
                </div>
            </div>

            <div class="header-right">
                <button class="btn btn-ghost btn-sm accessible-focus" title="Notifications"
                    aria-label="Notifications">
                    <i class="fa-solid fa-bell"></i>
                </button>

                <button class="btn btn-ghost btn-sm accessible-focus" title="Messages" aria-label="Messages">
                    <i class="fa-solid fa-envelope"></i>
                </button>

                <!-- small-screen quick menu (visible < lg) -->
                <div class="dropdown d-lg-none">
                    <button class="btn btn-primary btn-sm dropdown-toggle accessible-focus" id="quickMenu"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickMenu">
                        <li><a class="dropdown-item" href="#">Dashboard</a></li>
                        <li><a class="dropdown-item" href="#">My Courses</a></li>
                        <li><a class="dropdown-item" href="#">Calendar</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                    </ul>
                </div>

                <!-- profile -->
                <div class="dropdown">
                    <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#"
                        id="profileMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('assets/img/sky.jpg') }}" alt="User avatar" class="rounded-circle"
                            width="36" height="36">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileMenu">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="app-main" role="main" aria-live="polite">
            @yield('contents')
        </main>
    </div>

    <!-- Offcanvas - used on small screens; same nav as sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileOffcanvas"
        aria-labelledby="mobileOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 id="mobileOffcanvasLabel">MyLMS</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <nav class="nav nav-pills flex-column mb-2">
                <a class="nav-link active" href="{{ route('dashboard') }}"><i
                        class="fa-solid fa-gauge-high me-2"></i> Dashboard</a>
                <a class="nav-link" href="{{ route('course.index') }}"><i class="fa-solid fa-book me-2"></i>
                    Courses</a>
                <a class="nav-link" href="{{ route('course.index') }}"><i class="fa-solid fa-book me-2"></i>
                    Assignments</a>
                <a class="nav-link" href="#"><i class="fa-solid fa-calendar-days me-2"></i> Calendar</a>
                <a class="nav-link" href="#"><i class="fa-solid fa-chart-line me-2"></i> Grades</a>
                <a class="nav-link" href="#"><i class="fa-solid fa-envelope me-2"></i> Messages</a>
            </nav>
        </div>
    </div>

    <!-- Bootstrap JS (bundle includes Popper) -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>

</body>

</html>
