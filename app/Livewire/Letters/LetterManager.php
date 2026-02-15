<?php

namespace App\Livewire\Letters;

use App\Models\Letter;
use App\Models\Member;
use App\Models\ChurchOfficer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LetterManager extends Component
{
    use WithPagination;

    public $search = '';
    public $isModalOpen = false;
    public $letterUuid = null; // Gunakan UUID untuk edit

    // Form Properties
    public $member_id, $jenis = 'keterangan';
    public $nomor_surat, $tanggal_cetak;
    public $signed_by_id; // Penandatangan (ChurchOfficer ID)
    public $keperluan;

    // Search Helpers
    public $searchMember = '', $foundMembers = [], $selectedMemberName = '';

    protected function rules()
    {
        return [
            'member_id' => 'required|exists:members,id',
            'jenis' => 'required|in:keterangan,pindah,baptis,sidi,nikah',
            // Unique ignore UUID saat edit
            'nomor_surat' => 'required|unique:letters,nomor_surat,' . ($this->letterUuid ? Letter::where('uuid', $this->letterUuid)->first()->id : 'NULL'),
            'tanggal_cetak' => 'required|date',
            'signed_by_id' => 'required|exists:church_officers,id',
            'keperluan' => 'nullable|string',
        ];
    }

    protected $messages = [
        'member_id.required' => 'Pilih jemaat yang bersangkutan.',
        'nomor_surat.unique' => 'Nomor surat ini sudah terpakai.',
        'signed_by_id.required' => 'Pilih pejabat penandatangan.',
    ];

    public function mount()
    {
        $this->tanggal_cetak = date('Y-m-d');
        // Default Penandatangan: Cari Ketua Majelis atau Pendeta Aktif
        $pdt = ChurchOfficer::whereHas('position', fn($q) => $q->where('nama', 'like', '%Pendeta%')
            ->orWhere('nama', 'like', '%Ketua%'))
            ->where('is_active', true) // Asumsi ada scope atau kolom is_active
            ->first();
        $this->signed_by_id = $pdt?->id;
    }

    // --- GENERATOR NOMOR SURAT OTOMATIS ---
    public function generateNumber()
    {
        if ($this->letterUuid) return; // Jangan generate kalau sedang edit

        $date = Carbon::parse($this->tanggal_cetak);
        $year = $date->year;

        // 1. Cari surat terakhir di tahun yang sama
        $lastLetter = Letter::whereYear('tanggal_cetak', $year)
            ->orderBy('id', 'desc')
            ->first();

        // 2. Tentukan nomor urut
        $urutan = 1;
        if ($lastLetter) {
            $parts = explode('/', $lastLetter->nomor_surat);
            if (is_numeric($parts[0])) {
                $urutan = intval($parts[0]) + 1;
            }
        }

        // 3. Format Romawi Bulan
        $romans = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'];
        $monthRoman = $romans[$date->month];

        // 4. Kode Jenis Surat
        $kodeJenis = match($this->jenis) {
            'pindah' => 'PND',
            'baptis' => 'BPT',
            'sidi' => 'SDI',
            'nikah' => 'NKH',
            default => 'KET' // Keterangan
        };

        // 5. Susun Nomor: 001/GKS-RP-KET/II/2026
        $noPad = str_pad($urutan, 3, '0', STR_PAD_LEFT);
        $this->nomor_surat = "{$noPad}/GKS-RP-{$kodeJenis}/{$monthRoman}/{$year}";
    }

    public function updatedTanggalCetak() { $this->generateNumber(); }
    public function updatedJenis() { $this->generateNumber(); }

    // --- SEARCH MEMBER (Fix Relation) ---
    public function updatedSearchMember($value)
    {
        if (strlen($value) < 3) {
            $this->foundMembers = [];
            return;
        }

        $this->foundMembers = Member::whereHas('churchPeople', function(Builder $q) use ($value) {
            $q->where('full_name', 'like', "%{$value}%")
              ->orWhere('nik', 'like', "%{$value}%");
        })->with('churchPeople')->limit(5)->get();
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = '';
        $this->foundMembers = [];
    }

    // --- CRUD ACTIONS ---
    public function create()
    {
        $this->reset(['member_id', 'selectedMemberName', 'keperluan', 'letterUuid']);
        $this->jenis = 'keterangan';
        $this->tanggal_cetak = date('Y-m-d');
        $this->generateNumber();
        $this->isModalOpen = true;
    }

    public function edit($uuid)
    {
        $letter = Letter::where('uuid', $uuid)->with('member.churchPeople')->firstOrFail();
        
        $this->letterUuid = $letter->uuid;
        $this->member_id = $letter->member_id;
        $this->selectedMemberName = $letter->member->churchPeople->full_name ?? 'Data Orang Hilang';
        $this->jenis = $letter->jenis;
        $this->nomor_surat = $letter->nomor_surat;
        $this->tanggal_cetak = $letter->tanggal_cetak->format('Y-m-d');
        $this->signed_by_id = $letter->signed_by_id;
        $this->keperluan = $letter->keperluan;
        
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'member_id' => $this->member_id,
            'jenis' => $this->jenis,
            'nomor_surat' => $this->nomor_surat,
            'tanggal_cetak' => $this->tanggal_cetak,
            'signed_by_id' => $this->signed_by_id,
            'keperluan' => $this->keperluan,
        ];

        if ($this->letterUuid) {
            Letter::where('uuid', $this->letterUuid)->update($data);
            $msg = 'Surat berhasil diperbarui.';
        } else {
            $data['uuid'] = (string) Str::uuid();
            Letter::create($data);
            $msg = 'Surat berhasil dibuat & diarsipkan.';
        }

        $this->dispatch('notify', message: $msg, type: 'success');
        $this->isModalOpen = false;
    }

    public function delete($uuid)
    {
        $letter = Letter::where('uuid', $uuid)->first();
        if ($letter) {
            $letter->delete();
            $this->dispatch('notify', message: 'Arsip surat dihapus.', type: 'success');
        }
    }

    public function render()
    {
        $letters = Letter::with(['member.churchPeople', 'signatory.member.churchPeople', 'signatory.position'])
            ->where(function($q) {
                $q->where('nomor_surat', 'like', "%{$this->search}%")
                  ->orWhereHas('member.churchPeople', fn($m) => $m->where('full_name', 'like', "%{$this->search}%"));
            })
            ->latest('tanggal_cetak')
            ->paginate(10);

        // Ambil pejabat aktif untuk dropdown penandatangan
        $officers = ChurchOfficer::with(['member.churchPeople', 'position'])
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            })
            ->get();

        return view('livewire.letters.letter-manager', [
            'letters' => $letters,
            'officers' => $officers
        ]);
    }
}