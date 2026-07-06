@extends('layouts.app')

@section('title', 'Dashboard Tata Usaha')
@section('page_title', 'Dashboard Tata Usaha')

@section('content')
<div class="dashboard-grid">
    <div class="widget-card">
        <div class="widget-title">Total Guru Terdaftar</div>
        <div class="widget-value" style="color: var(--color-accent);">24 Guru</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Guru Hadir (Hari Ini)</div>
        <div class="widget-value" style="color: var(--color-success);">12 Guru</div>
    </div>
    <div class="widget-card">
        <div class="widget-title">Laporan Bulanan Dibuat</div>
        <div class="widget-value" style="color: var(--color-warning);">Juni 2026</div>
    </div>
</div>

<div class="form-row">
    <!-- Form Kelola Guru (Tambah Guru) -->
    <div class="section-card">
        <div class="section-title">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Guru Baru
        </div>
        <form>
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
        <div class="table-responsive">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Budi Guru</td>
                        <td>guru@example.com</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">Edit</button>
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>Agus Mulyana, S.T</td>
                        <td>agus.m@example.com</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-primary">Edit</button>
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
