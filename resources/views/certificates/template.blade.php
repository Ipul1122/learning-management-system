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
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            width: 297mm;
            height: 210mm;
            position: relative;
        }
        .certificate-container {
            width: 297mm;
            height: 210mm;
            padding: 12mm 15mm;
            position: relative;
            background: #ffffff;
        }
        .outer-border {
            width: 100%;
            height: 100%;
            border: 4px double #c59b27;
            padding: 6mm;
            position: relative;
        }
        .inner-border {
            width: 100%;
            height: 100%;
            border: 1.5px solid #0f172a;
            padding: 8mm 12mm;
            text-align: center;
            position: relative;
            background: radial-gradient(circle at center, #ffffff 60%, #fafafa 100%);
        }
        .corner-accent {
            position: absolute;
            width: 16mm;
            height: 16mm;
            border-color: #c59b27;
            border-style: solid;
        }
        .corner-tl { top: -2px; left: -2px; border-width: 4px 0 0 4px; }
        .corner-tr { top: -2px; right: -2px; border-width: 4px 4px 0 0; }
        .corner-bl { bottom: -2px; left: -2px; border-width: 0 0 4px 4px; }
        .corner-br { bottom: -2px; right: -2px; border-width: 0 4px 4px 0; }

        .institution-header {
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 11px;
            font-weight: bold;
            color: #475569;
            margin-bottom: 2mm;
        }
        .branch-badge {
            display: inline-block;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 1.5mm 5mm;
            font-size: 9px;
            font-weight: bold;
            color: #334155;
            letter-spacing: 1px;
            border-radius: 3px;
            margin-bottom: 4mm;
        }
        .cert-title {
            font-size: 26px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 4px;
            margin: 0 0 2mm 0;
            text-transform: uppercase;
        }
        .cert-subtitle {
            font-size: 10px;
            letter-spacing: 3px;
            color: #c59b27;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3mm;
        }
        .cert-number {
            font-size: 10px;
            font-family: monospace;
            color: #64748b;
            margin-bottom: 5mm;
            letter-spacing: 1px;
        }
        .awarded-to {
            font-size: 11px;
            font-style: italic;
            color: #475569;
            margin-bottom: 2mm;
        }
        .student-name {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 2px solid #c59b27;
            display: inline-block;
            padding: 0 10mm 1.5mm 10mm;
            margin-bottom: 4mm;
            letter-spacing: 1px;
        }
        .cert-description {
            font-size: 11px;
            line-height: 1.6;
            color: #334155;
            max-width: 200mm;
            margin: 0 auto 5mm auto;
        }
        .cert-description strong {
            color: #0f172a;
        }
        .badge-jp {
            display: inline-block;
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            padding: 1mm 4mm;
            border-radius: 3px;
            font-weight: bold;
            font-size: 10px;
        }

        /* Footer Table */
        .footer-table {
            width: 100%;
            margin-top: 4mm;
            border-collapse: collapse;
        }
        .footer-table td {
            vertical-align: bottom;
            padding: 0;
        }
        .qr-col {
            width: 30%;
            text-align: left;
        }
        .seal-col {
            width: 40%;
            text-align: center;
        }
        .signature-col {
            width: 30%;
            text-align: right;
        }

        .qr-box {
            display: inline-block;
            text-align: left;
        }
        .qr-box img {
            border: 1px solid #e2e8f0;
            padding: 1.5mm;
            background: #ffffff;
        }
        .qr-caption {
            font-size: 8px;
            color: #64748b;
            margin-top: 1mm;
            line-height: 1.3;
        }

        .seal-circle {
            width: 22mm;
            height: 22mm;
            border: 2px dashed #c59b27;
            border-radius: 50%;
            display: inline-block;
            line-height: 20mm;
            font-size: 8px;
            font-weight: bold;
            color: #c59b27;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sign-date {
            font-size: 10px;
            color: #475569;
            margin-bottom: 12mm;
        }
        .sign-name {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
            border-top: 1px solid #94a3b8;
            display: inline-block;
            padding-top: 1.5mm;
            min-width: 45mm;
            text-align: center;
        }
        .sign-role {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 0.5mm;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="outer-border">
            <div class="corner-accent corner-tl"></div>
            <div class="corner-accent corner-tr"></div>
            <div class="corner-accent corner-bl"></div>
            <div class="corner-accent corner-br"></div>

            <div class="inner-border">
                <!-- Institution Header -->
                <div class="institution-header">
                    Lembaga Pelatihan Kerja & Pengembangan Kompetensi Kejuruan
                </div>
                <div class="branch-badge">
                    KANTOR CABANG: {{ strtoupper($branch->name ?? 'PUSAT') }} &bull; KODE: {{ $branch->code ?? 'LMS' }}
                </div>

                <!-- Certificate Title -->
                <h1 class="cert-title">Sertifikat Kelulusan</h1>
                <div class="cert-subtitle">Certificate of Completion</div>
                <div class="cert-number">NOMOR: {{ $submission->certificate_number }}</div>

                <!-- Awarded To -->
                <div class="awarded-to">Diberikan secara sah kepada:</div>
                <div class="student-name">{{ strtoupper($student->name) }}</div>

                <!-- Description -->
                <div class="cert-description">
                    Telah mengikuti dan dinyatakan <strong>LULUS</strong> pada program pelatihan kejuruan intensif:
                    <br>
                    <strong style="font-size: 13px; color: #0f172a; display: inline-block; margin-top: 1mm;">
                        &ldquo;{{ $class->title }}&rdquo;
                    </strong>
                    <br>
                    Dengan akumulasi beban studi kumulatif sebesar
                    <span class="badge-jp">{{ $submission->total_jp_earned }} Jam Pelajaran (JP)</span>
                    setara 900 menit pembelajaran efektif, serta telah menuntaskan seluruh rangkaian evaluasi kuis dengan predikat kompeten.
                </div>

                <!-- Footer Signatures & QR Code -->
                <table class="footer-table">
                    <tr>
                        <!-- Left: QR Code Verification -->
                        <td class="qr-col">
                            <div class="qr-box">
                                <img src="{{ $qrCodeBase64 }}" width="75" height="75" alt="QR Code Verifikasi">
                                <div class="qr-caption">
                                    <strong>Pindai QR Code</strong> untuk<br>
                                    verifikasi keaslian digital
                                </div>
                            </div>
                        </td>

                        <!-- Center: Official Seal -->
                        <td class="seal-col">
                            <div class="seal-circle">
                                TERAKREDITASI
                            </div>
                            <div style="font-size: 8px; color: #94a3b8; margin-top: 1.5mm; letter-spacing: 0.5px;">
                                SISTEM MANAJEMEN MUTU LMS
                            </div>
                        </td>

                        <!-- Right: Trainer Signature -->
                        <td class="signature-col">
                            <div class="sign-date">
                                {{ $branch->city ?? 'Jakarta' }},
                                {{ $submission->reviewed_at ? \Carbon\Carbon::parse($submission->reviewed_at)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                            </div>
                            <div class="sign-name">
                                {{ $trainer->name }}
                            </div>
                            <div class="sign-role">
                                Instruktur Pengampu Pelatihan
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
