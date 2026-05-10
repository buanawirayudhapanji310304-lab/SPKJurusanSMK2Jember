@extends('layouts.admin')
@section('title','Kelola Penyakit')
@section('content')

<div class="page-title-row">
    <div>
        <div class="page-title">⚕️ Master Data Penyakit</div>
        <div class="page-subtitle">Kelola daftar penyakit untuk kriteria C8. Data ini digunakan untuk membatasi kualifikasi siswa pada jurusan tertentu.</div>
    </div>
</div>

@if(session('success'))
    <div class="flash-success">
        <span>✅ {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#065f46;font-size:16px;">✕</button>
    </div>
@endif

@if($errors->any())
    <div class="warn-box-custom" style="background:var(--rbg); border-color:var(--rb); color:var(--red); margin-bottom: 20px;">
        <div class="warn-title" style="color:var(--red);">⚠️ Input Tidak Valid</div>
        <ul style="font-size:12px; padding-left:16px; margin: 5px 0 0 0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: 320px 1fr; gap: 24px; align-items: start;">
    
    {{-- Sisi Kiri: Form Tambah --}}
    <div class="form-card-custom" style="position: sticky; top: 84px;">
        <p style="font-size:13px; font-weight:700; color:var(--text-dark); margin:0 0 14px 0;">➕ Tambah Penyakit Baru</p>
        <form method="POST" action="{{ route('admin.penyakit.store') }}">
            @csrf
            <div style="margin-bottom: 14px;">
                <label class="form-label-custom">Nama Penyakit</label>
                <input type="text" name="nama_penyakit" value="{{ old('nama_penyakit') }}" 
                    class="form-control-custom" placeholder="Contoh: Buta Warna Parsial">
            </div>
            <button type="submit" class="btn-custom btn-dark-custom" style="width: 100%; justify-content: center;">
                Simpan Penyakit
            </button>
        </form>

        <div class="warn-box-custom" style="margin-top:20px; padding: 12px; background: #fffbeb; border-color: #fde68a;">
            <div style="font-size:11px; font-weight: 700; color:#92400e; margin-bottom: 4px;">ℹ️ INFO SISTEM</div>
            <div style="font-size:11px; color:#92400e; line-height:1.5;">
                Penyakit yang didaftarkan di sini akan muncul sebagai pilihan di form tes siswa (C8).
            </div>
        </div>
    </div>

    {{-- Sisi Kanan: Tabel List --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">📋 Daftar Penyakit Terdaftar</div>
        </div>
        <div class="res-table">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">ID</th>
                        <th>Nama Penyakit</th>
                        <th style="width: 120px;">Status</th>
                        <th style="width: 200px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penyakits as $penyakit)
                        <tr>
                            <td style="text-align: center; font-size: 11px; color: var(--text-muted);">#{{ $penyakit->id }}</td>
                            <td>
                                <form id="form-edit-{{ $penyakit->id }}" method="POST" action="{{ route('admin.penyakit.update', $penyakit->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="nama_penyakit" 
                                        value="{{ $penyakit->nama_penyakit }}" 
                                        class="form-control-custom" style="padding:6px 10px; font-size: 13px; font-weight: 600; border-color: transparent; background: transparent;"
                                        onfocus="this.style.borderColor='var(--border)'; this.style.background='#fff';"
                                        onblur="if(this.value == '{{ $penyakit->nama_penyakit }}') { this.style.borderColor='transparent'; this.style.background='transparent'; }">
                                </form>
                            </td>
                            <td>
                                @if($penyakit->is_active)
                                    <span class="badge-custom badge-green">Aktif</span>
                                @else
                                    <span class="badge-custom badge-red">Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; justify-content:center;">
                                    <button type="submit" form="form-edit-{{ $penyakit->id }}" class="btn-custom btn-outline-blue" style="padding: 6px 10px; font-size: 11px;">
                                        Update
                                    </button>
                                    <form method="POST" action="{{ route('admin.penyakit.status', $penyakit->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-custom {{ $penyakit->is_active ? 'btn-outline-red' : 'btn-outline-green' }}" style="padding: 6px 10px; font-size: 11px;">
                                            {{ $penyakit->is_active ? 'Off' : 'On' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:var(--text-muted); padding:40px; font-size: 13px;">
                                📭 Belum ada data penyakit.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penyakits->hasPages())
            <div style="padding:14px 20px; border-top:1px solid var(--border);">
                {{ $penyakits->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    @media (max-width: 900px) {
        div[style*="display: grid"] {
            grid-template-columns: 1fr !important;
        }
        .form-card-custom {
            position: static !important;
        }
    }
</style>

@endsection
