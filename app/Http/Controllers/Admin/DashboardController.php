<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Siswa, GuruBk, Jurusan, Tes};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa   = Siswa::whereHas('user', fn($q) => $q->where('is_active', true))->count();
        $totalGuruBk  = GuruBk::whereHas('user', fn($q) => $q->where('is_active', true))->count();
        $totalJurusan = Jurusan::where('is_active', true)->count();
        $totalTes     = Tes::count();

        $recentGuruBk = GuruBk::with('user')->latest()->take(5)->get();
        $recentSiswa  = Siswa::with('user')->latest()->take(5)->get();
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
       
$recentLogs = DB::table('activity_log')
                ->join('users', 'users.id', '=', 'activity_log.user_id')
                ->select('activity_log.*', 'users.nama as user_nama')
                ->orderByDesc('activity_log.created_at')
                ->take(8)->get();
                
        return view('pages.admin.dashboard', compact(
            'totalSiswa', 'totalGuruBk', 'totalJurusan', 'totalTes',
            'recentGuruBk', 'recentSiswa', 'jurusans', 'recentLogs'
        ));
    }
}