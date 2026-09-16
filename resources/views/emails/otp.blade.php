<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi - LMS Multi-Cabang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F5F3EF;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1E1B18;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #F5F3EF;
            padding: 40px 0;
        }
        .container {
            max-width: 540px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 20px;
            border: 1px solid #E5E0D8;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #1E1B18 0%, #2D2620 100%);
            padding: 32px 30px;
            text-align: center;
        }
        .logo-badge {
            display: inline-block;
            width: 44px;
            height: 44px;
            line-height: 44px;
            background: linear-gradient(135deg, #FF6B00 0%, #E11D48 100%);
            color: #FFFFFF;
            font-weight: 800;
            font-size: 22px;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .header-title {
            color: #FFFFFF;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.3px;
        }
        .header-subtitle {
            color: #FB923C;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 600;
            margin-top: 4px;
        }
        .content {
            padding: 36px 32px;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #1E1B18;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .message-text {
            font-size: 14px;
            line-height: 1.6;
            color: #57534E;
            margin-bottom: 24px;
        }
        .otp-box {
            background: #FAF8F5;
            border: 2px dashed #FF6B00;
            border-radius: 16px;
            padding: 24px 16px;
            text-align: center;
            margin: 28px 0;
        }
        .otp-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #78716C;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 38px;
            font-weight: 800;
            color: #EA580C;
            letter-spacing: 10px;
            margin: 0;
            padding-left: 10px; /* offset letter-spacing */
        }
        .otp-expiry {
            font-size: 12px;
            color: #A8A29E;
            margin-top: 8px;
            margin-bottom: 0;
        }
        .security-notice {
            background-color: #FFF7ED;
            border-left: 4px solid #FF6B00;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 12px;
            color: #9A3412;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .footer {
            background-color: #FAF8F5;
            border-top: 1px solid #E5E0D8;
            padding: 24px 32px;
            text-align: center;
            font-size: 11px;
            color: #A8A29E;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="logo-badge">L</div>
                <h1 class="header-title">LMS Multi-Cabang</h1>
                <div class="header-subtitle">
                    {{ $type === 'password_reset' ? 'Pemulihan Kata Sandi' : 'Verifikasi Pendaftaran Akun' }}
                </div>
            </div>

            <!-- Content Body -->
            <div class="content">
                <p class="greeting">
                    Halo, {{ $userName ?: 'Pengguna LMS' }}!
                </p>

                @if($type === 'password_reset')
                    <p class="message-text">
                        Kami menerima permintaan untuk mereset kata sandi akun LMS Anda. Gunakan kode One-Time Password (OTP) berikut untuk melanjutkan proses pembuatan kata sandi baru:
                    </p>
                @else
                    <p class="message-text">
                        Terima kasih telah mendaftar di <strong>LMS Multi-Cabang</strong>. Untuk menyelesaikan pembuatan akun dan mengaktifkan akses kelas pelatihan, silakan masukkan kode One-Time Password (OTP) di bawah ini:
                    </p>
                @endif

                <!-- OTP Display Box -->
                <div class="otp-box">
                    <div class="otp-label">Kode Verifikasi OTP Anda</div>
                    <div class="otp-code">{{ $otpCode }}</div>
                    <p class="otp-expiry">Berlaku selama <strong>{{ $expiresInMinutes }} menit</strong> ke depan</p>
                </div>

                <div class="security-notice">
                    <strong>Penting:</strong> Jangan pernah memberitahukan kode OTP ini kepada siapa pun, termasuk pihak yang mengatasnamakan administrator LMS. Sistem kami tidak akan pernah meminta kode ini secara langsung.
                </div>

                <p class="message-text" style="font-size: 12px; color: #A8A29E; margin-bottom: 0;">
                    Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini dengan aman. Akun Anda tetap terlindungi.
                </p>
            </div>

            <!-- Footer -->
            <div class="footer">
                &copy; {{ date('Y') }} LMS Multi-Cabang. Seluruh hak cipta dilindungi undang-undang.<br>
                Email ini dikirimkan secara otomatis oleh sistem notifikasi LMS via Google SMTP.
            </div>
        </div>
    </div>
</body>
</html>
