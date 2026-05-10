<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ActivityLog;
class OtpController extends Controller
{
    public function __construct(private OtpService $otpService) {}

    public function showForm(): View
    {
        abort_unless(session()->has('otp_user_id'), 403);
        return view('auth.otp');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId   = session('otp_user_id');
        $roleName = session('otp_role');

        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi habis, silakan login ulang.']);
        }

        $user  = User::findOrFail($userId);
        $valid = $this->otpService->verify($user, $request->code);

        if (!$valid) {
            return back()->withErrors(['code' => 'Kode OTP salah atau sudah kadaluarsa.']);
        }

        Auth::login($user);
        $request->session()->regenerate();
        session()->forget(['otp_user_id', 'otp_role']);

        ActivityLog::create([
    'user_id' => $user->id,
    'aksi'    => 'Login sebagai ' . $roleName,
    ]);
        if ($roleName === 'admin')    return redirect()->route('admin.dashboard');
        if ($roleName === 'guru_bk') return redirect()->route('bk.dashboard');

        return redirect()->route('landingpage');
    }
    public function resend(Request $request): RedirectResponse
    {
        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi habis, silakan login ulang.']);
        }

        $user = User::findOrFail($userId);
        $code = $this->otpService->generate($user);
        $sent = $this->otpService->sendEmail($user, $code);

        if (!$sent) {
            return back()->withErrors(['code' => 'Gagal mengirim ulang OTP. Periksa koneksi internet Anda.']);
        }

        return back()->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}