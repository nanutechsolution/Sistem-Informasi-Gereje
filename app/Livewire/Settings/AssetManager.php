<?php

namespace App\Livewire\Settings;

use App\Models\Asset;
use App\Models\Member;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AssetManager extends Component
{
    use WithPagination;

    public $search = '', $filterKategori = '';
    public $isModalOpen = false;
    public $editId = null;

    // Properti Form
    public $nama_aset, $kategori, $jumlah = 1, $satuan = 'Unit', $kondisi = 'baik';
    public $lokasi_fisik, $asal_perolehan = 'pembelian', $tanggal_perolehan, $nilai_estimasi = 0, $catatan;
    
    // Search Donatur
    public $searchMember = '', $selectedMemberId, $selectedMemberName;
    public $foundMembers = [];

    public function mount()
    {
        $this->tanggal_perolehan = date('Y-m-d');
    }

    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get() 
            : [];
    }

    public function selectMember($id, $name)
    {
        $this->selectedMemberId = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = ''; $this->foundMembers = [];
    }

    public function create()
    {
        $this->reset(['editId', 'nama_aset', 'kategori', 'jumlah', 'satuan', 'kondisi', 'selectedMemberId', 'selectedMemberName', 'catatan']);
        $this->tanggal_perolehan = date('Y-m-d');
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'nama_aset' => 'required|min:3',
            'kategori' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_perolehan' => 'required|date',
        ]);

        Asset::updateOrCreate(['id' => $this->editId], [
            'uuid' => $this->editId ? Asset::find($this->editId)->uuid : (string) Str::uuid(),
            'nama_aset' => $this->nama_aset,
            'kategori' => $this->kategori,
            'jumlah' => $this->jumlah,
            'satuan' => $this->satuan,
            'kondisi' => $this->kondisi,
            'lokasi_fisik' => $this->lokasi_fisik,
            'asal_perolehan' => $this->asal_perolehan,
            'member_id' => $this->selectedMemberId,
            'tanggal_perolehan' => $this->tanggal_perolehan,
            'nilai_estimasi' => (float) str_replace(['.', ','], '', $this->nilai_estimasi),
            'catatan' => $this->catatan,
        ]);

        $this->dispatch('notify', message: 'Inventaris berhasil diperbarui.', type: 'success');
        $this->isModalOpen = false;
    }

    public function edit($id)
    {
        $asset = Asset::with('donatur')->findOrFail($id);
        $this->editId = $asset->id;
        $this->nama_aset = $asset->nama_aset;
        $this->kategori = $asset->kategori;
        $this->jumlah = $asset->jumlah;
        $this->satuan = $asset->satuan;
        $this->kondisi = $asset->kondisi;
        $this->lokasi_fisik = $asset->lokasi_fisik;
        $this->asal_perolehan = $asset->asal_perolehan;
        $this->selectedMemberId = $asset->member_id;
        $this->selectedMemberName = $asset->donatur->nama ?? null;
        $this->tanggal_perolehan = $asset->tanggal_perolehan->format('Y-m-d');
        $this->nilai_estimasi = number_format($asset->nilai_estimasi, 0, ',', '.');
        $this->catatan = $asset->catatan;
        $this->isModalOpen = true;
    }

    public function render()
    {
        $assets = Asset::with('donatur')
            ->where('nama_aset', 'like', "%{$this->search}%")
            ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
            ->latest()
            ->paginate(10);

        return view('livewire.settings.asset-manager', [
            'assets' => $assets
        ]);
    }
}