@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Status Absensi Hari Ini</div>
        @if(!$todayAttendance)
            <div class="widget-value" style="color: var(--color-warning);">Belum Absen</div>
        @elseif($todayAttendance->attendance_type === 'hadir')
            @if($todayAttendance->status === 'pending')
                <div class="widget-value" style="color: var(--color-warning);">Menunggu Validasi</div>
            @elseif($todayAttendance->status === 'approved')
                <div class="widget-value" style="color: var(--color-success);">Hadir (Disetujui)</div>
            @elseif($todayAttendance->status === 'rejected')
                <div class="widget-value" style="color: var(--color-danger);">Absen Ditolak</div>
            @endif
        @elseif($todayAttendance->attendance_type === 'sakit')
            @if($todayAttendance->status === 'pending')
                <div class="widget-value" style="color: var(--color-warning);">Sakit (Pending)</div>
            @elseif($todayAttendance->status === 'approved')
                <div class="widget-value" style="color: var(--color-success);">Sakit (Disetujui)</div>
            @elseif($todayAttendance->status === 'rejected')
                <div class="widget-value" style="color: var(--color-danger);">Sakit Ditolak</div>
            @endif
        @elseif($todayAttendance->attendance_type === 'izin')
            @if($todayAttendance->status === 'pending')
                <div class="widget-value" style="color: var(--color-warning);">Izin (Pending)</div>
            @elseif($todayAttendance->status === 'approved')
                <div class="widget-value" style="color: var(--color-success);">Izin (Disetujui)</div>
            @elseif($todayAttendance->status === 'rejected')
                <div class="widget-value" style="color: var(--color-danger);">Izin Ditolak</div>
            @endif
        @elseif($todayAttendance->attendance_type === 'alfa')
            <div class="widget-value" style="color: var(--color-danger);">Alpa (Tidak Hadir)</div>
        @endif
    </div>
    <div class="widget-card">
        <div class="widget-title">Waktu Presensi</div>
        <div class="widget-value" id="scan-time-value">
            @if(!$todayAttendance)
                -- : --
            @elseif($todayAttendance->attendance_type === 'hadir')
                {{ \Carbon\Carbon::parse($todayAttendance->scan_time)->format('H:i:s') }} WIB
            @elseif($todayAttendance->attendance_type === 'sakit')
                Sakit
            @elseif($todayAttendance->attendance_type === 'izin')
                Izin
            @elseif($todayAttendance->attendance_type === 'alfa')
                Alpa
            @endif
        </div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Total Kehadiran Bulan Ini</div>
        <div class="widget-value" style="color: var(--color-success);">{{ $monthlyAttendanceCount }} Hari</div>
    </div>
</div>

@if(!$todayAttendance || $todayAttendance->attendance_type === 'alfa')
    @if($todayAttendance && $todayAttendance->attendance_type === 'alfa')
        <div style="grid-column: 1 / -1; background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.18); color: #f87171; padding: 1rem; border-radius: 16px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <strong>Sistem: Status Alpa Otomatis Aktif.</strong> Anda ditandai Alpa karena belum melakukan presensi hari ini. Silakan pindai QR Code di bawah untuk masuk kerja dan memperbarui kehadiran.
            </div>
        </div>
    @endif
    <div class="form-row">
        <!-- Card Scan QR -->
        <div class="section-card" style="margin-bottom: 0;">
            <div class="section-title">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                Pindai QR Code Absensi
            </div>
            
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6; font-size: 0.9rem;">
                Arahkan kamera HP Anda ke QR Code yang ditampilkan oleh **Admin TU** untuk merekam kehadiran hari ini. Memerlukan izin Kamera & GPS.
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
                    <svg width="100" height="100" viewBox="0 0 100 100" fill="none" stroke="rgba(255, 255, 255, 0.15)" stroke-width="3" style="animation: pulse-slow 3s infinite;">
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
        </div>

        <!-- Card Sakit / Izin -->
        <div class="section-card" style="margin-bottom: 0;">
            <div class="section-title">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Pengajuan Sakit / Izin
            </div>
            
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6; font-size: 0.9rem;">
                Apabila Anda berhalangan hadir karena sakit atau kepentingan darurat lainnya, silakan ajukan permohonan di bawah ini.
            </p>

            <div id="request-alert" class="alert" style="display: none; margin-bottom: 1.5rem;"></div>

            <form id="form-izin-sakit">
                <div class="form-group">
                    <label class="form-label">Jenis Pengajuan</label>
                    <select class="form-control" id="request-type" required style="background: var(--bg-primary); color: var(--text-primary);">
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin (Perlu Alasan)</option>
                    </select>
                </div>
                
                <div class="form-group" id="group-alasan" style="display: none;">
                    <label class="form-label">Alasan Izin</label>
                    <textarea class="form-control" id="request-notes" placeholder="Tuliskan alasan permohonan izin Anda..." rows="4" style="resize: none;"></textarea>
                </div>

                <button type="submit" id="btn-submit-request" class="btn btn-primary">Kirim Permohonan</button>
            </form>
        </div>
    </div>
@else
    <div class="section-card">
        <div class="section-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Status Rekaman Kehadiran Hari Ini
        </div>
        
        @if($todayAttendance->attendance_type === 'hadir')
            <div class="qr-mock-container" style="background: rgba(16, 185, 129, 0.05); border: 2px solid rgba(16, 185, 129, 0.15); border-radius: 20px; padding: 2.5rem; text-align: center;">
                <svg width="60" height="60" fill="none" stroke="var(--color-success)" stroke-width="2.5" viewBox="0 0 24 24" style="margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Presensi Kehadiran Sukses</h3>
                <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
                    Anda telah memindai QR Code TU pada pukul <strong>{{ \Carbon\Carbon::parse($todayAttendance->scan_time)->format('H:i') }} WIB</strong>.
                    @if($todayAttendance->status === 'pending')
                        Status Anda saat ini adalah <strong style="color: var(--color-warning);">Menunggu Validasi</strong> dari petugas piket.
                    @elseif($todayAttendance->status === 'approved')
                        Status kehadiran Anda telah <strong style="color: var(--color-success);">Disetujui</strong>.
                    @elseif($todayAttendance->status === 'rejected')
                        Status kehadiran Anda <strong style="color: var(--color-danger);">Ditolak</strong>. Catatan: {{ $todayAttendance->notes ?? '-' }}
                    @endif
                </p>
            </div>
        @elseif($todayAttendance->attendance_type === 'sakit')
            <div class="qr-mock-container" style="background: rgba(245, 158, 11, 0.05); border: 2px solid rgba(245, 158, 11, 0.15); border-radius: 20px; padding: 2.5rem; text-align: center;">
                <svg width="60" height="60" fill="none" stroke="var(--color-warning)" stroke-width="2.5" viewBox="0 0 24 24" style="margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Permohonan Sakit Dikirim</h3>
                <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
                    Anda mengajukan keterangan Sakit pada pukul <strong>{{ \Carbon\Carbon::parse($todayAttendance->scan_time)->format('H:i') }} WIB</strong>.
                    @if($todayAttendance->status === 'pending')
                        Status: <strong style="color: var(--color-warning);">Menunggu Validasi</strong> oleh piket.
                    @elseif($todayAttendance->status === 'approved')
                        Status: <strong style="color: var(--color-success);">Sakit (Disetujui)</strong>.
                    @elseif($todayAttendance->status === 'rejected')
                        Status: <strong style="color: var(--color-danger);">Ditolak</strong>.
                    @endif
                </p>
            </div>
        @elseif($todayAttendance->attendance_type === 'izin')
            <div class="qr-mock-container" style="background: rgba(59, 130, 246, 0.05); border: 2px solid rgba(59, 130, 246, 0.15); border-radius: 20px; padding: 2.5rem; text-align: center;">
                <svg width="60" height="60" fill="none" stroke="var(--color-accent)" stroke-width="2.5" viewBox="0 0 24 24" style="margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">Permohonan Izin Dikirim</h3>
                <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
                    Anda mengajukan keterangan Izin pada pukul <strong>{{ \Carbon\Carbon::parse($todayAttendance->scan_time)->format('H:i') }} WIB</strong>.<br>
                    Alasan: <em>"{{ $todayAttendance->notes }}"</em><br>
                    @if($todayAttendance->status === 'pending')
                        Status: <strong style="color: var(--color-warning);">Menunggu Validasi</strong> oleh piket.
                    @elseif($todayAttendance->status === 'approved')
                        Status: <strong style="color: var(--color-success);">Izin (Disetujui)</strong>.
                    @elseif($todayAttendance->status === 'rejected')
                        Status: <strong style="color: var(--color-danger);">Ditolak</strong>.
                    @endif
                </p>
            </div>
        @elseif($todayAttendance->attendance_type === 'alfa')
            <div class="qr-mock-container" style="background: rgba(239, 68, 68, 0.05); border: 2px solid rgba(239, 68, 68, 0.15); border-radius: 20px; padding: 2.5rem; text-align: center;">
                <svg width="60" height="60" fill="none" stroke="var(--color-danger)" stroke-width="2.5" viewBox="0 0 24 24" style="margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 style="color: var(--color-danger); margin-bottom: 0.5rem;">Status Kehadiran: ALPA</h3>
                <p style="color: var(--text-secondary); max-width: 480px; margin: 0 auto; font-size: 0.95rem; line-height: 1.6;">
                    Anda ditandai **Alpa** otomatis oleh sistem karena tidak melakukan presensi kehadiran atau mengajukan izin/sakit melewati batas jam 08:00 WIB pagi ini.
                </p>
            </div>
        @endif
    </div>
@endif

<!-- Load html5-qrcode Library via CDN -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

@if(!$todayAttendance || $todayAttendance->attendance_type === 'alfa')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnStart = document.getElementById('btn-start-scanner');
    const btnStop = document.getElementById('btn-stop-scanner');
    const placeholder = document.getElementById('scanner-placeholder');
    const readerContainer = document.getElementById('reader-container');
    const alertBox = document.getElementById('scanner-alert');
    const loader = document.getElementById('scanner-loader');
    const locationStatus = document.getElementById('scanner-location-status');

    // Sakit/Izin Form elements
    const formRequest = document.getElementById('form-izin-sakit');
    const requestType = document.getElementById('request-type');
    const groupAlasan = document.getElementById('group-alasan');
    const requestNotes = document.getElementById('request-notes');
    const btnSubmitRequest = document.getElementById('btn-submit-request');
    const requestAlert = document.getElementById('request-alert');

    let html5QrCode = null;
    let latitude = null;
    let longitude = null;

    // Toggle Alasan Textarea dynamically based on leave type
    requestType.addEventListener('change', function() {
        if (this.value === 'izin') {
            groupAlasan.style.display = 'block';
            requestNotes.required = true;
        } else {
            groupAlasan.style.display = 'none';
            requestNotes.required = false;
            requestNotes.value = "";
        }
    });

    // Show dynamic notification for leave requests
    function showRequestNotification(message, type = 'success') {
        requestAlert.innerText = message;
        requestAlert.style.display = 'block';
        if (type === 'success') {
            requestAlert.style.background = 'rgba(16, 185, 129, 0.1)';
            requestAlert.style.borderColor = 'rgba(16, 185, 129, 0.2)';
            requestAlert.style.color = '#34d399';
        } else {
            requestAlert.style.background = 'rgba(239, 68, 68, 0.1)';
            requestAlert.style.borderColor = 'rgba(239, 68, 68, 0.2)';
            requestAlert.style.color = '#f87171';
        }
    }

    // Submit Sakit / Izin
    formRequest.addEventListener('submit', function(e) {
        e.preventDefault();

        const type = requestType.value;
        const notes = requestNotes.value;

        btnSubmitRequest.disabled = true;
        btnSubmitRequest.innerText = "Mengirim...";

        fetch("{{ route('guru.izin-sakit') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                type: type,
                notes: notes
            })
        })
        .then(res => res.json())
        .then(data => {
            btnSubmitRequest.disabled = false;
            btnSubmitRequest.innerText = "Kirim Permohonan";

            if (data.success || data.message.includes('berhasil')) {
                showRequestNotification(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showRequestNotification(data.message || "Gagal mengirim pengajuan.", 'error');
            }
        })
        .catch(err => {
            btnSubmitRequest.disabled = false;
            btnSubmitRequest.innerText = "Kirim Permohonan";
            console.error(err);
            showRequestNotification("Terjadi kesalahan koneksi jaringan.", 'error');
        });
    });

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
        stopScanner();
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
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    startScanner();
                },
                function(error) {
                    console.warn("Akses GPS ditolak, menggunakan mock lokasi untuk keperluan demo.");
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

    btnStop.addEventListener('click', stopScanner);
});
</script>
@endif
@endsection
