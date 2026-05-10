{{-- resources/views/pages/bk/profil.blade.php --}}
@extends('layouts.bk')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('page-sub', 'FR-BK-04 · Data pribadi guru BK')

@section('content')

{{-- Flash success --}}
@if(session('warning'))
    <div style="background:#fff7ed;border:1px solid #fdba74;padding:10px;margin-bottom:10px;color:#9a3412;">
        ⚠️ {{ session('warning') }}
    </div>
@endif

<div class="profile-container" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:20px; align-items:start;">

    {{-- ═══ KARTU PROFIL KIRI ═══ --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;box-shadow:0 1px 3px rgba(0,0,0,.05);text-align:center;">

        {{-- Avatar --}}
        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-light));display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800;color:#fff;margin:0 auto 16px;box-shadow: 0 4px 12px rgba(232, 160, 32, 0.2);">
            {{ strtoupper(substr($user->nama ?? 'G', 0, 1)) }}
        </div>

        <div style="font-size:18px;font-weight:800;color:var(--primary-dark);margin-bottom:4px;">
            {{ $user->nama ?? '-' }}
        </div>
        <div style="font-size:12.5px;color:var(--text-dim);margin-bottom:16px;">
            {{ $user->email ?? '-' }}
        </div>

        {{-- Badge status --}}
        <div style="margin-bottom: 20px;">
            @if($user->is_active)
                <span class="badge badge-green">✅ Aktif</span>
            @else
                <span class="badge badge-red">❌ Nonaktif</span>
            @endif
        </div>


        <div style="margin-top:14px;padding-top:16px;border-top:1px solid #f1f5f9; text-align: left;">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:8px;">JURUSAN</div>
            @if($guruBk->jurusan)
                <div style="background:var(--blue-bg);color:var(--blue);border:1px solid var(--blue-border);font-size:12px;font-weight:700;padding:6px 12px;border-radius:10px; display: inline-block;">
                    {{ $guruBk->jurusan->nama_jurusan }}
                </div>
            @else
                <span style="font-size:13px;color:#94a3b8;">—</span>
            @endif
        </div>

        <div style="margin-top:14px;padding-top:16px;border-top:1px solid #f1f5f9; text-align: left;">
            <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:8px;">BERGABUNG</div>
            <div style="font-size:13px;color:var(--text-mid); font-weight: 500;">📅 {{ $user->created_at?->translatedFormat('d F Y') }}</div>
        </div>

        {{-- Tombol ubah password --}}
        <a href="{{ route('bk.password.index') }}" class="btn btn-outline"
           style="display:flex; justify-content: center; margin-top:24px; width: 100%;">
            🔒 Ubah Password
        </a>
    </div>

    {{-- ═══ FORM EDIT PROFIL KANAN ═══ --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:26px;box-shadow:0 1px 3px rgba(0,0,0,.05);">

        <div style="font-size:16px;font-weight:800;color:var(--primary-dark);margin-bottom:20px;padding-bottom:14px;border-bottom:1px solid #f1f5f9;">
            ✏️ Edit Data Profil
        </div>

        <form method="POST" action="{{ route('bk.profil.update') }}">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span class="req">*</span></label>
                <input type="text" name="nama"
                       value="{{ old('nama', $user->nama) }}"
                       required
                       class="form-control"
                       placeholder="Masukkan nama lengkap...">
                @error('nama')
                    <div style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label">Email <span class="req">*</span></label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       required
                       class="form-control"
                       placeholder="Masukkan alamat email...">
                @error('email')
                    <div style="font-size:11px;color:var(--red);margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            {{-- NIP --}}
            <div class="form-group">
                <label class="form-label">NIP</label>
                <input type="text" name='nip'
                       value="{{ old('nip', $guruBk->nip ?? '') }}" required
                       class="form-control" disabled
                       style="background:#f1f5f9; color:#94a3b8; cursor:not-allowed;">
                <div class="form-hint">NIP hanya bisa diubah oleh Admin.</div>
            </div>

            {{-- Actions --}}
            <div class="form-actions" style="display:flex; gap:10px; justify-content:flex-end; flex-wrap: wrap-reverse; margin-top: 24px;">
                <a href="{{ route('bk.dashboard') }}" class="btn btn-outline" style="flex: 1; justify-content: center; min-width: 100px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center; min-width: 200px;">
                    ✓ Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

@endsection