<?php

namespace App\Livewire\Members;

use App\Models\Family;
use App\Models\Member;
use App\Models\ChurchPeople;
use App\Models\RefHubunganKeluarga;
use App\Models\RefPekerjaan;
use Livewire\Component;
use Illuminate\Support\Str;

class Create extends Component
{
    public Family $family;

    // Search Logic
    public $search = '';
    public $searchResults = [];
    public $selectedPerson = null; // Menyimpan obyek ChurchPeople yang dipilih

    // Form Member Data
    public $hubungan_keluarga_id;
    public $pekerjaan_id;

    public function mount(Family $family)
    {
        $this->family = $family;
    }

    // Real-time search saat mengetik
    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = [];
            return;
        }

        // Cari orang yang BELUM menjadi anggota keluarga ini (opsional: atau belum masuk KK manapun)
        $this->searchResults = ChurchPeople::where('full_name', 'like', '%' . $value . '%')
            ->orWhere('nik', 'like', '%' . $value . '%')
            ->limit(5)
            ->get();
    }

    public function selectPerson($id)
    {
        $person = ChurchPeople::find($id);
        
        // Validasi: Cek apakah orang ini sudah ada di tabel members (sudah punya KK)
        // Jika kebijakan gereja: 1 Orang hanya boleh 1 KK aktif
        $existingMember = Member::where('church_people_id', $id)
            ->where('status_keanggotaan', 'aktif') // Cek status aktif
            ->first();

        if ($existingMember) {
            $this->dispatch('notify', message: 'Orang ini sudah terdaftar di KK lain (No. KK: ' . $existingMember->family->nomor_kk . ')', type: 'error');
            return;
        }

        $this->selectedPerson = $person;
        $this->search = '';
        $this->searchResults = [];
    }

    public function cancelSelection()
    {
        $this->selectedPerson = null;
        $this->hubungan_keluarga_id = null;
        $this->pekerjaan_id = null;
    }

    public function save()
    {
        $this->validate([
            'selectedPerson' => 'required', // Pastikan orang sudah dipilih
            'hubungan_keluarga_id' => 'required|exists:ref_hubungan_keluargas,id',
            'pekerjaan_id' => 'required|exists:ref_pekerjaans,id',
        ], [
            'selectedPerson.required' => 'Anda belum memilih data orang dari pencarian.',
            'hubungan_keluarga_id.required' => 'Status hubungan keluarga wajib dipilih.',
            'pekerjaan_id.required' => 'Pekerjaan wajib dipilih.',
        ]);

        // Cek Double Entry di Keluarga yang sama (Validation Layer 2)
        $exists = $this->family->members()->where('church_people_id', $this->selectedPerson->id)->exists();
        if ($exists) {
            $this->dispatch('notify', message: 'Orang ini sudah ada di keluarga ini.', type: 'error');
            return;
        }

        // Simpan Member
        $this->family->members()->create([
            'uuid' => Str::uuid(),
            'church_people_id' => $this->selectedPerson->id,
            'hubungan_keluarga_id' => $this->hubungan_keluarga_id,
            'pekerjaan_id' => $this->pekerjaan_id,
            'status_keanggotaan' => 'aktif',
            'is_active' => 1,
        ]);

        $this->dispatch('notify', message: 'Anggota berhasil ditambahkan ke KK!', type: 'success');
        return redirect()->route('families.edit', $this->family->uuid);
    }

    public function render()
    {
        return view('livewire.members.create', [
            'refHubungans' => RefHubunganKeluarga::orderBy('urutan')->get(),
            'refPekerjaans' => RefPekerjaan::orderBy('nama')->get(),
        ]);
    }
}