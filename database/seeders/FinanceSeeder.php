<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\FiscalYear;
use App\Models\RefAccount;
use App\Models\RefBudgetPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FinanceSeeder extends Seeder
{
    public function run(): void
    {
        // 1. SETUP TAHUN ANGGARAN 2026
        $fiscalYear = FiscalYear::firstOrCreate(
            ['tahun' => '2026'],
            [
                'uuid' => Str::uuid(),
                'is_active' => true,
                'keterangan' => 'RAPB Jemaat Reda 2026'
            ]
        );

        // 2. SETUP AKUN KAS (DOMPET)
        // Kita buatkan akun standar dulu
        $accounts = [
            ['nama' => 'Kas Jemaat', 'jenis' => 'kas_tunai'],
            ['nama' => 'Bank (Rekening Gereja)', 'jenis' => 'bank'],
        ];

        foreach ($accounts as $acc) {
            RefAccount::firstOrCreate(
                ['nama' => $acc['nama']],
                array_merge($acc, ['uuid' => Str::uuid()])
            );
        }

        // 3. INPUT POS ANGGARAN & TARGET RAPB (Sesuai Foto)

        // --- A. PENDAPATAN (Kode 1) ---
        $pendapatan = [
            '1' => ['Mingguan', 35000000],
            '2' => ['PKS', 25000000],
            '3' => ['Syukuran', 34000000],
            '4' => ['PMK', 8000000],
            '5' => ['HRK', 5000000],
            '6' => ['HH', 5000000],
            '7' => ['Perpuluhan', 14000000],
            '8' => ['Istimewa', 4000000],
            '9' => ['Urusan Adat', 1700000],
            '10' => ['Akta Gerejawi', 5000000],
            '11' => ['Lelang', 10000000],
            '12' => ['Tanas', 1000000],
            '13' => ['Ternas', 3000000],
            '14' => ['TTA', 3000000],
            '15' => ['Duka', 1500000],
            '16' => ['SSG', 3000000],
            '17' => ['BK/Nazar', 500000],
            '18' => ['SMKA', 1000000],
            '19' => ['Sadar 2000', 3000000],
            '20' => ['Tak Terduga', 282000],
        ];

        // Buat Parent Pendapatan
        $parentPendapatan = RefBudgetPost::firstOrCreate(
            ['kode' => '1'],
            ['uuid' => Str::uuid(), 'nama' => 'PENDAPATAN', 'jenis' => 'pemasukan']
        );

        foreach ($pendapatan as $no => $data) {
            $this->createPostAndBudget(
                $fiscalYear,
                $parentPendapatan,
                '1.' . $no, // Kode: 1.1, 1.2, dst
                $data[0], // Nama
                'pemasukan',
                $data[1] // Target Rupiah
            );
        }

        // --- B. BELANJA (Kode 2) ---
        $parentBelanja = RefBudgetPost::firstOrCreate(
            ['kode' => '2'],
            ['uuid' => Str::uuid(), 'nama' => 'BELANJA', 'jenis' => 'pengeluaran']
        );

        // I. Pemeliharaan Pengerja (2.1)
        $cat1 = $this->createCategory('2.1', 'Pemeliharaan Pengerja', $parentBelanja);
        $this->createPostAndBudget($fiscalYear, $cat1, '2.1.1', 'Pdt. Alponia Malo, S.Th', 'pengeluaran', 58326000);
        $this->createPostAndBudget($fiscalYear, $cat1, '2.1.2', 'Vic.', 'pengeluaran', 18000000);
        $this->createPostAndBudget($fiscalYear, $cat1, '2.1.3', 'KA. Anderias Bili Koba', 'pengeluaran', 14400000);

        // II. Iuran Dana Pensiun (2.2)
        $cat2 = $this->createCategory('2.2', 'Iuran Dana Pensiun', $parentBelanja);
        $this->createPostAndBudget($fiscalYear, $cat2, '2.2.1', 'Pdt. Alponia Malo, S.Th', 'pengeluaran', 5856000);
        $this->createPostAndBudget($fiscalYear, $cat2, '2.2.2', 'Vic.', 'pengeluaran', 1980000);

        // III. Biaya Perumahan (2.3)
        $cat3 = $this->createCategory('2.3', 'Biaya Perumahan', $parentBelanja);
        $this->createPostAndBudget($fiscalYear, $cat3, '2.3.1', 'Pdt. Alponia Malo, S.Th', 'pengeluaran', 3000000);

        // IV. Insentif Karyawan (2.4)
        $cat4 = $this->createCategory('2.4', 'Insentif Karyawan', $parentBelanja);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.1', 'Sekretaris Jemaat', 'pengeluaran', 6600000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.2', 'Bendahara Jemaat', 'pengeluaran', 4200000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.3', 'Bendahara Cabang', 'pengeluaran', 2400000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.4', 'Koster Pusat', 'pengeluaran', 3000000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.5', 'Koster Cabang', 'pengeluaran', 2400000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.6', 'Guru Sekolah Minggu Pusat', 'pengeluaran', 2700000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.7', 'Guru Sekolah Minggu Cabang', 'pengeluaran', 1200000);
        $this->createPostAndBudget($fiscalYear, $cat4, '2.4.8', 'Pemusik', 'pengeluaran', 1800000);

        // V. Belanja Lain-Lain (2.5)
        $cat5 = $this->createCategory('2.5', 'Belanja Lain-Lain', $parentBelanja);
        $itemsLain = [
            '1' => ['Pos Umum Klasis (PUK)', 1500000],
            '2' => ['ATK', 500000],
            '3' => ['PMK', 500000],
            '4' => ['Rapat', 4000000],
            '5' => ['SSG', 200000],
            '6' => ['Transportasi', 3000000],
            '7' => ['HRG', 1000000],
            '8' => ['Perutusan Klasis', 750000],
            '9' => ['Perutusan MK', 300000],
            '10' => ['Perutusan BPMK', 300000],
            '11' => ['Perutusan BPP', 300000],
            '12' => ['PA Pengerja', 300000],
            '13' => ['Mahasiswa Praktek', 1600000],
            '14' => ['Perlengkapan Gereja', 3000000],
            '15' => ['Diakonia', 5000000],
            '16' => ['Tamu Jemaat', 1500000],
            '17' => ['Transport Tukar Mimbar Klasis', 350000],
            '18' => ['Tranport Tukar Mimbar Sinode', 500000],
            '19' => ['Bantuan Ke Komisi Jemaat', 1000000],
            '20' => ['Operasional Sinode', 3000000],
            '21' => ['Operasional Perwakilan SBD', 1500000],
            '22' => ['Pembangunan Kantor Perwakilan', 1200000],
            '23' => ['Listrik', 1320000],
            '24' => ['Mission Trif', 500000],
            '25' => ['Konven', 1000000],
            '26' => ['Tak Terduga', 3000000],
        ];

        foreach ($itemsLain as $no => $data) {
            $this->createPostAndBudget($fiscalYear, $cat5, '2.5.' . $no, $data[0], 'pengeluaran', $data[1]);
        }
    }

    // Helper: Buat Kategori (Parent)
    private function createCategory($kode, $nama, $parent)
    {
        return RefBudgetPost::firstOrCreate(
            ['kode' => $kode],
            [
                'uuid' => Str::uuid(),
                'nama' => $nama,
                'jenis' => $parent->jenis,
                'parent_id' => $parent->id
            ]
        );
    }

    // Helper: Buat Pos & Budget (Child)
    private function createPostAndBudget($fiscalYear, $parent, $kode, $nama, $jenis, $target)
    {
        // 1. Buat Pos di Master
        $post = RefBudgetPost::firstOrCreate(
            ['kode' => $kode],
            [
                'uuid' => Str::uuid(),
                'nama' => $nama,
                'jenis' => $jenis,
                'parent_id' => $parent->id
            ]
        );

        // 2. Pasang Target RAPB untuk Tahun Ini
        Budget::updateOrCreate(
            [
                'fiscal_year_id' => $fiscalYear->id,
                'ref_budget_post_id' => $post->id,
            ],
            [
                'uuid' => Str::uuid(), // Generate UUID manual untuk firstOrCreate
                'nominal_target' => $target
            ]
        );
    }
}
