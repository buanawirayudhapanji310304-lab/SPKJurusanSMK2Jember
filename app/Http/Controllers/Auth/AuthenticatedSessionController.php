<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Logout sementara, login penuh hanya setelah OTP
        Auth::guard('web')->logout();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek akun aktif
        if (!(int) $user->is_active) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun tidak aktif. Hubungi admin.']);
        }

        // Ambil role
        $roleName = strtolower(trim((string) DB::table('roles')
            ->where('id', $user->role_id)
            ->value('nama_role')));

        // Semua role wajib OTP
        $code = $this->otpService->generate($user);
        $sent = $this->otpService->sendEmail($user, $code);

        if (!$sent) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal mengirim kode OTP. Periksa koneksi internet atau email Anda dan coba lagi.']);
        }

        session([
            'otp_user_id' => $user->id,
            'otp_role'    => $roleName,
        ]);

        return redirect()->route('otp.form');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landingpage');
    }
}