<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>@yield('title', 'Admin Panel') — SPK SAW</title>
    <link rel="icon" href="{{ asset('Assets/Logo_SMKN2Jember.png') }}" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Merriweather:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --primary: #1a3c6e;
            --primary-dark: #0f2548;
            --accent: #e8a020;
            --accent-light: #f5c55a;

            --bg: #f0f4fa;
            --surface: #ffffff;
            --border: #e2e8f0;

            --text-dark: #1e2a3a;
            --text-muted: #6b7a8d;

            --green: #059669;
            --gbg: #ecfdf5;
            --gb: #a7f3d0;

            --blue: #1a3c6e;
            --bbg: #eff6ff;
            --bb: #bfdbfe;

            --red: #dc2626;
            --rbg: #fef2f2;
            --rb: #fecaca;

            --purple: #7c3aed;
            --pbg: #f5f3ff;
            --pbb: #ddd6fe;

            --amber: #e8a020;
            --abg: #fffbeb;
            --ab: #fde68a;

            --sw: 240px;
            --th: 58px;

            --radius-sm: 4px;
            --radius-md: 10px;
            --radius-lg: 12px;
            --radius-pill: 999px;

            --shadow-soft: 0 12px 30px rgba(26, 60, 110, 0.12);
            --shadow-deep: 0 16px 40px rgba(26, 60, 110, 0.15);
            --shadow-accent: 0 8px 25px rgba(232,160,32,0.4);

            --transition-fast: .2s ease;
            --transition-normal: .3s ease;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sw);
            background: var(--primary-dark);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 200;
            box-shadow: 4px 0 24px rgba(26, 60, 110, 0.15);
            transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sb-brand {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .sb-logo {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(0,0,0,.1);
        }

        .sb-name {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
        }

        .sb-sub {
            font-size: 10px;
            color: rgba(255,255,255,.45);
            margin-top: 1px;
        }

        .sb-nav {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
        }

        .sb-nav::-webkit-scrollbar {
            width: 3px;
        }

        .sb-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,.12);
            border-radius: 10px;
        }

        .sb-sec {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255,255,255,.35);
            padding: 0 8px;
            margin: 14px 0 4px;
        }

        .sb-sec:first-child {
            margin-top: 4px;
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,.75);
            text-decoration: none;
            transition: all var(--transition-fast);
            margin-bottom: 2px;
            position: relative;
        }

        .sb-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
            transform: translateX(2px);
        }

        .sb-link.active {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary-dark);
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(232,160,32,.28);
        }

        .sb-icon {
            font-size: 14px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .sb-foot {
            padding: 12px 16px;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            gap: 9px;
            flex-shrink: 0;
        }

        .sb-av {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 12px;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(232,160,32,.22);
        }

        .sb-uname {
            font-size: 11.5px;
            font-weight: 700;
            color: #fff;
        }

        .sb-urole {
            font-size: 10px;
            color: rgba(255,255,255,.45);
            margin-top: 1px;
        }

        /* ── MAIN WRAPPER ── */
        .main-wrapper {
            margin-left: var(--sw);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: var(--th);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 26px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 16px rgba(26, 60, 110, 0.05);
            gap: 10px;
        }

        .tb-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
        }

        .tb-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--primary-dark);
            color: #fff;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            flex-shrink: 0;
        }

        .tb-page {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tb-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .tb-bell {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f8fbff;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            position: relative;
            transition: all var(--transition-fast);
        }

        .tb-bell:hover {
            background: var(--surface);
            box-shadow: 0 4px 16px rgba(26, 60, 110, 0.08);
            transform: translateY(-1px);
        }

        .tb-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red);
            border: 1.5px solid #fff;
        }

        .tb-divider {
            width: 1px;
            height: 24px;
            background: var(--border);
        }

        .tb-user {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .tb-av {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            color: #fff;
            box-shadow: 0 3px 10px rgba(232,160,32,.22);
            flex-shrink: 0;
        }

        .tb-uname {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .tb-urole {
            font-size: 10.5px;
            color: var(--text-muted);
        }

        /* ── PAGE CONTENT ── */
        .page-content {
            padding: 24px 28px 56px;
            flex: 1;
        }

        /* ── GLOBAL COMPONENTS ── */

        /* Cards */
        .card-soft {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            transition: all var(--transition-normal);
        }

        .card-soft:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-deep);
        }

        .card-header-custom {
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-title-custom {
            font-family: 'Poppins', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .fr-tag {
            font-size: 9px;
            font-weight: 700;
            background: var(--primary-dark);
            color: #fff;
            padding: 3px 7px;
            border-radius: 999px;
        }

        .card-link {
            font-size: 12px;
            font-weight: 600;
            color: var(--blue);
            text-decoration: none;
            transition: color var(--transition-fast);
        }

        .card-link:hover {
            color: var(--primary-dark);
        }

        /* Tables */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
        }

        .table-custom thead {
            background: #f8fafc;
        }

        .table-custom th {
            padding: 9px 16px;
            text-align: left;
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .table-custom td {
            padding: 12px 16px;
            font-size: 13px;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover td {
            background: #f8fbff;
        }

        .res-table {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 0 0 12px 12px;
        }

        .table-custom th {
            white-space: nowrap;
        }

        /* Badges */
        .badge-custom {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .badge-green  { background: var(--gbg); color: var(--green);  border: 1px solid var(--gb); }
        .badge-red    { background: var(--rbg); color: var(--red);    border: 1px solid var(--rb); }
        .badge-blue   { background: var(--bbg); color: var(--blue);   border: 1px solid var(--bb); }
        .badge-amber  { background: var(--abg); color: #b45309;       border: 1px solid var(--ab); }
        .badge-purple { background: var(--pbg); color: var(--purple); border: 1px solid var(--pbb); }

        /* Buttons */
        .btn-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all var(--transition-fast);
        }

        .btn-dark-custom {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary-dark);
            box-shadow: var(--shadow-accent);
        }

        .btn-dark-custom:hover {
            transform: translateY(-2px);
            color: var(--primary-dark);
        }

        .btn-outline-blue {
            background: var(--bbg);
            color: var(--blue);
            border: 1px solid var(--bb);
        }

        .btn-outline-blue:hover {
            background: var(--blue);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-red {
            background: var(--rbg);
            color: var(--red);
            border: 1px solid var(--rb);
        }

        .btn-outline-red:hover {
            background: var(--red);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-outline-green {
            background: var(--gbg);
            color: var(--green);
            border: 1px solid var(--gb);
        }

        .btn-outline-green:hover {
            background: var(--green);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-ghost-custom {
            background: #f8fafc;
            color: var(--text-muted);
            border: 1.5px solid var(--border);
        }

        .btn-ghost-custom:hover {
            background: var(--bg);
            color: var(--primary-dark);
        }

        /* Forms */
        .form-card-custom {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 26px;
            box-shadow: var(--shadow-soft);
        }

        .form-label-custom {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .form-control-custom {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            padding: 9px 13px;
            outline: none;
            color: var(--text-dark);
            transition: border-color var(--transition-fast), background var(--transition-fast), box-shadow var(--transition-fast);
        }

        .form-control-custom:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 60, 110, 0.08);
        }

        .form-actions-custom {
            display: flex;
            gap: 9px;
            justify-content: flex-end;
            padding-top: 16px;
            border-top: 1px solid var(--border);
            margin-top: 6px;
        }

        .warn-box-custom {
            background: var(--abg);
            border: 1px solid var(--ab);
            border-left: 3px solid var(--accent);
            border-radius: 10px;
            padding: 13px 15px;
            margin-bottom: 16px;
        }

        .warn-title {
            font-size: 12px;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 8px;
        }

        /* Alert flash */
        .flash-success {
            background: var(--gbg);
            border: 1px solid var(--gb);
            border-left: 3px solid var(--green);
            border-radius: 10px;
            padding: 11px 15px;
            font-size: 13px;
            color: #065f46;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Page title row */
        .page-title-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
        }

        .page-title {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: var(--text-dark);
        }

        .page-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--blue);
            text-decoration: none;
            margin-bottom: 14px;
            transition: color var(--transition-fast);
        }

        .back-link:hover {
            color: var(--primary-dark);
        }

        /* Grid cards */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 14px;
        }

        /* Input group */
        .input-row {
            display: flex;
            gap: 7px;
            margin-bottom: 8px;
        }

        .input-row input {
            flex: 1;
            background: #f8fafc;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 13px;
            padding: 8px 12px;
            outline: none;
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            transition: all var(--transition-fast);
        }

        .input-row input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 60, 110, 0.08);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(26, 60, 110, .16);
            border-radius: 10px;
        }

        /* ── HAMBURGER & MOBILE SIDEBAR ── */
        #adminToggleSidebar {
            display: none;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            font-size: 18px;
            background: #f8fbff;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            color: var(--primary-dark);
            flex-shrink: 0;
            transition: all var(--transition-fast);
        }

        #adminToggleSidebar:hover {
            background: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 4px 12px rgba(26, 60, 110, 0.08);
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 37, 72, 0.5);
            backdrop-filter: blur(2px);
            z-index: 150;
            opacity: 0;
            transition: opacity .3s ease;
        }

        #sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Responsive Utilities */
        .res-table {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        @media (max-width: 900px) {
            :root {
                --sw: 260px;
            }

            .sidebar {
                transform: translateX(-100%);
                z-index: 300;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }

            #adminToggleSidebar {
                display: inline-flex;
            }

            .topbar {
                padding: 0 16px;
            }

            .tb-uname,
            .tb-urole,
            .tb-divider,
            .tb-bell {
                display: none;
            }

            .tb-left {
                gap: 12px;
            }

            .tb-page {
                font-size: 12px;
            }

            .page-content {
                padding: 16px 14px 40px;
            }

            .page-title {
                font-size: 18px;
            }

            .card-header-custom {
                padding: 12px 16px;
            }

            .form-card-custom {
                padding: 20px 16px;
            }
            
            .form-actions-custom {
                flex-direction: column-reverse;
            }
            
            .form-actions-custom .btn-custom {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Overlay untuk menutup sidebar saat klik di luar -->
<div id="sidebar-overlay"></div>

<!-- ════════════════ SIDEBAR ════════════════ -->
<aside class="sidebar">
    <div class="sb-brand">
        <div class="sb-logo">
            <img src="{{ asset('Assets/Logo_SMKN2Jember.png') }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
        </div>
        <div>
            <div class="sb-name">SPK SAW</div>
            <div class="sb-sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-sec">Utama</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sb-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="sb-icon">🏠</span> Dashboard
        </a>
        <a href="{{ route('landing.home') }}"
           class="sb-link {{ request()->routeIs('landing.home') ? 'active' : '' }}">
            <span class="sb-icon">🌐</span> Landing Page
        </a>

        <div class="sb-sec">Kelola Akun</div>
        <a href="{{ route('admin.gurubk.index') }}"
           class="sb-link {{ request()->routeIs('admin.gurubk.*') ? 'active' : '' }}">
            <span class="sb-icon">👩‍🏫</span> Akun Guru BK
        </a>
        <a href="{{ route('admin.siswa.index') }}"
           class="sb-link {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
            <span class="sb-icon">👨‍🎓</span> Akun Siswa
        </a>

        <div class="sb-sec">Kelola Jurusan</div>
        <a href="{{ route('admin.jurusan.index') }}"
           class="sb-link {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
            <span class="sb-icon">🏫</span> Data & Status Jurusan
        </a>
        <a href="{{ route('admin.penyakit.index') }}"
           class="sb-link {{ request()->routeIs('admin.penyakit.*') ? 'active' : '' }}">
            <span class="sb-icon">⚕</span> Data Penyakit
        </a>

        <div class="sb-sec">Monitoring</div>
        <a href="{{ route('admin.monitoring.index') }}"
           class="sb-link {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
            <span class="sb-icon">📊</span> Statistik & Log
        </a>
    </nav>

    <div class="sb-foot">
        <div class="sb-av">{{ strtoupper(substr(Auth::user()->nama ?? 'A', 0, 1)) }}</div>
        <div style="flex:1;min-width:0;">
            <div class="sb-uname" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ Auth::user()->nama ?? 'Administrator' }}
            </div>
            <div class="sb-urole">Super Admin</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Logout"
                style="background:none;border:none;color:rgba(255,255,255,.45);font-size:17px;cursor:pointer;padding:4px;transition:color .2s ease;"
                onmouseover="this.style.color='#fca5a5'"
                onmouseout="this.style.color='rgba(255,255,255,.45)'">⏻</button>
        </form>
    </div>
</aside>

<!-- ════════════════ MAIN ════════════════ -->
<div class="main-wrapper">

    <!-- TOPBAR -->
    <header class="topbar">
        <div class="tb-left">
            <button id="adminToggleSidebar" aria-label="Toggle Sidebar">☰</button>
            <div class="tb-badge">⚙️ Admin</div>
            <div class="tb-page">@yield('title', 'Dashboard')</div>
        </div>
        <div class="tb-right">
            <div class="tb-divider"></div>
            <div class="tb-user">
                <div class="tb-av">{{ strtoupper(substr(Auth::user()->nama ?? 'A', 0, 1)) }}</div>
                <div>
                    <div class="tb-uname">{{ Auth::user()->nama ?? 'Administrator' }}</div>
                    <div class="tb-urole">Super Admin</div>
                </div>
            </div>
        </div>
    </header>

    <!-- PAGE CONTENT -->
    <main class="page-content">
        @yield('content')
    </main>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const btn     = document.getElementById('adminToggleSidebar');
    const overlay = document.getElementById('sidebar-overlay');

    btn.addEventListener('click', function () {
        sidebar.classList.toggle('open');
        overlay.classList.toggle('show');
    });

    overlay.addEventListener('click', function () {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }
    });
});
</script>

@stack('scripts')
</body>
</html>
