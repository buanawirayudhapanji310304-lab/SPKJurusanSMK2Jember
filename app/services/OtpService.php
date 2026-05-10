<?php

namespace App\Services;

use App\Mail\OtpVerificationMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generate(User $user): string
    {
        // Hapus OTP lama
        PasswordReset::where('user_id', $user->id)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordReset::create([
            'user_id'    => $user->id,
            'otp_code'   => Hash::make($code),
            'expires_at' => now()->addMinutes(2),
            'is_used'    => false,
        ]);

        return $code;
    }

    public function verify(User $user, string $code): bool
    {
        $otp = PasswordReset::where('user_id', $user->id)
                            ->where('is_used', false)
                            ->latest()
                            ->first();

        if (!$otp) return false;
        if (now()->isAfter($otp->expires_at)) return false;
        if (!Hash::check($code, $otp->otp_code)) return false;

        $otp->update(['is_used' => true]);
        return true;
    }

    public function sendEmail(User $user, string $code): bool
    {
        try {
            Mail::to($user->email)->send(new OtpVerificationMail($user, $code));
            return true;
        } catch (\Exception $e) {
            // Log error jika diperlukan: \Log::error("Gagal mengirim OTP: " . $e->getMessage());
            return false;
        }
    }
}