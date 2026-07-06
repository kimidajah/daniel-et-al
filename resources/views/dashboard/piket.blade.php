@extends('layouts.app')

@section('title', 'Dashboard Admin Piket')
@section('page_title', 'Dashboard Admin Piket')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Guru Hadir (Hari Ini)</div>
        <div class="widget-value" style="color: var(--color-success);">12 / 24</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Menunggu Validasi</div>
        <div class="widget-value" style="color: var(--color-warning);">3 Antrean</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Persentase Kehadiran</div>
        <div class="widget-value" style="color: var(--color-accent);">50%</div>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Validasi QR Absensi Guru
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Waktu Scan</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Budi Guru</td>
                    <td>07:12:45 WIB</td>
                    <td>Scan QR Mandiri</td>
                    <td><span class="badge badge-warning">Menunggu Validasi</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-success">Setujui</button>
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Hendra Lesmana, M.Pd</td>
                    <td>07:15:10 WIB</td>
                    <td>Scan QR Mandiri</td>
                    <td><span class="badge badge-warning">Menunggu Validasi</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-success">Setujui</button>
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>Rina Herawati, S.Pd</td>
                    <td>07:22:30 WIB</td>
                    <td>Scan QR Mandiri</td>
                    <td><span class="badge badge-warning">Menunggu Validasi</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-success">Setujui</button>
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Laporan Kehadiran Harian
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Waktu Hadir</th>
                    <th>Status Validasi</th>
                    <th>Petugas Piket</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Agus Mulyana, S.T</td>
                    <td>06:55:00 WIB</td>
                    <td><span class="badge badge-success">Disetujui</span></td>
                    <td>Andi Piket</td>
                </tr>
                <tr>
                    <td>Dewi Lestari, M.Si</td>
                    <td>06:58:12 WIB</td>
                    <td><span class="badge badge-success">Disetujui</span></td>
                    <td>Andi Piket</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
