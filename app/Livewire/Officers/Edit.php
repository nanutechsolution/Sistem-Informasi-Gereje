<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\OfficerHistory;
use App\Models\RefPosition;
use App\Models\RefBudgetPost;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    public ChurchOfficer $officer;
    
    // Properti Form
    public $ref_position_id, $ref_budget_post_id, $nip_gereja;
    public $status_kepegawaian, $lokasi_tugas, $is_active;
    public $nomor_sk, $tanggal_mulai, $tanggal_selesai;
    public $gaji_pokok, $tunjangan_perumahan, $tunjangan_lain, $iuran_pensiun;

    public function mount(ChurchOfficer $officer)
    {
        $this->officer = $officer;
        $this->ref_position_id = $officer->ref_position_id;
        $this->ref_budget_post_id = $officer->ref_budget_post_id;
        $this->nip_gereja = $officer->nip_gereja;
        $this->status_kepegawaian = $officer->status_kepegawaian;
        $this->lokasi_tugas = $officer->lokasi_tugas;
        $this->is_active = $officer->is_active;
        $this->nomor_sk = $officer->nomor_sk;
        $this->tanggal_mulai = $officer->tanggal_mulai?->format('Y-m-d');
        $this->tanggal_selesai = $officer->tanggal_selesai?->format('Y-m-d');
        $this->gaji_pokok = $officer->gaji_pokok;
        $this->tunjangan_perumahan = $officer->tunjangan_perumahan;
        $this->tunjangan_lain = $officer->tunjangan_lain ?? 0;
        $this->iuran_pensiun = $officer->iuran_pensiun;
    }

    protected function rules()
    {
        return [
            'ref_position_id' => 'required',
            'ref_budget_post_id' => 'required',
            'status_kepegawaian' => 'required',
            'lokasi_tugas' => 'required',
            'gaji_pokok' => 'required|numeric',
            'tunjangan_perumahan' => 'numeric',
            'iuran_pensiun' => 'numeric',
        ];
    }

    public function update()
    {
        $this->validate();

        // Deteksi Perubahan Gaji untuk Riwayat
        $gajiBerubah = ($this->officer->gaji_pokok != $this->gaji_pokok);

        $this->officer->update([
            'ref_position_id' => $this->ref_position_id,
            'ref_budget_post_id' => $this->ref_budget_post_id,
            'nip_gereja' => $this->nip_gereja,
            'status_kepegawaian' => $this->status_kepegawaian,
            'lokasi_tugas' => $this->lokasi_tugas, // Fixed typo dari lokation_tugas
            'is_active' => $this->is_active,
            'nomor_sk' => $this->nomor_sk,
            'tanggal_mulai' => $this->tanggal_mulai,
            'tanggal_selesai' => $this->tanggal_selesai,
            'gaji_pokok' => (float)$this->gaji_pokok,
            'tunjangan_perumahan' => (float)$this->tunjangan_perumahan,
            'tunjangan_lain' => (float)$this->tunjangan_lain,
            'iuran_pensiun' => (float)$this->iuran_pensiun,
        ]);

        if ($gajiBerubah) {
            OfficerHistory::create([
                'church_officer_id' => $this->officer->id,
                'jenis_perubahan' => 'Penyesuaian Gaji',
                'tanggal_perubahan' => now(),
                'sk_pendukung' => $this->nomor_sk ?? 'Penyesuaian rutin sistem',
                'user_id' => Auth::id(),
            ]);
        }

        $this->dispatch('notify', message: 'Data personil berhasil diperbarui.', type: 'success');
        return redirect()->route('officers.show', $this->officer);
    }

    public function render()
    {
        return view('livewire.officers.edit', [
            'positions' => RefPosition::orderBy('urutan')->get(),
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')
                ->whereNotNull('parent_id')
                ->orderBy('kode')
                ->get()
        ]);
    }
}