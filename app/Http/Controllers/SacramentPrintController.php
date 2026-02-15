<?php

namespace App\Http\Controllers;

use App\Models\SacramentRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class SacramentPrintController extends Controller
{
    /**
     * Generate PDF Sertifikat Sakramen (Baptis/Sidi/Nikah)
     */
    public function show(SacramentRecord $record)
    {
        // Eager load data orang (churchPeople) dan relasi keluarga
        $record->load([
            'member.churchPeople', 
            'member.family.wilayah', 
            'partner.churchPeople', 
            'type'
        ]);

        $data = [
            'record' => $record,
            'title' => $record->type->nama
        ];

        $pdf = Pdf::loadView('clerical.print-sacrament', $data);
        $pdf->setPaper('a4', 'portrait');

        $fileName = 'Sertifikat_' . str_replace(' ', '_', $record->member->churchPeople->full_name ?? 'Sakramen') . '.pdf';
        
        return $pdf->stream($fileName);
    }
}