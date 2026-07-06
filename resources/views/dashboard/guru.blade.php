@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Status Absensi Hari Ini</div>
        <div class="widget-value" style="color: var(--color-warning);">Belum Absen</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Waktu Presensi</div>
        <div class="widget-value">-- : --</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Total Kehadiran Bulan Ini</div>
        <div class="widget-value" style="color: var(--color-success);">18 Hari</div>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        Pindai QR Code
    </div>
    <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6;">
        Arahkan kamera perangkat Anda ke QR Code yang disediakan oleh Admin Piket untuk melakukan perekaman kehadiran hari ini.
    </p>
    
    <div class="qr-mock-container">
        <div class="qr-box">
            <!-- Simulated QR Code placeholder with simple SVG/Design -->
            <svg width="100%" height="100%" viewBox="0 0 100 100" fill="none" stroke="#111827" stroke-width="4">
                <rect x="5" y="5" width="25" height="25" rx="3" stroke-width="6"/>
                <rect x="12" y="12" width="11" height="11" fill="#111827"/>
                <rect x="70" y="5" width="25" height="25" rx="3" stroke-width="6"/>
                <rect x="77" y="12" width="11" height="11" fill="#111827"/>
                <rect x="5" y="70" width="25" height="25" rx="3" stroke-width="6"/>
                <rect x="12" y="77" width="11" height="11" fill="#111827"/>
                <!-- Some random QR blocks -->
                <rect x="40" y="15" width="15" height="5" fill="#111827"/>
                <rect x="50" y="25" width="5" height="15" fill="#111827"/>
                <rect x="45" y="45" width="10" height="10" fill="#111827"/>
                <rect x="15" y="45" width="10" height="5" fill="#111827"/>
                <rect x="75" y="45" width="10" height="15" fill="#111827"/>
                <rect x="45" y="75" width="15" height="10" fill="#111827"/>
                <rect x="75" y="75" width="10" height="10" fill="#111827"/>
            </svg>
        </div>
        <button class="btn btn-primary" style="max-width: 250px;">Simulasikan Scan QR</button>
        <div class="qr-time">Gunakan kamera atau klik tombol di atas untuk menyimulasikan pemindaian.</div>
    </div>
</div>
@endsection
