<style>
    /* ========== SIDEBAR STYLES ========== */
    .sidebar {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 240px;
        background: #ffffff;
        z-index: 1050;
        transition: all 0.2s ease;
        border-right: 1px solid #e2e8f0;
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .sidebar.collapsed {
        width: 72px;
    }

    /* Logo Container */
    .logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .sidebar.collapsed .logo-container {
        padding: 1rem 0.5rem;
    }

    .logo-img {
        width: 110px;
        height: 90px;
        object-fit: contain;
        transition: all 0.2s ease;
    }

    .sidebar.collapsed .logo-img {
        width: 35px;
        height: 35px;
    }

    /* Navigation Items */
    .sidebar .nav {
        padding: 1rem 0.5rem;
    }

    .sidebar .nav-link {
        color: #64748b;
        padding: 0.5rem 0.5rem 0.5rem 0.5rem;
        margin-bottom: 0.25rem;
        border-radius: 0 5px 5px 0;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        position: relative;
        text-decoration: none;
        border-left: 4px solid transparent;
    }

    .sidebar .nav-link i {
        width: 20px;
        text-align: center;
        font-size: 1rem;
        transition: all 0.2s ease;
    }

    .sidebar .nav-text {
        margin-left: 0.75rem;
        transition: all 0.2s ease;
        white-space: nowrap;
        flex-grow: 1;
        font-size: 0.9rem;
    }

    .nav-arrow {
        transition: transform 0.3s ease;
        font-size: 0.8rem;
    }

    .sidebar.collapsed .nav-arrow {
        display: none;
    }

    .sidebar.collapsed .nav-link {
        position: relative;
        justify-content: center;
        padding: 0.75rem;
    }

    .sidebar.collapsed .nav-text {
        opacity: 0;
        width: 0;
        margin-left: 0;
        display: none;
    }

    .sidebar .nav-link:hover {
        color: #2c4ca0;
        background-color: #f5f7ff;
        border-left-color: #4169E1;
    }

    .sidebar .nav-link.active {
        color: #2c4ca0;
        background-color: #f5f7ff;
        border-left-color: #4169E1;
        font-weight: 500;
    }

    .nav-item {
        position: relative;
    }

    #inventarisSubmenu {
        background-color: transparent;
        border-left: 2px solid #e9efff;
        border-radius: 0;
        margin: 0.25rem 0 0.25rem 1.5rem;
        padding: 0;
        transition: all 0.2s ease;
    }

    .sub-link {
        padding: 0.6rem 0.75rem 0.6rem 2rem !important;
        font-size: 0.85rem;
        margin-bottom: 0.05rem;
        color: #64748b;
        border: none;
        border-radius: 0;
        position: relative;
    }

    .sub-link.active {
        color: #2c4ca0;
        background-color: #f5f7ff;
        font-weight: 500;
    }

    .nav-link[aria-expanded="true"] {
        background-color: #f5f7ff;
        border-left-color: #4169E1;
    }

    .nav-link[aria-expanded="true"] .nav-arrow {
        transform: rotate(180deg);
    }

    /* ========== TOOLTIP STYLES ========== */
    .sidebar.collapsed .nav-link::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #1e293b;
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1070;
        margin-left: 10px;
        pointer-events: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sidebar.collapsed .nav-link:hover::after {
        opacity: 1;
        visibility: visible;
        margin-left: 15px;
    }

    /* Tooltip arrow */
    .sidebar.collapsed .nav-link::before {
        content: '';
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 6px solid transparent;
        border-right-color: #1e293b;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1071;
        margin-left: 4px;
    }

    .sidebar.collapsed .nav-link:hover::before {
        opacity: 1;
        visibility: visible;
        margin-left: 9px;
    }

    /* Dropdown menu in collapsed state */
    .sidebar.collapsed #inventarisSubmenu {
        position: absolute;
        left: 100%;
        top: 0;
        width: 200px;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 1060;
        margin: 0;
        padding: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        transform: translateX(-10px);
        border: 1px solid #e2e8f0;
    }

    .sidebar.collapsed .nav-item:hover #inventarisSubmenu {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    /* Tooltip for sub-links */
    .sidebar.collapsed .sub-link {
        position: relative;
        padding: 0.5rem 0.75rem 0.5rem 1.5rem !important;
        margin-left: 0;
        border-radius: 6px;
    }

    .sidebar.collapsed .sub-link::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #1e293b;
        color: white;
        padding: 0.4rem 0.6rem;
        border-radius: 4px;
        font-size: 0.75rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        z-index: 1070;
        margin-left: 8px;
        pointer-events: none;
    }

    .sidebar.collapsed .sub-link:hover::after {
        opacity: 1;
        visibility: visible;
        margin-left: 12px;
    }

    .sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: #f8fafc;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 2px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* ========== RESPONSIVE SIDEBAR ========== */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            width: 280px;
            z-index: 1060;
        }

        .sidebar.show {
            transform: translateX(0);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar.show~.navbar-main,
        .sidebar.show~.main-content {
            pointer-events: none;
        }

        /* Disable tooltips on mobile */
        .sidebar.collapsed .nav-link::after,
        .sidebar.collapsed .nav-link::before,
        .sidebar.collapsed .sub-link::after {
            display: none !important;
        }

        .sidebar.collapsed #inventarisSubmenu {
            display: none !important;
        }

        .sidebar.show~.navbar-main::before,
        .sidebar.show~.main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }

        .sidebar-header {
            padding: 1rem;
        }

        .logo-container {
            padding: 0.5rem;
        }

        .logo-img {
            width: 35px;
            height: 35px;
        }
    }

    /* Additional fix for sidebar transition */
    .sidebar.collapsed .nav-link {
        justify-content: center;
        padding: 0.75rem;
    }

    .sidebar.collapsed .nav-link i {
        margin: 0;
    }

    /* Ensure proper z-index for tooltips */
    .sidebar.collapsed .nav-link::after,
    .sidebar.collapsed .nav-link::before {
        z-index: 1070;
    }

    .sidebar.collapsed .sub-link::after {
        z-index: 1070;
    }

    /* Fix for dropdown menu positioning */
    .sidebar.collapsed .nav-item {
        position: static;
    }

    .sidebar.collapsed #inventarisSubmenu {
        position: absolute;
        left: 100%;
        top: 0;
    }

    /* Prevent tooltip flickering */
    .sidebar.collapsed .nav-link:hover::after,
    .sidebar.collapsed .nav-link:hover::before {
        transition-delay: 0.1s;
    }
</style>

<nav class="sidebar bg-white shadow-sm" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="{{ asset('img/logo.JPG') }}" alt="Logo Desa" class="logo-img">
        </div>
    </div>

    <div class="nav flex-column">
        {{-- Dashboard --}}
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"
            data-tooltip="Dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span class="nav-text">Dashboard</span>
        </a>

        {{-- INVENTARIS DROPDOWN --}}
        <div class="nav-item">
            <a class="nav-link {{ (request()->routeIs('inventaris.*') || request()->routeIs('penghapusan.index')) ? 'active' : '' }}"
                href="#inventarisSubmenu" data-bs-toggle="collapse" role="button"
                aria-expanded="{{ (request()->routeIs('inventaris.*') || request()->routeIs('penghapusan.index')) ? 'true' : 'false' }}"
                aria-controls="inventarisSubmenu" data-tooltip="Inventaris">
                <i class="fa-solid fa-box fa-fw"></i>
                <span class="nav-text">Inventaris</span>
                <i class="fa-solid fa-angle-down me-2 nav-arrow"></i>
            </a>
            <div class="collapse {{ (request()->routeIs('inventaris.*') || request()->routeIs('penghapusan.index')) ? 'show' : '' }}"
                id="inventarisSubmenu">
                <a class="nav-link sub-link {{ request()->routeIs('inventaris.index') ? 'active' : '' }}"
                    href="{{ route('inventaris.index') }}" data-tooltip="Data Aktif">
                    Data Aktif
                </a>
                <a class="nav-link sub-link {{ request()->routeIs('inventaris.trashed') ? 'active' : '' }}"
                    href="{{ route('inventaris.trashed') }}" data-tooltip="Data Arsip">
                    Data Arsip
                </a>
                <a class="nav-link sub-link {{ request()->routeIs('penghapusan.index') ? 'active' : '' }}"
                    href="{{ route('penghapusan.index') }}" data-tooltip="Terhapus">
                    Terhapus
                </a>
            </div>
        </div>

        {{-- Kategori --}}
        <a class="nav-link {{ request()->routeIs('kategori.index') ? 'active' : '' }}"
            href="{{ route('kategori.index') }}" data-tooltip="Kategori">
            <i class="fas fa-fw fa-tags"></i>
            <span class="nav-text">Kategori</span>
        </a>

        {{-- Mutasi --}}
        <a class="nav-link {{ request()->routeIs('mutasi-inventaris.index') ? 'active' : '' }}"
            href="{{ route('mutasi-inventaris.index') }}" data-tooltip="Mutasi">
            <i class="fas fa-fw fa-exchange-alt"></i>
            <span class="nav-text">Mutasi</span>
        </a>

        {{-- Laporan --}}
        <a class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}"
            href="{{ route('laporan.index') }}" data-tooltip="Laporan">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span class="nav-text">Laporan</span>
        </a>

        {{-- Log Aktivitas --}}
        <a class="nav-link {{ request()->routeIs('log-aktivitas.index') ? 'active' : '' }}"
            href="{{ route('log-aktivitas.index') }}" data-tooltip="Log Aktivitas">
            <i class="fas fa-fw fa-history"></i>
            <span class="nav-text">Log Aktivitas</span>
        </a>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mainContent = document.querySelector('.main-content');
        const navbar = document.querySelector('.navbar-main');

        // Inisialisasi semua collapse dengan Bootstrap
        const collapseElements = document.querySelectorAll('.collapse');
        collapseElements.forEach(element => {
            new bootstrap.Collapse(element, {
                toggle: false
            });
        });

        // Fungsi untuk toggle sidebar
        function toggleSidebar() {
            if (window.innerWidth <= 768) {
                // Mobile behavior
                sidebar.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';

                // Tutup semua dropdown di mobile
                if (sidebar.classList.contains('show')) {
                    const openCollapses = document.querySelectorAll('.collapse.show');
                    openCollapses.forEach(collapse => {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    });
                }
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                if (navbar) navbar.classList.toggle('sidebar-collapsed');
                if (mainContent) mainContent.classList.toggle('sidebar-collapsed');

                // Jika sidebar dicollapse, tutup semua dropdown
                if (sidebar.classList.contains('collapsed')) {
                    const openCollapses = document.querySelectorAll('.collapse.show');
                    openCollapses.forEach(collapse => {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                        if (bsCollapse) {
                            bsCollapse.hide();
                        }
                    });
                }
            }
        }

        // Event listener untuk tombol hamburger
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSidebar();
            });
        }

        // Handle dropdown clicks pada sidebar
        const dropdownToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                // Jika sidebar collapsed di desktop, expand sidebar terlebih dahulu
                if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                    e.preventDefault();
                    sidebar.classList.remove('collapsed');
                    if (navbar) navbar.classList.remove('sidebar-collapsed');
                    if (mainContent) mainContent.classList.remove('sidebar-collapsed');

                    // Set timeout untuk membuka dropdown setelah sidebar expand
                    setTimeout(() => {
                        const target = this.getAttribute('data-bs-target');
                        const collapseElement = document.querySelector(target);
                        if (collapseElement) {
                            const bsCollapse = new bootstrap.Collapse(collapseElement, {
                                toggle: true
                            });
                        }
                    }, 300);
                }
            });
        });

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) {
                // Reset mobile state saat pindah ke desktop
                sidebar.classList.remove('show');
                document.body.style.overflow = '';
            } else {
                // Reset desktop state saat pindah ke mobile
                sidebar.classList.remove('collapsed');
                if (navbar) navbar.classList.remove('sidebar-collapsed');
                if (mainContent) mainContent.classList.remove('sidebar-collapsed');

                // Tutup semua dropdown di mobile
                const openCollapses = document.querySelectorAll('.collapse.show');
                openCollapses.forEach(collapse => {
                    const bsCollapse = bootstrap.Collapse.getInstance(collapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                });
            }
        });

        // Rotate arrow when dropdown is open
        dropdownToggles.forEach(toggle => {
            const target = toggle.getAttribute('data-bs-target');
            const collapseElement = document.querySelector(target);

            if (collapseElement) {
                collapseElement.addEventListener('show.bs.collapse', function () {
                    const arrow = toggle.querySelector('.nav-arrow');
                    if (arrow) {
                        arrow.style.transform = 'rotate(180deg)';
                    }
                });

                collapseElement.addEventListener('hide.bs.collapse', function () {
                    const arrow = toggle.querySelector('.nav-arrow');
                    if (arrow) {
                        arrow.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });

        // Handle hover effect untuk dropdown di collapsed state
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('mouseenter', function () {
                if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                    const dropdown = this.querySelector('#inventarisSubmenu');
                    if (dropdown) {
                        dropdown.style.opacity = '1';
                        dropdown.style.visibility = 'visible';
                        dropdown.style.transform = 'translateX(0)';
                    }
                }
            });

            item.addEventListener('mouseleave', function () {
                if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                    const dropdown = this.querySelector('#inventarisSubmenu');
                    if (dropdown && !dropdown.classList.contains('show')) {
                        dropdown.style.opacity = '0';
                        dropdown.style.visibility = 'hidden';
                        dropdown.style.transform = 'translateX(-10px)';
                    }
                }
            });
        });
    });
</script>