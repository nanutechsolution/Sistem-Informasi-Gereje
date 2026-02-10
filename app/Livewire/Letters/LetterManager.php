<?php

namespace App\Livewire\Letters;

use App\Models\Letter;
use App\Models\Member;
use App\Models\ChurchOfficer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LetterManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $letterId = null; // Property untuk Edit

    // Form Properties
    public $member_id, $jenis = 'keterangan';
    public $nomor_surat, $tanggal_cetak;
    public $signed_by_id; // Penandatangan (Ketua/Sekretaris)
    public $keperluan; // Khusus surat keterangan

    // Search Helpers
    public $searchMember = '', $foundMembers = [], $selectedMemberName = '';

    protected $messages = [
        'member_id.required' => 'Pilih jemaat yang bersangkutan.',
        'nomor_surat.unique' => 'Nomor surat ini sudah terpakai.',
        'signed_by_id.required' => 'Pilih pejabat penandatangan.',
    ];

    public function mount()
    {
        $this->tanggal_cetak = date('Y-m-d');
        // Default Penandatangan: Pendeta Aktif
        $pdt = ChurchOfficer::whereHas('position', fn($q) => $q->where('nama', 'like', '%Pendeta%'))->active()->first();
        $this->signed_by_id = $pdt?->id;
    }

    // --- GENERATOR NOMOR SURAT OTOMATIS ---
    public function generateNumber()
    {
        // Hanya generate otomatis jika mode Tambah Baru
        if ($this->letterId) return;

        $date = Carbon::parse($this->tanggal_cetak);
        $year = $date->year;
        
        // 1. Cari surat terakhir di tahun yang sama
        $lastLetter = Letter::whereYear('tanggal_cetak', $year)
            ->orderBy('id', 'desc')
            ->first();

        // 2. Tentukan nomor urut berikutnya
        $urutan = 1;
        if ($lastLetter) {
            // Asumsi format: 001/GKS-RP/II/2026
            $parts = explode('/', $lastLetter->nomor_surat);
            if (is_numeric($parts[0])) {
                $urutan = intval($parts[0]) + 1;
            }
        }

        // 3. Format Romawi Bulan
        $romans = [1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI', 7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'];
        $monthRoman = $romans[$date->month];

        // 4. Susun Nomor
        $noPad = str_pad($urutan, 3, '0', STR_PAD_LEFT);
        $this->nomor_surat = "{$noPad}/GKS-RP/{$monthRoman}/{$year}";
    }

    // Trigger update nomor jika tanggal berubah
    public function updatedTanggalCetak() { $this->generateNumber(); }

    public function updatedSearchMember($value)
    {
        $this->foundMembers = strlen($value) > 2 
            ? Member::where('nama', 'like', "%{$value}%")->limit(5)->get() 
            : [];
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = ''; $this->foundMembers = [];
    }

    public function create()
    {
        $this->reset(['member_id', 'selectedMemberName', 'keperluan', 'letterId']);
        $this->jenis = 'keterangan';
        $this->tanggal_cetak = date('Y-m-d');
        $this->generateNumber(); // Auto generate saat buka modal
        $this->isModalOpen = true;
    }

    // Fungsi Edit (Baru)
    public function edit($id)
    {
        $letter = Letter::with('member')->findOrFail($id);
        $this->letterId = $letter->id;
        $this->member_id = $letter->member_id;
        $this->selectedMemberName = $letter->member->nama;
        $this->jenis = $letter->jenis;
        $this->nomor_surat = $letter->nomor_surat;
        $this->tanggal_cetak = $letter->tanggal_cetak->format('Y-m-d');
        $this->signed_by_id = $letter->signed_by_id;
        $this->keperluan = $letter->keperluan;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'member_id' => 'required',
            'jenis' => 'required',
            // Unique ignore ID saat edit
            'nomor_surat' => 'required|unique:letters,nomor_surat,' . $this->letterId,
            'tanggal_cetak' => 'required|date',
            'signed_by_id' => 'required',
        ]);

        Letter::updateOrCreate(['id' => $this->letterId], [
            'uuid' => $this->letterId ? Letter::find($this->letterId)->uuid : (string) Str::uuid(),
            'member_id' => $this->member_id,
            'jenis' => $this->jenis,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_cetak' => $this->tanggal_cetak,
            'signed_by_id' => $this->signed_by_id,
            'keperluan' => $this->keperluan,
            'data_detail' => [] // Disiapkan untuk detail JSON (Nama Ayah/Ibu, dll)
        ]);

        $msg = $this->letterId ? 'Surat berhasil diperbarui.' : 'Surat berhasil dibuat & diarsipkan.';
        $this->dispatch('notify', message: $msg, type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        Letter::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Arsip surat dihapus.', type: 'success');
    }

    public function render()
    {
        return view('livewire.letters.letter-manager', [
            'letters' => Letter::with(['member', 'signatory.member'])
                ->where('nomor_surat', 'like', "%{$this->search}%")
                ->orWhereHas('member', fn($q) => $q->where('nama', 'like', "%{$this->search}%"))
                ->latest('tanggal_cetak')
                ->paginate(10),
            'officers' => ChurchOfficer::with(['member', 'position'])->active()->get()
        ]);
    }
}