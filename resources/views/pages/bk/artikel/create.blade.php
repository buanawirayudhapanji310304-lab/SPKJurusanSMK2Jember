@extends('layouts.bk')
@section('title','Tambah Artikel')
@section('page-title','Tambah Artikel')
@section('page-sub','FR-BK-07 · Buat artikel jurusan baru')

@section('content')
<a href="{{ route('bk.artikel.index') }}" class="btn btn-outline btn-sm" style="margin-bottom:16px;">← Kembali</a>
<div class="card" style="padding:24px; max-width:680px; margin: 0 auto;">
    <div style="font-family:'Playfair Display',serif;font-size:15px;font-weight:800;color:var(--primary-dark);margin-bottom:20px;">✏️ Tambah Artikel Baru</div>
    <form method="POST" action="{{ route('bk.artikel.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label">Judul <span class="req">*</span></label>
            <input name="judul" class="form-control" value="{{ old('judul') }}" required placeholder="Masukkan judul artikel..."/>
        </div>
        <div class="form-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 14px;">
            <div class="form-group">
                <label class="form-label">Jurusan <span class="req">*</span></label>
                @if($jurusans->count() === 1)
                    <input type="hidden" name="jurusan_id" value="{{ $jurusans->first()->id }}">
                    <input type="text" class="form-control" value="{{ $jurusans->first()->nama_jurusan }}" disabled
                        style="background:#f1f5f9;color:#64748b;cursor:not-allowed;">
                @else
                    <select name="jurusan_id" class="form-control" required>
                        <option value="">Pilih jurusan...</option>
                        @foreach($jurusans as $j)
                            <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected':'' }}>{{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="form-group">
                <label class="form-label">Upload Gambar</label>
                <input name="gambar" type="file" accept=".jpg,.jpeg" class="form-control"/>
                <div class="form-hint">Format: JPG, maks. 8MB</div>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Deskripsi <span class="req">*</span></label>
            <textarea name="deskripsi" class="form-control" required style="min-height: 150px;" placeholder="Tulis isi artikel di sini...">{{ old('deskripsi') }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">File Pendukung</label>
            <input name="file" type="file" accept=".pdf,.mp4" class="form-control"/>
            <div class="form-hint">Format: PDF (maks 8MB) atau MP4 (maks 50MB)</div>
        </div>
        <div class="form-actions" style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap-reverse; margin-top: 24px;">
            <a href="{{ route('bk.artikel.index') }}" class="btn btn-outline" style="flex: 1; justify-content: center; min-width: 120px;">Batal</a>
            <button class="btn btn-primary" style="flex: 2; justify-content: center; min-width: 180px;">💾 Simpan Artikel</button>
        </div>
    </form>
</div>
@endsection

