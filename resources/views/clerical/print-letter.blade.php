<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page { size: a4 portrait; margin: 0; }
        body { font-family: 'Times New Roman', Times, serif; color: #1a1a1a; line-height: 1.5; margin: 0; padding: 0; background-color: #fff; }
        
        /* Layout */
        .container { padding: 2cm 2.5cm; }
        
        /* Kop Surat */
        .header-table { width: 100%; border-bottom: 3px double #000; margin-bottom: 20px; padding-bottom: 10px; }
        .logo-cell { width: 80px; text-align: center; vertical-align: middle; }
        .logo { width: 80px; height: auto; }
        .kop-text { text-align: center; vertical-align: middle; }
        .kop-text h1 { font-size: 18pt; margin: 0; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text h2 { font-size: 14pt; margin: 2px 0; font-weight: bold; text-transform: uppercase; }
        .kop-text p { font-size: 10pt; margin: 0; font-style: italic; }

        /* Judul Surat */
        .title-section { text-align: center; margin: 30px 0; }
        .title-section h3 { font-size: 16pt; text-transform: uppercase; text-decoration: underline; margin: 0; font-weight: bold; }
        .nomor-surat { font-size: 11pt; margin-top: 5px; display: block; }

        /* Isi */
        .content { font-size: 12pt; text-align: justify; }
        .data-table { width: 100%; margin: 15px 0; border-collapse: collapse; }
        .data-table td { padding: 3px 0; vertical-align: top; }
        .label-col { width: 160px; }
        .sep-col { width: 15px; text-align: center; }
        .val-col { font-weight: bold; }

        /* Penutup & TTD */
        .closing { margin-top: 20px; text-indent: 30px; }
        .signature-section { margin-top: 50px; width: 100%; }
        .sign-col { width: 50%; text-align: center; vertical-align: top; }
        .sign-date { margin-bottom: 10px; }
        .sign-role { font-weight: bold; margin-bottom: 70px; }
        .sign-name { font-weight: bold; text-decoration: underline; }
        .sign-nip { font-size: 10pt; margin-top: 2px; }
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
                    <h1>Gereja Kristen Sumba</h1>
                    <h2>Jemaat Reda Pada</h2>
                    <p>Alamat: Lolo Ole, Kec. Kota Tambolaka, Kabupaten Sumba Barat Daya, NTT</p>
                </td>
            </tr>
        </table>

        <!-- JUDUL -->
        <div class="title-section">
            <h3>SURAT {{ strtoupper(str_replace('_', ' ', $letter->jenis)) }}</h3>
            <span class="nomor-surat">Nomor: {{ $letter->nomor_surat }}</span>
        </div>

        <!-- ISI SURAT -->
        <div class="content">
            <p>Majelis Jemaat GKS Reda Pada dengan ini menerangkan bahwa:</p>

            <table class="data-table">
                <tr>
                    <td class="label-col">Nama Lengkap</td>
                    <td class="sep-col">:</td>
                    <td class="val-col">{{ $letter->member->churchPeople->full_name }}</td>
                </tr>
                <tr>
                    <td class="label-col">Tempat, Tgl Lahir</td>
                    <td class="sep-col">:</td>
                    <td class="val-col">
                        {{ $letter->member->churchPeople->place_of_birth }}, 
                        {{ \Carbon\Carbon::parse($letter->member->churchPeople->date_of_birth)->isoFormat('D MMMM Y') }}
                    </td>
                </tr>
                <tr>
                    <td class="label-col">Jenis Kelamin</td>
                    <td class="sep-col">:</td>
                    <td class="val-col">{{ $letter->member->churchPeople->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Alamat</td>
                    <td class="sep-col">:</td>
                    <td class="val-col">{{ $letter->member->family->alamat ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Nomor Anggota</td>
                    <td class="sep-col">:</td>
                    <td class="val-col">{{ $letter->member->churchPeople->nik ?? '-' }}</td>
                </tr>
            </table>

            <p>Adalah benar anggota sidi jemaat GKS Reda Pada yang terdaftar aktif.</p>

            @if($letter->jenis == 'pindah')
                <p>Surat ini diterbitkan sebagai <strong>Surat Atestasi Pindah</strong> karena yang bersangkutan akan pindah domisili/keanggotaan ke gereja lain.</p>
            @endif

            @if($letter->keperluan)
                <p>Surat ini diberikan untuk keperluan: <br><strong>{{ $letter->keperluan }}</strong></p>
            @endif

            <p class="closing">Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya. Tuhan Yesus memberkati.</p>
        </div>

        <!-- TANDA TANGAN -->
        <table class="signature-section">
            <tr>
                <td class="sign-col"></td> <!-- Kosongkan kolom kiri jika hanya 1 TTD -->
                <td class="sign-col">
                    <div class="sign-date">Lolo Ole, {{ \Carbon\Carbon::parse($letter->tanggal_cetak)->isoFormat('D MMMM Y') }}</div>
                    <div class="sign-role">
                        Majelis Jemaat GKS Reda Pada<br>
                        {{ $letter->signatory->position->nama ?? 'Pejabat Gereja' }}
                    </div>
                    <div class="sign-name">{{ $letter->signatory->member->churchPeople->full_name }}</div>
                    @if($letter->signatory->nip_gereja)
                        <div class="sign-nip">NIP: {{ $letter->signatory->nip_gereja }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>
</html>