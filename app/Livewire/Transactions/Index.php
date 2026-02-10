<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\FiscalYear;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $startDate;
    public $endDate;
    public $filterAccount = '';
    public $filterJenis = '';

    protected $queryString = ['search', 'startDate', 'endDate', 'filterAccount', 'filterJenis'];

    public function mount()
    {
        // Default: Tampilkan data bulan ini
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function delete($id)
    {
        // PENGATURAN PERMISSION: Hanya yang punya izin manage_finance (Admin, Bendahara, Panitia Pembangunan)
        if (!Auth::user()->can('manage_finance')) {
            $this->dispatch('notify', message: 'AKSES DITOLAK: Anda tidak berhak menghapus transaksi.', type: 'error');
            return;
        }

        $trx = Transaction::find($id);
        
        if ($trx) {
            // Jika ini transfer (pindah buku), hapus pasangannya juga
            if ($trx->relatedTransaction) {
                $trx->relatedTransaction->delete();
            }
            
            $trx->delete();
            $this->dispatch('notify', message: 'Transaksi berhasil dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $activeYear = FiscalYear::active();

        $query = Transaction::with(['account', 'budgetPost', 'user'])
            ->where('fiscal_year_id', $activeYear?->id ?? 0)
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->when($this->search, function($q) {
                $q->where('keterangan', 'like', '%' . $this->search . '%')
                  ->orWhere('nominal', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterAccount, fn($q) => $q->where('ref_account_id', $this->filterAccount))
            ->when($this->filterJenis, fn($q) => $q->where('jenis', $this->filterJenis))
            ->latest('tanggal')
            ->latest('created_at');

        // Hitung Ringkasan Dinamis (Mendukung filter Panitia Pembangunan)
        $summaryQuery = clone $query;
        $totalMasuk = (clone $summaryQuery)->where('jenis', 'masuk')->sum('nominal');
        $totalKeluar = (clone $summaryQuery)->where('jenis', 'keluar')->sum('nominal');

        // Tambahan khusus: Saldo Kas Pembangunan dalam periode ini
        $kasBangun = (clone $summaryQuery)
            ->whereHas('account', fn($q) => $q->where('nama', 'like', '%Pembangunan%'))
            ->get();

        return view('livewire.transactions.index', [
            'transactions' => $query->paginate(15),
            'accounts' => RefAccount::orderBy('nama')->get(),
            'summary' => [
                'masuk' => $totalMasuk,
                'keluar' => $totalKeluar,
                'saldo_periode' => $totalMasuk - $totalKeluar,
                'pembangunan' => $kasBangun->where('jenis', 'masuk')->sum('nominal') - $kasBangun->where('jenis', 'keluar')->sum('nominal')
            ]
        ]);
    }
}