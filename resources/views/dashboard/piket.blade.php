@extends('layouts.app')

@section('title', 'Dashboard Admin Piket')
@section('page_title', 'Dashboard Admin Piket')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Guru Hadir (Hari Ini)</div>
        <div class="widget-value" style="color: var(--color-success);">{{ $presentToday }} / {{ $totalTeachers }}</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Menunggu Validasi</div>
        <div class="widget-value" style="color: var(--color-warning);" id="pending-counter">{{ $pendingCount }} Antrean</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Persentase Kehadiran</div>
        <div class="widget-value" style="color: var(--color-accent);">{{ $attendanceRate }}%</div>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Validasi QR Absensi Guru
    </div>
    <div class="table-responsive">
        <table class="custom-table" id="pending-table">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Waktu Scan</th>
                    <th>Koordinat Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingAttendances as $attendance)
                <tr id="attendance-row-{{ $attendance->id }}">
                    <td style="font-weight: 600;">{{ $attendance->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->scan_time)->format('H:i:s') }} WIB</td>
                    <td style="font-family: monospace; font-size: 0.85rem; color: var(--text-secondary);">
                        {{ round($attendance->latitude, 5) }}, {{ round($attendance->longitude, 5) }}
                    </td>
                    <td><span class="badge badge-warning" id="status-badge-{{ $attendance->id }}">Menunggu Validasi</span></td>
                    <td>
                        <div class="action-buttons" id="actions-{{ $attendance->id }}">
                            <button class="btn btn-sm btn-success btn-validate" data-id="{{ $attendance->id }}" data-status="approved">Setujui</button>
                            <button class="btn btn-sm btn-danger btn-validate" data-id="{{ $attendance->id }}" data-status="rejected">Tolak</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="5" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Tidak ada antrean validasi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Laporan Kehadiran Harian (Hari Ini)
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Waktu Hadir</th>
                    <th>Status Validasi</th>
                    <th>Petugas Validasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($validatedAttendances as $attendance)
                <tr>
                    <td style="font-weight: 600;">{{ $attendance->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->scan_time)->format('H:i:s') }} WIB</td>
                    <td>
                        @if($attendance->status === 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $attendance->validator->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">Belum ada laporan kehadiran tervalidasi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnValidates = document.querySelectorAll('.btn-validate');
    
    btnValidates.forEach(btn => {
        btn.addEventListener('click', function() {
            const attendanceId = this.getAttribute('data-id');
            const status = this.getAttribute('data-status');
            const actionButtons = document.getElementById(`actions-${attendanceId}`);
            
            // Disable buttons during send
            const buttons = actionButtons.querySelectorAll('button');
            buttons.forEach(b => b.disabled = true);
            
            // Call API
            fetch(`/dashboard/attendance/${attendanceId}/validate`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    status: status,
                    notes: status === 'rejected' ? 'Ditolak oleh admin piket' : 'Validasi kehadiran sukses'
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update status badge UI
                    const badge = document.getElementById(`status-badge-${attendanceId}`);
                    if (status === 'approved') {
                        badge.className = 'badge badge-success';
                        badge.innerText = 'Disetujui';
                    } else {
                        badge.className = 'badge badge-danger';
                        badge.innerText = 'Ditolak';
                    }
                    
                    // Show confirmation and reload page after a short delay to refresh dashboard stats
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    alert(data.error || "Gagal melakukan validasi.");
                    buttons.forEach(b => b.disabled = false);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan jaringan.");
                buttons.forEach(b => b.disabled = false);
            });
        });
    });
});
</script>
@endsection
