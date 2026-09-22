<!DOCTYPE html>
<html>
<head>
    <title>Kode OTP Reset Password Akun ROTASI Digital</title>
</head>
<body style="font-family: 'Figtree', Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-w-md; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="https://content.rotasipsti.id/images/rotasi-graywithtext-logo.png" alt="Logo ROTASI" style="max-height: 40px;">
        </div>
        <p>Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda.</p>
        <p>Berikut adalah kode OTP untuk melanjutkan proses reset kata sandi akun Anda:</p>
        <div style="font-size: 24px; font-weight: bold; padding: 10px; background-color: #f4f4f4; text-align: center; border-radius: 5px; letter-spacing: 5px; margin: 20px 0;">
            {{ $otp }}
        </div>
        <p>Kode OTP ini akan kedaluwarsa dalam 15 menit.</p>
        <p>Jika Anda tidak meminta reset kata sandi, abaikan email ini.</p>
        <br>
        <p>Hormat kami,<br>ROTASI {{ date('Y') }}</p>
    </div>
</body>
</html>
