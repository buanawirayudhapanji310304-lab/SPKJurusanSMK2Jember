@extends('layouts.bk')
@section('title','Data Siswa')
@section('page-title','Data Siswa')
@section('page-sub','FR-BK-05 · Lihat hasil rekomendasi siswa')

@section('content')
<div class="alert alert-info">ℹ️ Data bersifat <strong>read-only</strong>. Guru BK hanya dapat melihat hasil rekomendasi siswa.</div>

<div class="card">
    <div class="card-head" style="flex-direction: row; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <div class="card-title">Daftar Siswa</div>
        <form method="GET" style="display:flex; gap:8px; width: 100%; max-width: 350px;">
            <input name="search" class="form-control" style="flex: 1; padding:7px 12px;" placeholder="🔍 Cari nama siswa..." value="{{ request('search') }}"/>
            <button class="btn btn-primary btn-sm">Cari</button>
            @if(request('search'))
                <a href="{{ route('bk.siswa.index') }}" class="btn btn-outline btn-sm">Reset</a>
            @endif
        </form>
    </div>
    <div class="res-table" style="border: none; border-radius: 0; margin-bottom: 0;">
        <table>
            <thead>
                <tr>
                    <th>Nama Siswa</th><th>Sekolah Asal</th><th>Rekomendasi SAW</th>
                    <th>Minat 1</th><th>Minat 2</th><th>Sesuai?</th><th>Jml Tes</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $siswa)
                @php
                    $lastTes = $siswa->tes->first();
                    $rek     = $lastTes?->rekomendasiTeratas?->jurusan?->nama_jurusan;
                    $m1      = $lastTes?->minatJurusan1?->nama_jurusan;
                    $m2      = $lastTes?->minatJurusan2?->nama_jurusan;
                    $sesuai  = $lastTes
                        && $lastTes->rekomendasiTeratas
                        && $lastTes->rekomendasiTeratas->jurusan_id === $lastTes->minat_jurusan_1_id;
                @endphp
                <tr>
                    <td style="font-weight:700;color:var(--primary-dark); white-space: nowrap;">{{ $siswa->user->nama ?? '-' }}</td>
                    <td style="font-size:11.5px;color:var(--text-dim); white-space: nowrap;">{{ $siswa->sekolah_asal ?? '-' }}</td>
                    <td style="white-space: nowrap;">
                        @if($rek)
                            <span class="badge badge-blue" style="font-size: 10px; padding: 3px 8px;">{{ $rek }}</span>
                        @else
                            <span class="badge badge-gray" style="font-size: 10px; padding: 3px 8px;">Belum tes</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;"><span class="badge badge-gray" style="font-size: 10px; padding: 3px 8px;">{{ $m1 ?? '-' }}</span></td>
                    <td style="white-space: nowrap;"><span class="badge badge-gray" style="font-size: 10px; padding: 3px 8px;">{{ $m2 ?? '-' }}</span></td>
                    <td style="white-space: nowrap;">
                        @if($lastTes && $lastTes->rekomendasiTeratas)
                            <span class="badge {{ $sesuai ? 'badge-green':'badge-red' }}" style="font-size: 10px; padding: 3px 8px;">
                                {{ $sesuai ? '✅ Sesuai':'⚠ Beda' }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td style="text-align:center;font-weight:700;">{{ $siswa->tes->count() }}</td>
                    <td style="white-space: nowrap;"><a href="{{ route('bk.siswa.show', $siswa->id) }}" class="btn btn-outline btn-sm" style="padding: 4px 10px;">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text-dim);padding:24px;">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid var(--border);">{{ $siswas->links() }}</div>
</div>
@endsection
