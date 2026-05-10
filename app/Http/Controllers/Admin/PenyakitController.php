<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyakit;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PenyakitController extends Controller
{
    public function index()
    {
        $penyakits = Penyakit::orderBy('nama_penyakit')->paginate(15);

        return view('pages.admin.penyakit.index', compact('penyakits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penyakit' => 'required|string|max:120|unique:penyakit,nama_penyakit',
        ]);

        $penyakit = Penyakit::create([
            'nama_penyakit' => $request->nama_penyakit,
            'is_active' => true,
        ]);

        ActivityLogger::log('Admin tambah penyakit: ' . $penyakit->nama_penyakit);

        return redirect()->route('admin.penyakit.index')
            ->with('success', "Penyakit {$penyakit->nama_penyakit} berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $penyakit = Penyakit::findOrFail($id);

        $request->validate([
            'nama_penyakit' => 'required|string|max:120|unique:penyakit,nama_penyakit,' . $penyakit->id,
        ]);

        $penyakit->update([
            'nama_penyakit' => $request->nama_penyakit,
        ]);

        ActivityLogger::log('Admin edit penyakit: ' . $penyakit->nama_penyakit);

        return redirect()->route('admin.penyakit.index')
            ->with('success', "Penyakit {$penyakit->nama_penyakit} berhasil diperbarui.");
    }

    public function toggleStatus($id)
    {
        $penyakit = Penyakit::findOrFail($id);

        $penyakit->update([
            'is_active' => !$penyakit->is_active,
        ]);

        $status = $penyakit->is_active ? 'diaktifkan' : 'dinonaktifkan';

        ActivityLogger::log("Admin {$status} penyakit: {$penyakit->nama_penyakit}");

        return redirect()->route('admin.penyakit.index')
            ->with('success', "Penyakit {$penyakit->nama_penyakit} berhasil {$status}.");
    }
}
