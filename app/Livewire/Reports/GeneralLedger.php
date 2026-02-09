<?php

namespace App\Livewire\Reports;

use App\Models\RefAccount;
use App\Models\Transaction;
use App\Models\OpeningBalance;
use App\Models\FiscalYear;
use Livewire\Component;
use Carbon\Carbon;

class GeneralLedger extends Component
{
    public $startDate;
    public $endDate;
    public $accountId = 'all'; // Filter Akun (All / Bank BRI / Kas Tunai)

    public function mount()
    {
        // Default: Bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function render()
    {
        $activeYear = FiscalYear::active();
        
        // 1. HITUNG SALDO AWAL (Sebelum Start Date)
        // Rumus: (Saldo Awal Tahun) + (Total Masuk s/d H-1) - (Total Keluar s/d H-1)
        
        $queryOpening = Transaction::query()
            ->where('tanggal', '<', $this->startDate);
            
        $queryBaseSaldo = OpeningBalance::query();

        if ($activeYear) {
            $queryOpening->where('fiscal_year_id', $activeYear->id);
            $queryBaseSaldo->where('fiscal_year_id', $activeYear->id);
        }

        if ($this->accountId !== 'all') {
            $queryOpening->where('ref_account_id', $this->accountId);
            $queryBaseSaldo->where('ref_account_id', $this->accountId);
        }

        $saldoAwalTahun = $queryBaseSaldo->sum('nominal');
        
        // Mutasi sebelum periode
        $mutasiMasukSebelum = (clone $queryOpening)->where('jenis', 'masuk')->sum('nominal');
        $mutasiKeluarSebelum = (clone $queryOpening)->where('jenis', 'keluar')->sum('nominal');
        
        // Khusus Pindah Buku (Transfer)
        // Jika akun 'all', transfer internal tidak mengubah total saldo gabungan, jadi abaikan.
        // Jika akun spesifik, transfer masuk menambah, transfer keluar mengurangi.
        $mutasiTransferMasukSebelum = 0;
        $mutasiTransferKeluarSebelum = 0;

        if ($this->accountId !== 'all') {
            // Transfer Masuk ke akun ini (jenis=masuk, tapi dari transfer) - Di sistem kita 'jenis' transfer dicatat sebagai 'pindah_buku' (keluar) dan 'masuk' (tujuan).
            // Tapi di logic Transaction create kita: 
            // 1. Trx Keluar (pindah_buku)
            // 2. Trx Masuk (masuk) -> related_transaction_id not null
            // Jadi cukup sum 'masuk' dan 'keluar' seperti di atas sudah mencakup transfer, 
            // KECUALI 'pindah_buku' (keluar) belum terhitung di 'keluar'.
            $mutasiTransferKeluarSebelum = (clone $queryOpening)->where('jenis', 'pindah_buku')->sum('nominal');
        }

        $saldoAwalPeriode = $saldoAwalTahun + $mutasiMasukSebelum - $mutasiKeluarSebelum - $mutasiTransferKeluarSebelum;

        // 2. AMBIL TRANSAKSI PERIODE INI
        $transactions = Transaction::with(['account', 'budgetPost'])
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->when($this->accountId !== 'all', function($q) {
                $q->where('ref_account_id', $this->accountId);
            })
            ->orderBy('tanggal')
            ->orderBy('created_at')
            ->get();

        return view('livewire.reports.general-ledger', [
            'saldoAwal' => $saldoAwalPeriode,
            'transactions' => $transactions,
            'accounts' => RefAccount::all(),
            'totalMasuk' => $transactions->where('jenis', 'masuk')->sum('nominal'),
            'totalKeluar' => $transactions->whereIn('jenis', ['keluar', 'pindah_buku'])->sum('nominal'),
        ]);
    }
}
