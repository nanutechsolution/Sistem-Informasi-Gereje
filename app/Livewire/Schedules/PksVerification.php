<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\FiscalYear;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PksVerification extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $selectedId;
    
    // Form Verifikasi
    public $nominal_verifikasi, $ref_account_id, $ref_budget_post_id;
    public $catatan;

    protected $messages = [
        'ref_account_id.required' => 'Pilih akun kas tempat uang disimpan.',
        'ref_budget_post_id.required' => 'Tentukan Pos Anggaran untuk laporan warta.',
    ];

    public function openModal($id)
    {
        $schedule = ActivitySchedule::findOrFail($id);
        $this->selectedId = $id;
        $this->nominal_verifikasi = number_format($schedule->nominal_persembahan, 0, ',', '.');
        
        // Cari Akun Kas Umum secara otomatis
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first() ?: RefAccount::first();
        $this->ref_account_id = $acc?->id;

        // Cari Pos Persembahan PKS
        $pos = RefBudgetPost::where('nama', 'like', '%PKS%')->first();
        $this->ref_budget_post_id = $pos?->id;

        $this->isModalOpen = true;
    }

    public function verify()
    {
        $this->validate([
            'ref_account_id' => 'required',
            'ref_budget_post_id' => 'required',
            'nominal_verifikasi' => 'required'
        ]);

        DB::transaction(function () {
            $schedule = ActivitySchedule::findOrFail($this->selectedId);
            $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_verifikasi);
            $activeYear = FiscalYear::active();

            // 1. Masukkan ke Jurnal Transaksi
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $activeYear->id,
                'tanggal' => date('Y-m-d'),
                'jenis' => 'masuk',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $this->ref_budget_post_id,
                'nominal' => $cleanNominal,
                'keterangan' => "Setoran PKS: " . ($schedule->family->kepala_keluarga ?? $schedule->tema),
                'user_id' => Auth::id(),
            ]);

            // 2. Update Status Jadwal
            $schedule->update([
                'status_setoran' => 'disetor',
                'nominal_persembahan' => $cleanNominal, // Update jika ada selisih saat serah terima
                'transaction_id' => $trx->id,
                'verified_at' => now(),
            ]);
        });

        $this->dispatch('notify', message: 'Setoran PKS berhasil diverifikasi dan masuk kas!', type: 'success');
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.schedules.pks-verification', [
            'pendings' => ActivitySchedule::with(['family', 'wilayah', 'type'])
                ->where('ref_activity_type_id', 2) // Tipe PKS
                ->where('status_setoran', 'pending')
                ->where('nominal_persembahan', '>', 0)
                ->orderBy('tanggal', 'desc')
                ->paginate(10),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->get()
        ]);
    }
}