@extends('layouts.admin')
@section('title','Kelola Akun Siswa')
@section('content')

<div class="page-title-row">
    <div>
        <div class="page-title">👨‍🎓 Akun Siswa</div>
        <div class="page-subtitle">FR-A-05, FR-A-06 · Kelola profil, status, dan kredensial siswa</div>
    </div>
    <form method="GET" action="{{ route('admin.siswa.index') }}" style="display:flex;gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama / email..."
            class="form-control-custom"
            style="width:240px;">
        <button type="submit" class="btn-custom btn-dark-custom">Cari</button>
    </form>
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
                    <th>Sekolah Asal</th>
                    <th>Status Akun</th>
                    <th>Waktu Daftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                <tr>
                    <td style="white-space: nowrap;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div class="tb-av" style="width:32px;height:32px;font-size:12px;flex-shrink:0;">
                                {{ strtoupper(substr($siswa->user->nama ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:13px;color:var(--text-dark);">{{ $siswa->user->nama ?? '-' }}</div>
                                <div style="font-size:10.5px;color:var(--text-muted);">{{ $siswa->user->email ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:500; color:var(--text-dark); white-space: nowrap;">{{ $siswa->sekolah_asal ?? '-' }}</td>
                    <td style="white-space: nowrap;">
                        @if($siswa->user->is_active)
                            <span class="badge-custom badge-green">✅ Aktif</span>
                        @else
                            <span class="badge-custom badge-red">❌ Nonaktif</span>
                        @endif
                    </td>
                    <td style="font-size:11.5px; color:var(--text-muted); white-space: nowrap;">
                        📅 {{ $siswa->created_at?->format('d M Y') ?? '-' }}
                    </td>
                    <td style="white-space: nowrap;">
                        <div style="display:flex;gap:6px;">
                            <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn-custom btn-outline-blue" style="padding: 5px 10px; font-size: 11.5px;">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.siswa.status', $siswa->id) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-custom {{ $siswa->user->is_active ? 'btn-outline-red' : 'btn-outline-green' }}" style="padding: 5px 10px; font-size: 11.5px;">
                                    {{ $siswa->user->is_active ? '🔴 Mati' : '🟢 Hidup' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:40px;text-align:center;color:var(--text-muted);">
                        <div style="font-size:24px;margin-bottom:8px;">🔍</div>
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px;border-top:1px solid var(--border);">
        {{ $siswas->appends(request()->query())->links() }}
    </div>
</div>

@endsection