@extends('layouts.app')

@section('title', 'Dashboard Kepala Sekolah')
@section('page_title', 'Dashboard Kepala Sekolah')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Rata-rata Kehadiran Guru</div>
        <div class="widget-value" style="color: var(--color-success);">94.8%</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Total Hari Kerja</div>
        <div class="widget-value">22 Hari</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Izin / Sakit Bulan Ini</div>
        <div class="widget-value" style="color: var(--color-warning);">4 Kasus</div>
    </div>
</div>

<div class="section-card">
    <div class="section-title">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        Laporan Kehadiran Guru - Juni 2026
    </div>
    <div class="table-responsive">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Nama Guru</th>
                    <th>Kehadiran</th>
                    <th>Sakit</th>
                    <th>Izin</th>
                    <th>Alpa</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Budi Guru</td>
                    <td>20 Hari</td>
                    <td>1 Hari</td>
                    <td>1 Hari</td>
                    <td>0 Hari</td>
                    <td><span class="badge badge-success">90.9%</span></td>
                </tr>
                <tr>
                    <td>Agus Mulyana, S.T</td>
                    <td>22 Hari</td>
                    <td>0 Hari</td>
                    <td>0 Hari</td>
                    <td>0 Hari</td>
                    <td><span class="badge badge-success">100%</span></td>
                </tr>
                <tr>
                    <td>Dewi Lestari, M.Si</td>
                    <td>21 Hari</td>
                    <td>1 Hari</td>
                    <td>0 Hari</td>
                    <td>0 Hari</td>
                    <td><span class="badge badge-success">95.4%</span></td>
                </tr>
                <tr>
                    <td>Hendra Lesmana, M.Pd</td>
                    <td>18 Hari</td>
                    <td>2 Hari</td>
                    <td>1 Hari</td>
                    <td>1 Hari</td>
                    <td><span class="badge badge-warning">81.8%</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
