<div id="appWrapper" class="app">

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
    <aside id="sidebar" class="sidebar">

        {{-- Brand --}}
        <div class="sb-brand">
            <div class="sb-brand-icon">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <span class="sb-brand-text">MyLMS</span>
            <button id="sidebarCollapseBtn" class="sb-collapse-btn" title="Collapse sidebar">
                <i class="fa-solid fa-angles-left"></i>
            </button>
        </div>

        {{-- Role pill --}}
        <div class="sb-role-pill">
            <i class="fa-solid fa-chalkboard-user me-1"></i>
            <span class="sb-label">Teacher Panel</span>
        </div>

        {{-- Nav --}}
        <nav class="sb-nav">

            {{-- ── Overview ── --}}
            <p class="sb-section-label"><span class="sb-label">Overview</span></p>
            <ul class="sb-list">
                <li>
                    <a href="{{ route('teacher.main') }}" title="Dashboard"
                       class="sb-link {{ request()->routeIs('teacher.main') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-gauge-high"></i></span>
                        <span class="sb-label">Dashboard</span>
                    </a>
                </li>
            </ul>

            {{-- ── Teaching ── --}}
            <p class="sb-section-label"><span class="sb-label">Teaching</span></p>
            <ul class="sb-list">

                <li>
                    <a href="{{ route('teacherClass.view') }}" title="My Classes"
                       class="sb-link {{ request()->routeIs('teacherClass.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-chalkboard"></i></span>
                        <span class="sb-label">My Classes</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('manageLessons.view') }}" title="Manage Lessons"
                       class="sb-link {{ request()->routeIs('manageLessons.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-list-check"></i></span>
                        <span class="sb-label">Manage Lessons</span>
                    </a>
                </li>

                <li class="sb-has-sub">
                    <button class="sb-link sb-toggle {{ request()->routeIs('createOnlineClass.view','recordedLeacture.view') ? 'sb-open' : '' }}"
                            data-target="#subLive">
                        <span class="sb-icon"><i class="fa-solid fa-video"></i></span>
                        <span class="sb-label">Live Sessions</span>
                        <i class="fa-solid fa-chevron-right sb-arrow"></i>
                    </button>
                    <ul class="sb-sub {{ request()->routeIs('createOnlineClass.view','recordedLeacture.view') ? 'show' : '' }}" id="subLive">
                        <li>
                            <a href="{{ route('createOnlineClass.view') }}"
                               class="sb-sub-link {{ request()->routeIs('createOnlineClass.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-circle-play"></i>Schedule Class
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('recordedLeacture.view') }}"
                               class="sb-sub-link {{ request()->routeIs('recordedLeacture.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-film"></i>Recorded Lectures
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            {{-- ── Assignments ── --}}
            <p class="sb-section-label"><span class="sb-label">Assignments</span></p>
            <ul class="sb-list">

                <li>
                    <a href="{{ route('teacher.assignments.uplodaView') }}" title="Upload Assignment"
                       class="sb-link {{ request()->routeIs('teacher.assignments.uplodaView') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-file-arrow-up"></i></span>
                        <span class="sb-label">Upload Assignment</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('assignmentReviews.view') }}" title="Assignment Reviews"
                       class="sb-link {{ request()->routeIs('assignmentReviews.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-clipboard-check"></i></span>
                        <span class="sb-label">Review & Grade</span>
                    </a>
                </li>

            </ul>

            {{-- ── Resources ── --}}
            <p class="sb-section-label"><span class="sb-label">Resources</span></p>
            <ul class="sb-list">

                <li>
                    <a href="{{ route('uploadMaterials.view') }}" title="Upload Materials"
                       class="sb-link {{ request()->routeIs('uploadMaterials.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-folder-open"></i></span>
                        <span class="sb-label">Upload Materials</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('stdGradeUpload.view') }}" title="Bulk Grade Upload"
                       class="sb-link {{ request()->routeIs('stdGradeUpload.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-upload"></i></span>
                        <span class="sb-label">Bulk Grade Upload</span>
                    </a>
                </li>

            </ul>

            {{-- ── Permissions (conditional) ── --}}
            @canany(['manage-students', 'role-manage'])
            <p class="sb-section-label"><span class="sb-label">Access</span></p>
            <ul class="sb-list">
                @can('manage-students')
                <li>
                    <a href="{{ route('teacher.student.managment') }}" title="Students"
                       class="sb-link {{ request()->routeIs('teacher.student.managment') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-user-graduate"></i></span>
                        <span class="sb-label">Students</span>
                    </a>
                </li>
                @endcan
                @can('role-manage')
                <li>
                    <a href="{{ route('teacher.role') }}" title="Roles"
                       class="sb-link {{ request()->routeIs('teacher.role') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-user-shield"></i></span>
                        <span class="sb-label">Roles</span>
                    </a>
                </li>
                @endcan
            </ul>
            @endcanany

        </nav>

        {{-- User profile card --}}
        <div class="sb-profile">
            <div class="sb-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="sb-profile-info sb-label">
                <p class="sb-profile-name">{{ auth()->user()->name }}</p>
                <p class="sb-profile-role">Teacher</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="sb-label">
                @csrf
                <button type="submit" class="sb-logout-btn" title="Sign out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>

    </aside>

    {{-- ═══════════════ HEADER ═══════════════ --}}
    <header class="app-header" role="banner">
        <div class="header-left">
            <button id="mobileMenuBtn" class="hdr-icon-btn d-lg-none" type="button" aria-label="Open menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="search-input">
                <i class="fa-solid fa-magnifying-glass hdr-search-icon"></i>
                <input class="hdr-search-field" type="search" placeholder="Search..." aria-label="Search" />
            </div>
        </div>

        <div class="header-right">
            <button class="hdr-icon-btn" title="Notifications">
                <i class="fa-solid fa-bell"></i>
            </button>

            <div class="hdr-divider"></div>

            <div class="dropdown">
                <button class="hdr-profile-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="hdr-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="hdr-name d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down hdr-caret d-none d-md-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end hdr-dropdown">
                    <li class="hdr-dd-user">
                        <div class="hdr-dd-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <div>
                            <div class="hdr-dd-name">{{ auth()->user()->name }}</div>
                            <div class="hdr-dd-email">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item hdr-dd-item text-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    {{-- Mobile overlay --}}
    <div id="sidebarOverlay" class="sb-overlay"></div>
