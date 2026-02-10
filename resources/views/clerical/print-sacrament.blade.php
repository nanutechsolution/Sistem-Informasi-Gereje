<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} - {{ $record->member->nama }}</title>
    <style>
        /* Pengaturan Halaman A4 Portrait - Strict 1 Page */
        @page {
            size: a4 portrait;
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Bingkai Klasik & Profesional */
        .border-outer {
            position: absolute;
            top: 0.8cm; left: 0.8cm; right: 0.8cm; bottom: 0.8cm;
            border: 1px solid #1e3a8a;
            z-index: -1;
        }
        .border-inner {
            position: absolute;
            top: 0.95cm; left: 0.95cm; right: 0.95cm; bottom: 0.95cm;
            border: 4px double #1e3a8a;
            z-index: -1;
        }

        .container {
            padding: 1.5cm 2cm;
            text-align: center;
        }

        /* Kop Surat Resmi */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            margin-bottom: 5px;
            padding-bottom: 10px;
        }
        .logo-cell {
            width: 80px;
            vertical-align: middle;
        }
        .logo {
            width: 70px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            vertical-align: middle;
        }
        .kop-text h1 {
            font-size: 20pt;
            margin: 0;
            text-transform: uppercase;
            color: #1e3a8a;
            letter-spacing: 2px;
            font-weight: bold;
        }
        .kop-text h2 {
            font-size: 15pt;
            margin: 2px 0;
            text-transform: uppercase;
            color: #333;
        }
        .kop-text p {
            font-size: 9pt;
            margin: 0;
            font-style: italic;
            color: #555;
        }

        /* Judul Dokumen Utama */
        .doc-title-section {
            margin: 30px 0 20px 0;
        }
        .doc-title-section h3 {
            font-size: 24pt;
            text-transform: uppercase;
            margin: 0;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
        }
        .doc-title-section .nomor-surat {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 5px;
            display: block;
            color: #444;
            letter-spacing: 2px;
        }

        /* Isi Informasi Jemaat */
        .content-body {
            text-align: left;
            margin: 0 auto;
            width: 100%;
            font-size: 12pt;
        }
        .intro-text {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12pt;
        }
        .data-table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        .label-col {
            width: 180px;
            color: #555;
            font-style: italic;
        }
        .dots-col {
            width: 20px;
            text-align: center;
            font-weight: bold;
        }
        .value-col {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #ddd;
            color: #000;
        }

        /* Bagian Pelaksanaan */
        .event-context {
            margin: 25px 0 10px 0;
            text-align: center;
            font-size: 12pt;
        }

        /* Penutup */
        .closing-section {
            margin-top: 30px;
            text-align: center;
            font-size: 11pt;
            line-height: 1.6;
        }

        /* Area Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 40px;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-date {
            margin-bottom: 5px;
            font-size: 11pt;
        }
        .sign-title {
            font-weight: bold;
            margin-bottom: 60px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        /* Watermark Simbol Salib */
        .watermark {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 350pt;
            color: rgba(30, 58, 138, 0.03);
            z-index: -2;
        }
    </style>
</head>
<body>

    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <div class="watermark">†</div>

    <div class="container">
        <!-- KOP SURAT PROFESIONAL -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
                </td>
                <td class="kop-text">
                    <h1>Gereja Kristen Sumba</h1>
                    <h2>Jemaat Reda Pada</h2>
                    <p>Lolo Ole, Kec. Kota Tambolaka, Kabupaten Sumba Barat Daya, NTT</p>
                </td>
                <td class="logo-cell"></td>
            </tr>
        </table>

        <!-- JUDUL SERTIFIKAT -->
        <div class="doc-title-section">
            <h3>{{ strtoupper($record->type->nama) }}</h3>
            <span class="nomor-surat">NOMOR REGISTER: {{ $record->nomor_surat }}</span>
        </div>

        <!-- ISI SURAT -->
        <div class="content-body">
            @if($record->type->kode == 'NKH')
                <!-- KHUSUS SURAT NIKAH -->
                <p class="intro-text">Telah diberkati dan diteguhkan dalam Pernikahan Kudus Pasangan Suami Istri:</p>

                <table class="data-table">
                    <tr>
                        <td class="label-col">Mempelai Laki-laki</td>
                        <td class="dots-col">:</td>
                        <td class="value-col">{{ $record->member->jenis_kelamin == 'L' ? $record->member->nama : ($record->partner->nama ?? $record->partner_external_name) }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Mempelai Perempuan</td>
                        <td class="dots-col">:</td>
                        <td class="value-col">{{ $record->member->jenis_kelamin == 'P' ? $record->member->nama : ($record->partner->nama ?? $record->partner_external_name) }}</td>
                    </tr>
                </table>
            @else
                <!-- STANDAR BAPTIS / SIDI -->
                <p class="intro-text">Majelis Jemaat Gereja Kristen Sumba (GKS) Reda Pada menerangkan dengan sesungguhnya bahwa:</p>

                <table class="data-table">
                    <tr>
                        <td class="label-col">Nama Lengkap</td>
                        <td class="dots-col">:</td>
                        <td class="value-col">{{ $record->member->nama }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Tempat, Tanggal Lahir</td>
                        <td class="dots-col">:</td>
                        <td class="value-col">{{ $record->member->tempat_lahir }}, {{ $record->member->tanggal_lahir?->isoFormat('D MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Jenis Kelamin</td>
                        <td class="dots-col">:</td>
                        <td class="value-col">{{ $record->member->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Anak dari Ayah / Ibu</td>
                        <td class="dots-col">:</td>
                        <td class="value-col">{{ $record->member->family->kepala_keluarga }}</td>
                    </tr>
                </table>
            @endif

            <div class="event-context">
                Dilaksanakan Pelayanan Sakramen Kudus / Peneguhan pada:
            </div>

            <table class="data-table">
                <tr>
                    <td class="label-col">Hari / Tanggal</td>
                    <td class="dots-col">:</td>
                    <td class="value-col">{{ $record->tanggal_pelaksanaan->isoFormat('dddd, D MMMM Y') }}</td>
                </tr>
                <tr>
                    <td class="label-col">Tempat Pelaksanaan</td>
                    <td class="dots-col">:</td>
                    <td class="value-col">{{ $record->tempat_pelaksanaan }}</td>
                </tr>
                <tr>
                    <td class="label-col">Dilayani Oleh</td>
                    <td class="dots-col">:</td>
                    <td class="value-col">{{ $record->pelayan_firman }}</td>
                </tr>
            </table>
        </div>

        <!-- KALIMAT PENUTUP -->
        <div class="closing-section">
            <p>Demikian surat ini diberikan untuk dipergunakan sebagaimana mestinya.<br>
            <strong>"Segala kemuliaan hanya bagi Tuhan Yesus Kristus Sang Kepala Gereja."</strong></p>
        </div>

        <!-- PENGESAHAN -->
        <table class="signature-table">
            <tr>
                <td></td>
                <td>
                    <div class="sign-date">Lolo Ole, {{ date('d F Y') }}</div>
                    <div class="sign-title">
                        Majelis Jemaat GKS Reda Pada<br>
                        Ketua,
                    </div>
                    <div class="sign-name">
                        ( Pdt. ..................................... )
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>