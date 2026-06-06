<div id="appWrapper" class="app">

    {{-- ═══════════════════════════════════════════
         SIDEBAR
    ═══════════════════════════════════════════ --}}
    <aside id="sidebar" class="sidebar">

        {{-- Brand --}}
        <div class="sb-brand">
            <div class="sb-brand-icon">
                @if(isset($systemSettings['site_logo_nav']))
                    <img src="{{ asset('storage/'.$systemSettings['site_logo_nav']) }}" style="height: 24px; width: 24px; object-fit: contain;">
                @else
                    <i class="fa-solid fa-graduation-cap"></i>
                @endif
            </div>
            <span class="sb-brand-text">{{ $systemSettings['site_title'] ?? 'MyLMS' }}</span>
            <button id="sidebarCollapseBtn" class="sb-collapse-btn" id="sidebarCollapseBtn" title="Collapse sidebar">
                <i class="fa-solid fa-angles-left"></i>
            </button>
        </div>

        {{-- Admin pill --}}
        <div class="sb-role-pill">
            <i class="fa-solid fa-shield-halved me-1"></i>
            <span class="sb-label">Admin Panel</span>
        </div>

        {{-- Nav --}}
        <nav class="sb-nav">

            {{-- ── Overview ── --}}
            <p class="sb-section-label"><span class="sb-label">Overview</span></p>
            <ul class="sb-list">
                <li>
                    <a href="{{ route('dashboard') }}" title="Dashboard"
                       class="sb-link {{ request()->routeIs('dashboard') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-gauge-high"></i></span>
                        <span class="sb-label">Dashboard</span>
                    </a>
                </li>
            </ul>

            {{-- ── People ── --}}
            <p class="sb-section-label"><span class="sb-label">People</span></p>
            <ul class="sb-list">

                <li class="sb-has-sub">
                    <button class="sb-link sb-toggle {{ request()->routeIs('teacher.*','admin.student.*','manage.role.*','admin.role','roles.permissions.*','admin.system.admins') ? 'sb-open' : '' }}"
                            data-target="#subUsers">
                        <span class="sb-icon"><i class="fa-solid fa-users"></i></span>
                        <span class="sb-label">User Management</span>
                        <i class="fa-solid fa-chevron-right sb-arrow"></i>
                    </button>
                    <ul class="sb-sub {{ request()->routeIs('teacher.*','admin.student.*','manage.role.*','admin.role','roles.permissions.*','admin.system.admins') ? 'show' : '' }}" id="subUsers">
                        <li>
                            <a href="{{ route('manage.role.view') }}"
                               class="sb-sub-link {{ request()->routeIs('manage.role.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-user-tag"></i>Manage Roles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('teacher.Mangment.View') }}"
                               class="sb-sub-link {{ request()->routeIs('teacher.Mangment.View') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-chalkboard-user"></i>Teachers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.student.managment') }}"
                               class="sb-sub-link {{ request()->routeIs('admin.student.managment') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-user-graduate"></i>Students
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.role') }}"
                               class="sb-sub-link {{ request()->routeIs('admin.role') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-user-shield"></i>Roles &amp; Permissions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('roles.permissions.view') }}"
                               class="sb-sub-link {{ request()->routeIs('roles.permissions.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-shield-halved"></i>Access Control
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.system.admins') }}"
                               class="sb-sub-link {{ request()->routeIs('admin.system.admins') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-user-shield"></i>System Admins
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            {{-- ── Academic ── --}}
            <p class="sb-section-label"><span class="sb-label">Academic</span></p>
            <ul class="sb-list">

                <li class="sb-has-sub">
                    <button class="sb-link sb-toggle {{ request()->routeIs('department.*','classDep.*','subjectDep.*') ? 'sb-open' : '' }}"
                            data-target="#subAcademic">
                        <span class="sb-icon"><i class="fa-solid fa-school"></i></span>
                        <span class="sb-label">Academic Setup</span>
                        <i class="fa-solid fa-chevron-right sb-arrow"></i>
                    </button>
                    <ul class="sb-sub {{ request()->routeIs('department.*','classDep.*','subjectDep.*') ? 'show' : '' }}" id="subAcademic">
                        <li>
                            <a href="{{ route('department.view') }}"
                               class="sb-sub-link {{ request()->routeIs('department.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-layer-group"></i>Departments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('classDep.view') }}"
                               class="sb-sub-link {{ request()->routeIs('classDep.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-door-open"></i>Classes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('subjectDep.view') }}"
                               class="sb-sub-link {{ request()->routeIs('subjectDep.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-book-open"></i>Subjects
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('virtualRoom.view') }}"
                       class="sb-link {{ request()->routeIs('virtualRoom.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-video"></i></span>
                        <span class="sb-label">Virtual Classroom</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('course.index') }}"
                       class="sb-link {{ request()->routeIs('course.index') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-book"></i></span>
                        <span class="sb-label">Courses</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('assignments') }}"
                       class="sb-link {{ request()->routeIs('assignments') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-file-lines"></i></span>
                        <span class="sb-label">Assignments</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('attendence.view') }}"
                       class="sb-link {{ request()->routeIs('attendence.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-user-check"></i></span>
                        <span class="sb-label">Attendance</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('fee.view') }}"
                       class="sb-link {{ request()->routeIs('fee.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-money-bill-wave"></i></span>
                        <span class="sb-label">Fees</span>
                    </a>
                </li>

            </ul>

            {{-- ── System ── --}}
            <p class="sb-section-label"><span class="sb-label">System</span></p>
            <ul class="sb-list">

                <li class="sb-has-sub">
                    <button class="sb-link sb-toggle {{ request()->routeIs('financial.*','systemlogs.*') ? 'sb-open' : '' }}"
                            data-target="#subReports">
                        <span class="sb-icon"><i class="fa-solid fa-chart-pie"></i></span>
                        <span class="sb-label">Reports</span>
                        <i class="fa-solid fa-chevron-right sb-arrow"></i>
                    </button>
                    <ul class="sb-sub {{ request()->routeIs('financial.*','systemlogs.*') ? 'show' : '' }}" id="subReports">
                        <li>
                            <a href="{{ route('financial.view') }}"
                               class="sb-sub-link {{ request()->routeIs('financial.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-wallet"></i>Financial
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('systemlogs.view') }}"
                               class="sb-sub-link {{ request()->routeIs('systemlogs.view') ? 'sb-sub-active' : '' }}">
                                <i class="fa-solid fa-clipboard-list"></i>System Logs
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('settings.view') }}"
                       class="sb-link {{ request()->routeIs('settings.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-gear"></i></span>
                        <span class="sb-label">Settings</span>
                    </a>
                </li>

            </ul>

        </nav>

        {{-- User profile card --}}
        <div class="sb-profile">
            @if(auth()->user()->profile_image)
                <img src="{{ asset('storage/'.auth()->user()->profile_image) }}" class="sb-profile-avatar" style="object-fit: cover;">
            @else
                <div class="sb-profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            @endif
            <div class="sb-profile-info sb-label">
                <p class="sb-profile-name">{{ auth()->user()->name }}</p>
                <p class="sb-profile-role">Administrator</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="sb-label">
                @csrf
                <button type="submit" class="sb-logout-btn" title="Sign out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>

    </aside>

    {{-- ═══════════════════════════════════════════
         HEADER
    ═══════════════════════════════════════════ --}}
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
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/'.auth()->user()->profile_image) }}" class="hdr-avatar" style="object-fit: cover;">
                    @else
                        <div class="hdr-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    @endif
                    <span class="hdr-name d-none d-md-inline">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down hdr-caret d-none d-md-inline"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end hdr-dropdown">
                    <li class="hdr-dd-user">
                        @if(auth()->user()->profile_image)
                            <img src="{{ asset('storage/'.auth()->user()->profile_image) }}" class="hdr-dd-avatar" style="object-fit: cover;">
                        @else
                            <div class="hdr-dd-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <div class="hdr-dd-name">{{ auth()->user()->name }}</div>
                            <div class="hdr-dd-email">{{ auth()->user()->email }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li><a class="dropdown-item hdr-dd-item" href="{{ route('settings.view') }}"><i class="fa-solid fa-gear me-2"></i>Settings</a></li>
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
