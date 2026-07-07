@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha')
@section('page_title', 'Dashboard Tata Usaha')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Total Guru Terdaftar</div>
        <div class="widget-value" style="color: var(--color-accent);" id="widget-total-teachers">{{ $totalTeachers }} Guru</div>
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

<!-- Alert Box -->
<div id="crud-alert" class="alert" style="display: none; margin-bottom: 2rem;"></div>

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
    <!-- Form Kelola Guru (Tambah / Edit Guru) -->
    <div class="section-card" id="form-teacher-card">
        <div class="section-title" id="form-card-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Guru Baru
        </div>
        <form id="form-teacher">
            <input type="hidden" id="teacher-id" value="">
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="teacher-name" placeholder="Contoh: Dr. H. Ahmad Fauzi, M.Pd" required>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Email</label>
                <input type="email" class="form-control" id="teacher-email" placeholder="Contoh: ahmad@sekolah.sch.id" required>
            </div>
            <div class="form-group">
                <label class="form-label">NIP (Nomor Induk Pegawai)</label>
                <input type="text" class="form-control" id="teacher-nip" placeholder="Contoh: 198701022010121003">
            </div>
            <div class="form-group">
                <label class="form-label">Mata Pelajaran / Bidang</label>
                <input type="text" class="form-control" id="teacher-subject" placeholder="Contoh: Matematika" required>
            </div>
            <div class="form-group">
                <label class="form-label" id="label-password">Password</label>
                <input type="password" class="form-control" id="teacher-password" placeholder="Masukkan password (minimal 6 karakter)" required>
                <small id="password-help" style="display: none; color: var(--text-muted); font-size: 0.75rem; margin-top: 0.35rem;"></small>
            </div>
            <div class="form-group">
                <label class="form-label" id="label-password-confirmation">Konfirmasi Password</label>
                <input type="password" class="form-control" id="teacher-password-confirmation" placeholder="Ulangi password" required>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" id="btn-submit-teacher" class="btn btn-primary">Simpan Data Guru</button>
                <button type="button" id="btn-cancel-edit" class="btn btn-danger" style="display: none; background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.2); box-shadow: none;">Batal</button>
            </div>
        </form>
    </div>

    <!-- Daftar Guru Terdaftar -->
    <div class="section-card">
        <div class="section-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Daftar Guru Terdaftar
        </div>
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table class="custom-table" id="table-teachers">
                <thead>
                    <tr>
                        <th>Nama / NIP</th>
                        <th>Email</th>
                        <th>Bidang</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                    <tr id="teacher-row-{{ $teacher->id }}">
                        <td>
                            <div style="font-weight: 600;">{{ $teacher->name }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.15rem;">
                                NIP: {{ $teacher->teacherProfile->nip ?? '-' }}
                            </div>
                        </td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->teacherProfile->subject ?? '-' }}</td>
                        <td>
                            <div class="action-buttons" style="justify-content: center;">
                                <button class="btn btn-sm btn-primary btn-edit-teacher" 
                                    data-id="{{ $teacher->id }}"
                                    data-name="{{ $teacher->name }}"
                                    data-email="{{ $teacher->email }}"
                                    data-nip="{{ $teacher->teacherProfile->nip ?? '' }}"
                                    data-subject="{{ $teacher->teacherProfile->subject ?? '' }}"
                                    style="padding: 0.3rem 0.6rem;">
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete-teacher" 
                                    data-id="{{ $teacher->id }}"
                                    data-name="{{ $teacher->name }}"
                                    style="padding: 0.3rem 0.6rem;">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-teacher-row">
                        <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Belum ada data guru.</td>
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
    // Elements for QR Code
    const btnGenerate = document.getElementById('btn-generate-qr');
    const qrPlaceholder = document.getElementById('qr-placeholder');
    const qrCanvas = document.getElementById('qr-canvas');
    const timerContainer = document.getElementById('qr-timer-container');
    const timerVal = document.getElementById('qr-timer');
    const serverClock = document.getElementById('server-clock');

    // Elements for CRUD Guru
    const formTeacher = document.getElementById('form-teacher');
    const teacherIdInput = document.getElementById('teacher-id');
    const teacherNameInput = document.getElementById('teacher-name');
    const teacherEmailInput = document.getElementById('teacher-email');
    const teacherNipInput = document.getElementById('teacher-nip');
    const teacherSubjectInput = document.getElementById('teacher-subject');
    const teacherPasswordInput = document.getElementById('teacher-password');
    const labelPassword = document.getElementById('label-password');
    const passwordHelp = document.getElementById('password-help');
    const teacherPasswordConfirmationInput = document.getElementById('teacher-password-confirmation');
    const labelPasswordConfirmation = document.getElementById('label-password-confirmation');
    
    const cardTitle = document.getElementById('form-card-title');
    const btnSubmitTeacher = document.getElementById('btn-submit-teacher');
    const btnCancelEdit = document.getElementById('btn-cancel-edit');
    const alertBox = document.getElementById('crud-alert');

    let countdownInterval = null;
    let qrGenerator = null;

    // Show dynamic toast alert
    function showNotification(message, type = 'success') {
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
        
        // Auto scroll to alert
        alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Hide after 4 seconds
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 4000);
    }

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
                showNotification("QR Code absensi berhasil diaktifkan!");
            } else {
                showNotification(data.error || "Gagal membuat QR Code.", 'error');
            }
        })
        .catch(err => {
            btnGenerate.disabled = false;
            btnGenerate.innerText = "Generate QR Code Baru";
            console.error(err);
            showNotification("Terjadi kesalahan jaringan.", 'error');
        });
    });

    // Check active QR on load
    checkActiveQR();

    // ============================================
    // LOGIC CRUD GURU AJAX
    // ============================================

    // Handle Edit Button Click
    document.querySelectorAll('.btn-edit-teacher').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const email = this.getAttribute('data-email');
            const nip = this.getAttribute('data-nip');
            const subject = this.getAttribute('data-subject');

            // Populate form inputs
            teacherIdInput.value = id;
            teacherNameInput.value = name;
            teacherEmailInput.value = email;
            teacherNipInput.value = nip;
            teacherSubjectInput.value = subject;
            teacherPasswordInput.value = ""; // clear password field for editing
            teacherPasswordInput.required = false; // optional for edit
            teacherPasswordConfirmationInput.value = "";
            teacherPasswordConfirmationInput.required = false;

            // Change form title and buttons
            cardTitle.innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> Edit Data Guru`;
            btnSubmitTeacher.innerText = "Update Data Guru";
            btnCancelEdit.style.display = 'block';
            
            labelPassword.innerText = "Password (Opsional)";
            passwordHelp.innerText = "Kosongkan jika tidak ingin mengubah password.";
            passwordHelp.style.display = "block";
            labelPasswordConfirmation.innerText = "Konfirmasi Password (Opsional)";

            // Scroll to the form view
            document.getElementById('form-teacher-card').scrollIntoView({ behavior: 'smooth' });
        });
    });

    // Handle Cancel Edit Button Click
    btnCancelEdit.addEventListener('click', resetForm);

    function resetForm() {
        formTeacher.reset();
        teacherIdInput.value = "";
        teacherPasswordInput.required = true; // required for new
        teacherPasswordConfirmationInput.required = true;
        
        cardTitle.innerHTML = `<svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg> Tambah Guru Baru`;
        btnSubmitTeacher.innerText = "Simpan Data Guru";
        btnCancelEdit.style.display = 'none';
        
        labelPassword.innerText = "Password";
        passwordHelp.style.display = "none";
        labelPasswordConfirmation.innerText = "Konfirmasi Password";
    }

    // Handle form submit (Create or Update)
    formTeacher.addEventListener('submit', function(e) {
        e.preventDefault();

        const id = teacherIdInput.value;
        const isEdit = id !== "";
        
        const name = teacherNameInput.value;
        const email = teacherEmailInput.value;
        const nip = teacherNipInput.value;
        const subject = teacherSubjectInput.value;
        const password = teacherPasswordInput.value;
        const passwordConfirmation = teacherPasswordConfirmationInput.value;

        // Validasi kecocokan di sisi client dulu agar cepat
        if (password !== passwordConfirmation) {
            showNotification("Konfirmasi password tidak cocok dengan password yang dimasukkan.", 'error');
            return;
        }

        btnSubmitTeacher.disabled = true;
        btnSubmitTeacher.innerText = isEdit ? "Memperbarui..." : "Menyimpan...";

        const url = isEdit ? `/dashboard/tu/guru/${id}` : "/dashboard/tu/guru";
        const method = "POST"; // we will use PUT method override inside body if editing
        
        const requestData = {
            name: name,
            email: email,
            nip: nip,
            subject: subject,
            password: password,
            password_confirmation: passwordConfirmation
        };

        if (isEdit) {
            requestData['_method'] = 'PUT';
        }

        fetch(url, {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(requestData)
        })
        .then(res => res.json())
        .then(data => {
            btnSubmitTeacher.disabled = false;
            btnSubmitTeacher.innerText = isEdit ? "Update Data Guru" : "Simpan Data Guru";

            if (data.success) {
                showNotification(data.message);
                resetForm();
                // Refresh list cleanly after 1 second
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showNotification(data.error || "Gagal memproses data. Periksa kembali input Anda.", 'error');
            }
        })
        .catch(err => {
            btnSubmitTeacher.disabled = false;
            btnSubmitTeacher.innerText = isEdit ? "Update Data Guru" : "Simpan Data Guru";
            console.error(err);
            showNotification("Terjadi kesalahan koneksi database.", 'error');
        });
    });

    // Handle Delete Button Click
    document.querySelectorAll('.btn-delete-teacher').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            if (confirm(`Apakah Anda yakin ingin menghapus data guru "${name}"? Tindakan ini akan menghapus seluruh data kehadiran terkait.`)) {
                this.disabled = true;
                this.innerText = "...";

                fetch(`/dashboard/tu/guru/${id}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        _method: "DELETE"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message);
                        
                        // Dynamically remove the row with a nice transition
                        const row = document.getElementById(`teacher-row-${id}`);
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-100px)';
                        row.style.transition = 'all 0.5s ease';
                        
                        setTimeout(() => {
                            row.remove();
                            // If table is now empty, append placeholder row
                            const tableBody = document.querySelector('#table-teachers tbody');
                            if (tableBody.children.length === 0) {
                                tableBody.innerHTML = `
                                    <tr class="empty-teacher-row">
                                        <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Belum ada data guru.</td>
                                    </tr>
                                `;
                            }
                            
                            // Refresh total teacher widget counter
                            window.location.reload();
                        }, 500);
                    } else {
                        showNotification(data.error || "Gagal menghapus data guru.", 'error');
                        this.disabled = false;
                        this.innerText = "Hapus";
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification("Terjadi kesalahan jaringan saat menghapus data.", 'error');
                    this.disabled = false;
                    this.innerText = "Hapus";
                });
            }
        });
    });
});
</script>
@endsection
