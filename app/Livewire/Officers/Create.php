<?php

namespace App\Livewire\Officers;

use App\Models\ChurchOfficer;
use App\Models\Member;
use App\Models\RefPosition;
use App\Models\RefBudgetPost;
use App\Models\OfficerSalaryComponent;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    // Identitas
    public $member_id, $ref_position_id, $nip_gereja;
    public $status_kepegawaian = 'organik', $lokasi_tugas = 'pusat';
    public $nomor_sk, $tanggal_mulai, $tanggal_selesai;
    public $is_active = true;

    // KOMPONEN GAJI DINAMIS
    public $components = [];

    // Search Helper
    public $searchMember = '', $selectedMemberName = '';

    protected $rules = [
        'member_id' => 'required',
        'ref_position_id' => 'required',
        'tanggal_mulai' => 'required|date',
        // Validasi array komponen
        'components.*.nama_komponen' => 'required|string',
        'components.*.nominal' => 'required',
        'components.*.jenis' => 'required|in:penerimaan,potongan',
        'components.*.ref_budget_post_id' => 'nullable',
    ];

    public function mount()
    {
        // Default Template Komponen (Agar user tidak input dari nol)
        $this->components = [
            [
                'nama_komponen' => 'Gaji Pokok / Pemeliharaan',
                'jenis' => 'penerimaan',
                'nominal' => 0,
                'ref_budget_post_id' => null
            ],
            [
                'nama_komponen' => 'Tunjangan Perumahan',
                'jenis' => 'penerimaan',
                'nominal' => 0,
                'ref_budget_post_id' => null
            ],
            [
                'nama_komponen' => 'Iuran Pensiun',
                'jenis' => 'potongan', // Sesuai kesepakatan: Potongan mengurangi THP
                'nominal' => 0,
                'ref_budget_post_id' => null
            ]
        ];
    }

    public function addComponent()
    {
        $this->components[] = [
            'nama_komponen' => '',
            'jenis' => 'penerimaan',
            'nominal' => 0,
            'ref_budget_post_id' => null
        ];
    }

    public function removeComponent($index)
    {
        unset($this->components[$index]);
        $this->components = array_values($this->components); // Reindex array
    }

    public function selectMember($id, $name)
    {
        $this->member_id = $id;
        $this->selectedMemberName = $name;
        $this->searchMember = '';
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Simpan Personil
            $officer = ChurchOfficer::create([
                'uuid' => (string) Str::uuid(),
                'member_id' => $this->member_id,
                'ref_position_id' => $this->ref_position_id,
                'nip_gereja' => $this->nip_gereja,
                'status_kepegawaian' => $this->status_kepegawaian,
                'lokasi_tugas' => $this->lokasi_tugas,
                'nomor_sk' => $this->nomor_sk,
                'tanggal_mulai' => $this->tanggal_mulai,
                'tanggal_selesai' => $this->tanggal_selesai ?: null,
                'is_active' => $this->is_active,
            ]);

            // 2. Simpan Komponen Gaji
            foreach ($this->components as $comp) {
                // Bersihkan format rupiah
                $nominal = (float) str_replace(['.', ','], '', $comp['nominal']);

                if ($nominal > 0) { // Hanya simpan yang ada nilainya   
                    OfficerSalaryComponent::create([
                        'uuid' => (string) Str::uuid(),
                        'church_officer_id' => $officer->id,
                        'nama_komponen' => $comp['nama_komponen'],
                        'jenis' => $comp['jenis'],
                        'nominal' => $nominal,
                        'ref_budget_post_id' => $comp['ref_budget_post_id'] ?: null,
                        'is_fixed' => true,
                        'is_active' => true,
                        'tanggal_mulai' => now(), // Efektif sekarang
                    ]);
                }
            }
        });

        $this->dispatch('notify', message: 'Personil dan struktur gaji berhasil disimpan!', type: 'success');
        return redirect()->route('officers.index');
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
        return view('livewire.officers.create', [
            'positions' => RefPosition::orderBy('urutan')->get(),
            // Ambil semua pos pengeluaran (termasuk sub-pos) untuk opsi
            'budgetPosts' => RefBudgetPost::where('jenis', 'pengeluaran')
                ->whereNotNull('parent_id')
                ->orderBy('kode')
                ->get(),
            'foundMembers' => strlen($this->searchMember) > 2 ? Member::where('nama', 'like', '%' . $this->searchMember . '%')->limit(5)->get() : []
        ]);
    }
}
