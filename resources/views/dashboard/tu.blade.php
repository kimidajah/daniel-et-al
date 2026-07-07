@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha')
@section('page_title', 'Dashboard Tata Usaha')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Total Guru Terdaftar</div>
        <div class="widget-value" style="color: var(--color-accent);">{{ $totalTeachers }} Guru</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Guru Hadir (Hari Ini)</div>
        <div class="widget-value" style="color: var(--color-success);">{{ $presentToday }} Guru</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Waktu Server Hari Ini</div>
        <div class="widget-value" style="color: var(--color-warning);" id="server-clock">{{ now()->format('H:i:s') }}</div>
    </div>
</div>

<!-- QR Generator Section -->
<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        QR Code Absensi Hari Ini
    </div>
    
    <div style="display: flex; gap: 2.5rem; align-items: center; flex-wrap: wrap;">
        <div class="qr-mock-container" style="margin-top: 0; padding: 1.5rem; width: 240px; height: 240px; display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.02); border: 2px dashed rgba(255, 255, 255, 0.1); border-radius: 20px;">
            <div id="qr-placeholder" style="color: var(--text-secondary); text-align: center; font-size: 0.9rem; padding: 1rem;">
                Belum ada QR Code aktif.<br>Klik tombol untuk membuat.
            </div>
            <canvas id="qr-canvas" style="display: none; width: 200px; height: 200px; padding: 8px; background: white; border-radius: 12px; box-shadow: var(--shadow-premium);"></canvas>
        </div>
        <div style="flex: 1; min-width: 280px;">
            <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">Generate QR Code Presensi</h3>
            <p style="color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.6; font-size: 0.95rem;">
                QR Code yang digenerate akan aktif selama <strong>15 menit</strong>. 
                Tunjukkan QR Code ini di layar Anda agar guru dapat memindai menggunakan kamera ponsel mereka untuk melakukan absensi kehadiran.
            </p>
            <div id="qr-timer-container" style="display: none; margin-bottom: 1.5rem; background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.1); padding: 0.75rem 1rem; border-radius: 12px; max-width: 250px;">
                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 0.25rem; text-transform: uppercase; letter-spacing: 0.05em;">Sisa Waktu QR Code:</div>
                <div id="qr-timer" style="font-size: 1.6rem; font-weight: 800; color: var(--color-warning);">15:00</div>
            </div>
            <button id="btn-generate-qr" class="btn btn-primary" style="max-width: 250px;">Generate QR Code Baru</button>
        </div>
    </div>
</div>

<div class="form-row">
    <!-- Form Kelola Guru (Tambah Guru) -->
    <div class="section-card">
        <div class="section-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Guru Baru
        </div>
        <form action="#" method="POST" onsubmit="alert('Data Guru disimulasikan berhasil disimpan.'); return false;">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" placeholder="Contoh: Dr. H. Ahmad Fauzi, M.Pd" required>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" class="form-control" placeholder="Contoh: ahmad@sekolah.sch.id" required>
            </div>
            <div class="form-group">
                <label class="form-label">Mata Pelajaran / Bidang</label>
                <input type="text" class="form-control" placeholder="Contoh: Matematika" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Data Guru</button>
        </form>
    </div>

    <!-- Daftar Guru Terdaftar -->
    <div class="section-card">
        <div class="section-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Daftar Guru Terdaftar
        </div>
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Bidang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr>
                        <td style="font-weight: 600;">{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->teacherProfile->subject ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--text-secondary);">Belum ada data guru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Load QRious Library via CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerate = document.getElementById('btn-generate-qr');
    const qrPlaceholder = document.getElementById('qr-placeholder');
    const qrCanvas = document.getElementById('qr-canvas');
    const timerContainer = document.getElementById('qr-timer-container');
    const timerVal = document.getElementById('qr-timer');
    const serverClock = document.getElementById('server-clock');

    let countdownInterval = null;
    let qrGenerator = null;

    // Server Real-time Clock
    setInterval(() => {
        const now = new Date();
        serverClock.innerText = now.toTimeString().split(' ')[0];
    }, 1000);

    // Initialize QR Code generator instance
    function initQR(text) {
        if (!qrGenerator) {
            qrGenerator = new QRious({
                element: qrCanvas,
                size: 200,
                value: text,
                level: 'H'
            });
        } else {
            qrGenerator.value = text;
        }
        qrPlaceholder.style.display = 'none';
        qrCanvas.style.display = 'block';
    }

    // Function to check active QR
    function checkActiveQR() {
        fetch("{{ route('tu.active-qr') }}")
            .then(res => res.json())
            .then(data => {
                if (data.active) {
                    initQR(data.token);
                    startCountdown(data.expires_in_seconds);
                }
            })
            .catch(err => console.error("Gagal memuat QR aktif:", err));
    }

    // Start Countdown UI
    function startCountdown(seconds) {
        clearInterval(countdownInterval);
        timerContainer.style.display = 'block';

        let remaining = seconds;
        updateTimerText(remaining);

        countdownInterval = setInterval(() => {
            remaining--;
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                timerVal.innerText = "EXPIRED";
                timerVal.style.color = "var(--color-danger)";
                qrCanvas.style.opacity = "0.25";
                alert("QR Code telah kedaluwarsa. Silakan generate yang baru.");
            } else {
                updateTimerText(remaining);
            }
        }, 1000);
    }

    function updateTimerText(sec) {
        const mins = Math.floor(sec / 60);
        const secs = sec % 60;
        timerVal.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        timerVal.style.color = sec < 60 ? "var(--color-danger)" : "var(--color-warning)";
        qrCanvas.style.opacity = "1";
    }

    // Event listener generate QR
    btnGenerate.addEventListener('click', function() {
        btnGenerate.disabled = true;
        btnGenerate.innerText = "Generating...";

        fetch("{{ route('tu.generate-qr') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            btnGenerate.disabled = false;
            btnGenerate.innerText = "Generate QR Code Baru";

            if (data.success) {
                initQR(data.token);
                startCountdown(data.expires_in_seconds);
            } else {
                alert(data.error || "Gagal membuat QR Code.");
            }
        })
        .catch(err => {
            btnGenerate.disabled = false;
            btnGenerate.innerText = "Generate QR Code Baru";
            console.error(err);
            alert("Terjadi kesalahan jaringan.");
        });
    });

    // Check on load
    checkActiveQR();
});
</script>
@endsection
