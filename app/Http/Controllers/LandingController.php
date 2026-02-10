<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Member;
use App\Models\ActivitySchedule;
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
        $today = Carbon::today();
        $activeYear = FiscalYear::active();

        // 1. STATISTIK REAL-TIME
        $stats = [
            'total_kk' => Family::where('status', 'aktif')->count(),
            'total_jiwa' => Member::count(),
            'total_wilayah' => RefWilayah::count(),
        ];

        // 2. JADWAL PELAYANAN MENDATANG (Limit 3)
        // Mengambil jadwal yang belum terlaksana dan diurutkan dari yang terdekat
        $schedules = ActivitySchedule::with(['type', 'family', 'wilayah', 'servants.member'])
            ->where('tanggal', '>=', $today)
            ->where('status', 'rencana')
            ->orderBy('tanggal', 'asc')
            ->limit(3)
            ->get();

        // 3. WARTA & BERITA TERBARU (Limit 2 sesuai desain UI)
        $posts = Post::with('author')
            ->where('is_published', true)
            ->latest('published_at')
            ->limit(2)
            ->get();

        // 4. GALERI DOKUMENTASI (Limit 6)
        $galleries = Gallery::latest()->limit(6)->get();

        // 5. TRANSPARANSI SALDO KAS UMUM
        // Mengambil saldo akun yang mengandung nama 'Umum'
        $kasUmum = RefAccount::where('nama', 'like', '%Umum%')->first();
        $saldo = 0;
        
        if ($kasUmum && $activeYear) {
            $base = OpeningBalance::where('fiscal_year_id', $activeYear->id)
                ->where('ref_account_id', $kasUmum->id)->sum('nominal');
            $masuk = Transaction::where('ref_account_id', $kasUmum->id)->where('jenis', 'masuk')->sum('nominal');
            $keluar = Transaction::where('ref_account_id', $kasUmum->id)->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');
            $saldo = $base + $masuk - $keluar;
        }

        // Kirim semua data ke resources/views/public/index.blade.php
        return view('public.index', compact('stats', 'schedules', 'posts', 'galleries', 'saldo'));
    }
}