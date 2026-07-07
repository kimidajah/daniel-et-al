<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - DiAbsen+</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                <div class="header-date" style="font-size: 0.9rem; color: var(--text-secondary);">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
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
    });
    </script>
</body>
</html>
