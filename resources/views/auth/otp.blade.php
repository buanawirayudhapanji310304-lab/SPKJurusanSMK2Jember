<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi OTP · SPK Jurusan SMKN 2 Jember</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Style sama persis dengan login.blade.php */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    :root { --navy: #0d1b3e; --blue: #2563eb; --gold: #f59e0b; --gold2: #fbbf24; --white: #ffffff; --gray: #94a3b8; }
    body { min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--navy); display: flex; align-items: center; justify-content: center; }
    body::before { content: ''; position: fixed; inset: 0; background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px); background-size: 28px 28px; pointer-events: none; }
    .card { width: 100%; max-width: 400px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1); border-radius: 20px; padding: 40px 36px; backdrop-filter: blur(20px); box-shadow: 0 24px 64px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.08); position: relative; animation: fadeIn .7s ease both; }
    .card::before { content: ''; position: absolute; top: 0; left: 36px; right: 36px; height: 2px; background: linear-gradient(90deg, transparent, var(--gold), transparent); border-radius: 100px; }
    .icon { width: 56px; height: 56px; background: linear-gradient(135deg, var(--blue), var(--gold)); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px; }
    h2 { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 800; color: var(--white); margin-bottom: 8px; }
    p.sub { font-size: 13px; color: var(--gray); margin-bottom: 28px; line-height: 1.6; }
    label { display: block; font-size: 11.5px; font-weight: 600; color: rgba(255,255,255,.55); text-transform: uppercase; letter-spacing: .07em; margin-bottom: 7px; }
    input[type="text"] { width: 100%; background: rgba(255,255,255,.06); border: 1.5px solid rgba(255,255,255,.1); border-radius: 10px; padding: 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 28px; font-weight: 700; color: var(--white); outline: none; text-align: center; letter-spacing: 10px; transition: border-color .2s; }
    input:focus { border-color: var(--blue); background: rgba(37,99,235,.08); }
    .error-box { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); border-radius: 10px; padding: 12px 14px; font-size: 13px; color: #fca5a5; margin-bottom: 16px; }
    .btn { width: 100%; padding: 13px; border-radius: 10px; border: none; background: linear-gradient(135deg, var(--blue), #1d4ed8); color: #fff; font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 20px rgba(37,99,235,.4); margin-top: 16px; transition: transform .15s; }
    .btn:hover { transform: translateY(-1px); }
    .back { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: var(--gray); text-decoration: none; }
    .back:hover { color: var(--gold); }
    @keyframes fadeIn { from { opacity: 0; transform: scale(.97); } to { opacity: 1; transform: scale(1); } }
</style>
</head>
<body>
<div class="card">
    <div class="icon">🔐</div>
    <h2>Verifikasi OTP</h2>
    <p class="sub">Kode OTP 6 digit telah dikirim ke email kamu. Masukkan kode tersebut untuk melanjutkan.</p>

    @if ($errors->any())
    <div class="error-box">
        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
    </div>
    @endif

    @if (session('success'))
    <div style="background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.3); border-radius: 10px; padding: 12px 14px; font-size: 13px; color: #6ee7b7; margin-bottom: 16px;">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <label>Kode OTP</label>
        <input type="text" name="code" maxlength="6" inputmode="numeric"
               placeholder="······" autofocus autocomplete="off">
        <button type="submit" class="btn">Verifikasi →</button>
    </form>

    <div style="margin-top: 24px; text-align: center;">
        <p style="font-size: 13px; color: var(--gray);">Tidak menerima kode?</p>
        <form method="POST" action="{{ route('otp.resend') }}" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: var(--gold); font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 5px;">
                Kirim Ulang OTP
            </button>
        </form>
    </div>

    <a href="{{ route('login') }}" class="back">← Kembali ke Login</a>
</div>
</body>
</html>