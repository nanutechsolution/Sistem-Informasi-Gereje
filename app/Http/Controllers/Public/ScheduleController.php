<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ActivitySchedule;
use App\Models\RefActivityType;
use App\Models\RefWilayah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman jadwal lengkap dengan filter.
     */
    public function index(Request $request)
    {
        $query = ActivitySchedule::with(['type', 'family.refWilayah', 'servants.member', 'wilayah'])
            ->where('status', '!=', 'batal') // Hanya tampilkan yang aktif/terlaksana
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc');

        // 1. Filter Rentang Tanggal (Default: Mulai Hari Ini)
        $startDate = $request->input('start_date', Carbon::today()->format('Y-m-d'));
        $endDate = $request->input('end_date');

        $query->where('tanggal', '>=', $startDate);
        
        if ($endDate) {
            $query->where('tanggal', '<=', $endDate);
        }

        // 2. Filter Jenis Kegiatan
        if ($request->type_id) {
            $query->where('ref_activity_type_id', $request->type_id);
        }

        // 3. Filter Wilayah (Cerdas: Cek di jadwal umum ATAU keluarga PKS)
        if ($request->wilayah_id) {
            $query->where(function($q) use ($request) {
                $q->where('ref_wilayah_id', $request->wilayah_id)
                  ->orWhereHas('family', fn($fq) => $fq->where('wilayah_id', $request->wilayah_id));
            });
        }

        // 4. Pencarian Teks (Tema / Tuan Rumah / Pelayan)
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tema', 'like', "%{$search}%")
                  ->orWhereHas('family', fn($fq) => $fq->where('kepala_keluarga', 'like', "%{$search}%"))
                  ->orWhereHas('servants.member', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
            });
        }

        $schedules = $query->paginate(9)->withQueryString();
        $types = RefActivityType::all();
        $wilayahs = RefWilayah::orderBy('nama')->get();

        return view('public.schedules.index', compact('schedules', 'types', 'wilayahs'));
    }
}