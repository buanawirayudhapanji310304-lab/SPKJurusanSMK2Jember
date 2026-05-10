<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kriteria;
use App\Models\JurusanKriteria;
use App\Models\Penyakit;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JurusanAdminController extends Controller
{
    public function index()
    {
        $jurusans = Jurusan::withCount(['artikelJurusan', 'prospekKerja'])
            ->orderBy('nama_jurusan')
            ->get();

        return view('pages.admin.jurusan.index', compact('jurusans'));
    }

    public function create()
    {
        $kriterias = Kriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        $bobotMap = [];
        $aturanMap = [];
        $penyakits = Penyakit::where('is_active', true)
            ->orderBy('nama_penyakit')
            ->get();
        $penyakitTerlarangIds = [];

        return view('pages.admin.jurusan.create', compact('kriterias', 'bobotMap', 'aturanMap', 'penyakits', 'penyakitTerlarangIds'));
    }

    public function store(Request $request)
    {
        $kriterias = Kriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        $rules = [
            'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan',
            'is_active'    => 'required|boolean',
            'bobot'        => 'required|array',
        ];

        foreach ($kriterias as $kriteria) {
            $rules["bobot.{$kriteria->id}"] = 'required|numeric|min:0|max:1';

            if ($this->isKriteriaPenyakit($kriteria)) {
                $rules["wajib_lolos.{$kriteria->id}"] = 'nullable|boolean';
            } elseif ($this->isKriteriaWajibLolos($kriteria)) {
                $maxNilai = 100;
                if ($kriteria->kode_kriteria === 'C6') $maxNilai = 1;

                $rules["wajib_lolos.{$kriteria->id}"] = 'nullable|boolean';
                $rules["nilai_min.{$kriteria->id}"] = "nullable|numeric|min:0|max:{$maxNilai}";
                $rules["nilai_max.{$kriteria->id}"] = "nullable|numeric|min:0|max:{$maxNilai}";
            }
        }
        $rules['penyakit_larangan'] = 'nullable|array';
        $rules['penyakit_larangan.*'] = 'exists:penyakit,id';

        $request->validate($rules);

        if ($errors = $this->validateAturanWajibLolos($request, $kriterias)) {
            return back()->withErrors($errors)->withInput();
        }

        if ((int) $request->is_active === 1 && !$this->isBobotValid($request->bobot)) {
            return back()
                ->withErrors([
                    'bobot' => 'Jurusan tidak dapat dibuat aktif karena bobot kriteria belum lengkap atau total bobot tidak sama dengan 1.00.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($request, $kriterias) {
            $jurusan = Jurusan::create([
                'nama_jurusan' => $request->nama_jurusan,
                'is_active'    => $request->is_active,
            ]);

            $insertData = [];

            foreach ($kriterias as $kriteria) {
                $insertData[] = [
                    'jurusan_id'  => $jurusan->id,
                    'kriteria_id' => $kriteria->id,
                    'bobot'       => (float) ($request->bobot[$kriteria->id] ?? 0),
                    'wajib_lolos' => $this->isKriteriaWajibLolos($kriteria)
                        ? $request->boolean("wajib_lolos.{$kriteria->id}")
                        : false,
                    'nilai_min'   => $this->nilaiMinUntukKriteria($request, $kriteria),
                    'nilai_max'   => $this->nilaiMaxUntukKriteria($request, $kriteria),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            if (!empty($insertData)) {
                JurusanKriteria::upsert(
                    $insertData,
                    ['jurusan_id', 'kriteria_id'],
                    ['bobot', 'wajib_lolos', 'nilai_min', 'nilai_max', 'updated_at']
                );
            }

            $jurusan->penyakit()->sync($request->input('penyakit_larangan', []));
        });

        ActivityLogger::log('Admin tambah jurusan: ' . $request->nama_jurusan);

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Jurusan {$request->nama_jurusan} berhasil ditambahkan!");
    }

    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $kriterias = Kriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        $bobotMap = JurusanKriteria::where('jurusan_id', $jurusan->id)
            ->pluck('bobot', 'kriteria_id')
            ->toArray();

        $aturanMap = JurusanKriteria::where('jurusan_id', $jurusan->id)
            ->get()
            ->keyBy('kriteria_id');
        $penyakits = Penyakit::where('is_active', true)
            ->orderBy('nama_penyakit')
            ->get();
        $penyakitTerlarangIds = $jurusan->penyakit()
            ->pluck('penyakit.id')
            ->toArray();

        return view('pages.admin.jurusan.edit', compact('jurusan', 'kriterias', 'bobotMap', 'aturanMap', 'penyakits', 'penyakitTerlarangIds'));
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $kriterias = Kriteria::where('is_active', true)
            ->orderBy('kode_kriteria')
            ->get();

        $rules = [
            'nama_jurusan' => 'required|string|max:100|unique:jurusan,nama_jurusan,' . $id,
            'is_active'    => 'required|boolean',
            'bobot'        => 'required|array',
        ];

        foreach ($kriterias as $kriteria) {
            $rules["bobot.{$kriteria->id}"] = 'required|numeric|min:0|max:1';

            if ($this->isKriteriaPenyakit($kriteria)) {
                $rules["wajib_lolos.{$kriteria->id}"] = 'nullable|boolean';
            } elseif ($this->isKriteriaWajibLolos($kriteria)) {
                $maxNilai = 100;
                if ($kriteria->kode_kriteria === 'C6') $maxNilai = 1;

                $rules["wajib_lolos.{$kriteria->id}"] = 'nullable|boolean';
                $rules["nilai_min.{$kriteria->id}"] = "nullable|numeric|min:0|max:{$maxNilai}";
                $rules["nilai_max.{$kriteria->id}"] = "nullable|numeric|min:0|max:{$maxNilai}";
            }
        }
        $rules['penyakit_larangan'] = 'nullable|array';
        $rules['penyakit_larangan.*'] = 'exists:penyakit,id';

        $request->validate($rules);

        if ($errors = $this->validateAturanWajibLolos($request, $kriterias)) {
            return back()->withErrors($errors)->withInput();
        }

        if ((int) $request->is_active === 1 && !$this->isBobotValid($request->bobot)) {
            return back()
                ->withErrors([
                    'bobot' => 'Jurusan tidak dapat dibuat aktif karena bobot kriteria belum lengkap atau total bobot tidak sama dengan 1.00.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($request, $jurusan, $kriterias) {
            $jurusan->update([
                'nama_jurusan' => $request->nama_jurusan,
                'is_active'    => $request->is_active,
            ]);

            $updateData = [];
            $existingMap = JurusanKriteria::where('jurusan_id', $jurusan->id)
                ->get()
                ->keyBy('kriteria_id');

            foreach ($kriterias as $kriteria) {
                $existing = $existingMap->get($kriteria->id);
                $isAturanWajib = $this->isKriteriaWajibLolos($kriteria);

                $updateData[] = [
                    'jurusan_id'  => $jurusan->id,
                    'kriteria_id' => $kriteria->id,
                    'bobot'       => (float) ($request->bobot[$kriteria->id] ?? 0),
                    'wajib_lolos' => $isAturanWajib
                        ? $request->boolean("wajib_lolos.{$kriteria->id}")
                        : (bool) ($existing?->wajib_lolos ?? false),
                    'nilai_min'   => $isAturanWajib
                        ? $this->nilaiMinUntukKriteria($request, $kriteria)
                        : $existing?->nilai_min,
                    'nilai_max'   => $isAturanWajib
                        ? $this->nilaiMaxUntukKriteria($request, $kriteria)
                        : $existing?->nilai_max,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            if (!empty($updateData)) {
                JurusanKriteria::upsert(
                    $updateData,
                    ['jurusan_id', 'kriteria_id'],
                    ['bobot', 'wajib_lolos', 'nilai_min', 'nilai_max', 'updated_at']
                );
            }

            $jurusan->penyakit()->sync($request->input('penyakit_larangan', []));
        });

        ActivityLogger::log('Admin edit jurusan: ' . $jurusan->nama_jurusan);

        return redirect()->route('admin.jurusan.index')
            ->with('success', "Jurusan {$jurusan->nama_jurusan} berhasil diperbarui!");
    }

    public function toggleStatus($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        // Jika jurusan sedang nonaktif dan akan diaktifkan,
        // maka bobot harus valid terlebih dahulu.
        if (!$jurusan->is_active) {
            $bobot = JurusanKriteria::where('jurusan_id', $jurusan->id)
                ->pluck('bobot')
                ->toArray();

            if (!$this->isBobotValid($bobot)) {
                return redirect()->back()
                    ->withErrors([
                        'error' => 'Jurusan tidak dapat diaktifkan karena bobot kriteria belum lengkap atau total bobot tidak sama dengan 1.00.'
                    ]);
            }
        }

        $jurusan->update([
            'is_active' => !$jurusan->is_active
        ]);

        $status = $jurusan->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log("Admin {$status} jurusan: {$jurusan->nama_jurusan}");

        return redirect()->back()
            ->with('success', "Jurusan {$jurusan->nama_jurusan} berhasil {$status}.");
    }

    /**
     * Validasi bobot:
     * - semua bobot harus > 0
     * - total bobot harus = 1.00
     */
    private function isBobotValid(array $bobot): bool
    {
        if (empty($bobot)) {
            return false;
        }

        foreach ($bobot as $nilai) {
            if (!is_numeric($nilai) || (float) $nilai <= 0) {
                return false;
            }
        }

        $total = collect($bobot)->sum(fn($v) => (float) $v);

        return abs($total - 1) <= 0.001;
    }

    private function isKriteriaWajibLolos(Kriteria $kriteria): bool
    {
        return in_array($kriteria->kode_kriteria, ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8'], true);
    }

    private function isKriteriaPenyakit(Kriteria $kriteria): bool
    {
        return $kriteria->kode_kriteria === 'C8';
    }

    private function nullableFloat($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function validateAturanWajibLolos(Request $request, $kriterias): array
    {
        $errors = [];

        foreach ($kriterias as $kriteria) {
            if (!$this->isKriteriaWajibLolos($kriteria)) {
                continue;
            }

            $id = $kriteria->id;
            $wajibLolos = $request->boolean("wajib_lolos.{$id}");
            $nilaiMin = $this->nullableFloat($request->input("nilai_min.{$id}"));
            $nilaiMax = $this->nullableFloat($request->input("nilai_max.{$id}"));

            if ($this->isKriteriaPenyakit($kriteria)) {
                if ($wajibLolos && empty($request->input('penyakit_larangan', []))) {
                    $errors['penyakit_larangan'] = 'Pilih minimal satu penyakit larangan untuk aturan wajib lolos C8.';
                }

                continue;
            }

            if ($wajibLolos && $nilaiMin === null && $nilaiMax === null) {
                $errors["nilai_min.{$id}"] = "Isi nilai minimum atau maksimum untuk aturan wajib lolos {$kriteria->kode_kriteria}.";
            }

            if ($nilaiMin !== null && $nilaiMax !== null && $nilaiMin > $nilaiMax) {
                $errors["nilai_max.{$id}"] = "Nilai maksimum {$kriteria->kode_kriteria} harus lebih besar atau sama dengan nilai minimum.";
            }
        }

        return $errors;
    }

    private function nilaiMinUntukKriteria(Request $request, Kriteria $kriteria): ?float
    {
        if ($this->isKriteriaPenyakit($kriteria)) {
            return $request->boolean("wajib_lolos.{$kriteria->id}") ? 1 : null;
        }

        // Otomatisasi C6 (Buta Warna)
        if ($kriteria->kode_kriteria === 'C6') {
            return $request->boolean("wajib_lolos.{$kriteria->id}") ? 1 : null;
        }

        if ($this->isKriteriaWajibLolos($kriteria)) {
            return $this->nullableFloat($request->input("nilai_min.{$kriteria->id}"));
        }

        return null;
    }

    private function nilaiMaxUntukKriteria(Request $request, Kriteria $kriteria): ?float
    {
        if ($this->isKriteriaPenyakit($kriteria)) {
            return null;
        }

        if ($this->isKriteriaWajibLolos($kriteria)) {
            return $this->nullableFloat($request->input("nilai_max.{$kriteria->id}"));
        }

        return null;
    }
}
