<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Bukti Penyaluran Diakonia - {{ $data->member?->churchPeople?->full_name ?? $data->nama_luar }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #1a1a1a; line-height: 1.4; margin: 0; padding: 0; }
        .container { padding: 1cm 1.5cm; }
        
        /* Kop Surat */
        .header-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .logo-cell { width: 80px; text-align: center; vertical-align: middle; }
        .logo { width: 75px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-text h1 { font-size: 16pt; margin: 0; font-weight: bold; text-transform: uppercase; }
        .kop-text h2 { font-size: 13pt; margin: 2px 0; font-weight: bold; text-transform: uppercase; }
        .kop-text p { font-size: 9pt; margin: 0; font-style: italic; }

        .title-section { text-align: center; margin: 20px 0; }
        .title-section h3 { font-size: 14pt; text-transform: uppercase; text-decoration: underline; margin: 0; font-weight: bold; }
        .nomor-surat { font-size: 10pt; margin-top: 5px; font-weight: bold; }

        /* Data Info */
        .info-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .label { width: 150px; font-weight: bold; }
        .sep { width: 15px; text-align: center; }

        /* Table Items */
        .items-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .items-table th { background: #f5f5f5; border: 1px solid #000; padding: 8px; font-size: 9pt; text-transform: uppercase; }
        .items-table td { border: 1px solid #000; padding: 8px; font-size: 10pt; }
        
        .total-box { margin-top: 10px; text-align: right; font-size: 12pt; font-weight: bold; padding: 10px; border: 1px solid #000; background: #fafafa; }

        /* Tanda Tangan */
        .signature-table { width: 100%; margin-top: 50px; }
        .signature-table td { width: 50%; text-align: center; vertical-align: top; }
        .sign-date { margin-bottom: 10px; }
        .sign-space { height: 75px; }
        .sign-name { font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        
        .footer-note { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 7pt; color: #777; font-style: italic; border-top: 0.5pt solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <!-- KOP SURAT -->
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo" onerror="this.style.display='none'">
                </td>
                <td class="kop-text">
                    <h1>Gereja Kristen Sumba (GKS)</h1>
                    <h2>Jemaat Reda Pada</h2>
                    <p>Lolo Ole, Kec. Kota Tambolaka, Kabupaten Sumba Barat Daya, NTT</p>
                </td>
            </tr>
        </table>

        <!-- JUDUL -->
        <div class="title-section">
            <h3>Bukti Penyaluran Bantuan Diakonia</h3>
            <div class="nomor-surat">ID Transaksi: #{{ str_pad($data->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- INFO PENERIMA -->
        <table class="info-table">
            <tr>
                <td class="label">Nama Penerima</td>
                <td class="sep">:</td>
                <td style="font-weight: bold; text-transform: uppercase;">
                    {{ $data->member?->churchPeople?->full_name ?? $data->nama_luar }}
                </td>
            </tr>
            <tr>
                <td class="label">Status Penerima</td>
                <td class="sep">:</td>
                <td>{{ $data->member_id ? 'Anggota Jemaat' : 'Pihak Luar (Umum)' }}</td>
            </tr>
            <tr>
                <td class="label">Kategori Diakonia</td>
                <td class="sep">:</td>
                <td>{{ $data->type->nama }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Penyaluran</td>
                <td class="sep">:</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pemberian)->isoFormat('D MMMM Y') }}</td>
            </tr>
            <tr>
                <td class="label">Keterangan / Alasan</td>
                <td class="sep">:</td>
                <td>{{ $data->alasan_bantuan }}</td>
            </tr>
        </table>

        <!-- RINCIAN -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Rincian Barang / Dana</th>
                    <th width="15%">Jumlah</th>
                    <th width="15%">Satuan</th>
                    <th width="20%" style="text-align: right;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->items as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_item }}</td>
                    <td style="text-align: center;">{{ number_format($item->qty, 0) }}</td>
                    <td style="text-align: center;">{{ $item->satuan }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-box">
            TOTAL PENYALURAN: Rp {{ number_format($data->nominal, 0, ',', '.') }}
        </div>

        <!-- TANDA TANGAN -->
        <table class="signature-table">
            <tr>
                <td>
                    <div class="sign-date">&nbsp;</div>
                    <div class="sign-role">Penerima Manfaat,</div>
                    <div class="sign-space"></div>
                    <div class="sign-name">{{ $data->member?->churchPeople?->full_name ?? $data->nama_luar }}</div>
                </td>
                <td>
                    <div class="sign-date">Lolo Ole, {{ now()->isoFormat('D MMMM Y') }}</div>
                    <div class="sign-role">Bendahara / Petugas,</div>
                    <div class="sign-space"></div>
                    <div class="sign-name">{{ Auth::user()->name }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dicetak otomatis melalui SIG-GKS Jemaat Reda Pada pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>