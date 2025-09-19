{{-- D:\SEMESTER 10\TTU 2\Karangduren\inventaris-desa\resources\views\layouts\partials\navbar.blade.php --}}

@php
    // Logika untuk membuat inisial nama pengguna
    $name = Auth::user()->nama;
    $nameParts = explode(' ', trim($name));
    $firstName = $nameParts[0];
    $lastName = count($nameParts) > 1 ? end($nameParts) : '';
    $initials = strtoupper(substr($firstName, 0, 1) . ($lastName ? substr($lastName, 0, 1) : ''));
@endphp

<style>
    /* ========== NAVBAR STYLES (CONSISTENT WITH SIDEBAR) ========== */
    :root {
        --primary-color: #4169E1;
        /* Warna biru utama dari sidebar */
        --primary-text: #2c4ca0;
        /* Warna teks aktif dari sidebar */
        --primary-bg-hover: #f5f7ff;
        /* Warna background hover dari sidebar */
        --border-color: #e2e8f0;
        --text-muted: #64748b;
    }

    .navbar-main {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        position: fixed;
        top: 0;
        left: 240px;
        /* Lebar sidebar default */
        right: 0;
        z-index: 1040;
        height: 70px;
        background: #ffffff;
        border-bottom: 1px solid var(--border-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        transition: left 0.2s ease;
    }

    .navbar-main.sidebar-collapsed {
        left: 72px;
        /* Lebar sidebar collapsed */
    }

    .navbar-main .left-section,
    .navbar-main .right-section {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    /* Tombol Hamburger */
    .hamburger-btn {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        background: transparent;
        border: 1px solid transparent;
        color: var(--text-muted);
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }

    .hamburger-btn:hover {
        color: var(--primary-text);
        background-color: var(--primary-bg-hover);
        border-color: var(--primary-color);
    }

    .hamburger-btn:focus {
        box-shadow: 0 0 0 3px rgba(65, 105, 225, 0.2);
    }

    /* Tampilan Waktu */
    .time-display {
        background: #f8fafc;
        color: var(--text-muted);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        border: 1px solid var(--border-color);
    }

    /* ========== USER DROPDOWN ========== */
    .user-dropdown .dropdown-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: transparent;
        border: none;
        padding: 0.3rem;
        border-radius: 50px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .user-dropdown .dropdown-toggle:hover {
        background-color: var(--primary-bg-hover);
    }

    .user-dropdown .dropdown-toggle::after {
        display: none;
    }

    /* Avatar dengan inisial */
    .user-initials-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Menu Dropdown */
    .dropdown-menu {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 0;
        margin-top: 0.75rem !important;
        min-width: 240px;
        overflow: hidden;
    }

    .dropdown-header-user {
        padding: 1rem;
        background-color: #f8fafc;
        border-bottom: 1px solid var(--border-color);
    }

    .dropdown-header-user .fw-bold {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        color: #1e293b;
    }

    .dropdown-item {
        padding: 0.75rem 1.25rem;
        color: var(--text-muted);
        transition: all 0.2s ease;
    }

    .dropdown-item:hover,
    .dropdown-item:focus {
        color: var(--primary-text);
        background-color: var(--primary-bg-hover);
    }

    .dropdown-item.text-danger:hover,
    .dropdown-item.text-danger:focus {
        background-color: #ffeef0;
        color: #dc3545 !important;
    }


    /* ========== RESPONSIVE NAVBAR ========== */
    @media (max-width: 767.98px) {
        .navbar-main {
            left: 0 !important;
            padding: 0 1rem;
        }

        .user-info-text,
        .navbar-title small {
            display: none;
        }

        .navbar-title h5 {
            font-size: 1rem;
        }
    }

    @media (max-width: 575.98px) {
        .navbar-main {
            padding: 0 0.75rem;
        }

        .navbar-title {
            display: none;
        }

        .left-section {
            flex-grow: 1;
        }
    }
</style>

<header class="navbar-main">
    <div class="left-section">
        <button class="btn hamburger-btn" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <div class="navbar-title d-flex flex-column">
            <h5 class="m-0 fw-bold" style="color: var(--primary-text);">Sistem Inventaris Desa</h5>
            <small class="text-muted">Karangduren</small>
        </div>
    </div>

    <div class="right-section">
        <div class="time-display d-none d-md-flex align-items-center gap-2">
            <i class="far fa-clock"></i>
            <span id="current-time" class="fw-bold text-dark"></span>
            <span id="current-seconds" class="fw-bold d-none d-lg-inline text-dark"></span>
            <span id="current-date" class="d-none d-lg-inline"></span>
        </div>

        <div class="user-dropdown dropdown">
            <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                aria-expanded="false">
                <div class="user-initials-avatar">
                    {{ $initials }}
                </div>
                <div class="user-info-text text-start d-none d-md-block">
                    <div class="fw-bold small" style="color: #1e293b;">{{ Auth::user()->nama }}</div>
                    <div class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->role }}</div>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="dropdown-header-user">
                        <div class="fw-bold">{{ Auth::user()->nama }}</div>
                        <div class="small text-muted">{{ Auth::user()->email }}</div>
                    </div>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
    // Tidak ada perubahan pada JavaScript, biarkan seperti semula.
    function updateTime() {
        const now = new Date();
        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        const dayName = days[now.getDay()];
        const date = now.getDate();
        const monthName = months[now.getMonth()];
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');

        const timeElement = document.getElementById('current-time');
        const secondsElement = document.getElementById('current-seconds');
        const dateElement = document.getElementById('current-date');

        if (timeElement) timeElement.textContent = `${hours}:${minutes}`;
        if (secondsElement) secondsElement.textContent = `:${seconds}`;
        if (dateElement) dateElement.textContent = `| ${dayName}, ${date} ${monthName}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        setInterval(updateTime, 1000);
        updateTime();
    });
</script>