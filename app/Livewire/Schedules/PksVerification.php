<?php

namespace App\Livewire\Schedules;

use App\Models\ActivitySchedule;
use App\Models\Transaction;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use App\Models\RefActivityType;
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

    // Data Penunjang untuk Modal
    public $modalInfo = [];

    protected $messages = [
        'ref_account_id.required' => 'Pilih akun kas tempat uang disimpan.',
        'ref_budget_post_id.required' => 'Pos Anggaran wajib dipilih.',
        'nominal_verifikasi.required' => 'Nominal fisik uang wajib diisi.',
    ];

    public function openModal($id)
    {
        $schedule = ActivitySchedule::with('family')->findOrFail($id);
        $this->selectedId = $id;

        // Pre-fill data
        $this->nominal_verifikasi = number_format($schedule->nominal_persembahan, 0, ',', '.');
        $this->modalInfo = [
            'keluarga' => $schedule->family->kepala_keluarga ?? 'Tanpa Nama',
            'tanggal' => $schedule->tanggal->format('d/m/Y'),
            'tema' => $schedule->tema
        ];

        // Auto-select Kas Umum (Prioritas: Nama 'Umum' -> Tipe 'Kas Tunai')
        $acc = RefAccount::where('nama', 'like', '%Umum%')->first()
            ?: RefAccount::where('jenis', 'kas_tunai')->first();
        $this->ref_account_id = $acc?->id;

        // Auto-select Pos PKS (Cari kode 1.2 atau nama PKS)
        $pos = RefBudgetPost::where('kode', '1.2')->first()
            ?: RefBudgetPost::where('nama', 'like', '%PKS%')->first();
        $this->ref_budget_post_id = $pos?->id;

        $this->isModalOpen = true;
    }

    public function verify()
    {
        $this->validate([
            'ref_account_id' => 'required|exists:ref_accounts,id',
            'ref_budget_post_id' => 'required|exists:ref_budget_posts,id',
            'nominal_verifikasi' => 'required'
        ]);

        DB::transaction(function () {
            $schedule = ActivitySchedule::findOrFail($this->selectedId);
            $cleanNominal = (float) str_replace(['.', ','], '', $this->nominal_verifikasi);
            $activeYear = FiscalYear::active();

            if (!$activeYear) {
                throw new \Exception("Tahun anggaran aktif tidak ditemukan.");
            }

            // 1. Buat Jurnal Masuk
            $trx = Transaction::create([
                'uuid' => (string) Str::uuid(),
                'fiscal_year_id' => $activeYear->id,
                'tanggal' => now(), // Tanggal setoran (hari ini)
                'jenis' => 'masuk',
                'ref_account_id' => $this->ref_account_id,
                'ref_budget_post_id' => $this->ref_budget_post_id,
                'nominal' => $cleanNominal,
                'keterangan' => "Persembahan PKS: " . ($schedule->family->kepala_keluarga ?? $schedule->tema),
                'user_id' => Auth::id(),
            ]);

            // 2. Update Status Jadwal
            $schedule->update([
                'status_setoran' => 'disetor',
                'nominal_persembahan' => $cleanNominal, // Update angka real jika berbeda
                'transaction_id' => $trx->id,
                'verified_at' => now(),
                'status' => 'terlaksana'
            ]);
        });

        $this->dispatch('notify', message: 'Setoran PKS berhasil diverifikasi ke Kas!', type: 'success');
        $this->isModalOpen = false;
    }

    public function render()
    {
        // Cari ID Tipe Kegiatan "PKS" secara dinamis
        $pksTypeId = RefActivityType::where('nama', 'like', '%PKS%')->value('id');

        return view('livewire.schedules.pks-verification', [
            'pendings' => ActivitySchedule::with(['family.refWilayah', 'type', 'servants.member'])
                ->where('ref_activity_type_id', $pksTypeId) // Filter PKS Only
                ->where('status_setoran', 'pending')
                ->where('nominal_persembahan', '>', 0) // Hanya yg ada duitnya
                ->orderBy('tanggal', 'asc') // Urutkan dari yang terlama belum setor
                ->paginate(9),
            'accounts' => RefAccount::where('is_active', true)->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pemasukan')->orderBy('kode')->get()
        ]);
    }
}
