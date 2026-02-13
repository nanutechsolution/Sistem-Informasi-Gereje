<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bantuan Diakonia</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 0; font-size: 10px; color: #666; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; padding: 3px 0; }
        .label { font-weight: bold; width: 150px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .items-table th { background: #f2f2f2; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .items-table td { border: 1px solid #ddd; padding: 8px; }
        
        .total-box { margin-top: 20px; text-align: right; font-size: 14px; font-weight: bold; padding: 10px; background: #f9f9f9; border: 1px solid #ddd; }
        
        .footer-sig { margin-top: 50px; width: 100%; }
        .footer-sig td { width: 33%; text-align: center; }
        .space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Bukti Penyaluran Bantuan Diakonia</h1>
        <p>Sistem Informasi Gereja (SIG-GKS)</p>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Penerima</td>
            <td>: {{ $data->member->nama ?? $data->nama_luar }} 
                ({{ $data->member_id ? 'Internal Jemaat' : 'Umum/Luar' }})</td>
        </tr>
        <tr>
            <td class="label">Kategori Bantuan</td>
            <td>: {{ $data->type->nama }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pemberian</td>
            <td>: {{ \Carbon\Carbon::parse($data->tanggal_pemberian)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Keterangan/Alasan</td>
            <td>: {{ $data->alasan_bantuan }}</td>
        </tr>
    </table>

    <h4 style="margin-bottom: 5px; text-transform: uppercase;">Rincian Barang / Dana:</h4>
    <table class="items-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang / Item</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th style="text-align: right;">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data->items as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->nama_item }}</td>
                <td>{{ number_format($item->qty, 0) }}</td>
                <td>{{ $item->satuan }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        TOTAL BANTUAN: Rp {{ number_format($data->nominal, 0, ',', '.') }}
    </div>

    <table class="footer-sig">
        <tr>
            <td>
                Penerima Manfaat,<br><br>
                <div class="space"></div>
                ( ............................ )
            </td>
            <td></td>
            <td>
                Bendahara/Petugas,<br><br>
                <div class="space"></div>
                ( {{ Auth::user()->name }} )
            </td>
        </tr>
    </table>
</body>
</html>