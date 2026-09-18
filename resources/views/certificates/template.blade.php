<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan - {{ $student->name }}</title>
    <style>
        @page {
            size: a4 landscape;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        html, body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-family: 'DejaVu Sans', 'Helvetica Neue', Arial, sans-serif;
            color: #0f172a;
        }

        /* Certificate Main Container - Fixed mm width & explicit margins to prevent DomPDF box-sizing bug */
        .cert-container {
            width: 254mm;
            margin-left: 21.5mm;
            margin-right: 21.5mm;
            position: relative;
            padding-top: 14mm;
            padding-bottom: 10mm;
            background-color: #ffffff;
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        /* Double Gold Decorative Borders (Explicit mm to prevent DomPDF bottom bug) */
        .cert-border-outer {
            position: absolute;
            top: 7mm;
            left: 7mm;
            width: 283mm;
            height: 196mm;
            border: 3px solid #b8860b;
            pointer-events: none;
        }
        .cert-border-inner {
            position: absolute;
            top: 9.5mm;
            left: 9.5mm;
            width: 278mm;
            height: 191mm;
            border: 1px solid #d4af37;
            pointer-events: none;
        }

        /* Corner Filigree SVG Accents (Explicit top coordinates to avoid DomPDF bottom bug) */
        .corner-ornament {
            position: absolute;
            width: 50px;
            height: 50px;
            pointer-events: none;
        }
        .corner-ornament.tl { top: 8mm; left: 8mm; }
        .corner-ornament.tr { top: 8mm; right: 8mm; }
        .corner-ornament.bl { top: 153mm; left: 8mm; }
        .corner-ornament.br { top: 153mm; right: 8mm; }

        /* Content Sections */
        .header-table {
            width: 100%;
            margin-bottom: 6px;
            text-align: center;
        }
        .institution-title {
            font-size: 11.5px;
            font-weight: bold;
            letter-spacing: 2.5px;
            color: #475569;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .branch-badge {
            display: inline-block;
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            padding: 2.5px 16px;
            border-radius: 20px;
            font-size: 8.5px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #334155;
            text-transform: uppercase;
        }

        /* Certificate Titles */
        .titles-section {
            text-align: center;
            margin-top: 6px;
            margin-bottom: 8px;
        }
        .main-title {
            font-size: 27px;
            font-weight: 900;
            letter-spacing: 6px;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .sub-title {
            font-style: italic;
            font-size: 10.5px;
            letter-spacing: 2.5px;
            color: #b8860b;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
        }
        .cert-number-pill {
            display: inline-block;
            margin-top: 5px;
            font-family: monospace;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1.5px;
            color: #475569;
            background-color: #f8fafc;
            padding: 2.5px 14px;
            border: 1px dashed #cbd5e1;
            border-radius: 4px;
        }

        /* Recipient Section */
        .recipient-section {
            text-align: center;
            margin-top: 8px;
            margin-bottom: 10px;
        }
        .awarded-label {
            font-style: italic;
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .student-name {
            font-size: 25px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding-bottom: 4px;
            display: inline-block;
            border-bottom: 2px solid #c59b27;
        }

        /* Statement & Class Details */
        .statement-section {
            text-align: center;
            max-width: 88%;
            margin: 0 auto 16px auto;
            font-size: 10.5px;
            line-height: 1.6;
            color: #334155;
        }
        .class-title {
            font-size: 13.5px;
            font-weight: bold;
            color: #0f172a;
            display: block;
            margin: 5px 0;
            letter-spacing: 0.5px;
        }
        .jp-highlight {
            display: inline-block;
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fef3c7;
            padding: 1.5px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }

        /* Footer Triple Column (QR, Seal, Signature) */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        /* QR Card */
        .qr-card {
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 7px 12px;
            display: inline-block;
        }
        .qr-meta-title {
            font-size: 9px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .qr-meta-desc {
            font-size: 7.5px;
            color: #64748b;
            line-height: 1.25;
            margin: 2px 0;
        }
        .qr-meta-code {
            font-family: monospace;
            font-size: 8px;
            color: #b8860b;
            font-weight: bold;
        }

        /* Center Medal Seal */
        .seal-medal {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background-color: #c59b27;
            border: 3px solid #854d0e;
            margin: 0 auto;
            text-align: center;
            padding-top: 6px;
        }
        .seal-inner-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            border: 1.5px dashed #ffffff;
            margin: 0 auto;
            text-align: center;
            color: #ffffff;
            padding-top: 8px;
        }
        .seal-stars {
            font-size: 8.5px;
            letter-spacing: 2px;
            line-height: 1;
        }
        .seal-text-main {
            font-size: 6px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 3px;
            white-space: nowrap;
        }
        .seal-text-sub {
            font-size: 5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 1px;
            opacity: 0.95;
            white-space: nowrap;
        }
        .seal-text-jp {
            font-size: 6px;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-top: 2px;
            white-space: nowrap;
        }

        /* Signature Section */
        .signature-container {
            text-align: center;
            width: 220px;
            margin-left: auto;
        }
        .sign-date {
            font-size: 9.5px;
            color: #475569;
            margin-bottom: 2px;
        }
        .sign-canvas {
            height: 38px;
            text-align: center;
        }
        .sign-line {
            width: 100%;
            height: 1px;
            background-color: #64748b;
            margin: 2px auto 3px auto;
        }
        .trainer-name {
            font-size: 11.5px;
            font-weight: bold;
            color: #0f172a;
            letter-spacing: 0.5px;
        }
        .trainer-title {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 1px;
        }
    </style>
</head>
<body>

<div class="cert-container">
    <!-- Double Gold Decorative Borders -->
    <div class="cert-border-outer"></div>
    <div class="cert-border-inner"></div>

    <!-- Top Left Corner Filigree -->
    <svg class="corner-ornament tl" viewBox="0 0 60 60" fill="none">
        <path d="M3 57V18C3 9.71573 9.71573 3 18 3H57" stroke="#B8860B" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M9 57V21C9 14.3726 14.3726 9 21 9H57" stroke="#D4AF37" stroke-width="1.2" stroke-linecap="round"/>
        <circle cx="21" cy="21" r="3.5" fill="#B8860B"/>
        <path d="M21 12V30M12 21H30" stroke="#B8860B" stroke-width="1.2" stroke-linecap="round"/>
    </svg>

    <!-- Top Right Corner Filigree -->
    <svg class="corner-ornament tr" viewBox="0 0 60 60" fill="none">
        <path d="M57 57V18C57 9.71573 50.2843 3 42 3H3" stroke="#B8860B" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M51 57V21C51 14.3726 45.6274 9 39 9H3" stroke="#D4AF37" stroke-width="1.2" stroke-linecap="round"/>
        <circle cx="39" cy="21" r="3.5" fill="#B8860B"/>
        <path d="M39 12V30M48 21H30" stroke="#B8860B" stroke-width="1.2" stroke-linecap="round"/>
    </svg>

    <!-- Bottom Left Corner Filigree -->
    <svg class="corner-ornament bl" viewBox="0 0 60 60" fill="none">
        <path d="M3 3V42C3 50.2843 9.71573 57 18 57H57" stroke="#B8860B" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M9 3V39C9 45.6274 14.3726 51 21 51H57" stroke="#D4AF37" stroke-width="1.2" stroke-linecap="round"/>
        <circle cx="21" cy="39" r="3.5" fill="#B8860B"/>
        <path d="M21 30V48M12 39H30" stroke="#B8860B" stroke-width="1.2" stroke-linecap="round"/>
    </svg>

    <!-- Bottom Right Corner Filigree -->
    <svg class="corner-ornament br" viewBox="0 0 60 60" fill="none">
        <path d="M57 3V42C57 50.2843 50.2843 57 42 57H3" stroke="#B8860B" stroke-width="2.5" stroke-linecap="round"/>
        <path d="M51 3V39C51 45.6274 45.6274 51 39 51H3" stroke="#D4AF37" stroke-width="1.2" stroke-linecap="round"/>
        <circle cx="39" cy="39" r="3.5" fill="#B8860B"/>
        <path d="M39 30V48M48 39H30" stroke="#B8860B" stroke-width="1.2" stroke-linecap="round"/>
    </svg>

    <!-- 1. Institution Header -->
    <div class="header-table">
        <div style="margin-bottom: 3px;">
            <svg width="34" height="34" viewBox="0 0 100 100" fill="none">
                <circle cx="50" cy="50" r="46" stroke="#C59B27" stroke-width="3" fill="#FAF8F5"/>
                <path d="M50 16L60 36H78L63 48L69 68L50 56L31 68L37 48L22 36H40L50 16Z" fill="#D4AF37" stroke="#9A7516" stroke-width="1.5"/>
            </svg>
        </div>
        <div class="institution-title">
            Lembaga Pelatihan Kerja &amp; Pengembangan Kompetensi Kejuruan
        </div>
        <div class="branch-badge">
            KANTOR CABANG {{ strtoupper($branch->name ?? 'PUSAT') }} &bull; KODE REGIONAL: {{ $branch->code ?? 'CBG-LMS' }}
        </div>
    </div>

    <!-- 2. Titles -->
    <div class="titles-section">
        <h1 class="main-title">Sertifikat Kelulusan</h1>
        <div class="sub-title">Certificate of Completion &amp; Professional Competence</div>
        <div class="cert-number-pill">
            NOMOR REGISTRASI RESMI: {{ $submission->certificate_number }}
        </div>
    </div>

    <!-- 3. Recipient -->
    <div class="recipient-section">
        <div class="awarded-label">Diberikan dan dianugerahkan secara terhormat kepada:</div>
        <div class="student-name">
            {{ strtoupper($student->name) }}
        </div>
    </div>

    <!-- 4. Narrative Statement -->
    <div class="statement-section">
        Telah menyelesaikan seluruh rangkaian kurikulum, silabus pembelajaran teori dan praktik, serta dinyatakan
        <strong style="color: #0f172a;">LULUS KOMPETEN</strong> pada program pelatihan kejuruan intensif:
        <span class="class-title">&ldquo;{{ $class->title }}&rdquo;</span>
        Dengan akumulasi beban studi kumulatif sebesar
        <span class="jp-highlight">{{ $submission->total_jp_earned }} Jam Pelajaran (JP)</span>
        setara 900 menit pembelajaran efektif, serta telah menuntaskan seluruh evaluasi kuis dengan pencapaian predikat kompeten.
    </div>

    <!-- 5. Footer Table (QR Code, Gold Seal, Signature) -->
    <table class="footer-table" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <!-- Left: QR Code Verification Card -->
            <td width="38%" align="left" valign="bottom">
                <div class="qr-card">
                    <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="middle" style="padding-right: 8px;">
                                <img src="{{ $qrCodeBase64 }}" width="62" height="62" style="display: block; border: 1px solid #cbd5e1; padding: 2px; background: #ffffff;">
                            </td>
                            <td valign="middle">
                                <div class="qr-meta-title">Autentikasi Digital</div>
                                <div class="qr-meta-desc">
                                    Pindai QR Code untuk verifikasi<br>keaslian sertifikat pada sistem LMS
                                </div>
                                <div class="qr-meta-code">
                                    {{ $submission->certificate_number }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>

            <!-- Center: Gold Medal Seal -->
            <td width="24%" align="center" valign="bottom">
                <div class="seal-medal">
                    <div class="seal-inner-circle">
                        <div class="seal-stars">&#9733; &#9733; &#9733;</div>
                        <div class="seal-text-main">TERAKREDITASI</div>
                        <div class="seal-text-sub">STANDAR MUTU</div>
                        <div class="seal-text-jp">20 JP RESMI</div>
                    </div>
                </div>
            </td>

            <!-- Right: Trainer Signature -->
            <td width="38%" align="right" valign="bottom">
                <div class="signature-container">
                    <div class="sign-date">
                        {{ $branch->city ?? 'Jakarta' }}, {{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                    </div>
                    <div class="sign-canvas">
                        <!-- Stylized Signature SVG Path -->
                        <svg width="150" height="38" viewBox="0 0 200 60" fill="none">
                            <path d="M15 45C35 15 50 8 65 32C72 45 78 48 85 30C95 8 105 2 115 25C122 42 128 46 145 28C155 18 175 12 185 35" stroke="#1E293B" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M45 32C70 32 120 28 170 34" stroke="#1E293B" stroke-width="1.6" stroke-linecap="round"/>
                            <path d="M75 18C85 30 92 48 102 52" stroke="#1E293B" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="sign-line"></div>
                    <div class="trainer-name">
                        {{ $trainer->name }}
                    </div>
                    <div class="trainer-title">
                        Instruktur Pengampu Pelatihan
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>

</body>
</html>
