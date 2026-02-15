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
        $setting = ChurchSetting::first();

        // 2. Ambil Data Pendeta & Vicaris (Pimpinan Jemaat)
        $pastors = ChurchOfficer::with(['member.churchPeople', 'position'])
            ->whereHas('position', function($q) {
                $q->where('nama', 'like', '%Pendeta%')
                  ->orWhere('nama', 'like', '%Vicaris%');
            })
            ->where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('ref_position_id')
            ->get();

        // 3. Ambil Majelis (Penatua, Diaken, dan Pengurus lainnya)
        $officers = ChurchOfficer::with(['member.churchPeople', 'position'])
            ->whereHas('position', function($q) {
                // Mencari selain pimpinan jemaat
                $q->where('nama', 'not like', '%Pendeta%')
                  ->where('nama', 'not like', '%Vicaris%');
            })
            ->where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->orderBy('ref_position_id')
            ->get();

        return view('public.profile', compact('setting', 'pastors', 'officers'));
    }
}