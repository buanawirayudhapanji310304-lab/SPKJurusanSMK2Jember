@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
/* ── PAGE HEADER / HERO ── */
.page-hero {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, #2a5298 100%);
    border-radius: 12px;
    padding: 28px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.page-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='160' height='160' viewBox='0 0 160 160' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='white' fill-opacity='0.04'%3E%3Ccircle cx='20' cy='20' r='2'/%3E%3Ccircle cx='80' cy='40' r='2'/%3E%3Ccircle cx='120' cy='100' r='2'/%3E%3Ccircle cx='40' cy='120' r='2'/%3E%3C/g%3E%3C/svg%3E");
    opacity: 1;
    pointer-events: none;
}

.page-hero::after {
    content: '';
    position: absolute;
    top: -60px;
    right: -60px;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    filter: blur(4px);
}

.hero-badge {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 5px 12px;
    border-radius: 999px;
    margin-bottom: 12px;
    backdrop-filter: blur(8px);
}

.hero-title {
    position: relative;
    z-index: 1;
    font-family: 'Poppins', sans-serif;
    font-size: 24px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 6px;
    line-height: 1.2;
}

.hero-sub {
    position: relative;
    z-index: 1;
    font-size: 13px;
    color: rgba(255,255,255,.75);
    max-width: 700px;
}

.hero-action {
    position: relative;
    z-index: 1;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    color: var(--primary-dark);
    font-weight: 700;
    font-size: 12.5px;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    transition: all var(--transition-fast);
    white-space: nowrap;
    box-shadow: var(--shadow-accent);
}

.hero-action:hover {
    transform: translateY(-2px);
    color: var(--primary-dark);
}

/* ── STAT CARDS ── */
.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px 22px;
    position: relative;
    overflow: hidden;
    transition: all var(--transition-normal);
    box-shadow: var(--shadow-soft);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-deep);
}

.stat-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 4px;
}

.stat-card.blue::after   { background: #1a3c6e; }
.stat-card.green::after  { background: #059669; }
.stat-card.gold::after   { background: #e8a020; }
.stat-card.purple::after { background: #7c3aed; }

.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 14px;
}

.stat-icon.blue   { background: #eff6ff; }
.stat-icon.green  { background: #ecfdf5; }
.stat-icon.gold   { background: #fffbeb; }
.stat-icon.purple { background: #f5f3ff; }

.stat-num {
    font-family: 'Poppins', sans-serif;
    font-size: 30px;
    font-weight: 800;
    color: var(--text-dark);
    line-height: 1;
    margin-bottom: 6px;
}

.stat-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-muted);
}

/* ── CONTENT GRID ── */
.content-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 16px;
    margin-bottom: 20px;
}

/* ── PANELS ── */
.panel {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-soft);
    overflow: hidden;
    transition: all var(--transition-normal);
}

.panel:hover {
    box-shadow: var(--shadow-deep);
}

.panel-head {
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    background: #fff;
}

.panel-title {
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 8px;
}

/* ── QUICK LINKS ── */
.quick-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.quick-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px;
    background: #f8fafc;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    text-decoration: none;
    color: var(--text-dark);
    font-size: 12.5px;
    font-weight: 600;
    transition: all var(--transition-fast);
}

.quick-item:hover {
    border-color: var(--accent);
    background: #fffbeb;
    color: var(--text-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26, 60, 110, 0.08);
}

.quick-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
    background: var(--surface);
    border: 1px solid var(--border);
}

/* ── ACTIVITY LOG ── */
.log-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 20px;
    border-bottom: 1px solid var(--border);
}

.log-item:last-child {
    border-bottom: none;
}

.log-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--accent);
    flex-shrink: 0;
    margin-top: 6px;
}

.log-text {
    font-size: 12.5px;
    color: var(--text-dark);
    line-height: 1.5;
}

.log-time {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 2px;
}

/* ── BOTTOM GRID ── */
.bottom-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

@media (max-width: 1100px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .bottom-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 20px;
    }
    
    .hero-action {
        width: 100%;
        justify-content: center;
    }
    
    .quick-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')
@php
    $jurusanIkon = [
        'Alat Berat'   => '🚜', 'Otomotif'    => '🚗', 'Motor'       => '🏍️',
        'Pemesinan'    => '🔧', 'Mekatronika' => '🤖', 'Konstruksi'  => '🏗️',
        'Bangunan'     => '🏗️', 'Listrik'     => '⚡', 'Pembangkit'  => '🔋',
        'Audio'        => '📺', 'Komputer'    => '💻', 'Desain'      => '🎨',
    ];
@endphp

{{-- ── HERO ── --}}
<div class="page-hero">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px;">
        <div style="display:flex; align-items:center; gap:20px;">
            <div style="width:64px; height:64px; background:#fff; border-radius:50%; padding:8px; flex-shrink:0; box-shadow:0 8px 24px rgba(0,0,0,.15);">
                <img src="{{ asset('Assets/Logo_SMKN2Jember.png') }}" alt="Logo" style="width:100%; height:100%; object-fit:contain;">
            </div>
            <div>
                <div class="hero-badge">⚙️ Admin Panel</div>
                <div class="hero-title">Selamat Datang, {{ Auth::user()->nama }}!</div>
                <div class="hero-sub">Panel pengelolaan akun, jurusan, konten, dan monitoring sistem SPK SAW.</div>
            </div>
        </div>
        <a href="{{ route('admin.monitoring.index') }}" class="hero-action">
            📊 Lihat Monitoring
        </a>
    </div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 24px;">
    <div class="stat-card blue">
        <div class="stat-icon blue">👨‍🎓</div>
        <div class="stat-num">{{ $totalSiswa ?? 0 }}</div>
        <div class="stat-label">Total Siswa</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green">👩‍🏫</div>
        <div class="stat-num">{{ $totalGuruBk ?? 0 }}</div>
        <div class="stat-label">Total Guru BK</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon gold">🏫</div>
        <div class="stat-num">{{ $totalJurusan ?? 0 }}</div>
        <div class="stat-label">Total Jurusan</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon purple">📝</div>
        <div class="stat-num">{{ $totalTes ?? 0 }}</div>
        <div class="stat-label">Total Tes</div>
    </div>
</div>

{{-- ── MAIN CONTENT GRID ── --}}
<div class="content-grid">

    {{-- Activity Log --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">📋 Aktivitas Terakhir <span class="fr-tag">FR-A-11</span></div>
            <a href="{{ route('admin.monitoring.index') }}" class="card-link">Lihat semua →</a>
        </div>
        <div>
            @forelse(($recentLogs ?? []) as $log)
            <div class="log-item">
                <div class="log-dot"></div>
                <div>
                    <div class="log-text">
                        <strong>{{ $log->user_nama ?? 'System' }}</strong>
                        — {{ $log->aksi ?? '-' }}
                    </div>
                    <div class="log-time">{{ $log->created_at ?? '-' }}</div>
                </div>
            </div>
            @empty
            <div style="padding:32px 20px;text-align:center;color:var(--text-muted);font-size:13px;">
                Belum ada aktivitas tercatat.
            </div>
            @endforelse
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">⚡ Aksi Cepat</div>
        </div>
        <div style="padding: 20px;">
            <div class="quick-grid">
                <a href="{{ route('admin.gurubk.index') }}" class="quick-item">
                    <div class="quick-icon">👩‍🏫</div>
                    <span>Akun Guru BK</span>
                </a>
                <a href="{{ route('admin.siswa.index') }}" class="quick-item">
                    <div class="quick-icon">👨‍🎓</div>
                    <span>Akun Siswa</span>
                </a>
                <a href="{{ route('admin.jurusan.index') }}" class="quick-item">
                    <div class="quick-icon">🏫</div>
                    <span>Data Jurusan</span>
                </a>
                <a href="{{ route('admin.status.index') }}" class="quick-item">
                    <div class="quick-icon">🔘</div>
                    <span>Status Akun</span>
                </a>
            </div>
        </div>
    </div>

</div>

{{-- ── BOTTOM GRID ── --}}
<div class="bottom-grid">

    {{-- Guru BK Terbaru --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">👩‍🏫 Guru BK <span class="fr-tag">FR-A-02</span></div>
            <a href="{{ route('admin.gurubk.index') }}" class="card-link">Kelola →</a>
        </div>
        <div class="res-table">
            <table class="table-custom">
                <thead>
                    <tr><th>Nama</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse(($recentGuruBk ?? []) as $guru)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:12.5px;">{{ $guru->user->nama ?? '-' }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">NIP: {{ $guru->nip ?? '-' }}</div>
                        </td>
                        <td>
                            @if($guru->user->is_active ?? false)
                                <span class="badge-custom badge-green">Aktif</span>
                            @else
                                <span class="badge-custom badge-red">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:20px;">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Siswa Terbaru --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">👨‍🎓 Siswa Terbaru <span class="fr-tag">FR-A-05</span></div>
            <a href="{{ route('admin.siswa.index') }}" class="card-link">Kelola →</a>
        </div>
        <div class="res-table">
            <table class="table-custom">
                <thead>
                    <tr><th>Nama</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse(($recentSiswa ?? []) as $siswa)
                    <tr>
                        <td>
                            <div style="font-weight:600;font-size:12.5px;">{{ $siswa->user->nama ?? '-' }}</div>
                            <div style="font-size:11px;color:var(--text-muted);">{{ $siswa->sekolah_asal ?? '-' }}</div>
                        </td>
                        <td>
                            @if($siswa->user->is_active ?? false)
                                <span class="badge-custom badge-green">Aktif</span>
                            @else
                                <span class="badge-custom badge-red">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:20px;">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Jurusan --}}
    <div class="panel">
        <div class="panel-head">
            <div class="panel-title">🏫 Jurusan <span class="fr-tag">FR-A-08</span></div>
            <a href="{{ route('admin.jurusan.index') }}" class="card-link">Kelola →</a>
        </div>
        <div class="res-table">
            <table class="table-custom">
                <thead>
                    <tr><th>Jurusan</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse(($jurusans ?? []) as $j)
                    @php
                        $namaJ = $j->nama_jurusan ?? $j->nama ?? '-';
                        $iconJ = '🏫';
                        foreach($jurusanIkon as $keyword => $icon) {
                            if(str_contains(strtolower($namaJ), strtolower($keyword))) {
                                $iconJ = $icon;
                                break;
                            }
                        }
                    @endphp
                    <tr>
                        <td style="font-weight:600;font-size:12.5px;">
                            <span style="margin-right:6px;">{{ $iconJ }}</span>
                            {{ $namaJ }}
                        </td>
                        <td>
                            @if($j->is_active ?? false)
                                <span class="badge-custom badge-green">Aktif</span>
                            @else
                                <span class="badge-custom badge-red">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:20px;">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection