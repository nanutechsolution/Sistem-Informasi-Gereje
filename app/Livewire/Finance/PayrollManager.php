<?php

namespace App\Livewire\Finance;

use App\Models\Payroll;
use App\Models\PayrollPayment;
use App\Models\ChurchOfficer;
use App\Models\FiscalYear;
use App\Models\Transaction;
use App\Models\RefAccount;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollManager extends Component
{
    public $bulan, $tahun, $ref_account_id;
    
    // State Modal Cicilan
    public $isPaymentModalOpen = false;
    public $selectedPayrollId;
    public $nominal_bayar;
    public $catatan_bayar;

    public function mount()
    {
        $this->bulan = date('n');
        $this->tahun = date('Y');
        // Default cari akun Kas Umum
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first();
        $this->ref_account_id = $acc->id ?? null;
    }

    public function generate()
    {
        $fiscalYear = FiscalYear::active();
        if (!$fiscalYear) {
            $this->dispatch('notify', message: 'Tahun Anggaran Aktif tidak ditemukan!', type: 'error');
            return;
        }

        $activeOfficers = ChurchOfficer::where('is_active', true)->get();
        $count = 0;

        foreach ($activeOfficers as $off) {
            $exists = Payroll::where('church_officer_id', $off->id)
                ->where('bulan', $this->bulan)
                ->where('tahun', $this->tahun)
                ->exists();

            if (!$exists) {
                Payroll::create([
                    'uuid' => (string) Str::uuid(),
                    'church_officer_id' => $off->id,
                    'fiscal_year_id' => $fiscalYear->id,
                    'bulan' => $this->bulan,
                    'tahun' => $this->tahun,
                    'gaji_pokok' => $off->gaji_pokok,
                    'tunjangan_perumahan' => $off->tunjangan_perumahan,
                    'tunjangan_lain' => $off->tunjangan_lain,
                    'iuran_pensiun' => $off->iuran_pensiun,
                    'netto' => $off->net_salary,
                    'status' => 'draft',
                    'status_bayar' => 'belum'
                ]);
                $count++;
            }
        }

        $this->dispatch('notify', message: "$count draf gaji berhasil dibuat.", type: 'success');
    }

    public function openPaymentModal($id)
    {
        $this->selectedPayrollId = $id;
        $pay = Payroll::findOrFail($id);
        $this->nominal_bayar = number_format($pay->sisa_gaji, 0, '', '');
        $this->isPaymentModalOpen = true;
    }

    public function payFull($id)
    {
        $this->selectedPayrollId = $id;
        $pay = Payroll::findOrFail($id);
        $this->nominal_bayar = $pay->sisa_gaji;
        $this->savePayment();
    }

    public function savePayment()
    {
        $this->validate([
            'nominal_bayar' => 'required|numeric|min:1000',
            'ref_account_id' => 'required'
        ]);

        if ($this->nominal_bayar <= 0) return;

        // --- VALIDASI SEBELUM TRANSAKSI (FIX ERROR) ---
        $pay = Payroll::with('officer')->findOrFail($this->selectedPayrollId);
        
        if (!$pay->officer) {
            $this->dispatch('notify', message: 'Gagal: Data pengerja tidak ditemukan (mungkin sudah dihapus).', type: 'error');
            $this->isPaymentModalOpen = false;
            return;
        }

        if (!$pay->officer->ref_budget_post_id) {
            $this->dispatch('notify', message: 'Gagal: Pos Anggaran belum di-set di profil pengerja ini.', type: 'error');
            $this->isPaymentModalOpen = false;
            return;
        }

        DB::transaction(function () use ($pay) {
            // 1. Buat Jurnal Kas Keluar
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $pay->fiscal_year_id,
                'tanggal' => now(),
                'jenis' => 'keluar',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $pay->officer->ref_budget_post_id,
                'nominal' => $this->nominal_bayar,
                'keterangan' => "Gaji {$pay->nama_bulan} {$pay->tahun}: " . ($pay->officer->member->nama ?? 'N/A'),
                'user_id' => Auth::id(),
            ]);

            // 2. Catat Riwayat Cicilan
            PayrollPayment::create([
                'uuid' => (string) Str::uuid(),
                'payroll_id' => $pay->id,
                'transaction_id' => $trx->id,
                'nominal' => $this->nominal_bayar,
                'tanggal_bayar' => now(),
                'keterangan' => $this->catatan_bayar,
            ]);

            // 3. Update Status
            $pay->refresh();
            $statusBayar = $pay->is_lunas ? 'lunas' : 'cicil';
            $pay->update([
                'status_bayar' => $statusBayar,
                'status' => $pay->is_lunas ? 'paid' : 'draft',
                'tanggal_bayar' => now()
            ]);
        });

        $this->dispatch('notify', message: 'Pembayaran gaji berhasil dicatat.', type: 'success');
        $this->isPaymentModalOpen = false;
        $this->reset(['nominal_bayar', 'catatan_bayar']);
    }

    public function render()
    {
        return view('livewire.finance.payroll-manager', [
            'payrolls' => Payroll::with(['officer.member', 'officer.position', 'payments'])
                ->where('bulan', $this->bulan)
                ->where('tahun', $this->tahun)
                ->get(),
            'accounts' => RefAccount::where('is_active', true)->get()
        ]);
    }
}