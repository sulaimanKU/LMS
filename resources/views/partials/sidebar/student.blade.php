<div id="appWrapper" class="app">

    {{-- ═══════════════ SIDEBAR ═══════════════ --}}
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
            <button id="sidebarCollapseBtn" class="sb-collapse-btn" title="Collapse sidebar">
                <i class="fa-solid fa-angles-left"></i>
            </button>
        </div>

        {{-- Role pill --}}
        <div class="sb-role-pill">
            <i class="fa-solid fa-user-graduate me-1"></i>
            <span class="sb-label">Student Portal</span>
        </div>

        {{-- Nav --}}
        <nav class="sb-nav">

            {{-- ── Overview ── --}}
            <p class="sb-section-label"><span class="sb-label">Overview</span></p>
            <ul class="sb-list">
                <li>
                    <a href="{{ route('student.main') }}" title="Dashboard"
                       class="sb-link {{ request()->routeIs('student.main') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-gauge-high"></i></span>
                        <span class="sb-label">Dashboard</span>
                    </a>
                </li>
            </ul>

            {{-- ── Learning ── --}}
            <p class="sb-section-label"><span class="sb-label">Learning</span></p>
            <ul class="sb-list">

                <li>
                    <a href="{{ route('enrolledCourses.view') }}" title="My Courses"
                       class="sb-link {{ request()->routeIs('enrolledCourses.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-book-open-reader"></i></span>
                        <span class="sb-label">My Courses</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('jionClass.view') }}" title="Join Class"
                       class="sb-link {{ request()->routeIs('jionClass.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-video"></i></span>
                        <span class="sb-label">Join Class</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('learning.materials.view') }}" title="Learning Materials"
                       class="sb-link {{ request()->routeIs('learning.materials.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-book-open"></i></span>
                        <span class="sb-label">Learning Materials</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('assigments.upload.view') }}" title="Assignments"
                       class="sb-link {{ request()->routeIs('assigments.upload.view') ? 'sb-active' : '' }}">
                        <span class="sb-icon"><i class="fa-solid fa-file-lines"></i></span>
                        <span class="sb-label">Assignments</span>
                    </a>
                </li>

            </ul>

            {{-- ── System ── --}}
            <p class="sb-section-label"><span class="sb-label">System</span></p>
            <ul class="sb-list">
                <li>
                    <a href="{{ route('settings.view') }}" title="Settings"
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
                <p class="sb-profile-role">Student</p>
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
