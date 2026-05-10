<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Tes;
use App\Models\SoalMinat;
use App\Models\JawabanMinat;
use App\Models\Jurusan;
use App\Models\HasilSaw;
use App\Models\Upload;
use App\Models\TesPdf;
use App\Models\Penyakit;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;

class SpkController extends Controller
{
    /**
     * Tampilkan halaman tes siswa
     */
    public function index()
    {
        $user = Auth::user();

        $siswa = Siswa::with('user')->where('user_id', $user->id)->first();
        if (!$siswa) {
            return redirect()->route('landing.home')
                ->withErrors('Akses ditolak: akun ini bukan siswa.');
        }

        $soal = SoalMinat::where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($soal->count() === 0) {
            session()->flash('info', 'Belum ada soal minat aktif. Hubungi admin untuk melengkapi data soal.');
        }

        $tesTerakhir = Tes::with('penyakit')->where('siswa_id', $siswa->id)->latest()->first();

        $riwayatHasil = $tesTerakhir
            ? HasilSaw::where('tes_id', $tesTerakhir->id)->orderBy('peringkat')->get()
            : collect();

        $jurusan = Jurusan::where('is_active', true)
            ->orderBy('nama_jurusan')
            ->get();
        $penyakits = Penyakit::where('is_active', true)
            ->orderBy('nama_penyakit')
            ->get();

        return view('pages.siswa.tes', compact(
            'siswa',
            'soal',
            'tesTerakhir',
            'riwayatHasil',
            'jurusan',
            'penyakits'
        ));
    }

    /**
     * Simpan tes siswa, jawaban minat, hitung SAW, dan generate PDF
     */
    public function store(Request $request)
    {
        $user  = Auth::user();
        $siswa = Siswa::with('user')->where('user_id', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('landing.home')
                ->withErrors('Akses ditolak: akun ini bukan siswa.');
        }

        $soalAktif = SoalMinat::where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($soalAktif->count() === 0) {
            return back()
                ->withErrors('Belum ada soal minat aktif. Hubungi admin.')
                ->withInput();
        }

        $rules = [
            'jurusan_pilihan_1' => 'required|exists:jurusan,id',
            'jurusan_pilihan_2' => 'required|exists:jurusan,id|different:jurusan_pilihan_1',

            'tinggi_badan'  => 'required|numeric|min:100|max:220',
            'berat_badan'   => 'required|numeric|min:20|max:200',
            'buta_warna'    => 'required|in:ya,tidak',
            'penyakit_id'    => 'nullable|exists:penyakit,id',

            'nilai_matematika'       => 'required|numeric|min:0|max:100',
            'nilai_bahasa_indonesia' => 'required|numeric|min:0|max:100',
            'nilai_bahasa_inggris'   => 'required|numeric|min:0|max:100',
            'nilai_ipa'              => 'required|numeric|min:0|max:100',

            'setuju' => 'required|in:1',
        ];

        foreach ($soalAktif as $index => $item) {
            $nomor = $index + 1;
            $rules["bakat_q{$nomor}"] = 'required|integer|min:1|max:4';
        }

        $validated = $request->validate($rules);

        $jawabanBakat = [];
        foreach ($soalAktif as $index => $item) {
            $nomor = $index + 1;
            $jawabanBakat[] = (int) $validated["bakat_q{$nomor}"];
        }

        $sumSkor = array_sum($jawabanBakat);
        $jumlahSoal = count($jawabanBakat);
        $skorMinatBakat = (int) round(($sumSkor / ($jumlahSoal * 4)) * 100);

        DB::transaction(function () use ($validated, $siswa, $skorMinatBakat, $jawabanBakat, $soalAktif) {
            $tes = Tes::create([
                'siswa_id'               => $siswa->id,

                'nilai_matematika'       => $validated['nilai_matematika'],
                'nilai_bahasa_indonesia' => $validated['nilai_bahasa_indonesia'],
                'nilai_bahasa_inggris'   => $validated['nilai_bahasa_inggris'],
                'nilai_ipa'              => $validated['nilai_ipa'],

                'skor_minat_bakat'       => $skorMinatBakat,
                'tinggi_badan'           => $validated['tinggi_badan'],
                'berat_badan'            => $validated['berat_badan'],
                'buta_warna'             => ($validated['buta_warna'] === 'ya'),
                'penyakit_id'             => $validated['penyakit_id'] ?? null,

                'minat_jurusan_1_id'     => $validated['jurusan_pilihan_1'],
                'minat_jurusan_2_id'     => $validated['jurusan_pilihan_2'],
            ]);

            foreach ($soalAktif as $index => $soal) {
                JawabanMinat::create([
                    'tes_id'        => $tes->id,
                    'soal_minat_id' => $soal->id,
                    'skor'          => $jawabanBakat[$index],
                ]);
            }

            $this->prosesSaw($tes);

            $ok = $this->generateAndStorePdf($tes);

            if (!$ok) {
                Log::warning('PDF gagal dibuat saat penyimpanan tes', [
                    'tes_id' => $tes->id,
                ]);
            }
        });

        ActivityLogger::log("Siswa cek minat bakat: skor {$skorMinatBakat}");

        return redirect()->route('siswa.tes.hasil');
    }

    /**
     * Proses SAW:
     * - Alternatif = jurusan
     * - Kriteria = 8 kriteria final
     * - Aturan wajib_lolos diperlakukan sebagai aturan bisnis tambahan
     */
    private function prosesSaw(Tes $tes): void
    {
        $jurusans = Jurusan::with(['jurusanKriteria.kriteria', 'penyakit'])
            ->where('is_active', true)
            ->get();

        // ambil nilai siswa (0–1)
        $nilaiDasar = $this->getNilaiKriteriaSiswa($tes);

        $rows = [];

        foreach ($jurusans as $jurusan) {

            // 🔵 mapping kriteria biar rapi
            $map = $this->mapJurusanKriteriaByKode($jurusan);
            $nilai = $nilaiDasar;
            $nilai['C8'] = $this->getSkorPenyakitUntukJurusan($tes, $jurusan);

            // 🔴 1. WAJIB LOLOS (HARD CONSTRAINT)
            if ($this->isJurusanDiskualifikasi($map, $nilai)) {
                $rows[] = [
                    'jurusan_id' => $jurusan->id,
                    'nilai_preferensi' => 0,
                ];
                continue;
            }

            $nilaiPreferensi = 0;

            // 🔵 2. LOOP KRITERIA
            foreach ($map as $kode => $jk) {

                if (!isset($nilai[$kode])) continue;

                $nilaiSiswa = $nilai[$kode];
                $bobot = (float) $jk->bobot;

                // 🟡 3. PENYESUAIAN MIN & MAX
                $nilaiFix = $this->hitungNilaiKecocokan(
                    $nilaiSiswa,
                    $jk->nilai_min,
                    $jk->nilai_max,
                    $kode
                );

                // 🟢 4. RUMUS SAW (TETAP)
                $nilaiPreferensi += $bobot * $nilaiFix;
            }

            $rows[] = [
                'jurusan_id' => $jurusan->id,
                'nilai_preferensi' => round($nilaiPreferensi, 6),
            ];
        }

        // 🔽 SORTING
        usort($rows, fn($a, $b) => $b['nilai_preferensi'] <=> $a['nilai_preferensi']);

        // 🔄 SIMPAN ULANG
        HasilSaw::where('tes_id', $tes->id)->delete();

        foreach ($rows as $i => $row) {
            HasilSaw::create([
                'tes_id' => $tes->id,
                'jurusan_id' => $row['jurusan_id'],
                'nilai_preferensi' => $row['nilai_preferensi'],
                'peringkat' => $i + 1,
            ]);
        }
    }

    /**
     * Nilai final 8 kriteria siswa (Normalisasi 0-1)
     */
    private function getNilaiKriteriaSiswa(Tes $tes): array
    {
        return [
            'C1' => $tes->nilai_matematika / 100,
            'C2' => $tes->nilai_bahasa_indonesia / 100,
            'C3' => $tes->nilai_ipa / 100,
            'C4' => $tes->nilai_bahasa_inggris / 100,
            'C5' => $this->getSkorFisikDariBmi($tes->tinggi_badan, $tes->berat_badan) / 100,
            'C6' => $tes->buta_warna ? 0 : 1,
            'C7' => $tes->skor_minat_bakat / 100,
        ];
    }

    private function getSkorPenyakitUntukJurusan(Tes $tes, Jurusan $jurusan): int
    {
        if (!$tes->penyakit_id) {
            return 1;
        }

        return $jurusan->penyakit->contains('id', $tes->penyakit_id) ? 0 : 1;
    }

    /**
     * Hitung BMI siswa
     */
    private function hitungBmi(float $tinggiBadan, float $beratBadan): float
    {
        $tinggiMeter = $tinggiBadan / 100;
        if ($tinggiMeter <= 0) return 0;
        return round($beratBadan / ($tinggiMeter * $tinggiMeter), 2);
    }

    /**
     * Konversi BMI menjadi skor fisik
     */
    private function getSkorFisikDariBmi(float $tinggiBadan, float $beratBadan): int
    {
        $bmi = $this->hitungBmi($tinggiBadan, $beratBadan);

        if ($bmi >= 18.5 && $bmi <= 24.9) {
            return 100;
        }

        if ($bmi >= 25 && $bmi <= 29.9) {
            return 80;
        }

        return 70;
    }

    /**
     * Ubah koleksi jurusanKriteria menjadi map berdasarkan kode kriteria
     */
    private function mapJurusanKriteriaByKode($jurusan): array
    {
        $map = [];
        foreach ($jurusan->jurusanKriteria as $jurusanKriteria) {
            $kode = $jurusanKriteria->kriteria?->kode_kriteria;
            if ($kode) {
                $map[$kode] = $jurusanKriteria;
            }
        }
        return $map;
    }

    /**
     * Hitung nilai kecocokan siswa terhadap aturan jurusan
     */
    private function hitungNilaiKecocokan(float $nilaiSiswa, $nilaiMin = null, $nilaiMax = null, $kodeKriteria = null): float
    {
        // Khusus C6 & C8 adalah biner (0/1), kriteria lain 0-100 di database
        $isBinary = in_array($kodeKriteria, ['C6', 'C8']);
        $divider  = $isBinary ? 1 : 100;

        $nilaiMin = is_null($nilaiMin) ? null : $nilaiMin / $divider;
        $nilaiMax = is_null($nilaiMax) ? null : $nilaiMax / $divider;

        $score = $nilaiSiswa;

        // 🔻 Jika di bawah minimum → penalti
        if (!is_null($nilaiMin) && $nilaiMin > 0 && $nilaiSiswa < $nilaiMin) {
            $score = ($nilaiSiswa / $nilaiMin) * $nilaiSiswa;
        }

        // 🔺 Jika di atas maksimum → penalti
        if (!is_null($nilaiMax) && $nilaiSiswa > 0 && $nilaiSiswa > $nilaiMax) {
            $score = ($nilaiMax / $nilaiSiswa) * $score;
        }

        return round(max($score, 0), 6);
    }

    /**
     * Aturan bisnis tambahan:
     * Jika kriteria wajib_lolos tidak terpenuhi, jurusan didiskualifikasi.
     */
    private function isJurusanDiskualifikasi(array $mapJurusanKriteria, array $nilaiSiswa): bool
    {
        foreach ($mapJurusanKriteria as $kode => $jurusanKriteria) {
            if (!(bool) $jurusanKriteria->wajib_lolos) {
                continue;
            }

            $nilai = $nilaiSiswa[$kode] ?? null;
            if (is_null($nilai)) continue;

            // C6 & C8 biner (0/1), sisanya 0-100 di database
            $isBinary = in_array($kode, ['C6', 'C8']);
            $divider  = $isBinary ? 1 : 100;

            $nilaiMin = is_null($jurusanKriteria->nilai_min) ? null : $jurusanKriteria->nilai_min / $divider;
            $nilaiMax = is_null($jurusanKriteria->nilai_max) ? null : $jurusanKriteria->nilai_max / $divider;

            if (!is_null($nilaiMin) && $nilai < $nilaiMin) return true;
            if (!is_null($nilaiMax) && $nilai > $nilaiMax) return true;
        }

        return false;
    }

    /**
     * Tampilkan hasil tes terakhir
     */
    public function hasil()
    {
        $user  = Auth::user();
        $siswa = Siswa::with('user')->where('user_id', $user->id)->first();

        if (!$siswa) {
            return redirect()->route('landing.home')
                ->withErrors('Akses ditolak: akun ini bukan siswa.');
        }

        $tesTerakhir = Tes::where('siswa_id', $siswa->id)->latest()->first();
        if (!$tesTerakhir) {
            return redirect()->route('siswa.tes.index');
        }

        $hasilList = HasilSaw::where('tes_id', $tesTerakhir->id)
            ->with('jurusan')
            ->orderBy('peringkat')
            ->get();

        if ($hasilList->isEmpty()) {
            return redirect()->route('siswa.tes.index');
        }

        $jurusanPilihan1 = Jurusan::find($tesTerakhir->minat_jurusan_1_id);
        $jurusanPilihan2 = Jurusan::find($tesTerakhir->minat_jurusan_2_id);

        $skorPilihan1 = $tesTerakhir->minat_jurusan_1_id
            ? $hasilList->firstWhere('jurusan_id', $tesTerakhir->minat_jurusan_1_id)
            : null;

        $skorPilihan2 = $tesTerakhir->minat_jurusan_2_id
            ? $hasilList->firstWhere('jurusan_id', $tesTerakhir->minat_jurusan_2_id)
            : null;

        return view('pages.siswa.hasil', compact(
            'siswa',
            'tesTerakhir',
            'hasilList',
            'jurusanPilihan1',
            'jurusanPilihan2',
            'skorPilihan1',
            'skorPilihan2'
        ));
    }

    /**
     * Download PDF hasil tes terakhir
     */
    public function cetakPdf()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa) abort(403);

        $tes = Tes::with('penyakit')->where('siswa_id', $siswa->id)->latest()->first();
        if (!$tes) abort(404);

        $tesPdf = TesPdf::with('upload')->where('tes_id', $tes->id)->first();

        if (!$tesPdf || !$tesPdf->upload) {
            $ok = $this->generateAndStorePdf($tes);
            if (!$ok) {
                return redirect()->route('siswa.tes.hasil')->withErrors('Gagal membuat PDF.');
            }
            $tesPdf = TesPdf::with('upload')->where('tes_id', $tes->id)->first();
        }

        $filePath = storage_path('app/public/' . $tesPdf->upload->storage_path);
        if (!file_exists($filePath)) {
            return redirect()->route('siswa.tes.hasil')->withErrors('File PDF tidak ditemukan.');
        }

        return response()->download($filePath, 'hasil_tes_' . $tes->id . '.pdf');
    }

    /**
     * Generate PDF hasil tes
     */
    private function generateAndStorePdf(Tes $tes): bool
    {
        $tes->loadMissing('penyakit');
        $hasilList = HasilSaw::where('tes_id', $tes->id)->with('jurusan')->orderBy('peringkat')->get();
        $siswa = $tes->siswa()->with('user')->first();

        $html = view('pages.siswa.hasil-pdf', [
            'siswa'       => $siswa,
            'tesTerakhir' => $tes,
            'hasilList'   => $hasilList,
        ])->render();

        $fileName     = 'hasil_tes_' . $tes->id . '.pdf';
        $relativePath = 'hasil_pdf/' . $fileName;
        $absolutePath = storage_path('app/public/' . $relativePath);

        $dir = dirname($absolutePath);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $chromePath = config('browsershot.chrome_path');

        try {
            $browsershot = Browsershot::html($html)
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->showBackground()
                ->emulateMedia('screen')
                ->noSandbox();

            if ($chromePath && file_exists($chromePath)) {
                $browsershot->setChromePath($chromePath);
            }

            $browsershot->save($absolutePath);
        } catch (\Throwable $e) {
            Log::error('Browsershot gagal generate PDF', ['message' => $e->getMessage()]);
            return false;
        }

        $upload = Upload::create([
            'uploader_user_id' => $siswa->user_id,
            'file_name'        => $fileName,
            'ext'              => 'PDF',
            'mime_type'        => 'application/pdf',
            'size_mb'          => round(filesize($absolutePath) / 1024 / 1024, 2),
            'storage_path'     => $relativePath,
        ]);

        TesPdf::updateOrCreate(['tes_id' => $tes->id], ['upload_id' => $upload->id, 'generated_at' => now()]);

        return true;
    }

    /**
     * Riwayat tes siswa
     */
    public function history()
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa) return view('pages.siswa.history', ['histories' => collect()]);

        $histories = Tes::where('siswa_id', $siswa->id)
            ->with(['hasilSaw.jurusan', 'tesPDF.upload', 'penyakit'])
            ->latest()
            ->paginate(10);

        return view('pages.siswa.history', compact('histories'));
    }

    public function hasilByTes(Tes $tes)
    {
        $user  = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        if (!$siswa || $tes->siswa_id !== $siswa->id) abort(403);

        $tes->loadMissing('penyakit');
        $hasilList = HasilSaw::where('tes_id', $tes->id)->with('jurusan')->orderBy('peringkat')->get();

        if ($hasilList->isEmpty()) return redirect()->route('siswa.tes.index');

        $jurusanPilihan1 = Jurusan::find($tes->minat_jurusan_1_id);
        $jurusanPilihan2 = Jurusan::find($tes->minat_jurusan_2_id);
        $skorPilihan1 = $hasilList->firstWhere('jurusan_id', $tes->minat_jurusan_1_id);
        $skorPilihan2 = $hasilList->firstWhere('jurusan_id', $tes->minat_jurusan_2_id);

        return view('pages.siswa.hasil', compact('siswa', 'tes', 'hasilList', 'jurusanPilihan1', 'jurusanPilihan2', 'skorPilihan1', 'skorPilihan2'))->with('tesTerakhir', $tes);
    }

    public function cetakPdfByTes(Tes $tes)
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        if (!$siswa || $tes->siswa_id !== $siswa->id) abort(403);

        $tesPdf = TesPdf::with('upload')->where('tes_id', $tes->id)->first();
        if (!$tesPdf || !$tesPdf->upload) return back()->withErrors('File PDF tidak tersedia.');

        $filePath = storage_path('app/public/' . $tesPdf->upload->storage_path);
        return response()->download($filePath, 'hasil_tes_' . $tes->id . '.pdf');
    }
}
