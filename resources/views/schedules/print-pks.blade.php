<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal PKS {{ $wilayah }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 1.2cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* Kop Surat */
        .header-table {
            width: 100%;
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .logo-cell {
            width: 70px;
            vertical-align: middle;
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
            font-size: 16pt;
            margin: 0;
            text-transform: uppercase;
            font-weight: bold;
        }
        .kop-text h2 {
            font-size: 14pt;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .kop-text p {
            font-size: 9pt;
            margin: 0;
            font-style: italic;
        }

        /* Judul */
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h3 {
            font-size: 13pt;
            text-transform: uppercase;
            text-decoration: underline;
            margin: 0;
            font-weight: bold;
        }
        .report-title p {
            font-size: 10pt;
            margin: 5px 0;
            font-weight: bold;
        }

        /* Tabel Utama */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #f5f5f5;
            border: 1px solid #000;
            padding: 10px 5px;
            font-size: 9pt;
            text-transform: uppercase;
            text-align: center;
        }
        .main-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        /* Detail Pelayan */
        .role-box {
            margin-bottom: 6px;
        }
        .role-label {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
            display: block;
        }
        .role-name {
            font-weight: bold;
            font-style: italic;
            font-size: 9pt;
        }
        .servant-list {
            margin: 2px 0 0 12px;
            padding: 0;
            list-style-type: disc;
        }
        .servant-list li {
            font-size: 8.5pt;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
        }
        .sign-space {
            height: 70px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .footer-note {
            position: fixed;
            bottom: -20px;
            width: 100%;
            text-align: center;
            font-size: 7pt;
            color: #666;
            border-top: 0.5pt solid #ccc;
            padding-top: 5px;
        }

        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo" onerror="this.style.display='none'">
            </td>
            <td class="kop-text">
                <h1>Gereja Kristen Sumba (GKS)</h1>
                <h2>Jemaat Reda Pada</h2>
                <p>Kabupaten Sumba Barat Daya, Provinsi Nusa Tenggara Timur</p>
            </td>
            <td style="width: 70px;"></td>
        </tr>
    </table>

    <div class="report-title">
        <h3>Jadwal Ibadah Persekutuan Keluarga Sektor (PKS)</h3>
        <p>WILAYAH: {{ strtoupper($wilayah) }}</p>
        <div style="font-size: 9pt;">Periode: {{ $periode }}</div>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="18%">Hari / Tanggal</th>
                <th width="25%">Tuan Rumah / Lokasi</th>
                <th width="32%">Tim Pelayanan</th>
                <th width="25%">Tema / Firman</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $item)
            @php
                $head = $item->family->members->sortBy('hubungan_keluarga_id')->first();
                $hostName = $head ? ($head->churchPeople->full_name ?? 'Keluarga') : 'Keluarga';
            @endphp
            <tr>
                <td class="text-center">
                    <div class="font-bold">{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('dddd') }}</div>
                    <div>{{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('D MMMM Y') }}</div>
                    <div style="font-size: 8pt; margin-top: 4px;">Pkl. {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} WITA</div>
                </td>
                <td>
                    <div class="font-bold uppercase" style="color: #1e3a8a;">{{ $hostName }}</div>
                    <div style="font-size: 8pt; color: #444; margin-top: 4px; border-top: 0.5pt solid #eee; padding-top: 2px;">
                        {{ $item->family->alamat ?? 'Alamat tidak tersedia' }}
                    </div>
                </td>
                <td>
                    <div class="role-box">
                        <span class="role-label">Pelayan Firman:</span>
                        <span class="role-name">{{ $item->servants->where('peran', 'Pembaca Firman')->first()->member->churchPeople->full_name ?? 'Akan ditentukan' }}</span>
                    </div>
                    
                    <div style="border-top: 0.5pt solid #eee; padding-top: 4px;">
                        <span class="role-label">Pendamping:</span>
                        <ul class="servant-list">
                            @forelse($item->servants->where('peran', 'Pendamping') as $p)
                                <li>{{ $p->member->churchPeople->full_name }}</li>
                            @empty
                                <li style="list-style: none; font-style: italic; color: #888;">Belum ditugaskan</li>
                            @endforelse
                        </ul>
                    </div>
                </td>
                <td style="font-style: italic; font-size: 9pt;">
                    @if($item->tema)
                        "{{ $item->tema }}"
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 40px; color: #666; font-style: italic;">
                    TIDAK ADA JADWAL TERSEDIA UNTUK PERIODE INI
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p class="font-bold">Ketua Majelis Jemaat</p>
                <div class="sign-space"></div>
                <p class="sign-name">( Pdt. ..................................... )</p>
            </td>
            <td>
                <p>Lolo Ole, {{ date('d F Y') }}</p>
                <p class="font-bold">Sekretaris</p>
                <div class="sign-space"></div>
                <p class="sign-name">( ............................................. )</p>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen otomatis SIG-GKS Jemaat Reda Pada | Dicetak pada {{ date('d/m/Y H:i') }}
    </div>

</body>
</html>