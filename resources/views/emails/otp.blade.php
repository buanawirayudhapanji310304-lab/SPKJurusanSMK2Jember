<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 30px; }
        .card { max-width: 480px; margin: auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .header { background: linear-gradient(135deg, #0d1b3e, #2563eb); padding: 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 20px; margin: 0; }
        .header p { color: rgba(255,255,255,.7); font-size: 13px; margin: 6px 0 0; }
        .body { padding: 36px 32px; text-align: center; }
        .body p { color: #64748b; font-size: 14px; margin-bottom: 24px; }
        .otp-box { display: inline-block; background: #f8fafc; border: 2px dashed #2563eb; border-radius: 12px; padding: 20px 40px; margin-bottom: 24px; }
        .otp-code { font-size: 40px; font-weight: 800; letter-spacing: 12px; color: #0d1b3e; }
        .warning { background: #fef3c7; border-radius: 8px; padding: 12px 16px; font-size: 12px; color: #92400e; }
        .footer { background: #f8fafc; padding: 16px; text-align: center; font-size: 11px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div style="background: #fff; width: 60px; height: 60px; border-radius: 50%; padding: 8px; margin: 0 auto 16px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                <img src="{{ $message->embed(public_path('Assets/Logo_SMKN2Jember.png')) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1>SPK SMKN 2 Jember</h1>
            <p>Sistem Pendukung Keputusan Pemilihan Jurusan</p>
        </div>
        <div class="body">
            <p>Halo <strong>{{ $user->nama }}</strong>, gunakan kode OTP berikut untuk masuk ke akun kamu:</p>
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
            </div>
            <div class="warning">
                ⏱ Kode berlaku <strong>2 menit</strong>. Jangan bagikan kode ini kepada siapapun.
            </div>
        </div>
        <div class="footer">
            Jika kamu tidak merasa melakukan login, abaikan email ini.
        </div>
    </div>
</body>
</html>