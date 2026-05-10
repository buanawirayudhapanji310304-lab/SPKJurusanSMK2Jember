{{-- resources/views/pages/artikel/show.blade.php --}}
@extends('layouts.landing')

@section('title', $artikel->judul . ' — SPK SAW')

@section('content')

<div class="artikel-container" style="max-width:900px;margin:0 auto;padding:24px 16px 60px;">

    {{-- Breadcrumb --}}
    <div style="font-size:12px;color:#94a3b8;margin-bottom:20px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
        <a href="{{ route('landing.home') }}" style="color:#64748b;text-decoration:none;">Beranda</a>
        <span style="margin:0 6px;">›</span>
        <a href="{{ route('artikel.index') }}" style="color:#64748b;text-decoration:none;">Artikel</a>
        <span style="margin:0 6px;">›</span>
        <span style="color:#0f172a;">{{ Str::limit($artikel->judul, 30) }}</span>
    </div>

    <div class="artikel-grid" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:28px; align-items:start;">

        {{-- ═══ ARTIKEL UTAMA ═══ --}}
        <div class="artikel-main">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,.05);">

                {{-- Gambar --}}
                @if($artikel->gambarUpload)
                    <img src="{{ Storage::url($artikel->gambarUpload->storage_path) }}"
                         alt="{{ $artikel->judul }}"
                         style="width:100%; height: auto; max-height:450px; object-fit:cover;">
                @else
                    <div style="height:200px;background:linear-gradient(135deg,#0f172a,#1e3a5f);display:flex;align-items:center;justify-content:center;font-size:48px;">
                        📄
                    </div>
                @endif

                <div style="padding: 20px 24px 32px;">
                    {{-- Badge --}}
                    <div style="margin-bottom:14px;">
                        <span style="background:var(--blue-bg);color:var(--blue);border:1px solid var(--blue-border);font-size:10.5px;font-weight:700;padding:4px 12px;border-radius:100px;">
                            {{ $artikel->jurusan->nama_jurusan ?? '-' }}
                        </span>
                    </div>

                    {{-- Judul --}}
                    <h1 style="font-family:'Syne',sans-serif;font-size:clamp(20px, 4vw, 28px);font-weight:800;color:#0f172a;line-height:1.3;margin-bottom:16px;">
                        {{ $artikel->judul }}
                    </h1>

                    {{-- Meta --}}
                    <div style="display:flex;align-items:center;gap:16px;padding-bottom:20px;border-bottom:1px solid #f1f5f9;margin-bottom:24px;flex-wrap:wrap;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--accent),var(--accent-light));display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#fff;box-shadow: 0 2px 6px rgba(232, 160, 32, 0.2);">
                                {{ strtoupper(substr($artikel->creator->nama ?? 'G', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $artikel->creator->nama ?? 'Guru BK' }}</div>
                                <div style="font-size:11px;color:#94a3b8;">Guru BK</div>
                            </div>
                        </div>
                        <div style="font-size:12px;color:#94a3b8; display: flex; align-items: center; gap: 4px;">
                            <span>📅 {{ $artikel->created_at?->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="artikel-body" style="font-size:15px;line-height:1.8;color:#374151;white-space:pre-line;">
                        {{ $artikel->deskripsi }}
                    </div>

                    {{-- File pendukung --}}
                    @if($artikel->fileUpload)
                        <div style="margin-top:32px;padding:16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <span style="font-size:28px;">
                                    {{ $artikel->fileUpload->ext === 'PDF' ? '📄' : '🎬' }}
                                </span>
                                <div style="min-width: 0;">
                                    <div style="font-size:13px;font-weight:700;color:#0f172a;">File Pendukung</div>
                                    <div style="font-size:11px;color:#94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px;">{{ $artikel->fileUpload->file_name }}</div>
                                </div>
                            </div>
                            <a href="{{ Storage::url($artikel->fileUpload->storage_path) }}"
                               target="_blank"
                               style="background:#0f172a;color:#fff;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none; flex: 1; text-align: center; min-width: 120px;">
                                ⬇ Unduh File
                            </a>
                        </div>
                    @endif

                    {{-- Tombol kembali --}}
                    <div style="margin-top:36px;padding-top:24px;border-top:1px solid #f1f5f9;">
                        <a href="{{ route('artikel.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;background:#f8fafc;color:#374151;border:1.5px solid #e2e8f0;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:700;text-decoration:none; width: 100%; justify-content: center;">
                            ← Kembali ke Daftar Artikel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ SIDEBAR ═══ --}}
        <div class="artikel-sidebar">

            {{-- Info jurusan --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;margin-bottom:20px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:800;color:#0f172a;margin-bottom:14px; display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 18px;">🏫</span> Tentang Jurusan
                </div>
                <div style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;">
                    {{ $artikel->jurusan->nama_jurusan ?? '-' }}
                </div>
                @if($artikel->jurusan->informasiJurusan)
                    <div style="font-size:13px;color:#64748b;line-height:1.6;">
                        {{ Str::limit($artikel->jurusan->informasiJurusan->fasilitas, 150) }}
                    </div>
                @endif
            </div>

            {{-- Artikel terkait --}}
            @if($terkait->isNotEmpty())
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
                <div style="font-family:'Syne',sans-serif;font-size:13px;font-weight:800;color:#0f172a;margin-bottom:16px; display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 18px;">📚</span> Artikel Terkait
                </div>
                @foreach($terkait as $item)
                <a href="{{ route('artikel.show', $item->id) }}"
                   style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f1f5f9;text-decoration:none;color:inherit;">
                    @if($item->gambarUpload)
                        <img src="{{ Storage::url($item->gambarUpload->storage_path) }}"
                             style="width:60px;height:60px;border-radius:10px;object-fit:cover;flex-shrink:0;">
                    @else
                        <div style="width:60px;height:60px;border-radius:10px;background:linear-gradient(135deg,#0f172a,#1e3a5f);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">📄</div>
                    @endif
                    <div style="min-width: 0;">
                        <div style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $item->judul }}
                        </div>
                        <div style="font-size:11px;color:#94a3b8;margin-top:4px;">
                            {{ $item->created_at?->diffForHumans() }}
                        </div>
                    </div>
                </a>
                @endforeach
                <a href="{{ route('artikel.index', ['jurusan' => $artikel->jurusan_id]) }}"
                   style="display:block;text-align:center;margin-top:16px;font-size:12.5px;font-weight:700;color:#2563eb;text-decoration:none;">
                    Lihat semua artikel →
                </a>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection