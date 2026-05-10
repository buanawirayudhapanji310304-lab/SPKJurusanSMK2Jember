@extends('layouts.bk')
@section('title','Detail Siswa')
@section('page-title','Detail Siswa')
@section('page-sub','FR-BK-05 · Riwayat tes & perbandingan minat vs rekomendasi')

@push('styles')
<style>
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .minat-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: var(--surface2);
        border-radius: 9px;
        margin-bottom: 8px;
    }
    
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        .info-grid {
            grid-template-columns: 1fr;
        }
        .minat-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
        .minat-row .badge {
            align-self: flex-start;
            margin-top: 5px;
        }
        .minat-arrow {
            display: none;
        }
    }
</style>
@endpush

@section('content')

<div style="margin-bottom:16px;">
    <a href="{{ route('bk.siswa.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
</div>

<div class="detail-grid">

    {{-- ═══ INFO SISWA ═══ --}}
    <div class="card" style="padding:24px;">
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
            <div style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#1a3c6e,#2563eb);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($siswa->user->nama ?? 'S', 0, 1)) }}
            </div>
            <div>
                <div style="font-family:'Poppins',sans-serif;font-size:16px;font-weight:800;color:var(--primary-dark);">
                    {{ $siswa->user->nama ?? '-' }}
                </div>
                <div style="font-size:11.5px;color:var(--text-dim);">{{ $siswa->sekolah_asal ?? 'Sekolah asal tidak diisi' }}</div>
            </div>
        </div>

        <div class="info-grid">
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-dim);margin-bottom:3px;">EMAIL</div>
                <div style="font-size:12.5px;color:var(--text); word-break: break-all;">{{ $siswa->user->email ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-dim);margin-bottom:3px;">TELEPON</div>
                <div style="font-size:12.5px;color:var(--text);">{{ $siswa->no_telepon ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-dim);margin-bottom:3px;">GENDER</div>
                <div style="font-size:12.5px;color:var(--text);">
                    @if($siswa->jenis_kelamin === 'L') 👦 Laki-laki
                    @elseif($siswa->jenis_kelamin === 'P') 👧 Perempuan
                    @else -
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-dim);margin-bottom:3px;">JUMLAH TES</div>
                <div style="font-size:12.5px;font-weight:700;color:var(--text);">{{ $riwayatTes->count() }} kali</div>
            </div>
        </div>
    </div>

    {{-- ═══ MINAT VS REKOMENDASI ═══ --}}
    <div class="card" style="padding:24px;">
        <div style="font-family:'Poppins',sans-serif;font-size:13.5px;font-weight:800;color:var(--primary-dark);margin-bottom:14px;">
            🎯 Minat vs Rekomendasi
        </div>

        @php $lastTes = $riwayatTes->first(); @endphp

        @if($lastTes)
            @php
                $rek = $lastTes->rekomendasiTeratas?->jurusan?->nama_jurusan ?? '-';
                $m1  = $lastTes->minatJurusan1?->nama_jurusan ?? '-';
                $m2  = $lastTes->minatJurusan2?->nama_jurusan ?? '-';
                $sesuai1 = $lastTes->rekomendasiTeratas && $lastTes->rekomendasiTeratas->jurusan_id === $lastTes->minat_jurusan_1_id;
                $sesuai2 = $lastTes->rekomendasiTeratas && $lastTes->rekomendasiTeratas->jurusan_id === $lastTes->minat_jurusan_2_id;
            @endphp

            <div class="minat-row">
                <div style="flex:1;font-size:12px;">
                    <div style="font-size:10px;color:var(--text-dim);margin-bottom:2px;">Minat Pertama</div>
                    <strong>{{ $m1 }}</strong>
                </div>
                <div class="minat-arrow" style="color:var(--text-dim);">→</div>
                <div style="flex:1;font-size:12px;">
                    <div style="font-size:10px;color:var(--text-dim);margin-bottom:2px;">Rekomendasi SAW</div>
                    <strong>{{ $rek }}</strong>
                </div>
                <span class="badge {{ $sesuai1 ? 'badge-green' : 'badge-red' }}">
                    {{ $sesuai1 ? '✅ Sesuai' : 'Beda' }}
                </span>
            </div>

            <div class="minat-row" style="margin-bottom:12px;">
                <div style="flex:1;font-size:12px;">
                    <div style="font-size:10px;color:var(--text-dim);margin-bottom:2px;">Minat Kedua</div>
                    <strong>{{ $m2 }}</strong>
                </div>
                <div class="minat-arrow" style="color:var(--text-dim);">→</div>
                <div style="flex:1;font-size:12px;">
                    <div style="font-size:10px;color:var(--text-dim);margin-bottom:2px;">Rekomendasi SAW</div>
                    <strong>{{ $rek }}</strong>
                </div>
                <span class="badge {{ $sesuai2 ? 'badge-green' : 'badge-red' }}">
                    {{ $sesuai2 ? '✅ Sesuai' : 'Beda' }}
                </span>
            </div>

            <div style="background:var(--yellow-bg);border:1px solid var(--yellow-border);border-radius:9px;padding:10px 14px;font-size:11.5px;color:var(--yellow);">
                💡 Minat awal tidak mempengaruhi SAW, hanya referensi konseling.
            </div>
        @else
            <div style="text-align:center;padding:24px;color:var(--text-dim);font-size:13px;">
                Siswa belum mengerjakan tes.
            </div>
        @endif
    </div>

</div>

{{-- ═══ RIWAYAT TES ═══ --}}
<div class="card">
    <div class="card-head">
        <div class="card-title">📋 Riwayat Tes ({{ $riwayatTes->count() }} kali)</div>
    </div>
    <div class="res-table">
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:140px;">Waktu Tes</th>
                    <th>Rekomendasi Utama</th>
                    <th>Minat Awal Siswa</th>
                    <th style="width:100px; text-align:center;">Skor Minat</th>
                    <th style="width:80px; text-align:center;">Buta Warna</th>
                    <th style="width:160px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatTes as $i => $tes)
                @php
                    $rekTes = $tes->rekomendasiTeratas?->jurusan?->nama_jurusan ?? '-';
                    $m1Tes  = $tes->minatJurusan1?->nama_jurusan ?? '-';
                    $m2Tes  = $tes->minatJurusan2?->nama_jurusan ?? '-';
                    $skor   = (float) ($tes->skor_minat_bakat ?? 0);
                    $skorColor = $skor >= 7 ? 'var(--green)' : ($skor >= 5 ? 'var(--yellow)' : 'var(--red)');
                @endphp
                <tr>
                    <td><span style="color:var(--text-dim); font-weight:700;">{{ $i + 1 }}</span></td>
                    <td style="white-space: nowrap;">
                        <div style="font-weight:600; font-size:12.5px;">{{ $tes->created_at->format('d/m/Y') }}</div>
                        <div style="font-size:11px; color:var(--text-dim);">Jam {{ $tes->created_at->format('H:i') }}</div>
                    </td>
                    <td>
                        @if($rekTes !== '-')
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:8px; height:8px; border-radius:50%; background:var(--blue);"></div>
                                <span style="font-weight:700; color:var(--primary-dark);">{{ $rekTes }}</span>
                            </div>
                        @else
                            <span class="badge badge-gray">-</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span style="font-size:10px; padding:2px 6px; background:#f1f5f9; border-radius:4px; font-weight:700; color:#64748b;">1</span>
                                <span style="font-size:12px; color:var(--text-mid);">{{ $m1Tes }}</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span style="font-size:10px; padding:2px 6px; background:#f1f5f9; border-radius:4px; font-weight:700; color:#64748b;">2</span>
                                <span style="font-size:12px; color:var(--text-mid);">{{ $m2Tes }}</span>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <div style="font-weight:800; font-size:14px; color:{{ $skorColor }};">{{ $skor }}/10</div>
                        <div style="width:60px; height:4px; background:#e2e8f0; border-radius:10px; margin:4px auto 0; overflow:hidden;">
                            <div style="width:{{ ($skor/10)*100 }}%; height:100%; background:{{ $skorColor }};"></div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        @if($tes->buta_warna)
                            <span class="badge badge-red" style="font-size:10px;">Ya</span>
                        @else
                            <span class="badge badge-green" style="font-size:10px;">Tidak</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:6px; justify-content: flex-end; flex-wrap: wrap;">
                            <a href="{{ route('bk.siswa.hasil', [$siswa->id, $tes->id]) }}"
                               target="_blank"
                               class="btn btn-outline btn-sm"
                               title="Lihat rincian perhitungan">
                                📊 Detail
                            </a>
                            <a href="{{ route('bk.siswa.pdf', [$siswa->id, $tes->id]) }}"
                               class="btn btn-primary btn-sm"
                               title="Download laporan PDF">
                                📄 PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--text-dim);padding:40px;">
                        <div style="font-size:24px; margin-bottom:10px;">📝</div>
                        Siswa ini belum memiliki riwayat tes apapun.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection