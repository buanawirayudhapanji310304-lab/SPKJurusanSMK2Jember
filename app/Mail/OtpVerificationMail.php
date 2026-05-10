<?php
// app/Mail/OtpVerificationMail.php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpVerificationMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $otp
    ) {}

    public function build()
    {
        return $this->subject('Kode OTP Login - SPK SMKN 2 Jember')
                    ->view('emails.otp');
    }
}