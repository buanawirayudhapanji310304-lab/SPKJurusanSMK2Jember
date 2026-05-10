@extends('layouts.admin')
@section('title','Edit Siswa')
@section('content')
<div style="max-width:640px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.siswa.index') }}" class="back-link">← Kembali</a>
        <div class="page-title">Edit Profil Siswa</div>
        <div class="page-subtitle">FR-A-05, FR-A-06 · Edit profil dan reset password siswa</div>
    </div>

    <div class="form-card-custom">
        <form method="POST" action="{{ route('admin.siswa.update', $siswa->id) }}">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-custom">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $siswa->user->nama) }}" class="form-control-custom">
                    @error('nama')<div style="color:#dc2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-custom">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $siswa->user->email) }}" class="form-control-custom">
                    @error('email')<div style="color:#dc2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-custom">Sekolah Asal</label>
                    <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal', $siswa->sekolah_asal) }}" class="form-control-custom">
                </div>
                <div>
                    <label class="form-label-custom">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $siswa->no_telepon) }}" class="form-control-custom">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="form-label-custom">Alamat</label>
                <textarea name="alamat" rows="3" class="form-control-custom" style="resize:vertical;">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>

            {{-- <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:9px;padding:14px 16px;margin-bottom:16px;">
                <div style="font-size:11.5px;font-weight:700;color:#92400e;margin-bottom:8px;">🔒 Reset Password (FR-A-06)</div>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" style="width:100%;background:#fff;border:1.5px solid #fde68a;border-radius:9px;font-size:13px;padding:10px 14px;outline:none;">
            </div> --}}

            <div class="form-actions-custom">
                <a href="{{ route('admin.siswa.index') }}" class="btn-custom btn-ghost-custom">Batal</a>
                <button type="submit" class="btn-custom btn-dark-custom">✓ Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection




























