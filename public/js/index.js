/* ================================================================
   SIDEBAR — collapse, submenus, mobile overlay
================================================================ */
document.addEventListener('DOMContentLoaded', function () {

    const app        = document.getElementById('appWrapper');
    const sidebar    = document.getElementById('sidebar');
    const overlay    = document.getElementById('sidebarOverlay');
    const collapseBtn = document.getElementById('sidebarCollapseBtn');
    const mobileBtn  = document.getElementById('mobileMenuBtn');

    if (!app || !sidebar) return;

    // ── Desktop collapse ──
    function updateCollapseHint() {
        if (!collapseBtn) return;
        const isCollapsed = app.classList.contains('sb-collapsed');
        collapseBtn.title = isCollapsed ? 'Expand sidebar' : 'Collapse sidebar';
    }

    if (collapseBtn) {
        collapseBtn.addEventListener('click', function () {
            app.classList.toggle('sb-collapsed');
            localStorage.setItem('sb-collapsed', app.classList.contains('sb-collapsed') ? '1' : '0');
            updateCollapseHint();
        });
    }

    // Restore collapse state
    if (localStorage.getItem('sb-collapsed') === '1') {
        app.classList.add('sb-collapsed');
    }
    updateCollapseHint();

    // ── Mobile open/close ──
    function openSidebar() {
        sidebar.classList.add('show');
        overlay && overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay && overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (mobileBtn)  mobileBtn.addEventListener('click', openSidebar);
    if (overlay)    overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1200) closeSidebar();
    });

    // ── Submenu toggles ──
    document.querySelectorAll('.sb-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const targetId = btn.dataset.target;
            const sub = document.querySelector(targetId);
            if (!sub) return;

            const isOpen = sub.classList.contains('show');

            // Close all siblings first
            const parentList = btn.closest('.sb-list');
            if (parentList) {
                parentList.querySelectorAll('.sb-sub.show').forEach(function (s) {
                    s.classList.remove('show');
                });
                parentList.querySelectorAll('.sb-toggle.sb-open').forEach(function (t) {
                    t.classList.remove('sb-open');
                });
            }

            if (!isOpen) {
                sub.classList.add('show');
                btn.classList.add('sb-open');
            }
        });
    });

});

/* ================================================================
   DATATABLES
================================================================ */
$(document).ready(function () {
    if ($('#userTable').length) {
        $('#userTable').DataTable({
            pageLength: 10,
            ordering:   true,
            info:       true,
            language:   { search: 'Search User:', emptyTable: 'No users found' }
        });
    }
    if ($('#studentTable').length) {
        $('#studentTable').DataTable({
            pageLength:  10,
            ordering:    true,
            responsive:  true,
            language:    { search: '', searchPlaceholder: 'Search...' }
        });
    }
});
