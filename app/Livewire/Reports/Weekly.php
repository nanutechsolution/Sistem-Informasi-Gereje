<?php

namespace App\Livewire\Reports;

use App\Models\Transaction;
use App\Models\FiscalYear;
use App\Models\OpeningBalance;
use App\Models\RefAccount;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Weekly extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Default: Senin s/d Minggu pekan ini
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
    }

    public function render()
    {
        $activeYear = FiscalYear::active();
        
        // 1. IDENTIFIKASI AKUN (Lebih Robust)
        // Mencari akun Kas Umum (baik yang ada nama 'Umum' atau akun kas_tunai pertama)
        $kasUmum = RefAccount::where('nama', 'like', '%Umum%')
                    ->orWhere('jenis', 'kas_tunai')
                    ->first();
        
        $kasBangun = RefAccount::where('nama', 'like', '%Pembangunan%')->first();

        // 2. HITUNG SALDO AWAL
        $saldoAwalUmum = $this->calculateOpeningBalance($kasUmum, $activeYear);
        $saldoAwalBangun = $this->calculateOpeningBalance($kasBangun, $activeYear);

        // 3. DATA HIGHLIGHTS (Untuk Ibadah Rumah Tangga & Lelang)
        $detailPKS = Transaction::whereHas('budgetPost', fn($q) => $q->where('kode', 'like', '1.2%'))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])->get();

        $detailLelang = Transaction::whereHas('budgetPost', fn($q) => $q->whereIn('kode', ['1.11', '1.21.1']))
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])->get();

        // 4. REKAPITULASI TABEL (Summary per Pos Anggaran)
        $pemasukanUmum = $this->getSummary($kasUmum, 'masuk');
        $pengeluaranUmum = $this->getSummary($kasUmum, 'keluar');
        
        $pemasukanBangun = $this->getSummary($kasBangun, 'masuk');
        $pengeluaranBangun = $this->getSummary($kasBangun, 'keluar');

        return view('livewire.reports.weekly', [
            'saldoAwalUmum' => $saldoAwalUmum,
            'saldoAwalBangun' => $saldoAwalBangun,
            'detailPKS' => $detailPKS,
            'detailLelang' => $detailLelang,
            'pemasukanUmum' => $pemasukanUmum,
            'pengeluaranUmum' => $pengeluaranUmum,
            'pemasukanBangun' => $pemasukanBangun,
            'pengeluaranBangun' => $pengeluaranBangun,
            'totalMasukUmum' => $pemasukanUmum->sum('total'),
            'totalKeluarUmum' => $pengeluaranUmum->sum('total'),
            'totalMasukBangun' => $pemasukanBangun->sum('total'),
            'totalKeluarBangun' => $pengeluaranBangun->sum('total'),
        ]);
    }

    private function calculateOpeningBalance($account, $activeYear)
    {
        if (!$account || !$activeYear) return 0;

        $base = OpeningBalance::where('fiscal_year_id', $activeYear->id)
            ->where('ref_account_id', $account->id)->sum('nominal');

        $mutasi = Transaction::where('ref_account_id', $account->id)
            ->where('tanggal', '<', $this->startDate);

        $masuk = (clone $mutasi)->where('jenis', 'masuk')->sum('nominal');
        $keluar = (clone $mutasi)->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal');

        return $base + $masuk - $keluar;
    }

    private function getSummary($account, $jenis)
    {
        if (!$account) return collect();

        // Ambil transaksi, group berdasarkan Pos Anggaran agar diringkas (misal: semua Gaji jadi satu baris)
        return Transaction::with('budgetPost')
            ->where('ref_account_id', $account->id)
            ->where('jenis', $jenis)
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->select('ref_budget_post_id', DB::raw('sum(nominal) as total'))
            ->groupBy('ref_budget_post_id')
            ->get();
    }
}