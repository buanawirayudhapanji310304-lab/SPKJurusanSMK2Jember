@extends('layouts.admin')
@section('title','Edit Guru BK')
@section('content')
<div style="max-width:640px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.gurubk.index') }}" class="back-link">← Kembali</a>
        <div class="page-title">Edit Guru BK</div>
        <div class="page-subtitle">FR-A-03, FR-A-04, FR-A-05 · Edit profil, kredensial, dan jurusan</div>
    </div>

    <div class="form-card-custom">
        <form method="POST" action="{{ route('admin.gurubk.update', $guruBk->id) }}">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));gap:16px;margin-bottom:16px;">
                <div>
                    <label class="form-label-custom">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $guruBk->user->nama) }}" class="form-control-custom">
                    @error('nama')<div style="color:#dc2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label-custom">NIP *</label>
                    <input type="text" name="nip" value="{{ old('nip', $guruBk->nip) }}" class="form-control-custom">
                    @error('nip')<div style="color:#dc2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-custom">Email *</label>
                <input type="email" name="email" value="{{ old('email', $guruBk->user->email) }}" class="form-control-custom">
                @error('email')<div style="color:#dc2626;font-size:11.5px;margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-custom">Jurusan</label>
                <select name="jurusan_id" class="form-control-custom">
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id', $guruBk->jurusan_id) == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="warn-box-custom">
                <div class="warn-title">🔒 Reset Password (FR-A-04)</div>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="form-control-custom" style="background:#fff;">
                <div style="font-size:11px;color:#92400e;margin-top:8px;">Jika diisi, Guru BK akan diwajibkan ganti password saat login berikutnya.</div>
            </div>

            <div class="form-actions-custom">
                <a href="{{ route('admin.gurubk.index') }}" class="btn-custom btn-ghost-custom">Batal</a>
                <button type="submit" class="btn-custom btn-dark-custom">✓ Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

