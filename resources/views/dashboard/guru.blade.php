@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Status Absensi Hari Ini</div>
        @if(!$todayAttendance)
            <div class="widget-value" style="color: var(--color-warning);">Belum Absen</div>
        @elseif($todayAttendance->status === 'pending')
            <div class="widget-value" style="color: var(--color-warning);">Menunggu Validasi</div>
        @elseif($todayAttendance->status === 'approved')
            <div class="widget-value" style="color: var(--color-success);">Hadir (Disetujui)</div>
        @elseif($todayAttendance->status === 'rejected')
            <div class="widget-value" style="color: var(--color-danger);">Absen Ditolak</div>
        @endif
    </div>
    <div class="widget-card">
        <div class="widget-title">Waktu Presensi</div>
        <div class="widget-value" id="scan-time-value">
            {{ $todayAttendance ? \Carbon\Carbon::parse($todayAttendance->scan_time)->format('H:i:s') . ' WIB' : '-- : --' }}
        </div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Total Kehadiran Bulan Ini</div>
        <div class="widget-value" style="color: var(--color-success);">{{ $monthlyAttendanceCount }} Hari</div>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        Pindai QR Code Absensi
    </div>
    
    @if(!$todayAttendance)
        <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6; font-size: 0.95rem;">
            Arahkan kamera perangkat Anda ke QR Code yang ditampilkan oleh **Admin TU** untuk melakukan perekaman kehadiran hari ini. Pastikan Anda mengizinkan akses kamera dan lokasi (GPS) pada perangkat Anda.
        </p>
        
        <div class="qr-mock-container" style="background: rgba(255, 255, 255, 0.02); border: 2px dashed rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 2rem;">
            <!-- Custom Alert Notification inside Scanner area -->
            <div id="scanner-alert" class="alert" style="display: none; width: 100%; max-width: 320px; margin-bottom: 1rem;"></div>

            <!-- Loader / Processing Spinner -->
            <div id="scanner-loader" style="display: none; color: var(--text-primary); text-align: center; margin-bottom: 1rem;">
                <div class="spinner" style="border: 3px solid rgba(255,255,255,0.1); border-top: 3px solid var(--color-accent); border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; margin: 0 auto 0.5rem auto;"></div>
                <span>Memproses absensi...</span>
            </div>

            <!-- Real camera reader container -->
            <div id="reader-container" style="display: none; position: relative; margin-bottom: 1.5rem; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-premium);">
                <div id="reader"></div>
                <div class="scanner-laser" id="laser-line"></div>
            </div>

            <!-- Static placeholder vector when camera is closed -->
            <div id="scanner-placeholder" style="margin-bottom: 1.5rem;">
                <svg width="120" height="120" viewBox="0 0 100 100" fill="none" stroke="rgba(255, 255, 255, 0.15)" stroke-width="3" style="animation: pulse-slow 3s infinite;">
                    <rect x="5" y="5" width="25" height="25" rx="3" stroke-width="4"/>
                    <rect x="12" y="12" width="11" height="11" fill="rgba(255, 255, 255, 0.1)"/>
                    <rect x="70" y="5" width="25" height="25" rx="3" stroke-width="4"/>
                    <rect x="77" y="12" width="11" height="11" fill="rgba(255, 255, 255, 0.1)"/>
                    <rect x="5" y="70" width="25" height="25" rx="3" stroke-width="4"/>
                    <rect x="12" y="77" width="11" height="11" fill="rgba(255, 255, 255, 0.1)"/>
                    <path d="M40 20 h10 M50 20 v15 M75 45 h10 M45 45 v10 M20 45 h20 M45 75 h15 M75 75 h10" stroke-linecap="round"/>
                </svg>
            </div>

            <div style="display: flex; gap: 1rem; width: 100%; justify-content: center; max-width: 320px;">
                <button id="btn-start-scanner" class="btn btn-primary">Buka Kamera & Scan QR</button>
                <button id="btn-stop-scanner" class="btn btn-danger" style="display: none;">Tutup Kamera</button>
            </div>
            
            <div class="qr-time" id="scanner-location-status">Presensi memerlukan koordinat GPS Anda.</div>
        </div>
    @else
        <div class="qr-mock-container" style="background: rgba(16, 185, 129, 0.05); border: 2px solid rgba(16, 185, 129, 0.15); border-radius: 20px; padding: 2.5rem; text-align: center;">
            <svg width="60" height="60" fill="none" stroke="var(--color-success)" stroke-width="2.5" viewBox="0 0 24 24" style="margin-bottom: 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Absensi Hari Ini Terekam</h3>
            <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
                Anda telah memindai QR Code kehadiran pada pukul <strong>{{ \Carbon\Carbon::parse($todayAttendance->scan_time)->format('H:i') }} WIB</strong>.
                @if($todayAttendance->status === 'pending')
                    Status Anda saat ini adalah <strong style="color: var(--color-warning);">Menunggu Validasi</strong> dari petugas piket.
                @elseif($todayAttendance->status === 'approved')
                    Status kehadiran Anda telah <strong style="color: var(--color-success);">Disetujui</strong>.
                @elseif($todayAttendance->status === 'rejected')
                    Status kehadiran Anda <strong style="color: var(--color-danger);">Ditolak</strong>. Catatan: {{ $todayAttendance->notes ?? '-' }}
                @endif
            </p>
        </div>
    @endif
</div>

<!-- Load html5-qrcode Library via CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

@if(!$todayAttendance)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnStart = document.getElementById('btn-start-scanner');
    const btnStop = document.getElementById('btn-stop-scanner');
    const placeholder = document.getElementById('scanner-placeholder');
    const readerContainer = document.getElementById('reader-container');
    const alertBox = document.getElementById('scanner-alert');
    const loader = document.getElementById('scanner-loader');
    const locationStatus = document.getElementById('scanner-location-status');

    let html5QrCode = null;
    let latitude = null;
    let longitude = null;

    // Show message inside scanner alert
    function showAlert(message, type = 'error') {
        alertBox.innerText = message;
        alertBox.style.display = 'flex';
        if (type === 'success') {
            alertBox.style.background = 'rgba(16, 185, 129, 0.1)';
            alertBox.style.borderColor = 'rgba(16, 185, 129, 0.2)';
            alertBox.style.color = '#34d399';
        } else {
            alertBox.style.background = 'rgba(239, 68, 68, 0.1)';
            alertBox.style.borderColor = 'rgba(239, 68, 68, 0.2)';
            alertBox.style.color = '#f87171';
        }
    }

    // Hide scanner alert
    function hideAlert() {
        alertBox.style.display = 'none';
    }

    // Start QR scanner function
    function startScanner() {
        hideAlert();
        placeholder.style.display = 'none';
        readerContainer.style.display = 'block';
        btnStart.style.display = 'none';
        btnStop.style.display = 'block';

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        const config = { 
            fps: 15, 
            qrbox: { width: 220, height: 220 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" }, 
            config, 
            onScanSuccess
        )
        .then(() => {
            locationStatus.innerText = `Kamera aktif | GPS Lokasi: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
            locationStatus.style.color = "var(--color-success)";
        })
        .catch(err => {
            console.error("Gagal membuka kamera:", err);
            showAlert("Gagal mengakses kamera perangkat Anda. Pastikan izin kamera telah diberikan.");
            stopScanner();
        });
    }

    // Stop QR scanner function
    function stopScanner() {
        btnStop.style.display = 'none';
        btnStart.style.display = 'block';
        readerContainer.style.display = 'none';
        placeholder.style.display = 'block';
        locationStatus.innerText = "Gunakan kamera atau klik tombol di atas untuk scan.";
        locationStatus.style.color = "var(--text-secondary)";

        if (html5QrCode && html5QrCode.isScanning) {
            html5QrCode.stop().catch(err => console.error("Gagal stop camera:", err));
        }
    }

    // Triggered when QR code is successfully scanned
    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning immediately to prevent duplicate sends
        stopScanner();
        
        // Show loader
        loader.style.display = 'block';

        // Send scan to server via AJAX
        fetch("{{ route('guru.scan-qr') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                token: decodedText,
                latitude: latitude,
                longitude: longitude
            })
        })
        .then(res => res.json())
        .then(data => {
            loader.style.display = 'none';
            if (data.success) {
                showAlert(data.message, 'success');
                // Play notification flash & sound simulation
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert(data.message || "Gagal memproses absensi.");
            }
        })
        .catch(err => {
            loader.style.display = 'none';
            console.error(err);
            showAlert("Terjadi kesalahan jaringan saat mengirimkan absensi.");
        });
    }

    // Button event listener for starting scanner
    btnStart.addEventListener('click', function() {
        locationStatus.innerText = "Meminta akses GPS...";
        
        // Request Geolocation first
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    startScanner();
                },
                function(error) {
                    console.warn("Akses GPS ditolak, menggunakan mock lokasi untuk keperluan demo.");
                    // Fallback to mock school coords (e.g., Jakarta)
                    latitude = -6.2088;
                    longitude = 106.8456;
                    startScanner();
                },
                { enableHighAccuracy: true, timeout: 5000 }
            );
        } else {
            showAlert("Browser Anda tidak mendukung deteksi lokasi (GPS).");
            latitude = -6.2088;
            longitude = 106.8456;
            startScanner();
        }
    });

    // Button event listener for stopping scanner
    btnStop.addEventListener('click', stopScanner);
});
</script>
@endif
@endsection
