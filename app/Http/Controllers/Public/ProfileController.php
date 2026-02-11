<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ChurchSetting;
use App\Models\ChurchOfficer;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Menampilkan Halaman Profil Gereja Lengkap
     */
    public function index()
    {
        // 1. Ambil Identitas & Konfigurasi Gereja
        $setting = ChurchSetting::current();

        // 2. Ambil Data Pendeta & Vicaris (Pimpinan Jemaat)
        $pastors = ChurchOfficer::with(['member', 'position'])
            ->whereHas('position', function($q) {
                $q->where('nama', 'like', '%Pendeta%')
                  ->orWhere('nama', 'like', '%Vicaris%');
            })
            ->active()
            ->orderBy('ref_position_id')
            ->get();

        // 3. Ambil Majelis / Badan Pengurus Harian (BPH)
        // Asumsi: Jabatan selain Pendeta/Vicaris
        $officers = ChurchOfficer::with(['member', 'position'])
            ->whereHas('position', function($q) {
                $q->where('nama', 'not like', '%Pendeta%')
                  ->where('nama', 'not like', '%Vicaris%');
            })
            ->active()
            ->orderBy('ref_position_id') // Urutkan berdasarkan hierarki jabatan
            ->limit(12) // Batasi tampilan agar rapi
            ->get();

        return view('public.profile', compact('setting', 'pastors', 'officers'));
    }
}