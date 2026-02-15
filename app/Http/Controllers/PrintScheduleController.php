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
    public function pks(Request $request)
    {
        // Pastikan nama type sesuai dengan di database
        $pksType = RefActivityType::where('nama', 'like', '%PKS%')->first();
        
        $query = ActivitySchedule::with([
                'family.wilayah', 
                'family.members.churchPeople', 
                'servants.member.churchPeople'
            ])
            ->where('ref_activity_type_id', $pksType?->id ?? 0);

        // Filter dari Request (Sinkron dengan Livewire)
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
            'periode' => $request->startDate && $request->endDate 
                ? Carbon::parse($request->startDate)->isoFormat('D MMMM') . ' - ' . Carbon::parse($request->endDate)->isoFormat('D MMMM Y')
                : 'Semua Periode'
        ];

        $pdf = Pdf::loadView('schedules.print-pks', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Jadwal_PKS_' . Str::slug($wilayahName) . '.pdf');
    }
}