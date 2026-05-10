@extends('layouts.admin')
@section('title', isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan')
@section('content')

<a href="{{ route('admin.jurusan.index') }}" class="back-link">← Kembali ke Daftar Jurusan</a>

<div class="page-title-row">
    <div>
        <div class="page-title">{{ isset($jurusan) ? '✏️ Edit Jurusan' : '➕ Tambah Jurusan' }}</div>
        <div class="page-subtitle">
            {{ isset($jurusan)
                ? 'Perbarui data jurusan beserta bobot setiap kriteria untuk perhitungan SPK metode SAW.'
                : 'Tambahkan jurusan baru beserta bobot setiap kriteria agar dapat langsung digunakan dalam perhitungan SPK metode SAW.' }}
        </div>
    </div>
</div>

<div class="form-card-custom">
    <form method="POST" action="{{ isset($jurusan) ? route('admin.jurusan.update', $jurusan->id) : route('admin.jurusan.store') }}">
        @csrf
        @if(isset($jurusan))
            @method('PUT')
        @endif

        {{-- Data Jurusan --}}
        <div style="margin-bottom:22px;">
            <p style="font-size:13px;font-weight:700;color:var(--text-dark);margin:0 0 14px 0;">Data Jurusan</p>

            <div style="margin-bottom:14px;">
                <label class="form-label-custom">Nama Jurusan *</label>
                <input
                    type="text"
                    name="nama_jurusan"
                    value="{{ old('nama_jurusan', $jurusan->nama_jurusan ?? '') }}"
                    placeholder="Contoh: Teknik Komputer dan Jaringan"
                    class="form-control-custom"
                >
                @error('nama_jurusan')
                    <div style="color:var(--red);font-size:11.5px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label-custom">Status *</label>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;flex:1;min-width:130px;font-size:13px;font-weight:600;color:var(--text-dark);">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $jurusan->is_active ?? 1) == 1 ? 'checked' : '' }}>
                        ✅ Aktif
                    </label>
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:1.5px solid var(--border);border-radius:10px;flex:1;min-width:130px;font-size:13px;font-weight:600;color:var(--text-dark);">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $jurusan->is_active ?? 1) == 0 ? 'checked' : '' }}>
                        ❌ Nonaktif
                    </label>
                </div>
                @error('is_active')
                    <div style="color:var(--red);font-size:11.5px;margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="border-top:1px solid var(--border);margin-bottom:22px;"></div>

        {{-- Bobot Kriteria --}}
        <div style="margin-bottom:6px;">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:14px;">
                <div>
                    <p style="font-size:13px;font-weight:700;color:var(--text-dark);margin:0 0 4px 0;">Bobot Kriteria</p>
                    <p style="font-size:12px;color:var(--text-muted);margin:0;">Isi bobot setiap kriteria. Total bobot ideal adalah <strong>1.00</strong>.</p>
                </div>
                <div style="padding:10px 14px;border-radius:10px;background:#f8fafc;border:1px solid var(--border);">
                    <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);margin-bottom:4px;">Total Bobot</div>
                    <div id="total-bobot" style="font-size:18px;font-weight:800;color:var(--text-dark);">0.00</div>
                </div>
            </div>

            @if ($errors->has('bobot') || $errors->has('bobot.*'))
                <div style="margin-bottom:12px;padding:10px 14px;border-radius:10px;background:var(--rbg);border:1px solid var(--rb);color:#b91c1c;font-size:12px;">
                    Terdapat kesalahan pada input bobot. Pastikan semua bobot telah diisi dengan benar.
                </div>
            @endif

            @if ($errors->has('wajib_lolos.*') || $errors->has('nilai_min.*') || $errors->has('nilai_max.*') || $errors->has('penyakit_larangan'))
                <div style="margin-bottom:12px;padding:10px 14px;border-radius:10px;background:var(--rbg);border:1px solid var(--rb);color:#b91c1c;font-size:12px;">
                    Terdapat kesalahan pada aturan wajib lolos C5/C6/C8. Pastikan pengaturan sudah diisi dengan benar.
                </div>
            @endif

            <div class="res-table">
                <table class="table-custom" style="min-width:760px;">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Kriteria</th>
                            <th>Atribut</th>
                            <th style="width:160px;">Bobot</th>
                            <th style="width:340px;">Aturan Wajib Lolos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kriterias as $kriteria)
                            @php
                                $defaultBobot = $bobotMap[$kriteria->id] ?? 0;
                                $nilaiBobot = old("bobot.{$kriteria->id}", $defaultBobot);
                                $aturan = $aturanMap[$kriteria->id] ?? null;
                                // Perluas kriteria yang bisa wajib lolos
                                $isAturanWajib = in_array($kriteria->kode_kriteria, ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8'], true);
                                $isPenyakit = $kriteria->kode_kriteria === 'C8';
                                $isFisik = $kriteria->kode_kriteria === 'C5';
                                $isButaWarna = $kriteria->kode_kriteria === 'C6';
                                
                                $wajibLolos = old("wajib_lolos.{$kriteria->id}", $aturan?->wajib_lolos ? 1 : 0);
                                $nilaiMin = old("nilai_min.{$kriteria->id}", $aturan?->nilai_min);
                                $nilaiMax = old("nilai_max.{$kriteria->id}", $aturan?->nilai_max);
                                $nilaiMaxInput = $isButaWarna ? 1 : 100;
                                $nilaiStepInput = $isButaWarna ? 1 : 1;
                            @endphp
                            <tr>
                                <td style="font-weight:700;font-size:12px;">{{ $kriteria->kode_kriteria }}</td>
                                <td>{{ $kriteria->nama_kriteria }}</td>
                                <td>
                                 <span class="badge-custom badge-green">benefit</span>
                                </td>
                                <td>
                                    <input
                                        type="number"
                                        name="bobot[{{ $kriteria->id }}]"
                                        value="{{ number_format((float)$nilaiBobot, 3) }}"
                                        step="0.001"
                                        min="0"
                                        max="1"
                                        class="bobot-input form-control-custom"
                                        placeholder="0.000"
                                        style="padding:8px 12px;"
                                    >
                                    @error("bobot.$kriteria->id")
                                        <div style="color:var(--red);font-size:11px;margin-top:4px;">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @if($isAturanWajib)
                                        <div style="display:grid;gap:8px;">
                                            <input type="hidden" name="wajib_lolos[{{ $kriteria->id }}]" value="0">
                                            <label style="display:flex;align-items:center;gap:8px;font-size:12px;font-weight:700;color:var(--text-dark);cursor:pointer;">
                                                <input
                                                    type="checkbox"
                                                    name="wajib_lolos[{{ $kriteria->id }}]"
                                                    value="1"
                                                    {{ (int) $wajibLolos === 1 ? 'checked' : '' }}
                                                >
                                                Wajib lolos
                                            </label>
                                            
                                            @if($isPenyakit)
                                                <div style="font-size:11px;color:var(--text-muted);line-height:1.4;">
                                                    Jika wajib lolos aktif, siswa dengan penyakit yang dipilih di bawah akan didiskualifikasi.
                                                </div>
                                            @elseif($isFisik)
                                                {{-- Dropdown untuk Fisik --}}
                                                <div>
                                                    <select name="nilai_min[{{ $kriteria->id }}]" class="form-control-custom" style="padding:8px 10px; font-size: 12px;">
                                                        <option value="">-- Pilih Batas Minimal --</option>
                                                        <option value="100" {{ $nilaiMin == 100 ? 'selected' : '' }}>100 (Normal)</option>
                                                        <option value="80" {{ $nilaiMin == 80 ? 'selected' : '' }}>80 (Overweight)</option>
                                                        <option value="70" {{ $nilaiMin == 70 ? 'selected' : '' }}>70 (Kurus/Obesitas)</option>
                                                    </select>
                                                    @error("nilai_min.$kriteria->id")
                                                        <div style="color:var(--red);font-size:11px;margin-top:4px;">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div style="font-size:11px;color:var(--text-muted);line-height:1.4;">
                                                    Pilih batas minimal kategori fisik yang diperbolehkan.
                                                </div>
                                            @elseif($isButaWarna)
                                                {{-- Buta Warna tidak perlu input lagi, cukup checkbox di atas --}}
                                                <div style="font-size:11px;color:var(--text-muted);line-height:1.4; padding: 4px 0;">
                                                    ⚠️ Jika <strong>Wajib Lolos</strong> aktif, siswa yang buta warna akan didiskualifikasi (Hard Constraint). Jika nonaktif, maka diperbolehkan tanpa pinalti.
                                                </div>
                                            @else
                                                <div style="display:grid;grid-template-columns:1fr;gap:8px;">
                                                    <div>
                                                        <input
                                                            type="number"
                                                            name="nilai_min[{{ $kriteria->id }}]"
                                                            value="{{ $nilaiMin !== null && $nilaiMin !== '' ? rtrim(rtrim(number_format((float) $nilaiMin, 2, '.', ''), '0'), '.') : '' }}"
                                                            step="{{ $nilaiStepInput }}"
                                                            min="0"
                                                            max="{{ $nilaiMaxInput }}"
                                                            class="form-control-custom"
                                                            placeholder="Batas Minimal"
                                                            style="padding:8px 10px;"
                                                        >
                                                        @error("nilai_min.$kriteria->id")
                                                            <div style="color:var(--red);font-size:11px;margin-top:4px;">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    {{-- Field Max disembunyikan sesuai permintaan --}}
                                                    <input type="hidden" name="nilai_max[{{ $kriteria->id }}]" value="{{ $nilaiMax }}">
                                                </div>
                                                <div style="font-size:11px;color:var(--text-muted);line-height:1.4;">
                                                    Gunakan skala 0-100 untuk nilai rapor / minat bakat.
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span style="font-size:12px;color:var(--text-muted);">Tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--text-muted);padding:24px;">
                                    Belum ada data kriteria aktif.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="bobot-warning" style="display:none;margin-top:10px;" class="warn-box-custom">
                <div class="warn-title">⚠️ Total Belum 1.00</div>
                <div style="font-size:12px;color:#92400e;">Total bobot saat ini belum sama dengan <strong>1.00</strong>. Harap sesuaikan kembali.</div>
            </div>
        </div>

        <div style="border-top:1px solid var(--border);margin:22px 0;"></div>

        <div style="margin-bottom:18px;">
            <p style="font-size:13px;font-weight:700;color:var(--text-dark);margin:0 0 4px 0;">Aturan Penyakit</p>
            <p style="font-size:12px;color:var(--text-muted);margin:0 0 12px 0;">Pilih penyakit yang tidak cocok untuk jurusan ini. Aturan ini dipakai oleh kriteria C8.</p>

            @error('penyakit_larangan')
                <div style="color:var(--red);font-size:11.5px;margin-bottom:8px;">{{ $message }}</div>
            @enderror

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:10px;">
                @forelse($penyakits as $penyakit)
                    <label style="display:flex;align-items:center;gap:8px;padding:10px 12px;border:1px solid var(--border);border-radius:10px;background:#f8fafc;font-size:12px;font-weight:600;color:var(--text-dark);cursor:pointer;">
                        <input
                            type="checkbox"
                            name="penyakit_larangan[]"
                            value="{{ $penyakit->id }}"
                            {{ in_array($penyakit->id, old('penyakit_larangan', $penyakitTerlarangIds), false) ? 'checked' : '' }}
                        >
                        {{ $penyakit->nama_penyakit }}
                    </label>
                @empty
                    <div style="grid-column:1/-1;color:var(--text-muted);font-size:12px;padding:12px;border:1px dashed var(--border);border-radius:10px;">
                        Belum ada data penyakit aktif. Tambahkan dari menu Data Penyakit.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Actions --}}
        <div class="form-actions-custom">
            <a href="{{ route('admin.jurusan.index') }}" class="btn-custom btn-ghost-custom">Batal</a>
            <button type="submit" class="btn-custom btn-dark-custom">✓ Simpan</button>
        </div>
    </form>
</div>

<script>
    function hitungTotalBobot() {
        const inputs = document.querySelectorAll('.bobot-input');
        let total = 0;
        inputs.forEach(function(input) {
            const nilai = parseFloat(input.value);
            if (!isNaN(nilai)) total += nilai;
        });
        const totalEl = document.getElementById('total-bobot');
        const warningEl = document.getElementById('bobot-warning');
        totalEl.textContent = total.toFixed(3);
        if (Math.abs(total - 1) > 0.001) {
            totalEl.style.color = '#b45309';
            warningEl.style.display = 'block';
        } else {
            totalEl.style.color = 'var(--green)';
            warningEl.style.display = 'none';
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.bobot-input').forEach(function(input) {
            input.addEventListener('input', hitungTotalBobot);
        });
        hitungTotalBobot();
    });
</script>

@endsection
