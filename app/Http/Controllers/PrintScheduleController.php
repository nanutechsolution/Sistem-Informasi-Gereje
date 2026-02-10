<?php

namespace App\Http\Controllers;

use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf; 
use Illuminate\Support\Str;

class PrintScheduleController extends Controller
{
    /**
     * Generate PDF Jadwal PKS menggunakan DomPDF
     */
    public function pks(Request $request)
    {
        $pksTypeId = RefActivityType::where('nama', 'like', '%PKS%')->value('id');
        
        $query = ActivitySchedule::with(['family.refWilayah', 'servants.member'])
            ->where('ref_activity_type_id', $pksTypeId);

        // Filter Sinkron dengan UI
        if ($request->startDate && $request->endDate) {
            $query->whereBetween('tanggal', [$request->startDate, $request->endDate]);
        }

        if ($request->wilayah) {
            $query->whereHas('family', fn($q) => $q->where('wilayah_id', $request->wilayah));
        }

        $schedules = $query->orderBy('tanggal', 'asc')->get();
        $wilayahName = $request->wilayah ? RefWilayah::find($request->wilayah)?->nama : 'Semua Wilayah';

        $data = [
            'schedules' => $schedules,
            'wilayah' => $wilayahName,
            'periode' => Carbon::parse($request->startDate)->isoFormat('D MMMM') . ' - ' . Carbon::parse($request->endDate)->isoFormat('D MMMM Y')
        ];

        // Load View dan Convert ke PDF
        $pdf = Pdf::loadView('schedules.print-pks', $data);
        
        // Atur Kertas A4 Portrait
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Jadwal_PKS_' . Str::slug($wilayahName) . '.pdf');
    }
}