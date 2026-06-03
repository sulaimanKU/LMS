

    <!-- Outer wrapper; toggled 'sidebar-collapsed' class collapses sidebar on desktop -->


        <!-- SIDEBAR For Teachers -->
     @if(Auth::user()->role == 'teacher' || Auth::user()->role =='admin')
    <li class="nav-item"><small class="text-uppercase px-3 opacity-50 label">Instructor Panel</small></li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="fa-solid fa-chalkboard-user me-2"></i>
            <span class="label">My Classes</span>
        </a>
    </li>

    <li class="nav-item has-submenu">
        <a class="nav-link nav-main-link" href="#liveSessionsMenu" data-bs-toggle="collapse" role="button">
            <i class="fa-solid fa-video me-2 text-success"></i>
            <span class="label">Live Sessions</span>
            <i class="fa-solid fa-chevron-down arrow-icon"></i>
        </a>
        <ul class="collapse submenu" id="liveSessionsMenu">
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-play"></i>
                    <span class="label">Start Online Class</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-clapperboard"></i>
                    <span class="label">Recorded Lectures</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item has-submenu">
        <a class="nav-link nav-main-link" href="#progressMenu" data-bs-toggle="collapse" role="button">
            <i class="fa-solid fa-list-check me-2"></i>
            <span class="label">Student Progress</span>
            <i class="fa-solid fa-chevron-down arrow-icon"></i>
        </a>
        <ul class="collapse submenu" id="progressMenu">
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-upload"></i>
                    <span class="label">Bulk Grade Upload</span>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span class="label">Assignment Reviews</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="fa-solid fa-folder-open me-2"></i>
            <span class="label">Resource Library</span>
        </a>
    </li>
@endif

