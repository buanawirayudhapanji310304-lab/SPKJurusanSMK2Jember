@extends('layouts.bk')
@section('title','Info Jurusan')
@section('page-title','Info Jurusan')
@section('page-sub','FR-BK-08/09 · Kelola fasilitas & prospek kerja')

@section('content')
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:16px;">
    @foreach($jurusans as $j)
    <div class="card" style="padding:18px; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                <div style="width:44px;height:44px;border-radius:10px;background:var(--blue-bg);display:flex;align-items:center;justify-content:center;font-size:22px;box-shadow: inset 0 0 0 1px var(--blue-border);">🏫</div>
                <div style="min-width: 0;">
                    <div style="font-size:14px;font-weight:800;color:var(--primary-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $j->nama }}</div>
                    <div style="font-size:11px;color:var(--text-dim);">
                        {{ $j->informasiJurusan ? '✅ Info tersedia' : '⚪ Belum ada info' }}
                        · {{ $j->prospekKerja->count() }} prospek
                    </div>
                </div>
            </div>
            @if($j->informasiJurusan?->fasilitas)
                <div style="font-size:12.5px;color:var(--text-mid);line-height:1.6;margin-bottom:14px;">{{ Str::limit($j->informasiJurusan->fasilitas, 90) }}</div>
            @else
                <div style="font-size:12.5px;color:var(--text-dim);font-style:italic;margin-bottom:14px; background: var(--surface2); padding: 8px; border-radius: 8px;">Fasilitas & prospek belum diisi.</div>
            @endif
        </div>
        <a href="{{ route('bk.infojurusan.edit', $j->id) }}" class="btn btn-outline btn-sm" style="width: 100%; justify-content: center;">✏️ Edit Info</a>
    </div>
    @endforeach
</div>
@endsection