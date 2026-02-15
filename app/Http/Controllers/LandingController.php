<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Member;
use App\Models\ActivitySchedule;
use App\Models\ChurchSetting;
use App\Models\RefAccount;
use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\Post;
use App\Models\Gallery;
use App\Models\RefWilayah;
use Carbon\Carbon;

class LandingController extends Controller
{
    /**
     * Menampilkan Landing Page Website Jemaat dengan data dinamis.
     */
    public function index()
    {
        // 1. Identitas Gereja (First row)
        $setting = ChurchSetting::first();
        $today = Carbon::today();
        $activeYear = FiscalYear::where('is_active', true)->first();

        // 2. STATISTIK JEMAAT AKTIF
        $stats = [
            'total_kk' => Family::where('status', 'aktif')->count(),
            'total_jiwa' => Member::where('status_keanggotaan', 'aktif')->count(),
            'total_wilayah' => RefWilayah::count(),
        ];

        // 3. JADWAL PELAYANAN (Include Host/Tuan Rumah)
        $schedules = ActivitySchedule::with([
                'type', 
                'family.wilayah', 
                'family.members.churchPeople', 
                'servants.member.churchPeople'
            ])
            ->where('tanggal', '>=', $today)
            ->where('status', 'rencana')
            ->orderBy('tanggal', 'asc')
            ->limit(3)
            ->get();

        // 4. WARTA & BERITA
        $posts = Post::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        // 5. GALERI (Limit 4 untuk layout grid)
        $galleries = Gallery::latest()->limit(4)->get();

        // 6. TRANSPARANSI KAS (Logic Saldo Akurat)
        $kasUmum = RefAccount::where('nama', 'like', '%Kas%')->first();
        $saldo = 0;

        if ($kasUmum && $activeYear) {
            $base = OpeningBalance::where('fiscal_year_id', $activeYear->id)
                ->where('ref_account_id', $kasUmum->id)->sum('nominal');
            $masuk = Transaction::where('ref_account_id', $kasUmum->id)->where('jenis', 'masuk')->sum('nominal');
            $keluar = Transaction::where('ref_account_id', $kasUmum->id)->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');
            $saldo = $base + $masuk - $keluar;
        }

        return view('public.index', compact('setting', 'stats', 'schedules', 'posts', 'galleries', 'saldo'));
    }
}