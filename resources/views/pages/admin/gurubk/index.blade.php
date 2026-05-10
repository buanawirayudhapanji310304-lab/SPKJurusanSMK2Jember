@extends('layouts.admin')
@section('title','Kelola Akun Guru BK')
@section('content')
<div class="page-title-row">
    <div>
        <div class="page-title">👩‍🏫 Akun Guru BK</div>
        <div class="page-subtitle">FR-A-02 · Kelola akun, profil, dan jurusan Guru BK</div>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <form method="GET" action="{{ route('admin.gurubk.index') }}" style="display:flex;gap:8px;">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama / email..."
                class="form-control-custom"
                style="width:220px;">
            <button type="submit" class="btn-custom btn-dark-custom">Cari</button>
        </form>
        <a href="{{ route('admin.gurubk.create') }}" class="btn-custom btn-dark-custom">
            ＋ Tambah Guru BK
        </a>
    </div>
</div>

@if(session('success'))
<div class="flash-success">
    <span>✅ {{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#065f46;font-size:16px;">✕</button>
</div>
@endif

<div class="card-soft">
    <div class="res-table">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Nama & Email</th>
                    <th>NIP</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guruBks as $guru)
                <tr>
                    <td style="white-space: nowrap;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="tb-av" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">
                                {{ strtoupper(substr($guru->user->nama ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13px;color:var(--text-dark);">{{ $guru->user->nama ?? '-' }}</div>
                                <div style="font-size:10.5px;color:var(--text-muted);">{{ $guru->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:500; white-space: nowrap;">{{ $guru->nip ?? '-' }}</td>
                    <td style="white-space: nowrap;">
                        @if($guru->jurusan)
                            <span class="badge-custom badge-blue">{{ $guru->jurusan->nama_jurusan }}</span>
                        @else
                            <span style="color:var(--text-muted);font-size:12px;font-style:italic;">Belum ditentukan</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        @if($guru->user->is_active)
                            <span class="badge-custom badge-green">✅ Aktif</span>
                        @else
                            <span class="badge-custom badge-red">❌ Nonaktif</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">
                        @php
                            $mustChange = $guru->user->must_change_password ?? false;
                        @endphp
                        <span class="badge-custom {{ $mustChange ? 'badge-amber' : 'badge-green' }}" style="font-size: 11px; padding: 3px 8px;">
                            {{ $mustChange ? '⚠️ Wajib Ganti' : '✔ Terverifikasi' }}
                        </span>
                    </td>
                    <td style="white-space: nowrap;">
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.gurubk.edit', $guru->id) }}" class="btn-custom btn-outline-blue" style="padding: 5px 10px; font-size: 11.5px;">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.gurubk.status', $guru->id) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-custom {{ $guru->user->is_active ? 'btn-outline-red' : 'btn-outline-green' }}" style="padding: 5px 10px; font-size: 11.5px;">
                                    {{ $guru->user->is_active ? '🔴 Mati' : '🟢 Hidup' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:40px;text-align:center;color:var(--text-muted);">
                        <div style="font-size:24px;margin-bottom:8px;">🔍</div>
                        Tidak ada data Guru BK ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px;border-top:1px solid var(--border);">
        {{ $guruBks->appends(request()->query())->links() }}
    </div>
</div>
@endsection