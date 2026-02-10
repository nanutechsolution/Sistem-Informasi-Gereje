<?php

namespace App\Http\Controllers;

use App\Models\SacramentRecord;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SacramentPrintController extends Controller
{
    /**
     * Generate PDF Sertifikat Sakramen (Baptis/Sidi/Nikah)
     */
    public function show(SacramentRecord $record)
    {
        $record->load(['member.family.refWilayah', 'type']);

        $data = [
            'record' => $record,
            'title' => $record->type->nama
        ];

        // Load view khusus PDF
        $pdf = Pdf::loadView('clerical.print-sacrament', $data);

        // Atur Kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');

        // Stream ke browser
        return $pdf->stream('Sertifikat_' . str_replace(' ', '_', $record->member->nama) . '.pdf');
    }
}
