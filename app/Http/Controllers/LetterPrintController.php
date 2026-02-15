<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LetterPrintController extends Controller
{
    /**
     * Cetak Surat PDF
     */
    public function show(Letter $letter)
    {
        // Load relasi yang dibutuhkan untuk tampilan cetak
        $letter->load([
            'member.churchPeople', // Data Jemaat
            'member.family',       // Data Keluarga (jika perlu alamat/no kk)
            'signatory.member.churchPeople', // Data Penandatangan
            'signatory.position'   // Jabatan Penandatangan
        ]);

        $data = [
            'letter' => $letter,
            'title'  => 'SURAT ' . strtoupper($letter->jenis)
        ];

        // Load view cetak khusus
        $pdf = Pdf::loadView('clerical.print-letter', $data);
        
        // Setup kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Nama file saat didownload/stream
        $namaFile = 'Surat_' . $letter->jenis . '_' . 
                    str_replace(' ', '_', $letter->member->churchPeople->full_name ?? 'Jemaat') . 
                    '.pdf';

        return $pdf->stream($namaFile);
    }
}