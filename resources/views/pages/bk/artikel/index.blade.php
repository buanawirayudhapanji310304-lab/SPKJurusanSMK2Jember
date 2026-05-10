@extends('layouts.bk')
@section('title','Artikel Jurusan')
@section('page-title','Artikel Jurusan')
@section('page-sub','FR-BK-07 · Kelola artikel jurusan')

@section('content')

<div style="display:flex; justify-content: space-between; align-items: center; margin-bottom:16px; flex-wrap: wrap; gap: 10px;">
    <div style="font-size: 14px; color: var(--text-dim);">Total: <strong>{{ $artikels->total() }}</strong> Artikel</div>
    <a href="{{ route('bk.artikel.create') }}" class="btn btn-primary" style="width: auto;">➕ Tambah Artikel</a>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap:16px;">
    
    @forelse($artikels as $a)
    <div class="card"
         onclick="window.location='{{ route('artikel.show', $a->id) }}'"
         style="overflow:hidden;cursor:pointer;transition:0.2s; display: flex; flex-direction: column;">

        {{-- Gambar --}}
        @if($a->gambarUpload)
            <img src="{{ Storage::url($a->gambarUpload->storage_path) }}"
                 style="width:100%;height:150px;object-fit:cover;"/>
        @else
            <div style="height:150px;
                        background:linear-gradient(135deg,var(--primary-dark),var(--primary));
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:40px;">
                📄
            </div>
        @endif

        {{-- Content --}}
        <div style="padding:14px; flex: 1;">
            <div style="font-size:10px;
                        font-weight:700;
                        text-transform:uppercase;
                        color:var(--accent);
                        margin-bottom:5px;">
                {{ $a->jurusan->nama_jurusan ?? '-' }}
            </div>

            <div style="font-size:13.5px;
                        font-weight:700;
                        color:var(--primary-dark);
                        line-height:1.4;
                        margin-bottom:8px;">
                {{ $a->judul }}
            </div>

            <div style="font-size:11px;color:var(--text-dim); display: flex; align-items: center; gap: 4px;">
                <span>📅 {{ $a->created_at->translatedFormat('d M Y') }}</span>
            </div>

            @if($a->fileUpload)
                <div style="font-size:11px;color:var(--blue);margin-top:6px; background: var(--blue-bg); padding: 4px 8px; border-radius: 6px; display: inline-block;">
                    📎 {{ Str::limit($a->fileUpload->file_name, 25) }}
                </div>
            @endif
        </div>

        {{-- Action --}}
        <div style="padding:12px 14px;
                    border-top:1px solid var(--border);
                    display:flex;
                    gap:8px;">

            <a href="{{ route('bk.artikel.edit', $a->id) }}"
               onclick="event.stopPropagation()"
               class="btn btn-outline btn-sm" style="flex: 1; justify-content: center;">
               ✏️ Edit
            </a>

            <form method="POST"
                  action="{{ route('bk.artikel.destroy', $a->id) }}"
                  onclick="event.stopPropagation()"
                  onsubmit="return confirm('Hapus artikel ini?')"
                  style="flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="width: 100%; justify-content: center;">
                    🗑️ Hapus
                </button>
            </form>

        </div>
    </div>

    @empty
    <div style="grid-column:1/-1;
                text-align:center;
                padding:48px;
                background: var(--surface2);
                border-radius: var(--radius);
                color:var(--text-dim);">
        Belum ada artikel.
        <br>
        <a href="{{ route('bk.artikel.create') }}"
           style="color:var(--primary); font-weight: 700; margin-top: 10px; display: inline-block;">
           Tambah sekarang
        </a>
    </div>
    @endforelse

</div>

<div style="margin-top:16px;">
    {{ $artikels->links() }}
</div>

@endsection

