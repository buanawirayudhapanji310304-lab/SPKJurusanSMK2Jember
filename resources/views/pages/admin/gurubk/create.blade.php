@extends('layouts.admin')
@section('title','Tambah Guru BK')
@section('content')
<div style="max-width:640px;">
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.gurubk.index') }}" class="back-link">← Kembali</a>
        <div class="page-title">Tambah Akun Guru BK</div>
        <div class="page-subtitle">FR-A-02 · Password default: <code style="background:#f1f5f9;padding:1px 6px;border-radius:4px;">password123</code></div>
    </div>

    <div class="form-card-custom">
        <form method="POST" action="{{ route('admin.gurubk.store') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="form-label-custom">Nama Lengkap <span style="color:#dc2626;">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" 
                    placeholder="Contoh: Budi Santoso, S.Pd." 
                    class="form-control-custom">
                @error('nama')<div style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-custom">Email <span style="color:#dc2626;">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" 
                    placeholder="gurubk@spksaw.sch.id" 
                    class="form-control-custom">
                @error('email')<div style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:16px;">
                <label class="form-label-custom">NIP <span style="color:#dc2626;">*</span></label>
                <input type="text" name="nip" value="{{ old('nip') }}" 
                    placeholder="198502142010011002" 
                    class="form-control-custom">
                @error('nip')<div style="color:#dc2626;font-size:11.5px;margin-top:5px;">{{ $message }}</div>@enderror
            </div>

            <div style="margin-bottom:24px;">
                <label class="form-label-custom">Jurusan (Opsional)</label>
                <select name="jurusan_id" class="form-control-custom">
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach($jurusans as $j)
                        <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions-custom">
                <a href="{{ route('admin.gurubk.index') }}" class="btn-custom btn-ghost-custom">Batal</a>
                <button type="submit" class="btn-custom btn-dark-custom">✓ Simpan Akun</button>
            </div>
        </form>
    </div>
</div>
@endsection