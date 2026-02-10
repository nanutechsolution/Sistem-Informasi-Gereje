<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal PKS - {{ $wilayah }}</title>
    <style>
        /* Pengaturan Kertas A4 Portrait untuk DomPDF */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat Profesional */
        .header-table {
            width: 100%;
            border-bottom: 4px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .logo-cell {
            width: 70px;
            vertical-align: middle;
            text-align: left;
        }
        .logo {
            width: 65px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            vertical-align: middle;
        }
        .kop-text h1 {
            font-size: 15pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .kop-text h2 {
            font-size: 13pt;
            margin: 2px 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .kop-text p {
            font-size: 9pt;
            margin: 0;
            font-style: italic;
        }

        /* Judul Laporan */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h3 {
            font-size: 12pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
        }
        .report-title p {
            font-size: 10pt;
            margin: 3px 0;
            font-weight: bold;
        }

        /* Tabel Utama */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 8px 4px;
            font-size: 9pt;
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }
        .main-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 9pt;
            vertical-align: top;
        }

        /* Tim Pelayan Detail */
        .label-role {
            font-size: 8pt;
            font-weight: bold;
            color: #444;
            text-transform: uppercase;
            margin-bottom: 1px;
        }
        .name-role {
            font-weight: bold;
            font-style: italic;
        }
        .servant-list {
            margin: 5px 0 0 0;
            padding: 0 0 0 10px;
            list-style-type: circle;
        }
        .servant-list li {
            font-size: 8.5pt;
            margin-bottom: 1px;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .sign-space {
            height: 60px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        /* Footer Sistem */
        .footer-note {
            position: fixed;
            bottom: -10px;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            color: #777;
            font-style: italic;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .text-center { text-align: center; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo">
            </td>
            <td class="kop-text">
                <h1>Gereja Kristen Sumba (GKS)</h1>
                <h2>Jemaat Reda Pada</h2>
                <p>Kabupaten Sumba Barat Daya, Provinsi Nusa Tenggara Timur</p>
            </td>
            <td style="width: 70px;"></td> <!-- Spacer balance -->
        </tr>
    </table>

    <!-- JUDUL -->
    <div class="report-title">
        <h3>Jadwal Ibadah Rumah Tangga (PKS)</h3>
        <p>WILAYAH: {{ strtoupper($wilayah) }}</p>
        <div style="font-size: 9pt; font-weight: normal;">Periode: {{ $periode }}</div>
    </div>

    <!-- TABEL DATA -->
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 18%;">Hari / Tanggal</th>
                <th style="width: 25%;">Tuan Rumah</th>
                <th style="width: 32%;">Tim Pelayanan</th>
                <th style="width: 25%;">Pokok / Tema</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $sch)
            <tr>
                <td class="text-center">
                    <div style="font-weight: bold;">{{ $sch->tanggal->isoFormat('dddd') }}</div>
                    <div>{{ $sch->tanggal->format('d M Y') }}</div>
                    <div style="font-size: 8pt; font-weight: bold; margin-top: 2px;">Pkl. {{ $sch->jam_mulai->format('H:i') }} WITA</div>
                </td>
                <td>
                    <div style="font-weight: bold; text-transform: uppercase;">{{ $sch->family->kepala_keluarga ?? '-' }}</div>
                    <div style="font-size: 8pt; color: #333; margin-top: 3px;">{{ $sch->family->alamat ?? 'Alamat tidak tersedia' }}</div>
                </td>
                <td>
                    <div class="label-role">Pelayan Firman:</div>
                    <div class="name-role">{{ $sch->servants->where('peran', 'Pembaca Firman')->first()->member->nama ?? 'Akan ditentukan' }}</div>
                    
                    <div style="border-top: 0.5pt solid #ccc; margin-top: 4px; padding-top: 4px;">
                        <div class="label-role">Anggota:</div>
                        <ul class="servant-list">
                            @forelse($sch->servants->where('peran', 'Pendamping') as $p)
                                <li>{{ $p->member->nama }}</li>
                            @empty
                                <li style="list-style: none; font-style: italic; color: #999;">Belum ditugaskan</li>
                            @endforelse
                        </ul>
                    </div>
                </td>
                <td style="font-style: italic; font-size: 8.5pt;">
                    @if($sch->tema)
                        "{{ $sch->tema }}"
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 30px; color: #888;">
                    TIDAK ADA JADWAL TERSEDIA UNTUK PERIODE INI
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <table class="signature-table">
        <tr>
            <td>
                <p style="margin-bottom: 5px;">Mengetahui,</p>
                <p style="font-weight: bold;">Ketua Majelis Jemaat</p>
                <div class="sign-space"></div>
                <p class="sign-name">( Pdt. ..................................... )</p>
            </td>
            <td>
                <p style="margin-bottom: 5px;">Lolo Ole, {{ date('d F Y') }}</p>
                <p style="font-weight: bold;">Sekretaris</p>
                <div class="sign-space"></div>
                <p class="sign-name">( ............................................. )</p>
            </td>
        </tr>
    </table>

    <!-- FOOTER SISTEM -->
    <div class="footer-note">
        Dokumen otomatis SIG-GKS Jemaat Reda Pada | Dicetak pada {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>