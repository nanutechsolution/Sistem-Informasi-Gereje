<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\OfficerHistory;
use App\Models\RefPosition;
use App\Models\RefBudgetPost;
use App\Models\OfficerSalaryComponent;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public ChurchOfficer $officer;
    public $components = []; // Array Dinamis
    
    // Properti Form Standar
    public $ref_position_id, $nip_gereja, $status_kepegawaian, $lokasi_tugas, $is_active, $nomor_sk, $tanggal_mulai, $tanggal_selesai;

    public function mount(ChurchOfficer $officer)
    {
        $this->officer = $officer;
        $this->ref_position_id = $officer->ref_position_id;
        $this->nip_gereja = $officer->nip_gereja;
        $this->status_kepegawaian = $officer->status_kepegawaian;
        $this->lokasi_tugas = $officer->lokasi_tugas;
        $this->is_active = $officer->is_active;
        $this->nomor_sk = $officer->nomor_sk;
        $this->tanggal_mulai = $officer->tanggal_mulai?->format('Y-m-d');
        $this->tanggal_selesai = $officer->tanggal_selesai?->format('Y-m-d');

        // Load Komponen Gaji yang Aktif
        $this->loadComponents();
    }

    public function loadComponents()
    {
        $savedComponents = $this->officer->salaryComponents()->active()->get();
        
        foreach ($savedComponents as $comp) {
            $this->components[] = [
                'id' => $comp->id, // ID untuk update
                'nama_komponen' => $comp->nama_komponen,
                'jenis' => $comp->jenis,
                'nominal' => number_format($comp->nominal, 0, '', '.'),
                'ref_budget_post_id' => $comp->ref_budget_post_id
            ];
        }

        // Jika kosong (migrasi), beri template default
        if (empty($this->components)) {
            $this->components = [
                ['id' => null, 'nama_komponen' => 'Gaji Pokok', 'jenis' => 'penerimaan', 'nominal' => 0, 'ref_budget_post_id' => null],
                ['id' => null, 'nama_komponen' => 'Iuran Pensiun', 'jenis' => 'potongan', 'nominal' => 0, 'ref_budget_post_id' => null],
            ];
        }
    }

    public function addComponent()
    {
        $this->components[] = [
            'id' => null,
            'nama_komponen' => '',
            'jenis' => 'penerimaan',
            'nominal' => 0,
            'ref_budget_post_id' => null
        ];
    }

    public function removeComponent($index)
    {
        // Jika komponen sudah ada di DB (punya ID), kita tandai hapus (soft delete) atau non-aktifkan
        // Untuk simpel di UI: Hapus dari array view, nanti di save() kita sinkronisasi
        $comp = $this->components[$index];
        if ($comp['id']) {
            OfficerSalaryComponent::find($comp['id'])->update(['is_active' => false, 'tanggal_berakhir' => now()]);
        }
        unset($this->components[$index]);
        $this->components = array_values($this->components);
    }

    public function update()
    {
        $this->validate([
            'ref_position_id' => 'required',
            'components.*.nama_komponen' => 'required',
            'components.*.nominal' => 'required',
        ]);

        DB::transaction(function () {
            // 1. Update Data Utama
            $this->officer->update([
                'ref_position_id' => $this->ref_position_id,
                'status_kepegawaian' => $this->status_kepegawaian,
                'lokasi_tugas' => $this->lokasi_tugas,
                'is_active' => $this->is_active,
                'nomor_sk' => $this->nomor_sk,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai,
            ]);

            // 2. Update/Create Komponen
            $riwayatPerubahan = [];

            foreach ($this->components as $comp) {
                $cleanNominal = (float) str_replace(['.', ','], '', $comp['nominal']);
                
                if ($comp['id']) {
                    // Update existing
                    $model = OfficerSalaryComponent::find($comp['id']);
                    if ($model->nominal != $cleanNominal) {
                        $riwayatPerubahan[] = "{$comp['nama_komponen']}: " . number_format($model->nominal) . " -> " . number_format($cleanNominal);
                    }
                    $model->update([
                        'nama_komponen' => $comp['nama_komponen'],
                        'jenis' => $comp['jenis'],
                        'nominal' => $cleanNominal,
                        'ref_budget_post_id' => $comp['ref_budget_post_id'] ?: null,
                    ]);
                } else {
                    // Create new
                    if ($cleanNominal > 0) {
                        OfficerSalaryComponent::create([
                            'uuid' => (string) Str::uuid(),
                            'church_officer_id' => $this->officer->id,
                            'nama_komponen' => $comp['nama_komponen'],
                            'jenis' => $comp['jenis'],
                            'nominal' => $cleanNominal,
                            'ref_budget_post_id' => $comp['ref_budget_post_id'] ?: null,
                            'is_fixed' => true,
                            'is_active' => true,
                            'tanggal_mulai' => now(),
                        ]);
                        $riwayatPerubahan[] = "Tambah Komponen: {$comp['nama_komponen']}";
                    }
                }
            }

            // 3. Catat History jika ada perubahan gaji
            if (!empty($riwayatPerubahan)) {
                OfficerHistory::create([
                    'church_officer_id' => $this->officer->id,
                    'jenis_perubahan' => 'Penyesuaian Struktur Gaji',
                    'tanggal_perubahan' => now(),
                    'detail_perubahan' => json_encode($riwayatPerubahan), // Simpan array string
                    'user_id' => Auth::id(),
                ]);
            }
        });

        $this->dispatch('notify', message: 'Data personil diperbarui.', type: 'success');
        return redirect()->route('officers.show', $this->officer);
    }

    // Helper Hitung THP Realtime
    public function getEstimatedThpProperty()
    {
        $total = 0;
        foreach ($this->components as $c) {
            $val = (float) str_replace(['.', ','], '', $c['nominal']);
            if ($c['jenis'] == 'penerimaan') $total += $val;
            else $total -= $val;
        }
        return $total;
    }

    public function render()
    {
        return view('livewire.officers.edit', [
            'positions' => RefPosition::orderBy('urutan')->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')->whereNotNull('parent_id')->orderBy('kode')->get()
        ]);
    }
}