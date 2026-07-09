<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DiAbsen+</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script>
        // Apply theme early to prevent FOUC (flash of unstyled content)
        if (localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.add('light-theme');
        } else {
            document.documentElement.classList.remove('light-theme');
        }
    </script>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="bg-glow-secondary"></div>

    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                DiAbsen<span style="color: #60a5fa;">+</span>
            </div>

            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="user-info">
                    <div class="user-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</div>
                    <div class="user-role">
                        @switch(Auth::user()->role)
                            @case('guru')
                                Guru
                                @break
                            @case('piket')
                                Admin Piket
                                @break
                            @case('tu')
                                Tata Usaha
                                @break
                            @case('kepala_sekolah')
                                Kepala Sekolah
                                @break
                            @default
                                {{ Auth::user()->role }}
                        @endswitch
                    </div>
                </div>
            </div>

            <nav class="nav-list">
                <!-- Dashboard / Home (Semua Aktor) -->
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link active">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                </li>

                <!-- Fitur Khusus Guru: Scan QR -->
                @if(Auth::user()->role === 'guru')
                <li>
                    <a href="#" class="nav-link">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Scan QR Kehadiran
                    </a>
                </li>
                @endif

                <!-- Fitur Khusus Piket: Validasi QR -->
                @if(Auth::user()->role === 'piket')
                <li>
                    <a href="#" class="nav-link">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Validasi QR Absen
                    </a>
                </li>
                @endif

                <!-- Fitur Khusus TU: Kelola Guru -->
                @if(Auth::user()->role === 'tu')
                <li>
                    <a href="#" class="nav-link">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Kelola Data Guru
                    </a>
                </li>
                @endif

                <!-- Fitur Piket, TU, Kepala Sekolah: Lihat Laporan -->
                @if(in_array(Auth::user()->role, ['piket', 'tu', 'kepala_sekolah']))
                <li>
                    <a href="#" class="nav-link">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Lihat Laporan Absen
                    </a>
                </li>
                @endif

                <!-- Logout -->
                <li class="nav-logout">
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin keluar?')">
                        @csrf
                        <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </li>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <button id="sidebar-toggle" class="sidebar-toggle-btn" aria-label="Toggle Sidebar" style="background: none; border: none; color: var(--text-primary); cursor: pointer; display: none; padding: 0.5rem; border-radius: 8px;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="page-title">@yield('page_title', 'Dashboard')</div>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle Theme" style="background: none; border: none; color: var(--text-primary); cursor: pointer; padding: 0.5rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: var(--bg-input); border: 1px solid var(--border-color); width: 36px; height: 36px; transition: all 0.2s;">
                        <!-- Sun Icon (shown in dark theme) -->
                        <svg id="theme-toggle-light-icon" class="theme-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        <!-- Moon Icon (shown in light theme) -->
                        <svg id="theme-toggle-dark-icon" class="theme-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                    <div class="header-date" style="font-size: 0.9rem; color: var(--text-secondary);">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                </div>
            </header>

            <div class="content-body">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Toggle Sidebar JS for Mobile -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (toggleBtn && sidebar) {
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
            
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
            
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });
            
            // Close when clicking nav links
            const navLinks = sidebar.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            });
        }

        // Theme Toggle Functionality
        const themeToggleBtn = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');

        function updateIcons() {
            if (themeToggleBtn && lightIcon && darkIcon) {
                if (document.documentElement.classList.contains('light-theme')) {
                    lightIcon.style.display = 'none';
                    darkIcon.style.display = 'block';
                } else {
                    lightIcon.style.display = 'block';
                    darkIcon.style.display = 'none';
                }
            }
        }

        // Initialize icons on load
        updateIcons();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                if (document.documentElement.classList.contains('light-theme')) {
                    document.documentElement.classList.remove('light-theme');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.add('light-theme');
                    localStorage.setItem('theme', 'light');
                }
                updateIcons();
            });
        }
    });
    </script>
</body>
</html>
